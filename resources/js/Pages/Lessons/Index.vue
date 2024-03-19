<script lang="ts" setup>
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout.vue'
import { iInstructor, iLink, iCourse, iLesson } from '../../interfaces/index';
import Paginator from '../../Components/Paginator.vue';
import SecondaryButton from '../../Components/SecondaryButton.vue';
import { ref, watch } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import { debounce } from 'lodash';
import Icon from '../../Components/Icons/Icon.vue';
import InputText from 'primevue/inputtext';
import ListItem from '@/Components/ListItem.vue';
import Dialog from 'primevue/dialog';
import Dropdown from 'primevue/dropdown';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Calendar from 'primevue/calendar';
import Button from 'primevue/button';
import { useToast } from 'primevue/usetoast';
import Toast from 'primevue/toast';

const props = defineProps<{
    lessons: {
        current_page: number,
        first_page_url: string,
        from: number,
        last_page: number,
        last_page_url: string,
        links: Array<iLink>,
        next_page_url: string,
        path: string,
        per_page: number,
        prev_page_url: string,
        to: number,
        total: number
        data: Array<iLesson>
    },
    search: string,
    notification: Object,
    errors: Object
}>()

const toast = useToast()

const form = useForm({
    id: 0,
    title: "",
    day: "",
    start_at: null,
    end_at: null,
})

const showLessonDialog = ref(false)
const edit = ref(false)
const dialogTitle = ref('New Lesson')

const newLesson = () => {
    showLessonDialog.value = true
    edit.value = false
}

const editLesson = (lesson: iLesson) => {

    form.id = lesson.id
    form.title = lesson.title
    form.day = lesson.day
    form.start_at = lesson.start_at
    form.end_at = lesson.end_at

    showLessonDialog.value = true
    edit.value = true
}

const cancel = () => {
    showLessonDialog.value = false
    edit.value = false
    dialogTitle.value = 'New Lesson'
    form.reset();
}

const searchVal = ref(props.search)

watch(() => searchVal.value, debounce((value: string) => {

    let data = {}

    if (value) {
        data = { search: value }
    }

    router.get(route('lessons'), data, {
        only: ['lessons', 'search'],
        preserveScroll: true,
        preserveState: true
    })
}, 500))


const submit = () => {
    if (edit.value) {
        form.patch(route('lessons-update', form.id), {
            only: ['notification', 'lessons', 'errors'],
            onSuccess: () => {
                toast.add({
                    severity: 'success',
                    summary: 'Success',
                    detail: props?.notification?.success,
                    life: 8000
                })
                form.reset()
                showLessonDialog.value = false
            },
            onError: () => {
                toast.add({
                    severity: 'error',
                    summary: 'Error',
                    detail: props?.notification?.danger ?? 'An error ocurred! Please try gain',
                    life: 8000
                })
            }
        })
    } else {

        form.post(route('lessons-store'), {
            only: ['notification', 'lessons', 'errors'],
            onSuccess: () => {
                toast.add({
                    severity: 'success',
                    summary: 'Success',
                    detail: props?.notification?.success,
                    life: 8000
                })
                form.reset()
                showLessonDialog.value = false
            },
            onError: () => {
                toast.add({
                    severity: 'error',
                    summary: 'Error',
                    detail: props?.notification?.danger ?? 'An error ocurred! Please try gain',
                    life: 8000
                })
            }
        })
    }

}
const days = ref(["Monday", "Tuesday", "Wednesday", "Thursday", "Friday"])
</script>
<template>
    <Toast position="top-center" />
    <Dialog modal :header="dialogTitle" v-model:visible="showLessonDialog" :style="{ width: '50vw' }"
        :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
        <form @submit.prevent="submit">
            <div class="mb-3 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <InputLabel value="Title" />
                    <InputText v-model="form.title" filter />
                    <InputError :message="form.errors.day" />
                </div>
                <div>
                    <InputLabel value="Day" />
                    <Dropdown :options="days" v-model="form.day" filter />
                    <InputError :message="form.errors.day" />
                </div>
                <div>
                    <InputLabel value="Start Time" />
                    <Calendar v-model="form.start_at" timeOnly />
                    <InputError :message="form.errors.start_at" />
                </div>
                <div>
                    <InputLabel value="End Time" />
                    <Calendar v-model="form.end_at" timeOnly />
                    <InputError :message="form.errors.end_at" />
                </div>
            </div>
            <div class="flex items-center justify-between mt-8">
                <Button type="submit" label="Save" size="small" rounded />
                <Button @click="cancel" label="Cancel" size="small" rounded outlined />
            </div>
        </form>
    </Dialog>
    <AuthenticatedLayout title="Lessons">
        <div class="flex items-center justify-between gap-2 mb-3 md:pb-8 ">
            <SecondaryButton @click="newLesson">
                <Icon type="add" />
                <span class="hidden md:inline-flex">New Lesson</span>
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
            <ListItem v-for="lesson in lessons.data" class="px-4 py-2 rounded-lg shadow-lg bg-white">
                <div>
                    <div v-text="lesson.title"
                        class="uppercase text-sm font-semibold text-gray-800 dark:text-primary-default">
                    </div>
                    <div class="flex gap-2 flex-col md:flex-row divide-x">
                        <div class="flex items-center gap-1">
                            <span class="text-xs font-semibold text-gray-800 dark:text-gray-100">Day:</span>
                            <span v-text="lesson.day" class="text-xs text-gray-500 dark:text-gray-300"></span>
                        </div>
                        <div class="flex items-center gap-1 pl-2">
                            <span class="text-xs font-semibold text-gray-800 dark:text-gray-100">Time:</span>
                            <span v-text="lesson.start_at" class="text-xs text-gray-500 dark:text-gray-300"></span> -
                            <span v-text="lesson.end_at" class="text-xs text-gray-500 dark:text-gray-300"></span>
                        </div>
                    </div>
                </div>
                <div>
                    <SecondaryButton @click="editLesson(lesson)">
                        <Icon class="h-4" type="edit" />
                        <span class="hidden md:inline-block">Edit</span>
                    </SecondaryButton>
                </div>
            </ListItem>
            <Paginator :items="lessons" />
        </div>
    </AuthenticatedLayout>
</template>
