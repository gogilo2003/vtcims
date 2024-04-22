<script setup lang="ts">
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout.vue';
import { iStudent, iStudents, iItem, iPhoto } from '../../interfaces/index';
import SecondaryButton from '../../Components/SecondaryButton.vue';
import PrimaryButton from '../../Components/PrimaryButton.vue';
import Icon from '../../Components/Icons/Icon.vue'
import { ref, watch } from 'vue';
import Student from './Student.vue';
import View from './View.vue';
import Picture from './Picture.vue';
import { router } from '@inertiajs/vue3';
import Paginator from '../../Components/Paginator.vue';
import { debounce } from 'lodash'
import InputText from 'primevue/inputtext';
import ListItem from '../../Components/ListItem.vue';
import Modal from '../../Components/Modal.vue';
import InputLabel from '../../Components/InputLabel.vue';
import Dropdown from 'primevue/dropdown';

const props = defineProps<{
    students: iStudents,
    departments: null | Array<iItem> | any[],
    courses: null | Array<iItem> | any[],
    intakes: null | Array<iItem> | any[],
    programs: null | Array<iItem> | any[],
    sponsors: null | Array<iItem> | any[],
    roles: null | Array<iItem> | any[],
    errors: Object,
    search?: string
}>()

const edit = ref(false)
const show = ref(false)
const student = ref<iStudent | null>(null)
const photo = ref<iPhoto | null>(null)

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
        date_of_birth: new Date(),
        birth_cert_no: null,
        idno: null,
        gender: false,
        date_of_admission: null,
        intake: null,
        program: null,
        sponsor: null,
        role: null,
        status: "",
        plwd: false,
        plwd_details: "",
    }
    show.value = true
}

const editStudent = (STUDENT: iStudent) => {
    student.value = STUDENT
    show.value = true
    edit.value = true
}

const viewStudent = (STUDENT: iStudent) => {
    console.log('here');

    student.value = STUDENT
    showView.value = true
}

const onClose = (value: boolean) => {
    show.value = value
    edit.value = false
}

const onClosePic = (value: boolean) => {
    showPic.value = value
}

const onCloseView = (value: boolean) => {
    showView.value = value
}

const showPic = ref(false)
const showView = ref(false)

const searchVal = ref<string | null | undefined>(props?.search)

watch(() => searchVal.value, debounce((value: string) => {

    let data = {}

    if (value) {
        data = { search: value }
    }

    router.get(route('students'), data, {
        only: ['students', 'search'],
        preserveScroll: true,
        preserveState: true
    })
}, 500))

const uploadPic = (student: iStudent) => {
    photo.value = {
        id: student.id,
        url: student.photo_url,
        photo: student.photo
    }

    showPic.value = true

}
const showDownload = ref<boolean>()
const downloadForm = ref<{
    t: string | ""// title
    st: string | ""// sub title
    d: iItem | null // department
    c: iItem | null // course
    i: iItem | null // intake
    g: number | null // gender
    su: string | "" // status
    sp: iItem | null // sponsor
    pr: iItem | null // program
    r: number | null // role
    da: string | null // date_of_admission
    age: number | null // age
}>({
    t: "",
    st: "",
    d: null,
    c: null,
    i: null,
    g: null,
    su: "",
    sp: null,
    pr: null,
    r: null,
    da: null,
    age: null,
})
const showDownloadDialog = () => {
    showDownload.value = true
}

