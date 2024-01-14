<script lang="ts" setup>
import { computed, onMounted, ref, watch } from "vue";
import { useForm, usePage } from '@inertiajs/vue3';
import Dialog from 'primevue/dialog';
import InputText from "primevue/inputtext";
import { useToast } from 'primevue/usetoast';
import Toast from "primevue/toast";
import Button from 'primevue/button';
import Textarea from 'primevue/textarea';
import Dropdown from 'primevue/dropdown';
import Calendar from 'primevue/calendar';
import { iStudent } from "@/interfaces/index";

const props = defineProps<{
    show: Boolean,
    edit: Boolean,
    student: {
        type: iStudent,
        default: {
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
            student_role: null,
            status: "",
        }
    }
}>()
const emit = defineEmits(['closed', 'saved'])

const form = useForm({
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
    student_role: null,
    status: "",
})

const page = usePage()
const toast = useToast()

const intakes = computed(() => page?.props?.intakes)
const programs = computed(() => page?.props?.programs)
const sponsors = computed(() => page?.props?.sponsors)
const student_roles = computed(() => page?.props?.student_roles)
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
    form.intake = value.intake_id
    form.program = value.program_id
    form.sponsor = value.sponsor_id
    form.student_role = value.student_role_id
    form.status = value.status
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
                close()
                resetErrors()
            },
            onError: () => {
                toast.add({
                    severity: 'success',
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
                form.first_name = null
                form.middle_name = null
                form.surname = null
                form.phone = null
                form.email = null
                form.box_no = null
                form.post_code = null
                form.town = null
                form.physical_address = null
                form.date_of_birth = null
                form.birth_cert_no = null
                form.idno = null
                form.gender = null
                form.date_of_admission = null
                form.intake = null
                form.program = null
                form.sponsor = null
                form.student_role = null
                form.status = null

                toast.add({
                    severity: 'success',
                    summary: 'Success',
                    detail: page?.props?.notification?.success,
                    life: 3000
                })

                resetErrors()

                close()
            },
            onError: () => {
                toast.add({
                    severity: 'error',
                    summary: 'Error',
                    detail: "An error ocurred! Please check the values you provided and try again.",
                    life: 3000
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

const close = () => {
    emit('closed', true)
}

const visible = ref(false)
const dialogTitle = computed(() => props.edit ? 'Edit Student' : 'New Student')

watch(() => props.show, (value) => {
    visible.value = value
})
</script>
<template>
    <Toast position="top-center" />
    <Dialog v-model:visible="visible" :closeable="true" @close="close" modal :header="dialogTitle"
        :style="{ width: '50rem' }" :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
        <template #header>
            <h4>Student Details</h4>
        </template>
        <div class="">
            <form @submit.prevent="submit">
                <div class="card-body">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 py-6">
                        <div class="p-float-label relative">
                            <label :class="{ 'text-red-400': page.props.errors.first_name }" for="first_name">First
                                Name</label>
                            <InputText id="first_name" v-model="form.first_name" />
                            <span v-if="page.props.errors.first_name" v-text="page.props.errors.first_name"
                                class="text-red-400"></span>
                        </div>
                        <div class="relative z-0" :class="{ 'has-error': page.props.errors.middle_name }">
                            <label :class="{ 'text-red-400': page.props.errors.middle_name }" for="middle_name">Middle
                                Name</label>
                            <InputText id="middle_name" v-model="form.middle_name" />
                            <span class="text-red-400" v-if="page.props.errors.middle_name"
                                v-text="page.props.errors.middle_name"></span>
                        </div>
                        <div class="relative z-0" :class="{ 'has-error': page.props.errors.surname }">
                            <label :class="{ 'text-red-400': page.props.errors.surname }" for="surname">Surname</label>
                            <InputText id="surname" v-model="form.surname" />
                            <span class="text-red-400" v-if="page.props.errors.surname"
                                v-text="page.props.errors.surname"></span>
                        </div>
                        <div class="relative z-0" :class="{ 'has-error': page.props.errors.phone }">
                            <label :class="{ 'text-red-400': page.props.errors.phone }" for="phone">Phone</label>
                            <InputText id="phone" v-model="form.phone" />
                            <span class="text-red-400" v-if="page.props.errors.phone"
                                v-text="page.props.errors.phone"></span>
                        </div>
                        <div class="relative z-0 col-span-2" :class="{ 'has-error': page.props.errors.email }">
                            <label :class="{ 'text-red-400': page.props.errors.email }" for="email">Email</label>
                            <InputText id="email" v-model="form.email" />
                            <span class="text-red-400" v-if="page.props.errors.email"
                                v-text="page.props.errors.email"></span>
                        </div>
                        <div class="relative z-0" :class="{ 'has-error': page.props.errors.box_no }">
                            <label :class="{ 'text-red-400': page.props.errors.box_no }" for="box_no">Box No</label>
                            <InputText id="box_no" v-model="form.box_no" />
                            <span class="text-red-400" v-if="page.props.errors.box_no"
                                v-text="page.props.errors.box_no"></span>
                        </div>
                        <div class="relative z-0" :class="{ 'has-error': page.props.errors.post_code }">
                            <label :class="{ 'text-red-400': page.props.errors.post_code }" for="post_code">Post
                                Code</label>
                            <InputText id="post_code" v-model="form.post_code" />
                            <span class="text-red-400" v-if="page.props.errors.post_code"
                                v-text="page.props.errors.post_code"></span>
                        </div>
                        <div class="relative z-0" :class="{ 'has-error': page.props.errors.town }">
                            <label :class="{ 'text-red-400': page.props.errors.town }" for="town">Town</label>
                            <InputText id="town" v-model="form.town" />
                            <span class="text-red-400" v-if="page.props.errors.town" v-text="page.props.errors.town"></span>
                        </div>
                        <div class="relative z-0 col-span-3" :class="{ 'has-error': page.props.errors.physical_address }">
                            <label :class="{ 'text-red-400': page.props.errors.physical_address }"
                                for="physical_address">Physical Address</label>
                            <Textarea id="physical_address" v-model="form.physical_address"></Textarea>
                            <span class="text-red-400" v-if="page.props.errors.physical_address"
                                v-text="page.props.errors.physical_address"></span>
                        </div>
                        <div class="relative z-0">
                            <label :class="{ 'text-red-400': page.props.errors.date_of_birth }" for="date_of_birth">Date of
                                Birth</label>
                            <!-- <Calendar v-model="form.date_of_birth" /> -->
                            <Calendar v-model="form.date_of_birth" showIcon iconDisplay="input" />
                            <span class="text-red-400" v-if="page.props.errors.date_of_birth"
                                v-text="page.props.errors.date_of_birth"></span>
                        </div>
                        <div class="relative z-0" :class="{ 'has-error': page.props.errors.birth_cert_no }">
                            <label :class="{ 'text-red-400': page.props.errors.birth_cert_no }" for="birth_cert_no">Birth
                                Certificate No</label>
                            <InputText id="birth_cert_no" v-model="form.birth_cert_no" />
                            <span class="text-red-400" v-if="page.props.errors.birth_cert_no"
                                v-text="page.props.errors.birth_cert_no"></span>
                        </div>
                        <div class="relative z-0" :class="{ 'has-error': page.props.errors.idno }">
                            <label :class="{ 'text-red-400': page.props.errors.idno }" for="idno">IDNo</label>
                            <InputText id="idno" v-model="form.idno" />
                            <span class="text-red-400" v-if="page.props.errors.idno" v-text="page.props.errors.idno"></span>
                        </div>
                        <div class="relative z-0 is-filled is-focused" :class="{ 'has-error': page.props.errors.gender }">
                            <label :class="{ 'text-red-400': page.props.errors.gender }" for="gender">Gender</label>
                            <Dropdown :options="genderOptions" option-label="name" option-value="id"
                                v-model="form.gender" />
                            <span class="text-red-400" v-if="page.props.errors.gender"
                                v-text="page.props.errors.gender"></span>
                        </div>
                        <div class="relative z-0" :class="{ 'has-error': page.props.errors.date_of_admission }">
                            <label :class="{ 'text-red-400': page.props.errors.date_of_admission }"
                                for="date_of_admission">Date of Admission</label>
                            <Calendar id="date_of_admission" v-model="form.date_of_admission" />
                            <span class="text-red-400" v-if="page.props.errors.date_of_admission"
                                v-text="page.props.errors.date_of_admission"></span>
                        </div>
                        <div class="form-group" :class="{ 'has-error': page.props.errors.intake }">
                            <label :class="{ 'text-red-400': page.props.errors.intake }" for="intake">Intake</label>
                            <Dropdown id="intakes" :options="intakes" filter option-label="name" option-value="id"
                                v-model="form.intake" />
                            <span class="text-red-400" v-if="page.props.errors.intake"
                                v-text="page.props.errors.intake"></span>
                        </div>
                        <div class="relative z-0 is-filled is-focused" :class="{ 'has-error': page.props.errors.program }">
                            <label :class="{ 'text-red-400': page.props.errors.program }" for="program">Program</label>
                            <Dropdown id="program" option-label="name" option-value="id" :options="programs"
                                v-model="form.program" />
                            <span class="text-red-400" v-if="page.props.errors.program"
                                v-text="page.props.errors.program"></span>
                        </div>
                        <div class="relative z-0 is-filled is-focused" :class="{ 'has-error': page.props.errors.sponsor }">
                            <label :class="{ 'text-red-400': page.props.errors.sponsor }" for="sponsor">Sponsor</label>
                            <Dropdown id="sponsor" option-label="name" option-value="id" :options="sponsors"
                                v-model="form.sponsor" />
                            <span class="text-red-400" v-if="page.props.errors.sponsor"
                                v-text="page.props.errors.sponsor"></span>
                        </div>
                        <div class="relative z-0 is-filled is-focused"
                            :class="{ 'has-error': page.props.errors.student_role }">
                            <label :class="{ 'text-red-400': page.props.errors.student_role }" for="student_role">Student
                                Role</label>
                            <Dropdown id="student_role" option-label="name" option-value="id" :options="student_roles"
                                v-model="form.student_role" />
                            <span class="text-red-400" v-if="page.props.errors.student_role"
                                v-text="page.props.errors.student_role"></span>
                        </div>
                        <div class="relative z-0 is-filled is-focused" :class="{ 'has-error': page.props.errors.status }">
                            <label :class="{ 'text-red-400': page.props.errors.status }" for="status">Status</label>
                            <Dropdown id="status" :options="states" v-model="form.status" />
                            <span class="text-red-400" v-if="page.props.errors.status"
                                v-text="page.props.errors.status"></span>
                        </div>
                    </div>
                </div>
                <div class="flex gap-2 items-center justify-between">
                    <Button type="submit" label="Save" rounded />
                    <Button type="reset" @click="close" label="Cancel" rounded outlined severity="danger" />
                </div>
            </form>
        </div>
    </Dialog>
</template>
