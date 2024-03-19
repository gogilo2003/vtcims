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


const props = defineProps<{
    lesson: iLesson,
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

</script>
<template>
    <Toast position="top-center" />
    <AuthenticatedLayout title="Attendances">
        <div class="flex items-center justify-between gap-2 mb-3 md:pb-8 ">
            <div>
                <Link class="" :href="route('attendances')">
                <Icon class="w-6 h-6 object-contain" type="chevron-circle-back" />
                <span class="hidden md:inline-flex">Attendances</span>
                </Link>

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
            <pre v-text="lesson"></pre>
            <template v-for="intake in lesson.intakes">
                <ListItem v-for="student in intake.students" class="px-4 py-2 rounded-lg shadow-lg bg-white">
                    <pre v-text="student"></pre>
                </ListItem>
            </template>
        </div>
    </AuthenticatedLayout>
</template>
