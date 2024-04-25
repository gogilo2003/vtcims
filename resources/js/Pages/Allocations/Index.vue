<script lang="ts" setup>
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout.vue'
import { iInstructor, iAllocation, iAllocations, iLesson } from '../../interfaces/index';
import Paginator from '../../Components/Paginator.vue';
import SecondaryButton from '../../Components/SecondaryButton.vue';
import PrimaryButton from '../../Components/PrimaryButton.vue';
import { ref, watch } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import { debounce } from 'lodash';
import Icon from '../../Components/Icons/Icon.vue';
import InputText from 'primevue/inputtext';
import ListItem from '../../Components/ListItem.vue';
import Modal from '../../Components/Modal.vue';
import Dropdown from 'primevue/dropdown';
import InputError from '../../Components/InputError.vue';
import InputLabel from '../../Components/InputLabel.vue';
import { useToast } from 'primevue/usetoast';
import Toast from 'primevue/toast';
import MultiSelect from 'primevue/multiselect';
import { getShortDayName } from '../../helpers'

const props = defineProps<{
    allocations: iAllocations,
    instructors: Array<iInstructor>
    subjects: Array<{
        id: number,
        code: string,
        name: string,
    }>
    terms: Array<{
        id: number
        name: string
        year: string
        year_name: string
        start_date: string
        end_date: string
    }>
    intakes: Array<{
        id: number,
        name: string
        start_date: string
        end_date: string
    }>,
    lessons: Array<iLesson>
    search: string,
    notification: Object,
    errors: Object
}>()

const toast = useToast()

const form = useForm({
    id: 0,
    term: 0,
    instructor: 0,
    subject: 0,
    intakes: new Array
})

const formLessons = useForm({
    allocation: null,
    lessons: new Array
})

const showAllocationDialog = ref(false)
const edit = ref(false)
const dialogTitle = ref('New Allocation')

const newAllocation = () => {
    showAllocationDialog.value = true
    edit.value = false
}

const editAllocation = (allocation: iAllocation) => {

    form.id = allocation.id
    form.term = allocation.term.id
    form.instructor = allocation.instructor.id
    form.subject = allocation.subject.id
    form.intakes = allocation.intakes.map(intake => intake.id)

    showAllocationDialog.value = true
    edit.value = true
}

const cancel = () => {
    showAllocationDialog.value = false
    edit.value = false
    dialogTitle.value = 'New Allocation'
    form.reset();
}

const searchVal = ref(props.search)

watch(() => searchVal.value, debounce((value: string) => {

    let data = {}

    if (value) {
        data = { search: value }
    }

    router.get(route('allocations'), data, {
        only: ['allocations', 'search'],
        preserveScroll: true,
        preserveState: true
    })
}, 500))


