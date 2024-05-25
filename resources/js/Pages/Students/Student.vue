<script lang="ts" setup>
import { computed, onMounted, ref, watch } from "vue";
import { useForm, usePage } from '@inertiajs/vue3';
import InputText from "primevue/inputtext";
import { useToast } from 'primevue/usetoast';
import Toast from "primevue/toast";
import Textarea from 'primevue/textarea';
import Dropdown from 'primevue/dropdown';
import Calendar from 'primevue/calendar';
import { iStudent } from "@/interfaces/index";
import { iItem } from '../../interfaces/index';
import InputNumber from "primevue/inputnumber";
import Modal from "../../Components/Modal.vue";
import Icon from '../../Components/Icons/Icon.vue';
import PrimaryButton from '../../Components/PrimaryButton.vue';
import SecondaryButton from '../../Components/SecondaryButton.vue';

const props = defineProps<{
    show: boolean,
    edit: boolean,
    student: iStudent | null
}>()
const emit = defineEmits(['closed', 'saved'])

const form = useForm<{
    id: number | null,
    first_name: string | "",
    middle_name?: string | "",
    surname: string | "",
    phone?: string | "",
    email?: string | "",
    box_no?: string | "",
    post_code?: string | "",
    town?: string | "",
    physical_address?: string | "",
    date_of_birth: Date | null,
    birth_cert_no?: string | null,
    idno?: string | null,
    gender: boolean | null,
    date_of_admission: Date | null,
    intake: number | null,
    program: number | null,
    sponsor: number | null,
    role: number | null,
    level: number | null,
    status: string | "" | number | null | { id: number, name: string },
    guardian_phone?: string | "",
    guardian_name?: string | "",
    guardian_email?: string | "",
    plwd: boolean,
    plwd_details?: string | "",
}>({
    id: null,
    first_name: "",
    middle_name: "",
    surname: "",
    phone: "",
    email: "",
    box_no: "",
    post_code: "",
    town: "",
    physical_address: "",
    date_of_birth: null,
    birth_cert_no: null,
    idno: null,
    gender: null,
    date_of_admission: null,
    intake: null,
    program: null,
    sponsor: null,
    role: null,
    level: null,
    status: "",
    guardian_phone: "",
    guardian_name: "",
    guardian_email: "",
    plwd: false,
    plwd_details: "",
})

const page = usePage()
const toast = useToast()

const intakes = computed<iItem[] | any[]>((): iItem[] => page?.props?.intakes)
const programs = computed<iItem[] | any[]>((): iItem[] => page?.props?.programs)
const sponsors = computed<iItem[] | any[]>((): iItem[] => page?.props?.sponsors)
const roles = computed<iItem[] | any[]>((): iItem[] => page?.props?.roles)
const levels = computed<iItem[] | any[]>((): iItem[] => page?.props?.academicLevels)
const states = ['In session', 'On Attachment', 'Completed', 'Dropout']
const genderOptions = ref([
    { id: 0, name: 'Male' },
    { id: 1, name: 'Female' },
]);

watch(() => props.student, value => {
    form.id = value?.id
    form.first_name = value?.first_name
    form.middle_name = value?.middle_name
    form.surname = value?.surname
    form.phone = value?.phone
    form.email = value?.email
    form.box_no = value?.box_no
    form.post_code = value?.post_code
    form.town = value?.town
    form.physical_address = value?.physical_address
    form.date_of_birth = value?.date_of_birth
    form.birth_cert_no = value?.birth_cert_no
    form.idno = value?.idno ?? null
    form.gender = value?.gender
    form.date_of_admission = value?.date_of_admission
    form.intake = value?.intake?.id ?? null
    form.program = value?.program?.id ?? null
    form.sponsor = value?.sponsor?.id ?? null
    form.role = value?.role?.id ?? null
    form.level = value?.level?.id ?? null
    form.status = value?.status ?? null
    form.guardian_name = value?.guardian?.name
    form.guardian_phone = value?.guardian?.phone
    form.guardian_email = value?.guardian?.email
    form.plwd = value?.plwd
    form.plwd_details = value?.plwd_details
})

