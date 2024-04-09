<script lang="ts" setup>
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout.vue'
import { iInstructor, iCourse, iAllocation, iAllocations, iIntake, iAttendance, iTerm, iSubject } from '../../interfaces/index';
import Paginator from '../../Components/Paginator.vue';
import SecondaryButton from '../../Components/SecondaryButton.vue';
import { ref, watch, onMounted, computed } from 'vue';
import { router, useForm, Link } from '@inertiajs/vue3';
import { debounce } from 'lodash';
import Icon from '../../Components/Icons/Icon.vue';
import InputText from 'primevue/inputtext';
import ListItem from '../../Components/ListItem.vue';
import Dialog from 'primevue/dialog';
import Dropdown from 'primevue/dropdown';
import InputError from '../../Components/InputError.vue';
import InputLabel from '../../Components/InputLabel.vue';
import Button from 'primevue/button';
import { useToast } from 'primevue/usetoast';
import Toast from 'primevue/toast';
import MultiSelect from 'primevue/multiselect';
import InputSwitch from 'primevue/inputswitch';
import VueClickAway from 'vue3-click-away';


const props = defineProps<{
    allocations: iAllocations,
    search: string,
    current: string,
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

const showAttendanceDialog = ref(false)
const edit = ref(false)
const dialogTitle = ref('New Attendance')
const currentTerm = ref(props.current)

const newAttendance = () => {
    showAttendanceDialog.value = true
    edit.value = false
}

const editAttendance = (attendance: iAttendance) => {

    form.id = attendance.id
    form.term = attendance.term.id
    form.instructor = attendance.instructor.id
    form.subject = attendance.subject.id
    form.intakes = attendance.intakes.map(intake => intake.id)

    showAttendanceDialog.value = true
    edit.value = true
}

const cancel = () => {
    showAttendanceDialog.value = false
    edit.value = false
    dialogTitle.value = 'New Attendance'
    form.reset();
}

const searchVal = ref(props.search)

watch(() => searchVal.value, debounce((value: string) => {

    let data = {}

    if (value) {
        data = { search: value }
    }

    router.get(route('attendances'), data, {
        only: ['allocations', 'search', 'current'],
        preserveScroll: true,
        preserveState: true
    })
}, 500))

watch(() => currentTerm.value, debounce((value: boolean) => {

    let data = {}

    if (value) {
        data = { current: value }
    }

    router.get(route('attendances'), data, {
        only: ['allocations', 'search', 'current'],
        preserveScroll: true,
        preserveState: true
    })
}, 500))


const submit = () => {
    if (edit.value) {
        form.patch(route('attendances-update', form.id), {
            only: ['notification', 'attendances', 'errors'],
            onSuccess: () => {
                toast.add({
                    severity: 'success',
                    summary: 'Success',
                    detail: props?.notification?.success,
                    life: 8000
                })
                form.reset()
                showAttendanceDialog.value = false
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

        form.post(route('attendances-store'), {
            only: ['notification', 'attendances', 'errors'],
            onSuccess: () => {
                toast.add({
                    severity: 'success',
                    summary: 'Success',
                    detail: props?.notification?.success,
                    life: 8000
                })
                form.reset()
                showAttendanceDialog.value = false
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

const download = (allocation: iAllocation, TYPE = 'pdf') => {
    let link = document.createElement('a');
    link.href = route('attendances-download', { allocation: allocation.id, type: TYPE })

    link.target = '_BLANK'
    link.click()
}

const showId = ref()

</script>
<template>
    <Toast position="top-center" />
    <AuthenticatedLayout title="Attendances">
        <div class="flex items-center justify-between gap-2 mb-3 md:pb-8 ">
            <div>
                <SecondaryButton @click="newAttendance">
                    <Icon type="add" />
                    <span class="hidden md:inline-flex">New Attendance</span>
                </SecondaryButton>

            </div>
            <div class="flex flex-wrap gap-2 items-center relative">

                <label for="current_term" class="flex items-center gap-2">
                    <InputSwitch id="current_term" v-model="currentTerm" />
                    Current Term
                </label>

                <span class="relative flex items-center">
                    <i class="pi pi-search absolute bottom-[50%] translate-y-[50%] left-2 opacity-50" />
                    <InputText v-model="searchVal" placeholder="Search" class="px-8 w-full"
                        :pt="{ root: { class: 'rounded-full focus:ring-primary-500 text-surface-600 dark:text-surface-200 bg-surface-0 dark:bg-surface-700' } }" />
                </span>

            </div>
        </div>
        <div class="flex flex-col gap-2">
            <ListItem v-for="allocation in allocations.data" class="px-4 py-2 rounded-lg shadow-lg bg-white">
                <div>
                    <div class="flex flex-col">
                        <span class="uppercase text-sm font-semibold text-gray-800 dark:text-primary-default"
                            v-text="`${allocation.term.year_name}: ${allocation.subject.name}`"></span>
                        <div class="flex items-start gap-1">
                            <span class="text-xs font-semibold text-gray-800 dark:text-gray-100">Intakes:</span>
                            <div class="flex flex-wrap gap-1">
                                <span v-for="({ name }, index) in allocation.intakes"
                                    v-text="`${name}${index < allocation.intakes.length - 1 ? ', ' : ''}`"
                                    class="text-xs text-gray-500 dark:text-gray-300"></span>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col md:flex-row divide-x">
                        <div class="text-xs flex gap-2 pr-3">
                            <span class='font-semibold text-gray-800 dark:text-gray-200'>Instructor: </span>
                            <span class="capitalize font-medium text-gray-400 dark:text-gray-50"
                                v-text="allocation.instructor.name"></span>
                        </div>
                        <div class="flex gap-2 text-xs pl-3">
                            <span class="font-semibold text-gray-800 dark:text-gray-200">Lessons</span>
                            <span class="capitalize font-medium text-gray-400 dark:text-gray-50"
                                v-text="allocation.lessons.length"></span>
                        </div>
                    </div>
                </div>
                <div class="flex gap-1">
                    <SecondaryButton class="relative group"
                        @click="showId = showId == allocation.id ? showId = null : allocation.id">
                        <Icon class="h-6 w-4 cursor-pointer" type="checkmark" />
                        <span class="hidden md:inline-block">Mark</span>
                        <div class="absolute z-10 top-full grid md:grid-rows-[0fr] md:group-hover:grid-rows-[1fr] gap-2 bg-white shadow w-40 transition-[grid-template-rows] duration-300"
                            :class="{ 'grid-rows-[0fr]': allocation.id !== showId, 'grid-rows-[1fr]': allocation.id == showId }">
                            <div class="flex flex-col overflow-hidden">
                                <Link
                                    class="px-3 py-2 hover:bg-gray-100 whitespace-nowrap transition-colors duration-150"
                                    :href="route('attendances-show-mark', id)"
                                    v-for="{ id, title } in allocation.lessons">
                                {{ title }}
                                </Link>
                            </div>
                        </div>
                    </SecondaryButton>
                    <SecondaryButton class="relative group"
                        @click="showId = showId == allocation.id ? showId = null : allocation.id">
                        <Icon class="h-6 w-4 cursor-pointer" type="upload" />
                        <span class="hidden md:inline-block">Upload</span>
                        <div class="absolute z-10 top-full grid md:grid-rows-[0fr] md:group-hover:grid-rows-[1fr] gap-2 bg-white shadow w-40 transition-[grid-template-rows] duration-300"
                            :class="{ 'grid-rows-[0fr]': allocation.id !== showId, 'grid-rows-[1fr]': allocation.id == showId }">
                            <div class="flex flex-col overflow-hidden">
                                <Link
                                    class="px-3 py-2 hover:bg-gray-100 whitespace-nowrap transition-colors duration-150"
                                    :href="route('attendances-show-mark', id)"
                                    v-for="{ id, title } in allocation.lessons">
                                {{ title }}
                                </Link>
                            </div>
                        </div>
                    </SecondaryButton>
                    <SecondaryButton class="relative group">
                        <Icon class="h-6 w-4 cursor-pointer" type="download" />
                        <span class="hidden md:inline-block">Template</span>
                        <div class="absolute z-10 top-full grid md:grid-rows-[0fr] md:group-hover:grid-rows-[1fr] gap-2 bg-white shadow w-40 transition-[grid-template-rows] duration-300"
                            :class="{ 'grid-rows-[0fr]': allocation.id !== showId, 'grid-rows-[1fr]': allocation.id == showId }">
                            <div class="flex flex-col overflow-hidden">
                                <div @click="download(allocation)"
                                    class="flex items-center w-full px-3 py-2 hover:bg-gray-100 whitespace-nowrap transition-colors duration-150 ">
                                    <Icon class="h-6 w-4 cursor-pointer" type="pdf" />
                                    <span class="hidden md:inline-block">Pdf</span>
                                </div>
                                <div @click="download(allocation, 'excel')"
                                    class="block px-3 py-2 hover:bg-gray-100 whitespace-nowrap transition-colors duration-150">
                                    <Icon class="h-6 w-4 cursor-pointer" type="excel" />
                                    <span class="hidden md:inline-block">Excel</span>
                                </div>
                            </div>
                        </div>
                    </SecondaryButton>
                </div>
            </ListItem>
            <Paginator :items="allocations" />
        </div>
    </AuthenticatedLayout>
</template>