const download = () => {

    let data = {};

    if (downloadForm.value.t) {
        data = { ...data, t: downloadForm.value.t }
    }
    if (downloadForm.value.st) {
        data = { ...data, st: downloadForm.value.st }
    }
    if (downloadForm.value.d?.id) {
        data = { ...data, d: downloadForm.value.d.id }
    }
    if (downloadForm.value.c?.id) {
        data = { ...data, c: downloadForm.value.c.id }
    }
    if (downloadForm.value.i?.id) {
        data = { ...data, i: downloadForm.value.i.id }
    }
    if (downloadForm.value.g) {
        data = { ...data, g: downloadForm.value.g }
    }
    if (downloadForm.value.su) {
        data = { ...data, su: downloadForm.value.su }
    }
    if (downloadForm.value.sp?.id) {
        data = { ...data, sp: downloadForm.value.sp.id }
    }
    if (downloadForm.value.pr?.id) {
        data = { ...data, pr: downloadForm.value.pr.id }
    }
    if (downloadForm.value.da) {
        data = { ...data, da: downloadForm.value.da }
    }
    if (downloadForm.value.age) {
        data = { ...data, age: downloadForm.value.age }
    }
    window.open(route('students-download', data), '_BLANK')
}

watch(() => downloadForm.value.d, (value) => {
    downloadForm.value.t = ""
    if (downloadForm.value.d?.id) {
        let name = downloadForm.value.d?.name
        downloadForm.value.t = `Trainees from ${name} Department`
    }
    router.get(route('students'), { d: downloadForm.value.d?.id }, {
        only: ['courses'],
        preserveScroll: true,
        preserveState: true
    })
})
watch(() => downloadForm.value.c, (value) => {

    if (value?.id) {
        let name = downloadForm.value.c?.name

        downloadForm.value.t = `Trainees from ${name} Course`
    }
    router.get(route('students'), { d: downloadForm.value.d?.id, c: downloadForm.value.c?.id }, {
        only: ['intakes'],
        preserveScroll: true,
        preserveState: true
    })
})
watch(() => downloadForm.value.i, (value) => {

    if (value?.id) {
        let name = downloadForm.value.i?.name

        downloadForm.value.t = `Trainees from ${name} Intake`
    }
})
watch(() => downloadForm.value.su, (value) => {

    if (value) {
        let name = downloadForm.value.su

        downloadForm.value.t = `${name} Trainees`
    }
})

const enrollment = () => { }


</script>