const submit = async () => {

    if (props.edit) {
        form.patch(route('students-update', form.id), {
            only: ['students', 'notification', 'errors'],
            onSuccess: () => {
                toast.add({
                    severity: 'success',
                    summary: 'Success',
                    detail: page?.props?.notification?.success,
                    life: 3000
                })
                close(false)
            },
            onError: () => {
                toast.add({
                    severity: 'error',
                    summary: 'Success',
                    detail: "An error ocurred! Please check the values you provided and try again.",
                    life: 3000
                })

            }
        })
    } else {
        form.post(route('students-store'), {
            only: ['students', 'notification', 'errors'],
            onSuccess: () => {

                toast.add({
                    severity: 'success',
                    summary: 'Success',
                    detail: page?.props?.notification?.success,
                    life: 3000
                })
                close(false)
            },
            onError: () => {
                toast.add({
                    severity: 'error',
                    summary: 'Error',
                    detail: "An error ocurred! Please check the values you provided and try again.",
                    life: 3000
                })

            }
        })
    }
}

const resetErrors = () => {
    form.clearErrors()
}

const close = (value: boolean) => {
    form.id = null
    form.first_name = ""
    form.middle_name = ""
    form.surname = ""
    form.phone = ""
    form.email = ""
    form.box_no = ""
    form.post_code = ""
    form.town = ""
    form.physical_address = ""
    form.date_of_birth = null
    form.birth_cert_no = null
    form.idno = null
    form.gender = null
    form.date_of_admission = new Date()
    form.intake = null
    form.program = null
    form.sponsor = null
    form.role = null
    form.level = null
    form.status = ""
    form.guardian_name = ""
    form.guardian_phone = ""
    form.guardian_email = ""
    form.plwd = false
    form.plwd_details = ""
    resetErrors()
    emit('closed', value)
}

const visible = ref(false)
const dialogTitle = computed(() => props.edit ? 'Edit Student' : 'New Student')

watch(() => props.show, (value) => {
    visible.value = value
})

