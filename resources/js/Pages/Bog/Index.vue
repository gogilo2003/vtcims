<script setup lang="ts">
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout.vue';
import { iBogMember, iBogMembers, iItem, iPhoto } from '../../interfaces/index';
import SecondaryButton from '../../Components/SecondaryButton.vue';
import Icon from '../../Components/Icons/Icon.vue'
import { ref, watch } from 'vue';
import Member from './Member.vue';
import View from './View.vue';
import Picture from './Picture.vue';
import { router } from '@inertiajs/vue3';
import Paginator from '../../Components/Paginator.vue';
import { debounce } from 'lodash'
import InputText from 'primevue/inputtext';
import ListItem from '../../Components/ListItem.vue';

const props = defineProps<{
    students: iBogMembers,
    intakes: Array<iItem>,
    programs: Array<iItem>,
    sponsors: Array<iItem>,
    student_roles: Array<iItem>,
    errors: Object,
    search: String
}>()

const edit = ref(false)
const show = ref(false)
const student = ref<iBogMember>()
const photo = ref<iPhoto>()

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
        birth_cert_no: "",
        idno: null,
        gender: "",
        date_of_admission: null,
        intake_id: null,
        program_id: null,
        sponsor_id: null,
        student_role_id: null,
        status: "",
    }
    show.value = true
}

const editStudent = (STUDENT: iBogMember) => {
    student.value = STUDENT
    show.value = true
    edit.value = true
}

const viewStudent = (STUDENT: iBogMember) => {
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

const uploadPic = (student: iBogMember) => {
    photo.value = {
        id: student.id,
        url: student.photo_url,
        photo: student.photo
    }

    showPic.value = true

}

</script>

<template>
    <Student :show="show" :edit="edit" @closed="onClose" :student="student" />
    <Picture :show="showPic" @closed="onClosePic" :photo="photo" />
    <View :show="showView" @closed="onCloseView" :student="student" />

    <AuthenticatedLayout title="Students">
        <div class="pb-3 md:pb-8 flex gap-3 justify-between">
            <SecondaryButton @click="newStudent">
                <Icon type="add" />
                <span class="hidden md:inline-flex">New Student</span>
            </SecondaryButton>
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
                <div>
                    <div class="text-sm font-semibold uppercase" v-text="student.name"></div>
                    <div
                        class="flex flex-col md:flex-row md:items-center text-xs capitalize text-gray-600 dark:text-gray-400 divide-x">
                        <div class="flex gap-2 md:pr-2">
                            <span class="md:hidden font-semibold uppercase">Admission Number:</span>
                            <span v-text="student.admission_no" class="text-lime-700 dark:text-lime-500"></span>
                        </div>
                        <span class="md:px-2" v-text="student.course_name"></span>
                        <span class="md:px-2" v-text="student.program_name"></span>
                        <span class="md:px-2" v-text="student.sponsor_name"></span>
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
