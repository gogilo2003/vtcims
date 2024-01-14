<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { iStudent, iStudents } from '@/interfaces/index';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Icon from '@/Components/Icons/Icon.vue'
import { ref } from 'vue';
import Student from './Student.vue';
import { iItem } from '../../interfaces/index';
import { useForm } from '@inertiajs/vue3';
import Dialog from 'primevue/dialog';

defineProps<{
    students: iStudents,
    intakes: Array<iItem>,
    programs: Array<iItem>,
    sponsors: Array<iItem>,
    student_roles: Array<iItem>,
    errors: Object
}>()

const edit = ref(false)
const show = ref(false)
const student = ref({
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

const newStudent = () => {
    student.value = {
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
    show.value = true
}

const editStudent = (STUDENT: iStudent) => {
    show.value = true
    edit.value = true
    student.value = STUDENT
}

const onClose = (value: boolean) => {
    show.value = value
    edit.value = false
}


const showPic = ref(false)
const picForm = useForm({
    id: null,
    photo: null
})
const uploadPic = (id: any) => {
    showPic.value = true
    picForm.id = id
}
</script>

<template>
    <Student :show="show" :edit="edit" @closed="onClose" :student="student" />
    <Dialog v-model:visible="showPic" modal header="Upload Picture">
        welcome
        <p v-text="picForm.id"></p>
    </Dialog>
    <AuthenticatedLayout title="Students">
        <div class="pb-3 md:pb-8">
            <SecondaryButton @click="newStudent">
                <Icon type="add" />
                <span>New Student</span>
            </SecondaryButton>
        </div>
        <div class="flex flex-col gap-2">
            <div class="px-5 py-3 shadow bg-white rounded-lg flex flex-col md:flex-row gap-2 md:items-center justify-between"
                v-for="student in students.data">
                <div>
                    <div class="text-sm font-semibold uppercase" v-text="student.name"></div>
                    <div class="flex flex-col md:flex-row gap-2 md:items-center text-xs capitalize text-gray-600">
                        <div class="flex gap-2">
                            <span class="md:hidden font-semibold uppercase">Admission Number:</span>
                            <span v-text="student.admission_no" class="text-lime-700"></span>
                        </div>
                        <span v-text="student.course_name"></span>
                        <span v-text="student.program_name"></span>
                        <span v-text="student.sponsor_name"></span>
                    </div>
                </div>
                <div class="flex flex-wrap gap-1">
                    <SecondaryButton @click="editStudent(student)">
                        <Icon class="h-4 w-4" type="edit" /><span class="hidden md:block">edit</span>
                    </SecondaryButton>
                    <SecondaryButton @click="uploadPic(student.id)">
                        <Icon class="h-4 w-4" type="picture" /><span class="hidden md:block">Picture</span>
                    </SecondaryButton>
                    <SecondaryButton>
                        <Icon class="h-4 w-4" type="person-details" /><span class="hidden md:block">Details</span>
                    </SecondaryButton>
                    <SecondaryButton>
                        <Icon class="h-4 w-4" type="delete" /><span class="hidden md:block">Delete</span>
                    </SecondaryButton>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