const submit = () => {
    if (edit.value) {
        form.patch(route('allocations-update', form.id), {
            only: ['notification', 'allocations', 'errors'],
            onSuccess: () => {
                toast.add({
                    severity: 'success',
                    summary: 'Success',
                    detail: props?.notification?.success,
                    life: 8000
                })
                form.reset()
                showAllocationDialog.value = false
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

        form.post(route('allocations-store'), {
            only: ['notification', 'allocations', 'errors'],
            onSuccess: () => {
                toast.add({
                    severity: 'success',
                    summary: 'Success',
                    detail: props?.notification?.success,
                    life: 8000
                })
                form.reset()
                showAllocationDialog.value = false
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

const showLessonsDialog = ref(false)
const selectedAllocation = ref()

const editLessons = (allocation: iAllocation) => {
    showLessonsDialog.value = true
    selectedAllocation.value = allocation
    formLessons.lessons = allocation.lessons.map((item) => item.id)
    formLessons.allocation = allocation.id
}

const toggleLessonSelection = (lessonId: number) => {
    const index = formLessons.lessons.indexOf(lessonId);
    if (index === -1) {
        // Lesson not selected, add it to the array
        formLessons.lessons.push(lessonId);
    } else {
        // Lesson already selected, remove it from the array
        formLessons.lessons.splice(index, 1);
    }
};

const cancelLessons = () => {
    formLessons.reset()
    selectedAllocation.value = null
    showLessonsDialog.value = false
}
const saveLessons = () => {
    formLessons.patch(route('allocations-lessons', formLessons.allocation), {
        only: ['notification', 'allocations'],
        onSuccess: () => {
            toast.add({
                severity: 'success',
                summary: 'Success',
                detail: props?.notification?.success,
                life: 8000
            })
            formLessons.reset()
            showLessonsDialog.value = false
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
</script>
<template>
    <Toast position="top-center" />
    <Modal modal :show="showLessonsDialog">
        <template #header>
            <div>Set Lessons</div>
            <button @click="cancelLessons"></button>
        </template>
        <form @submit.prevent="saveLessons" class="flex flex-col">
            <div class="dark:bg-gray-700 shadow p-3 rounded-lg my-3 flex flex-col gap-2 text-sm capitalize flex-none">
                <div class="flex gap-2">
                    <span class="font-medium dark:text-gray-300">Term:</span><span class="dark:text-gray-100"
                        v-text="selectedAllocation.term.year_name"></span>
                </div>
                <div class="flex gap-2">
                    <span class="font-medium dark:text-gray-300">Subject:</span><span class="dark:text-gray-100"
                        v-text="selectedAllocation.subject.name"></span>
                </div>
                <div class="flex gap-2">
                    <span class="font-medium dark:text-gray-300">Instructor:</span><span class="dark:text-gray-100"
                        v-text="selectedAllocation.instructor.name"></span>
                </div>
                <div class="flex gap-2">
                    <span class="font-medium dark:text-gray-300">Intakes:</span><span class="dark:text-gray-100"
                        v-text="selectedAllocation.intakes.map(intake => intake.name).join(', ')"></span>

                </div>
            </div>
            <div class="flex-1 overflow-y-auto">
                <div class="grid grid-cols-4">
                    <div></div>
                    <div
                        class="font-medium uppercase border dark:border-gray-600 bg-primary-500 p-3 text-gray-100 text-center">
                        <span class="hidden md:block">Lesson 1</span>
                        <span class="md:hidden">L1</span>
                    </div>
                    <div
                        class="font-medium uppercase border dark:border-gray-600 bg-primary-500 p-3 text-gray-100 text-center">
                        <span class="hidden md:block">Lesson 2</span>
                        <span class="md:hidden">L2</span>
                    </div>
                    <div
                        class="font-medium uppercase border dark:border-gray-600 bg-primary-500 p-3 text-gray-100 text-center">
                        <span class="hidden md:block">Lesson 3</span>
                        <span class="md:hidden">L3</span>
                    </div>
                    <template :key="id" v-for="({ id, title, day }, index) in lessons">
                        <div v-if="index % 3 == 0"
                            class="font-semibold px-3 py-6 border dark:border-gray-600 bg-primary-500 text-gray-50 uppercase">
                            <span class="hidden md:block" v-text="day"></span>
                            <span class="md:hidden" v-text="getShortDayName(day)"></span>
                        </div>
                        <div class="border dark:border-gray-600 px-3 py-6 flex items-center justify-center"
                            :class="{ 'bg-primary-300': formLessons.lessons.includes(id) }"
                            @click="toggleLessonSelection(id)">
                            <Icon v-if="formLessons.lessons.includes(id)" class="text-gray-800 h-6 w-6"
                                type="checkmark" />
                        </div>
                    </template>
                </div>
            </div>
        </form>
        <template #footer>
            <PrimaryButton @click="saveLessons">Save</PrimaryButton>
            <SecondaryButton @click="cancelLessons">Cancel
            </SecondaryButton>
        </template>
    </Modal>
    <Modal modal :show="showAllocationDialog">
        <template #header>
            <div v-text="dialogTitle"></div>
            <button @click="cancel">
                <Icon class="h-4 w-4" type="close" />
            </button>
        </template>
        <form @submit.prevent="submit">
            <div class="mb-3 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <InputLabel value="Subject" />
                    <Dropdown :options="subjects" option-value="id" option-label="name" v-model="form.subject" filter />
                    <InputError :message="form.errors.subject" />
                </div>
                <div>
                    <InputLabel value="Instructor" />
                    <Dropdown :options="instructors" option-value="id" option-label="name" v-model="form.instructor"
                        filter />
                    <InputError :message="form.errors.instructor" />
                </div>
                <div>
                    <InputLabel value="Term" />
                    <Dropdown :options="terms" option-value="id" option-label="year_name" v-model="form.term" filter />
                    <InputError :message="form.errors.term" />
                </div>
                <div>
                    <InputLabel value="Intakes" />
                    <MultiSelect :options="intakes" option-value="id" option-label="name" v-model="form.intakes"
                        filter />
                    <InputError :message="form.errors.intakes" />
                </div>
            </div>
        </form>
        <template #footer>
            <PrimaryButton @click="submit">Save</PrimaryButton>
            <SecondaryButton @click="cancel">Cancel</SecondaryButton>
        </template>
    </Modal>
    <AuthenticatedLayout title="Allocations">
        <div class="flex items-center justify-between gap-2 mb-3 md:pb-8 ">
            <SecondaryButton @click="newAllocation">
                <Icon type="add" />
                <span class="hidden md:inline-flex">New Allocation</span>
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
            <ListItem v-for="allocation in allocations.data" class="px-4 py-2 rounded-lg shadow-lg bg-white">
                <div>
                    <div v-text="`${allocation.term.year_name}: ${allocation.subject.name}`"
                        class="uppercase text-sm font-semibold text-gray-800 dark:text-primary-default">
                    </div>
                    <div class="flex flex-col md:flex-row md:divide-x justify-start">
                        <div class="flex items-start gap-1 md:pr-2">
                            <span class="text-xs font-semibold text-gray-800 dark:text-gray-100">Intakes:</span>
                            <div class="flex flex-wrap gap-1">
                                <span v-for="({ name }, index) in allocation.intakes"
                                    v-text="`${name}${index < allocation.intakes.length - 1 ? ', ' : ''}`"
                                    class="text-xs text-gray-500 dark:text-gray-300"></span>
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-2 items-start">
                        <span class="text-xs font-semibold text-gray-800 dark:text-gray-100">Instructor:</span>
                        <span class="uppercase text-xs text-gray-500 dark:text-gray-300"
                            v-text="allocation.instructor.name"></span>
                    </div>
                </div>
                <div class="flex gap-2">
                    <SecondaryButton @click="editLessons(allocation)">
                        <Icon class="h-4 w-4" type="calendar" />
                        <span class="hidden md:inline-block">Lessons</span>
                    </SecondaryButton>
                    <SecondaryButton @click="editAllocation(allocation)">
                        <Icon class="h-4" type="edit" />
                        <span class="hidden md:inline-block">Edit</span>
                    </SecondaryButton>
                </div>
            </ListItem>
            <Paginator :items="allocations" />
        </div>
    </AuthenticatedLayout>
</template>
