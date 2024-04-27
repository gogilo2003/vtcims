<script lang="ts" setup>
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout.vue'
import { iInstructor, iSubject, iSubjects, iNotification, iItem } from '../../interfaces/index';
import Paginator from '../../Components/Paginator.vue';
import SecondaryButton from '../../Components/SecondaryButton.vue';
import PrimaryButton from '../../Components/PrimaryButton.vue';
import { ref, watch } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import { debounce } from 'lodash';
import Icon from '../../Components/Icons/Icon.vue';
import InputText from 'primevue/inputtext';
import ListItem from '@/Components/ListItem.vue';
import { useToast } from 'primevue/usetoast';
import Toast from 'primevue/toast'
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import MultiSelect from 'primevue/multiselect';

const props = defineProps<{
    subjects: iSubjects
    courses: Array<iItem>
    notification: iNotification
    search: string
}>()

const toast = useToast()

const form = useForm<iSubject>({
    id: null,
    code: "",
    name: "",
    courses: [],
})
const edit = ref(false)
const title = ref('New Subject')

const newSubject = () => { }
const editSubject = (subject: iSubject) => {
    form.id = subject.id
    form.code = subject.code
    form.name = subject.name
    form.courses = subject.courses.map(course => course.id)

    edit.value = true
    title.value = "Edit Subject"
}
const cancel = () => {
    form.reset()
    form.clearErrors()
    edit.value = false
    title.value = "New Subject"
}

const submit = () => {
    if (edit.value) {
        form.patch(route('subjects-update', form.id), {
            only: ['notification', 'subjects', 'errors'],
            onSuccess: () => {
                toast.add({
                    severity: 'success',
                    summary: 'Success',
                    detail: props?.notification?.success,
                    life: 4000
                })
                cancel()
            },
            onError: () => {
                toast.add({
                    severity: 'error',
                    summary: 'Error',
                    detail: props?.notification?.danger ?? 'An error ocurred! Please try gain',
                    life: 4000
                })
            }
        })
    } else {

        form.post(route('subjects-store'), {
            only: ['notification', 'subjects', 'errors'],
            onSuccess: () => {
                toast.add({
                    severity: 'success',
                    summary: 'Success',
                    detail: props?.notification?.success,
                    life: 4000
                })
                cancel()
            },
            onError: () => {
                toast.add({
                    severity: 'error',
                    summary: 'Error',
                    detail: props?.notification?.danger ?? 'An error ocurred! Please try gain',
                    life: 4000
                })
            }
        })
    }
}

const searchVal = ref(props.search)

watch(() => searchVal.value, debounce((value: string) => {

    let data = {}

    if (value) {
        data = { search: value }
    }

    router.get(route('subjects'), data, {
        only: ['subjects', 'search'],
        preserveScroll: true,
        preserveState: true
    })
}, 500))

const deleteSubject = (id: number) => {
    router.delete(route('subjects-destroy', id), {
        only: ['notification', 'subjects', 'errors'],
        onSuccess: () => {
            if (props.notification?.success) {
                toast.add({
                    severity: 'success',
                    summary: 'Success',
                    detail: props?.notification?.success,
                    life: 4000
                })
                cancel()
            } else {
                toast.add({
                    severity: 'error',
                    summary: 'Error',
                    detail: props?.notification?.danger ?? 'An error ocurred! Please try gain',
                    life: 4000
                })
            }
        },
        onError: () => {
            toast.add({
                severity: 'error',
                summary: 'Error',
                detail: props?.notification?.danger ?? 'An error ocurred! Please try gain',
                life: 4000
            })
        }
    })
}
</script>
<template>
    <Toast position="top-center" />
    <AuthenticatedLayout title="Subjects">
        <div class="flex items-center justify-between gap-2 mb-3 md:pb-8 ">
            <SecondaryButton @click="newSubject">
                <Icon type="add" />
                <span class="hidden md:inline-flex">New Subject</span>
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
                <ListItem v-for="subject in subjects.data" class="px-4 py-2 rounded-lg shadow-lg bg-white">
                    <div>
                        <div v-text="subject.name"
                            class="uppercase text-sm font-semibold text-gray-800 dark:text-primary-500">
                        </div>
                        <div class="text-xs flex gap-3">
                            <div class="flex gap-1"><span class="font-medium">CODE:</span>
                                <span class="text-gray-500 dark:text-gray-400" v-text="subject.code"></span>
                            </div>
                            <div class="flex gap-1">
                                <span class="font-semibold text-gray-800 dark:text-gray-200">COURSES:</span>
                                <span v-text="subject.courses.map(course => course.code).join(', ')"
                                    class="text-gray-500 dark:text-gray-400"></span>
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <SecondaryButton @click="editSubject(subject)">
                            <Icon class="h-4 w-4" type="edit" />
                            Edit
                        </SecondaryButton>
                        <!--
                        <SecondaryButton @click="deleteSubject(subject.id)">
                            <Icon class="h-4 w-4" type="delete" />
                            Delete
                        </SecondaryButton>
                    -->
                    </div>
                </ListItem>
                <Paginator :items="subjects" />
            </div>
            <div class="shadow rounded-lg bg-white p-4">
                <div v-text="title" class="text-lg font-light uppercase"></div>
                <form @submit.prevent="submit">
                    <div class="mb-4">
                        <InputLabel value="Subject Code" />
                        <InputText v-model="form.code" />
                        <InputError :message="form.errors.code" />
                    </div>
                    <div class="mb-4">
                        <InputLabel value="Subject Name" />
                        <InputText v-model="form.name" />
                        <InputError :message="form.errors.name" />
                    </div>
                    <div class="mb-4">
                        <InputLabel value="Courses" />
                        <MultiSelect :filter="true" :options="courses" optionLabel="name" optionValue="id"
                            v-model="form.courses" />
                        <InputError :message="form.errors.courses" />
                    </div>
                    <div class="flex justify-between mt-4">
                        <PrimaryButton :class="{ 'opacity-30': form.processing }" :disabled="form.processing">
                            Save
                        </PrimaryButton>
                        <SecondaryButton @click="cancel" v-if="edit">Cancel</SecondaryButton>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
