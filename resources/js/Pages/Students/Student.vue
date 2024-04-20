<script lang="ts" setup>
import { computed, onMounted, ref, watch } from "vue";
import { useForm, usePage } from '@inertiajs/vue3';
import InputText from "primevue/inputtext";
import Checkbox from "primevue/checkbox";
import { useToast } from 'primevue/usetoast';
import Toast from "primevue/toast";
import Button from 'primevue/button';
import Textarea from 'primevue/textarea';
import Dropdown from 'primevue/dropdown';
import Calendar from 'primevue/calendar';
import { iStudent } from "@/interfaces/index";
import { iItem } from '../../interfaces/index';
import InputNumber from "primevue/inputnumber";
import Modal from "../../Components/Modal.vue";
import Icon from '@/Components/Icons/Icon.vue';

const props = defineProps<{
    show: boolean,
    edit: Boolean,
    student: iStudent
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
    birth_cert_no?: number | null,
    idno?: number | null,
    gender: boolean | null,
    date_of_admission: Date | null,
    intake: number | null,
    program: number | null,
    sponsor: number | null,
    role: number | null,
    status: string | "" | number | null | { id: number, name: string },
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
    status: "",
    plwd: false,
    plwd_details: "",
})

const page = usePage()
const toast = useToast()

const intakes = computed<iItem[] | any[]>((): iItem[] => page?.props?.intakes)
const programs = computed<iItem[] | any[]>((): iItem[] => page?.props?.programs)
const sponsors = computed<iItem[] | any[]>((): iItem[] => page?.props?.sponsors)
const roles = computed<iItem[] | any[]>((): iItem[] => page?.props?.roles)
const states = ['In session', 'On Attachment', 'Completed', 'Dropout']
const genderOptions = ref([
    { id: 0, name: 'Male' },
    { id: 1, name: 'Female' },
]);

watch(() => props.student, value => {
    form.id = value.id
    form.first_name = value.first_name
    form.middle_name = value.middle_name
    form.surname = value.surname
    form.phone = value.phone
    form.email = value.email
    form.box_no = value.box_no
    form.post_code = value.post_code
    form.town = value.town
    form.physical_address = value.physical_address
    form.date_of_birth = value.date_of_birth
    form.birth_cert_no = value.birth_cert_no
    form.idno = value.idno
    form.gender = value.gender
    form.date_of_admission = value.date_of_admission
    form.intake = value?.intake?.id
    form.program = value?.program?.id
    form.sponsor = value?.sponsor?.id
    form.role = value?.role?.id
    form.status = value.status
    form.plwd = value.plwd
    form.plwd_details = value.plwd_details
})

const submit = async () => {

    if (props.edit) {
        form.patch(route('students-update', form.id), {
            onSuccess: () => {
                toast.add({
                    severity: 'success',
                    summary: 'Success',
                    detail: page?.props?.notification?.success,
                    life: 3000
                })
                close(false)
                resetErrors()
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
                form.date_of_admission = null
                form.intake = null
                form.program = null
                form.sponsor = null
                form.role = null
                form.status = ""
                form.plwd = false
                form.plwd_details = ""

                toast.add({
                    severity: 'success',
                    summary: 'Success',
                    detail: page?.props?.notification?.success,
                    life: 8000
                })

                resetErrors()

                close(false)
            },
            onError: () => {
                toast.add({
                    severity: 'error',
                    summary: 'Error',
                    detail: "An error ocurred! Please check the values you provided and try again.",
                    life: 8000
                })

                resetErrors()

            }
        })
    }
}

const resetErrors = () => {
    form.clearErrors()
}
onMounted(() => {
})

