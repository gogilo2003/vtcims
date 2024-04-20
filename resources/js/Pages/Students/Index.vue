<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { iStudent, iStudents, iItem, iPhoto } from '@/interfaces/index';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Icon from '@/Components/Icons/Icon.vue'
import { ref, watch } from 'vue';
import Student from './Student.vue';
import View from './View.vue';
import Picture from './Picture.vue';
import { router } from '@inertiajs/vue3';
import Paginator from '@/Components/Paginator.vue';
import { debounce } from 'lodash'
import InputText from 'primevue/inputtext';
import ListItem from '@/Components/ListItem.vue';

const props = defineProps<{
    students: iStudents,
    intakes: Array<iItem>,
    programs: Array<iItem>,
    sponsors: Array<iItem>,
    roles: Array<iItem>,
    errors: Object,
    search: String
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

const searchVal = ref(props.search)

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

const download = () => {
    window.open(route('students-download'), '_BLANK')
}
const enrollment = () => { }
</script>

<template>
    <Student :show="show" :edit="edit" @closed="onClose" :student="student" />
    <Picture :show="showPic" @closed="onClosePic" :photo="photo" />
    <View :show="showView" @closed="onCloseView" :student="student" />

    <AuthenticatedLayout title="Students">
        <div class="pb-3 md:pb-8 flex gap-3 justify-between">
            <div class="flex gap-1">
                <SecondaryButton @click="newStudent">
                    <Icon type="add" />
                    <span class="hidden md:inline-flex">New Student</span>
                </SecondaryButton>
                <SecondaryButton @click="download">
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
                    <i class="pi pi-search absolute -top-[40%] translate-y-[50%] left-2 opacity-50" />
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
