<script lang="ts" setup>
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout.vue'
import { iInstructor, iCourse, iIntake, iIntakes } from '../../interfaces/index';
import Paginator from '../../Components/Paginator.vue';
import SecondaryButton from '../../Components/SecondaryButton.vue';
import PrimaryButton from '../../Components/PrimaryButton.vue';
import Modal from '../../Components/Modal.vue';
import { ref, watch } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import { debounce } from 'lodash';
import Icon from '../../Components/Icons/Icon.vue';
import InputText from 'primevue/inputtext';
import ListItem from '@/Components/ListItem.vue';
import Dropdown from 'primevue/dropdown';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Calendar from 'primevue/calendar';
import { useToast } from 'primevue/usetoast';
import { generateIntakeName } from '../../helpers';
import Toast from 'primevue/toast';

const props = defineProps<{
    intakes: iIntakes,
    instructors: Array<iInstructor>
    courses: Array<iCourse>
    search?: string,
    notification: Object,
    errors: Object
}>()

const toast = useToast()

const form = useForm<iIntake>({
    id: 0,
    course: 0,
    start_date: null,
    end_date: null,
    instructor: 0,
    name: ""
})

const showIntakeDialog = ref(false)
const edit = ref(false)
const dialogTitle = ref('New Intake')
const course = ref<iCourse>()

const newIntake = () => {
    form.start_date = new Date()
    form.end_date = new Date()

    showIntakeDialog.value = true
    edit.value = false
}

const editIntake = (intake: iIntake) => {
    form.id = intake.id
    form.course = intake.course?.id
    form.start_date = intake.start_date
    form.end_date = intake.end_date ?? prepareEndDate(intake.start_date)
    form.name = intake.name
    form.instructor = intake.instructor?.id

    showIntakeDialog.value = true
    edit.value = true
}

const cancel = () => {
    showIntakeDialog.value = false
    edit.value = false
    dialogTitle.value = 'New Intake'
    form.reset();
}

const searchVal = ref(props.search)

watch(() => searchVal.value, debounce((value: string) => {

    let data = {}

    if (value) {
        data = { search: value }
    }

    router.get(route('intakes'), data, {
        only: ['intakes', 'search'],
        preserveScroll: true,
        preserveState: true
    })
}, 500))

watch(() => form.course, (value) => {
    course.value = props.courses.filter(item => item.id == value)[0]
    if (course.value) {
        form.name = generateIntakeName(course.value.code, form.start_date)
    }
})
watch(() => form.start_date, (value) => {
    course.value = props.courses.filter(item => item.id == form.course)[0]
    form.name = generateIntakeName(course?.value?.code, form.start_date)
})

const submit = () => {

    form.end_date = endDate.value

    if (edit.value) {
        form.patch(route('intakes-update', form.id), {
            only: ['notification', 'intakes', 'errors'],
            onSuccess: () => {
                toast.add({
                    severity: 'success',
                    summary: 'Success',
                    detail: props?.notification?.success,
                    life: 8000
                })
                form.reset()
                showIntakeDialog.value = false
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

        form.post(route('intakes-store'), {
            only: ['notification', 'intakes', 'errors'],
            onSuccess: () => {
                toast.add({
                    severity: 'success',
                    summary: 'Success',
                    detail: props?.notification?.success,
                    life: 8000
                })
                form.reset()
                showIntakeDialog.value = false
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

const selectedCourse = ref<iCourse>({
    id: null,
    name: "",
    code: "",
    duration: 0
})

const endDate = ref<Date | null>()

watch(() => form.course, (value) => {

    selectedCourse.value = props.courses.filter((course: iCourse) => value == course.id)[0]

    if (form.end_date) {
        endDate.value = prepareEndDate(form.start_date)
    }
})

const prepareEndDate = (start: any) => {
    let endDate: Date = new Date(start)
    let year = endDate.getFullYear()
    let month = endDate?.getMonth() + (selectedCourse.value.duration ?? 0)
    let date = endDate.getDate()
    return new Date(year, month, date)
}
</script>
<template>
    <Toast position="top-center" />
    <Modal modal :show="showIntakeDialog">
        <template #header>
            <div v-text="dialogTitle"></div>
            <button @click="cancel">
                <Icon class="h-5 w-5" type="close" />
            </button>
        </template>
        <form @submit.prevent="submit">
            <div class="mb-3 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <InputLabel value="Course" />
                    <Dropdown :options="courses" option-label="name" option-value="id" v-model="form.course" filter />
                    <InputError :message="form.errors.course" />
                </div>
                <div>
                    <InputLabel value="Instructor" />
                    <Dropdown :options="instructors" option-value="id" option-label="name" v-model="form.instructor"
                        filter />
                    <InputError :message="form.errors.instructor" />
                </div>
                <div>
                    <InputLabel value="Start Date" />
                    <Calendar :manualInput="false" dateFormat="D, d MM, yy" v-model="form.start_date" />
                    <InputError :message="form.errors.start_date" />
                </div>
                <div>
                    <InputLabel value="End Date" />
                    <Calendar :manualInput="false" dateFormat="D, d MM, yy" v-model="endDate" />
                    <InputError :message="form.errors.end_date" />
                </div>
            </div>
            <div class="mb-3">
                <InputLabel value="Name" />
                <InputText v-model="form.name" readonly />
                <InputError :message="form.errors.name" />
            </div>
        </form>
        <template #footer>
            <PrimaryButton @click="submit">Save</PrimaryButton>
            <SecondaryButton @click="cancel">Cancel</SecondaryButton>
        </template>
    </Modal>
    <AuthenticatedLayout title="Intakes">
        <div class="flex items-center justify-between gap-2 mb-3 md:pb-8 ">
            <SecondaryButton @click="newIntake">
                <Icon type="add" />
                <span class="hidden md:inline-flex">New Intake</span>
            </SecondaryButton>
            <div>
                <div class="relative">
                    <span class="pi pi-search absolute top-[50%] -translate-y-[50%] left-2 opacity-50"></span>
                    <InputText v-model="searchVal" placeholder="Search" class="px-8 w-full"
                        :pt="{ root: { class: 'rounded-full focus:ring-primary-500 text-surface-600 dark:text-surface-200 bg-surface-0 dark:bg-surface-700' } }" />
                </div>
            </div>
        </div>
        <div class="flex flex-col gap-2">
            <ListItem v-for="intake in intakes.data" class="px-4 py-2 rounded-lg shadow-lg bg-white">
                <div>
                    <div v-text="intake.name"
                        class="uppercase text-sm font-medium text-gray-800 dark:text-primary-default">
                    </div>
                    <div class="flex gap-2 flex-col md:flex-row divide-x">
                        <div class="flex items-center gap-1">
                            <span class="text-xs font-medium text-gray-800 dark:text-gray-100">Course Name:</span>
                            <span v-text="intake.course?.name" class="text-xs text-gray-500 dark:text-gray-300"></span>
                        </div>
                        <div class="flex items-center gap-1 pl-2">
                            <span class="text-xs font-medium text-gray-800 dark:text-gray-100">Head Of
                                Course:</span>
                            <span v-text="intake.instructor?.name"
                                class="text-xs text-gray-500 dark:text-gray-300"></span>
                        </div>
                    </div>
                </div>
                <div>
                    <SecondaryButton @click="editIntake(intake)">
                        <Icon class="h-4" type="edit" />
                        <span class="hidden md:inline-block">Edit</span>
                    </SecondaryButton>
                </div>
            </ListItem>
            <Paginator :items="intakes" />
        </div>
    </AuthenticatedLayout>
</template>
