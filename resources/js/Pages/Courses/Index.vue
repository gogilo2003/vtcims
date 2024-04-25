<script lang="ts" setup>
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout.vue'
import { iInstructor, iCourse, iCourses, iDepartment, iNotification } from '../../interfaces/index';
import Paginator from '../../Components/Paginator.vue';
import SecondaryButton from '../../Components/SecondaryButton.vue';
import { ref, watch } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import { debounce } from 'lodash';
import Icon from '../../Components/Icons/Icon.vue';
import InputText from 'primevue/inputtext';
import ListItem from '@/Components/ListItem.vue';
import InputLabel from '../../Components/InputLabel.vue';
import InputError from '../../Components/InputError.vue';
import PrimaryButton from '../../Components/PrimaryButton.vue';
import Toast from 'primevue/toast';
import { useToast } from 'primevue/usetoast';
import Dropdown from 'primevue/dropdown';

const props = defineProps<{
    courses: iCourses
    instructors: Array<iInstructor>
    departments: Array<iDepartment>
    notification: iNotification
    search: string
}>()

const toast = useToast()

const edit = ref(false)
const title = ref('New Course')
const newCourse = () => {
    cancel()
}
const editCourse = (course: iCourse) => {
    title.value = "Edit Course"

    form.id = course.id
    form.code = course.code
    form.name = course.name
    form.department = course.department?.id
    form.staff = course.staff?.id

    edit.value = true

}

const form = useForm<iCourse>({
    id: null,
    code: "",
    name: "",
    department: null,
    staff: null,
})

const submit = () => {
    if (edit.value) {
        form.patch(route('courses-update', form.id), {
            only: ['errors', 'notification', 'courses'],
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => {
                toast.add({
                    severity: 'success',
                    summary: 'Success',
                    detail: props.notification.success,
                    life: 4000
                })
                cancel()
            },
            onError: () => {
                toast.add({
                    severity: 'error',
                    summary: 'Error',
                    detail: "An error occurred! Please check your fields and try again.",
                    life: 4000
                })
            },
        })
    } else {
        form.post(route('courses-store'), {
            only: ['errors', 'notification', 'courses'],
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => {
                toast.add({
                    severity: 'success',
                    summary: 'Success',
                    detail: props.notification.success,
                    life: 4000
                })
                cancel()
            },
            onError: () => {
                toast.add({
                    severity: 'error',
                    summary: 'Error',
                    detail: "An error occurred! Please check your fields and try again.",
                    life: 4000
                })
            },
        })
    }
}

const cancel = () => {
    form.reset()
    form.clearErrors()
    title.value = "New Course"
    edit.value = false
}

const searchVal = ref(props.search)

watch(() => searchVal.value, debounce((value: string) => {

    let data = {}

    if (value) {
        data = { search: value }
    }

    router.get(route('courses'), data, {
        only: ['courses', 'search'],
        preserveScroll: true,
        preserveState: true
    })
}, 500))

</script>
<template>
    <Toast position="top-center" />
    <AuthenticatedLayout title="Courses">
        <div class="flex items-center justify-between gap-2 pb-3 md:pb-8">
            <SecondaryButton @click="newCourse">
                <Icon type="add" />
                <span class="hidden md:inline-flex">New Course</span>
            </SecondaryButton>
            <div>
                <span class="relative">
                    <span class="pi pi-search absolute top-[50%] -translate-y-[50%] left-2 opacity-50"></span>
                    <InputText v-model="searchVal" placeholder="Search" class="px-8 w-full"
                        :pt="{ root: { class: 'rounded-full focus:ring-primary-500 text-surface-600 dark:text-surface-200 bg-surface-0 dark:bg-surface-700' } }" />
                </span>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-2 flex flex-col gap-2">
                <ListItem v-for="course in courses.data" class="px-4 py-2 rounded-lg shadow-lg bg-white">
                    <div>
                        <div v-text="course.name"
                            class="uppercase text-sm font-semibold text-gray-800 dark:text-primary-500">
                        </div>
                        <div class="flex gap-4 divide-x flex-col md:flex-row">
                            <div class="flex gap-1 flex-col first:pl-0 pl-4">
                                <span class="text-xs font-semibold text-gray-800 dark:text-gray-200">Code:</span>
                                <span v-text="course.code" class="text-xs text-gray-500 dark:text-gray-400"></span>
                            </div>
                            <div class="flex gap-1 flex-col first:pl-0 pl-4">
                                <span class="text-xs font-semibold text-gray-800 dark:text-gray-200">Instructor:</span>
                                <span v-text="course.staff?.name"
                                    class="text-xs text-gray-500 dark:text-gray-400"></span>
                            </div>
                            <div class="flex gap-1 flex-col first:pl-0 pl-4">
                                <span class="text-xs font-semibold text-gray-800 dark:text-gray-200">Department:</span>
                                <span v-text="course.department?.name"
                                    class="text-xs text-gray-500 dark:text-gray-400"></span>
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <SecondaryButton @click="editCourse(course)">
                            <Icon class="h-4 w-4" type="edit" />
                            Edit
                        </SecondaryButton>
                    </div>
                </ListItem>
                <Paginator :items="courses" />
            </div>
            <div class="rounded-lg shadow bg-white p-4">
                <div v-text="title" class="text-lg uppercase pb-3 font-light"></div>
                <form @submit.prevent="submit">
                    <div class="mb-4">
                        <InputLabel value="Department" />
                        <Dropdown :options="departments" optionValue="id" optionLabel="name"
                            v-model="form.department" />
                        <InputError :message="form.errors.department" />
                    </div>
                    <div class="mb-4">
                        <InputLabel value="Code" />
                        <InputText v-model="form.code" />
                        <InputError :message="form.errors.code" />
                    </div>
                    <div class="mb-4">
                        <InputLabel value="Course Name" />
                        <InputText v-model="form.name" />
                        <InputError :message="form.errors.name" />
                    </div>
                    <div class="mb-4">
                        <InputLabel value="Head of Course" />
                        <Dropdown :options="instructors" optionValue="id" optionLabel="name" v-model="form.staff" />
                        <InputError :message="form.errors.staff" />
                    </div>
                    <div class="flex justify-between mt-4">
                        <PrimaryButton :class="{ 'opacity-30': form.processing }" :disabled="form.processing">Save
                        </PrimaryButton>
                        <SecondaryButton type="button" v-if="edit" @click="cancel">Cancel</SecondaryButton>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