const close = (value: boolean) => {
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
            <div class="text-white" v-text="dialogTitle"></div>
            <button @click="close(false)">
                <Icon type="close" class="h-6 w-6 object-contain text-gray-50" />
            </button>
        </template>
        <div class="">
            <form @submit.prevent="submit">
                <div class="card-body">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 py-6">
                        <div class="relative">
                            <label class="text-sm" :class="{ 'text-red-400': page.props.errors.first_name }"
                                for="first_name">First
                                Name</label>
                            <InputText id="first_name" v-model="form.first_name" />
                            <span v-if="page.props.errors.first_name" v-text="page.props.errors.first_name"
                                class="text-red-400 text-xs"></span>
                        </div>
                        <div class="relative z-0" :class="{ 'has-error': page.props.errors.middle_name }">
                            <label class="text-sm" :class="{ 'text-red-400': page.props.errors.middle_name }"
                                for="middle_name">Middle
                                Name</label>
                            <InputText id="middle_name" v-model="form.middle_name" />
                            <span class="text-red-400 text-xs" v-if="page.props.errors.middle_name"
                                v-text="page.props.errors.middle_name"></span>
                        </div>
                        <div class="relative z-0" :class="{ 'has-error': page.props.errors.surname }">
                            <label class="text-sm" :class="{ 'text-red-400': page.props.errors.surname }"
                                for="surname">Surname</label>
                            <InputText id="surname" v-model="form.surname" />
                            <span class="text-red-400 text-xs" v-if="page.props.errors.surname"
                                v-text="page.props.errors.surname"></span>
                        </div>
                        <div class="relative z-0" :class="{ 'has-error': page.props.errors.phone }">
                            <label class="text-sm" :class="{ 'text-red-400': page.props.errors.phone }"
                                for="phone">Phone</label>
                            <InputText id="phone" v-model="form.phone" />
                            <span class="text-red-400 text-xs" v-if="page.props.errors.phone"
                                v-text="page.props.errors.phone"></span>
                        </div>
                        <div class="relative z-0 md:col-span-2" :class="{ 'has-error': page.props.errors.email }">
                            <label class="text-sm" :class="{ 'text-red-400': page.props.errors.email }"
                                for="email">Email</label>
                            <InputText id="email" v-model="form.email" />
                            <span class="text-red-400 text-xs" v-if="page.props.errors.email"
                                v-text="page.props.errors.email"></span>
                        </div>
                        <div class="relative z-0" :class="{ 'has-error': page.props.errors.box_no }">
                            <label class="text-sm" :class="{ 'text-red-400': page.props.errors.box_no }"
                                for="box_no">Box
                                No</label>
                            <InputText id="box_no" v-model="form.box_no" />
                            <span class="text-red-400 text-xs" v-if="page.props.errors.box_no"
                                v-text="page.props.errors.box_no"></span>
                        </div>
                        <div class="relative z-0" :class="{ 'has-error': page.props.errors.post_code }">
                            <label class="text-sm" :class="{ 'text-red-400': page.props.errors.post_code }"
                                for="post_code">Post
                                Code</label>
                            <InputText id="post_code" v-model="form.post_code" />
                            <span class="text-red-400 text-xs" v-if="page.props.errors.post_code"
                                v-text="page.props.errors.post_code"></span>
                        </div>
                        <div class="relative z-0" :class="{ 'has-error': page.props.errors.town }">
                            <label class="text-sm" :class="{ 'text-red-400': page.props.errors.town }"
                                for="town">Town</label>
                            <InputText id="town" v-model="form.town" />
                            <span class="text-red-400 text-xs" v-if="page.props.errors.town"
                                v-text="page.props.errors.town"></span>
                        </div>
                        <div class="relative z-0 md:col-span-3"
                            :class="{ 'has-error': page.props.errors.physical_address }">
                            <label class="text-sm" :class="{ 'text-red-400': page.props.errors.physical_address }"
                                for="physical_address">Physical Address</label>
                            <Textarea id="physical_address" v-model="form.physical_address"></Textarea>
                            <span class="text-red-400 text-xs" v-if="page.props.errors.physical_address"
                                v-text="page.props.errors.physical_address"></span>
                        </div>
                        <div class="relative z-0">
                            <label class="text-sm" :class="{ 'text-red-400': page.props.errors.date_of_birth }"
                                for="date_of_birth">Date
                                of
                                Birth</label>
                            <Calendar v-model="form.date_of_birth" :showIcon="true" iconDisplay="input"
                                :manualInput="false" />
                            <span class="text-red-400 text-xs" v-if="page.props.errors.date_of_birth"
                                v-text="page.props.errors.date_of_birth"></span>
                        </div>
                        <div class="relative z-0" :class="{ 'has-error': page.props.errors.birth_cert_no }">
                            <label class="text-sm" :class="{ 'text-red-400': page.props.errors.birth_cert_no }"
                                for="birth_cert_no">Birth
                                Certificate No</label>
                            <InputText id="birth_cert_no" v-model="form.birth_cert_no" />
                            <span class="text-red-400 text-xs" v-if="page.props.errors.birth_cert_no"
                                v-text="page.props.errors.birth_cert_no"></span>
                        </div>
                        <div class="relative z-0" :class="{ 'has-error': page.props.errors.idno }">
                            <label class="text-sm" :class="{ 'text-red-400': page.props.errors.idno }"
                                for="idno">IDNo</label>
                            <InputNumber :useGrouping="false" id="idno" v-model="form.idno" />
                            <span class="text-red-400 text-xs" v-if="page.props.errors.idno"
                                v-text="page.props.errors.idno"></span>
                        </div>
                        <div class="relative z-0 is-filled is-focused"
                            :class="{ 'has-error': page.props.errors.gender }">
                            <label class="text-sm" :class="{ 'text-red-400': page.props.errors.gender }"
                                for="gender">Gender</label>
                            <Dropdown :options="genderOptions" option-label="name" option-value="id"
                                v-model="form.gender" :invalid="page.props.errors.gender" />
                            <span class="text-red-400 text-xs" v-if="page.props.errors.gender"
                                v-text="page.props.errors.gender"></span>
                        </div>
                        <div class="relative z-0" :class="{ 'has-error': page.props.errors.date_of_admission }">
                            <label class="text-sm" :class="{ 'text-red-400': page.props.errors.date_of_admission }"
                                for="date_of_admission">Date
                                of Admission</label>
                            <Calendar id="date_of_admission" v-model="form.date_of_admission" :showIcon="true"
                                iconDisplay="input" :manualInput="false"
                                :invalid="page.props.errors.date_of_admission" />
                            <span class="text-red-400 text-xs" v-if="page.props.errors.date_of_admission"
                                v-text="page.props.errors.date_of_admission"></span>
                        </div>
                        <div class="form-group" :class="{ 'has-error': page.props.errors.intake }">
                            <label class="text-sm" :class="{ 'text-red-400': page.props.errors.intake }"
                                for="intake">Intake</label>
                            <Dropdown id="intakes" :options="intakes" filter option-label="name" option-value="id"
                                v-model="form.intake" :invalid="page.props.errors.intake" />
                            <span class="text-red-400 text-xs" v-if="page.props.errors.intake"
                                v-text="page.props.errors.intake"></span>
                        </div>
                        <div class="relative z-0 is-filled is-focused"
                            :class="{ 'has-error': page.props.errors.program }">
                            <label class="text-sm" :class="{ 'text-red-400': page.props.errors.program }"
                                for="program">Program</label>
                            <Dropdown id="program" option-label="name" option-value="id" :options="programs"
                                v-model="form.program" />
                            <span class="text-red-400 text-xs" v-if="page.props.errors.program"
                                v-text="page.props.errors.program"></span>
                        </div>
                        <div class="relative z-0 is-filled is-focused"
                            :class="{ 'has-error': page.props.errors.sponsor }">
                            <label class="text-sm" :class="{ 'text-red-400': page.props.errors.sponsor }"
                                for="sponsor">Sponsor</label>
                            <Dropdown id="sponsor" option-label="name" option-value="id" :options="sponsors"
                                v-model="form.sponsor" />
                            <span class="text-red-400 text-xs" v-if="page.props.errors.sponsor"
                                v-text="page.props.errors.sponsor"></span>
                        </div>
                        <div class="relative z-0 is-filled is-focused" :class="{ 'has-error': page.props.errors.role }">
                            <label class="text-sm" :class="{ 'text-red-400': page.props.errors.role }"
                                for="role">Student
                                Role</label>
                            <Dropdown id="role" option-label="name" option-value="id" :options="roles"
                                v-model="form.role" />
                            <span class="text-red-400 text-xs" v-if="page.props.errors.role"
                                v-text="page.props.errors.role"></span>
                        </div>
                        <div class="relative z-0 is-filled is-focused"
                            :class="{ 'has-error': page.props.errors.status }">
                            <label class="text-sm" :class="{ 'text-red-400': page.props.errors.status }"
                                for="status">Status</label>
                            <Dropdown id="status" :options="states" v-model="form.status" />
                            <span class="text-red-400 text-xs" v-if="page.props.errors.status"
                                v-text="page.props.errors.status"></span>
                        </div>
                        <div class="relative z-0 is-filled is-focused"
                            :class="{ 'has-error': page.props.errors.status }">
                            <label class="text-sm" :class="{ 'text-red-400': page.props.errors.status }"
                                for="status">Status</label>
                            <div>
                                <Dropdown v-model="form.plwd" :options="[{}]" optionValue="id" optionLabel="name" />
                            </div>
                            <span class="text-red-400 text-xs" v-if="page.props.errors.status"
                                v-text="page.props.errors.status"></span>
                        </div>
                    </div>
                </div>
                <div class="flex gap-2 items-center justify-between">
                    <Button type="submit" label="Save" rounded />
                    <Button type="reset" @click="close(false)" label="Cancel" rounded outlined severity="danger" />
                </div>
            </form>
        </div>
    </Modal>
</template>
