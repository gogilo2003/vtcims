<script lang="ts" setup>
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout.vue'
import { iInstructor, iCourse, iAllocation, iAllocations, iIntake, iAttendance, iTerm, iSubject } from '../../interfaces/index';
import Paginator from '../../Components/Paginator.vue';
import SecondaryButton from '../../Components/SecondaryButton.vue';
import { ref, watch, onMounted, computed } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
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

const download = (id, TYPE = 'pdf') => {
    let link = document.createElement('a');
    link.href = route('attendances-download', { allocation: id, type: TYPE })

    link.target = '_BLANK'
    link.click()
}

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
                        <span class="capitalize text-xs font-bold text-gray-600 dark:text-primary-300"
                            v-html="`By <span class='italic'>${allocation.instructor.name}</span>`"></span>
                    </div>
                    <div class="flex gap-2 flex-col md:flex-row divide-x">
                        <div class="flex items-start gap-1">
                            <span class="text-xs font-semibold text-gray-800 dark:text-gray-100">Intakes:</span>
                            <div class="flex flex-wrap gap-1">
                                <span v-for="({ name }, index) in allocation.intakes"
                                    v-text="`${name}${index < allocation.intakes.length - 1 ? ', ' : ''}`"
                                    class="text-xs text-gray-500 dark:text-gray-300"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex gap-1">
                    <!-- <SecondaryButton @click="download(allocation.id)"> -->
                    <Icon class="h-8 w-6 cursor-pointer" type="pdf" @click="download(allocation.id)" />
                    <!-- <span class="hidden md:inline-block">Download</span> -->
                    <!-- </SecondaryButton> -->
                    <!-- <SecondaryButton @click="download(allocation.id, 'excel')"> -->
                    <Icon class="h-8 w-6 cursor-pointer" type="excel" @click="download(allocation.id, 'excel')" />
                    <!-- <span class="hidden md:inline-block">Download</span> -->
                    <!-- </SecondaryButton> -->
                </div>
            </ListItem>
            <Paginator :items="allocations" />
        </div>
    </AuthenticatedLayout>
</template>
