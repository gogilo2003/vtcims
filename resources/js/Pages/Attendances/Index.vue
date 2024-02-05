<script lang="ts" setup>
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout.vue'
import { iInstructor, iCourse, iAllocation, iAllocations, iIntake } from '../../interfaces/index';
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
import Calendar from 'primevue/calendar';
import Button from 'primevue/button';
import { useToast } from 'primevue/usetoast';
import Toast from 'primevue/toast';
import MultiSelect from 'primevue/multiselect';


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

const showAttendanceDialog = ref(false)
const edit = ref(false)
const dialogTitle = ref('New Attendance')

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
        only: ['attendances', 'search'],
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

</script>
<template>
    <Toast position="top-center" />
    <Dialog modal :header="dialogTitle" v-model:visible="showAttendanceDialog" :pt="{
        root: { class: 'w-full md:w-72 lg:w-[48rem]' }
    }">
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
                    <MultiSelect :options="intakes" option-value="id" option-label="name" v-model="form.intakes" filter />
                    <InputError :message="form.errors.intakes" />
                </div>
            </div>
            <div class="flex items-center justify-between mt-8">
                <Button type="submit" label="Save" size="small" rounded />
                <Button @click="cancel" label="Cancel" size="small" rounded outlined />
            </div>
        </form>
    </Dialog>
    <AuthenticatedLayout title="Attendances">
        <div class="flex items-center justify-between gap-2 mb-3 md:pb-8 ">
            <SecondaryButton @click="newAttendance">
                <Icon type="add" />
                <span class="hidden md:inline-flex">New Attendance</span>
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
                    <div v-text="`${allocation.term.year_name}: ${allocation.subject.name} by ${allocation.instructor.name}`"
                        class="uppercase text-sm font-semibold text-gray-800 dark:text-primary-default">
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
                <div>
                    <SecondaryButton @click="editAttendance(allocation)">
                        <Icon class="h-4" type="edit" />
                        <span class="hidden md:inline-block">Edit</span>
                    </SecondaryButton>
                </div>
            </ListItem>
            <Paginator :items="allocations" />
        </div>
    </AuthenticatedLayout>
</template>