</script>
<template>
    <Toast position="top-center" />
    <Modal :show="visible" maxWidth="3xl">
        <template #header>
            <div class="" v-text="dialogTitle"></div>
            <button @click="close(false)">
                <Icon type="close" class="h-6 w-6 object-contain " />
            </button>
        </template>
        <div class="">
            <form @submit.prevent="submit">
                <div class="py-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div class="relative">
                            <label class="text-sm" :class="{ 'text-red-400': form.errors.first_name }"
                                for="first_name">First
                                Name</label>
                            <InputText id="first_name" v-model="form.first_name"
                                :invalid="form.errors.first_name ? true : false" />
                            <span v-if="form.errors.first_name" v-text="form.errors.first_name"
                                class="text-red-400 text-xs"></span>
                        </div>
                        <div class="relative z-0" :class="{ 'has-error': form.errors.middle_name }">
                            <label class="text-sm" :class="{ 'text-red-400': form.errors.middle_name }"
                                for="middle_name">Middle
                                Name</label>
                            <InputText id="middle_name" v-model="form.middle_name"
                                :invalid="form.errors.middle_name ? true : false" />
                            <span class="text-red-400 text-xs" v-if="form.errors.middle_name"
                                v-text="form.errors.middle_name"></span>
                        </div>
                        <div class="relative z-0" :class="{ 'has-error': form.errors.surname }">
                            <label class="text-sm" :class="{ 'text-red-400': form.errors.surname }"
                                for="surname">Surname</label>
                            <InputText id="surname" v-model="form.surname"
                                :invalid="form.errors.surname ? true : false" />
                            <span class="text-red-400 text-xs" v-if="form.errors.surname"
                                v-text="form.errors.surname"></span>
                        </div>
                        <div class="relative z-0" :class="{ 'has-error': form.errors.phone }">
                            <label class="text-sm" :class="{ 'text-red-400': form.errors.phone }"
                                for="phone">Phone</label>
                            <InputText id="phone" v-model="form.phone" :invalid="form.errors.phone ? true : false" />
                            <span class="text-red-400 text-xs" v-if="form.errors.phone"
                                v-text="form.errors.phone"></span>
                        </div>
                        <div class="relative z-0 md:col-span-2" :class="{ 'has-error': form.errors.email }">
                            <label class="text-sm" :class="{ 'text-red-400': form.errors.email }"
                                for="email">Email</label>
                            <InputText id="email" v-model="form.email" :invalid="form.errors.email ? true : false" />
                            <span class="text-red-400 text-xs" v-if="form.errors.email"
                                v-text="form.errors.email"></span>
                        </div>
                        <div class="relative z-0" :class="{ 'has-error': form.errors.box_no }">
                            <label class="text-sm" :class="{ 'text-red-400': form.errors.box_no }" for="box_no">Box
                                No</label>
                            <InputText id="box_no" v-model="form.box_no" :invalid="form.errors.box_no ? true : false" />
                            <span class="text-red-400 text-xs" v-if="form.errors.box_no"
                                v-text="form.errors.box_no"></span>
                        </div>
                        <div class="relative z-0" :class="{ 'has-error': form.errors.post_code }">
                            <label class="text-sm" :class="{ 'text-red-400': form.errors.post_code }"
                                for="post_code">Post
                                Code</label>
                            <InputText id="post_code" v-model="form.post_code"
                                :invalid="form.errors.post_code ? true : false" />
                            <span class="text-red-400 text-xs" v-if="form.errors.post_code"
                                v-text="form.errors.post_code"></span>
                        </div>
                        <div class="relative z-0" :class="{ 'has-error': form.errors.town }">
                            <label class="text-sm" :class="{ 'text-red-400': form.errors.town }" for="town">Town</label>
                            <InputText id="town" v-model="form.town" :invalid="form.errors.town ? true : false" />
                            <span class="text-red-400 text-xs" v-if="form.errors.town" v-text="form.errors.town"></span>
                        </div>
                        <div class="relative z-0 md:col-span-3" :class="{ 'has-error': form.errors.physical_address }">
                            <label class="text-sm" :class="{ 'text-red-400': form.errors.physical_address }"
                                for="physical_address">Physical Address</label>
                            <Textarea id="physical_address" v-model="form.physical_address"
                                :invalid="form.errors.physical_address ? true : false"></Textarea>
                            <span class="text-red-400 text-xs" v-if="form.errors.physical_address"
                                v-text="form.errors.physical_address"></span>
                        </div>
                        <div class="relative z-0">
                            <label class="text-sm" :class="{ 'text-red-400': form.errors.date_of_birth }"
                                for="date_of_birth">Date
                                of
                                Birth</label>
                            <Calendar v-model="form.date_of_birth" :showIcon="true" iconDisplay="input"
                                :manualInput="false" :invalid="form.errors.date_of_birth ? true : false"
                                dateFormat="D, d M, yy" />
                            <span class="text-red-400 text-xs" v-if="form.errors.date_of_birth"
                                v-text="form.errors.date_of_birth"></span>
                        </div>
                        <div class="relative z-0" :class="{ 'has-error': form.errors.birth_cert_no }">
                            <label class="text-sm" :class="{ 'text-red-400': form.errors.birth_cert_no }"
                                for="birth_cert_no">Birth
                                Certificate No</label>
                            <InputText id="birth_cert_no" v-model="form.birth_cert_no"
                                :invalid="form.errors.birth_cert_no ? true : false" />
                            <span class="text-red-400 text-xs" v-if="form.errors.birth_cert_no"
                                v-text="form.errors.birth_cert_no"></span>
                        </div>
                        <div class="relative z-0" :class="{ 'has-error': form.errors.idno }">
                            <label class="text-sm" :class="{ 'text-red-400': form.errors.idno }" for="idno">IDNo</label>
                            <InputText class="w-full" id="idno" v-model="form.idno"
                                :invalid="form.errors.idno ? true : false" />
                            <span class="text-red-400 text-xs" v-if="form.errors.idno" v-text="form.errors.idno"></span>
                        </div>
                        <div class="relative z-0 is-filled is-focused" :class="{ 'has-error': form.errors.gender }">
                            <label class="text-sm" :class="{ 'text-red-400': form.errors.gender }"
                                for="gender">Gender</label>
                            <Dropdown :options="genderOptions" option-label="name" option-value="id"
                                v-model="form.gender" :invalid="form.errors.gender ? true : false" />
                            <span class="text-red-400 text-xs" v-if="form.errors.gender"
                                v-text="form.errors.gender"></span>
                        </div>
                        <div class="relative z-0" :class="{ 'has-error': form.errors.date_of_admission }">
                            <label class="text-sm" :class="{ 'text-red-400': form.errors.date_of_admission }"
                                for="date_of_admission">Date
                                of Admission</label>
                            <Calendar id="date_of_admission" v-model="form.date_of_admission" :showIcon="true"
                                iconDisplay="input" :manualInput="false"
                                :invalid="form.errors.date_of_admission ? true : false" dateFormat="D, d M, yy" />
                            <span class="text-red-400 text-xs" v-if="form.errors.date_of_admission"
                                v-text="form.errors.date_of_admission"></span>
                        </div>
                        <div class="form-group" :class="{ 'has-error': form.errors.intake }">
                            <label class="text-sm" :class="{ 'text-red-400': form.errors.intake }"
                                for="intake">Intake</label>
                            <Dropdown id="intakes" :options="intakes" filter option-label="name" option-value="id"
                                v-model="form.intake" :invalid="form.errors.intake ? true : false" />
                            <span class="text-red-400 text-xs" v-if="form.errors.intake"
                                v-text="form.errors.intake"></span>
                        </div>
                        <div class="relative z-0 is-filled is-focused" :class="{ 'has-error': form.errors.program }">
                            <label class="text-sm" :class="{ 'text-red-400': form.errors.program }"
                                for="program">Program</label>
                            <Dropdown id="program" option-label="name" option-value="id" :options="programs"
                                v-model="form.program" :invalid="form.errors.program ? true : false" />
                            <span class="text-red-400 text-xs" v-if="form.errors.program"
                                v-text="form.errors.program"></span>
                        </div>
                        <div class="relative z-0 is-filled is-focused" :class="{ 'has-error': form.errors.sponsor }">
                            <label class="text-sm" :class="{ 'text-red-400': form.errors.sponsor }"
                                for="sponsor">Sponsor</label>
                            <Dropdown id="sponsor" option-label="name" option-value="id" :options="sponsors"
                                v-model="form.sponsor" :invalid="form.errors.sponsor ? true : false" />
                            <span class="text-red-400 text-xs" v-if="form.errors.sponsor"
                                v-text="form.errors.sponsor"></span>
                        </div>
                        <div class="relative z-0 is-filled is-focused" :class="{ 'has-error': form.errors.role }">
                            <label class="text-sm" :class="{ 'text-red-400': form.errors.role }" for="role">Student
                                Role</label>
                            <Dropdown id="role" option-label="name" option-value="id" :options="roles"
                                v-model="form.role" :invalid="form.errors.role ? true : false" />
                            <span class="text-red-400 text-xs" v-if="form.errors.role" v-text="form.errors.role"></span>
                        </div>

                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mt-4">

                        <div class="relative z-0 is-filled is-focused" :class="{ 'has-error': form.errors.status }">
                            <label class="text-sm" :class="{ 'text-red-400': form.errors.status }"
                                for="status">Status</label>
                            <Dropdown id="status" :options="states" v-model="form.status"
                                :invalid="form.errors.status ? true : false" />
                            <span class="text-red-400 text-xs" v-if="form.errors.status"
                                v-text="form.errors.status"></span>
                        </div>
                        <div class="relative z-0 is-filled is-focused" :class="{ 'has-error': form.errors.status }">
                            <label class="text-sm" :class="{ 'text-red-400': form.errors.level }" for="level">Academic
                                Level</label>
                            <Dropdown filter id="level" :options="levels" v-model="form.level"
                                :invalid="form.errors.level ? true : false" optionValue="id" optionLabel="name" />
                            <span class="text-red-400 text-xs" v-if="form.errors.level"
                                v-text="form.errors.level"></span>
                        </div>
                        <div class="relative z-0 is-filled is-focused" :class="{ 'has-error': form.errors.plwd }">
                            <label class="text-sm" :class="{ 'text-red-400': form.errors.plwd }" for="plwd">PLWD</label>
                            <div>
                                <Dropdown v-model="form.plwd" :options="[{ id: 1, name: 'Yes' }, { id: 0, name: 'No' }]"
                                    optionValue="id" optionLabel="name" :invalid="form.errors.plwd ? true : false" />
                            </div>
                            <span class="text-red-400 text-xs" v-if="form.errors.plwd" v-text="form.errors.plwd"></span>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mt-4">
                        <div class="relative z-0 is-filled is-focused col-span-1 md:col-span-3"
                            :class="{ 'has-error': form.errors.plwd_details }">
                            <label class="text-sm" :class="{ 'text-red-400': form.errors.plwd_details }"
                                for="plwd_details">PLWD
                                Details</label>
                            <div>
                                <InputText v-model="form.plwd_details"
                                    :invalid="form.errors.plwd_details ? true : false" />
                            </div>
                            <span class="text-red-400 text-xs" v-if="form.errors.plwd_details"
                                v-text="form.errors.plwd_details"></span>
                        </div>
                    </div>
                    <div class="shadow p-2 rounded border dark:border-gray-800 my-4">
                        <div>Guardian Details</div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <div class="relative md:col-span-3" :class="{ 'has-error': form.errors.plwd_details }">
                                <label class="text-sm" :class="{ 'text-red-400': form.errors.guardian_name }"
                                    for="guardian_name">Name</label>
                                <div>
                                    <InputText v-model="form.guardian_name"
                                        :invalid="form.errors.guardian_name ? true : false" />
                                </div>
                                <span class="text-red-400 text-xs" v-if="form.errors.guardian_name"
                                    v-text="form.errors.guardian_name"></span>
                            </div>
                            <div class="relative" :class="{ 'has-error': form.errors.guardian_phone }">
                                <label class="text-sm" :class="{ 'text-red-400': form.errors.guardian_phone }"
                                    for="guardian_phone">Phone</label>
                                <div>
                                    <InputText v-model="form.guardian_phone"
                                        :options="[{ id: 1, name: 'Yes' }, { id: 0, name: 'No' }]" optionValue="id"
                                        optionLabel="name" :invalid="form.errors.guardian_phone ? true : false" />
                                </div>
                                <span class="text-red-400 text-xs" v-if="form.errors.guardian_phone"
                                    v-text="form.errors.guardian_phone"></span>
                            </div>
                            <div class="relative md:col-span-2" :class="{ 'has-error': form.errors.guardian_email }">
                                <label class="text-sm" :class="{ 'text-red-400': form.errors.guardian_email }"
                                    for="guardian_email">Email</label>
                                <div>
                                    <InputText v-model="form.guardian_email"
                                        :invalid="form.errors.guardian_email ? true : false" />
                                </div>
                                <span class="text-red-400 text-xs" v-if="form.errors.guardian_email"
                                    v-text="form.errors.guardian_email"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <template #footer>
            <PrimaryButton :class="{ 'opacity-30': form.processing }" :disabled="form.processing" type="submit"
                @click="submit">Save</PrimaryButton>
            <SecondaryButton type="reset" @click="close(false)">Cancel</SecondaryButton>
        </template>
    </Modal>
</template>