<template>
    <Student :show="show" :edit="edit" @closed="onClose" :student="student" />
    <Picture :show="showPic" @closed="onClosePic" :photo="photo" />
    <View :show="showView" @closed="onCloseView" :student="student" />
    <Modal :show="showDownload">
        <template #header>Download Options</template>
        <div class="mb-4">
            <InputLabel value="Report Title" />
            <InputText v-model="downloadForm.t" />
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="mb-4">
                <InputLabel value="Departments" />
                <Dropdown showClear v-model="downloadForm.d" :options="departments" optionLabel="name" />
            </div>
            <div class="mb-4">
                <InputLabel value="Courses" />
                <Dropdown showClear v-model="downloadForm.c" :options="courses" optionLabel="name" />
            </div>
            <div class="mb-4">
                <InputLabel value="Intakes" />
                <Dropdown showClear v-model="downloadForm.i" :options="intakes" optionValue="id" optionLabel="name" />
            </div>
            <div class="mb-4">
                <InputLabel value="Program" />
                <Dropdown showClear v-model="downloadForm.pr" :options="programs" optionLabel="name" />
            </div>
            <div class="mb-4">
                <InputLabel value="Sponsor" />
                <Dropdown showClear v-model="downloadForm.sp" :options="sponsors" optionLabel="name" />
            </div>
            <div class="mb-4">
                <InputLabel value="Gender" />
                <Dropdown showClear v-model="downloadForm.g"
                    :options="[{ id: 0, name: 'Male' }, { id: 1, name: 'Female' }]" optionValue="id"
                    optionLabel="name" />
            </div>
            <div class="mb-4">
                <InputLabel value="Status" />
                <Dropdown showClear v-model="downloadForm.su"
                    :options="['In session', 'On Attachment', 'Completed', 'Dropout']" />
            </div>
        </div>
        <div class="flex justify-between">
            <PrimaryButton @click="download">Download</PrimaryButton>
            <SecondaryButton @click="showDownload = false">Cancel</SecondaryButton>
        </div>
    </Modal>
    <AuthenticatedLayout title="Students">
        <div class="pb-3 md:pb-8 flex gap-3 justify-between">
            <div class="flex gap-1">
                <SecondaryButton @click="newStudent">
                    <Icon type="add" />
                    <span class="hidden md:inline-flex">New Student</span>
                </SecondaryButton>
                <SecondaryButton @click="showDownloadDialog">
                    <Icon class="h-6 w-6" type="download" />
                    <span class="hidden md:inline-flex">Students List</span>
                </SecondaryButton>
                <SecondaryButton @click="enrollment">
                    <Icon class="h-6 w-6" type="download" />
                    <span class="hidden md:inline-flex">Enrollment</span>
                </SecondaryButton>
            </div>
            <div>
                <span class="relative">
                    <i class="pi pi-search absolute -top-[40%] translate-y-[50%] left-2 opacity-50"></i>
                    <InputText v-model="searchVal" placeholder="Search" class="px-8 w-full"
                        :pt="{ root: { class: 'rounded-full focus:ring-primary-500 text-surface-600 dark:text-surface-200 bg-surface-0 dark:bg-surface-700' } }" />
                </span>
            </div>
        </div>
        <div class="flex flex-col gap-2">
            <ListItem v-for="student in students.data">
                <div class="flex items-start md:items-center gap-2">
                    <div class="h-16 w-16 flex-none overflow-hidden p-1 border rounded-lg shadow-lg">
                        <img class="rounded-md h-full w-full object-cover object-top" :src="student.photo_url" alt="">
                    </div>
                    <div class="flex-1">
                        <div class="text-sm font-semibold uppercase"
                            v-text="`${student.first_name}${student.middle_name ? ' ' + student.middle_name : ''} ${student.surname}`">
                        </div>
                        <div
                            class="flex gap-1 md:gap-0 flex-col md:flex-row md:items-center text-xs capitalize text-gray-600 dark:text-gray-400 md:divide-x">
                            <div class="flex gap-2 md:pr-2">
                                <span class="md:hidden font-semibold uppercase">Admission Number:</span>
                                <span v-text="student.admission_no" class="text-lime-700 dark:text-lime-500"></span>
                            </div>
                            <div v-if="student.intake?.course" class="flex items-center gap-1">
                                <span class="md:hidden font-semibold uppercase">Course:</span>
                                <span class="md:px-2" v-text="student?.intake?.course"></span>
                            </div>
                            <div v-if="student.program?.name" class="flex items-center gap-1">
                                <span class="md:hidden font-semibold uppercase">Program:</span>
                                <span class="md:px-2" v-text="student.program?.name"></span>
                            </div>
                            <div v-if="student.sponsor?.name" class="flex items-center gap-1">
                                <span class="md:hidden font-semibold uppercase">Sponsor:</span>
                                <span class="md:px-2" v-text="student.sponsor?.name"></span>
                            </div>
                            <div v-if="student.gender" class="flex items-center gap-1">
                                <span class="md:hidden font-semibold uppercase">Gender:</span>
                                <span class="md:px-2" v-text="student.gender ? 'Female' : 'Male'"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex flex-wrap gap-1">
                    <SecondaryButton @click="editStudent(student)">
                        <Icon class="h-4 w-4" type="edit" /><span class="hidden md:block">edit</span>
                    </SecondaryButton>
                    <SecondaryButton @click="uploadPic(student)">
                        <Icon class="h-4 w-4" type="picture" /><span class="hidden md:block">Picture</span>
                    </SecondaryButton>
                    <SecondaryButton @click="viewStudent(student)">
                        <Icon class="h-4 w-4" type="person-details" /><span class="hidden md:block">Details</span>
                    </SecondaryButton>
                    <SecondaryButton>
                        <Icon class="h-4 w-4" type="delete" /><span class="hidden md:block">Delete</span>
                    </SecondaryButton>
                </div>
            </ListItem>
            <Paginator :items="students" />
        </div>
    </AuthenticatedLayout>
</template>
