<script lang="ts" setup>
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout.vue'
import { iInstructor, iCourse, iAllocation, iAllocations } from '../../interfaces/index';
import Paginator from '../../Components/Paginator.vue';
import SecondaryButton from '../../Components/SecondaryButton.vue';
import { ref, watch } from 'vue';
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

const props = defineProps<{
    allocations: iAllocations,
    instructors: Array<iInstructor>
    courses: Array<iCourse>
    search: string,
    notification: Object,
    errors: Object
}>()

const toast = useToast()

const form = useForm({
    id: 0,
    staff_subject: 0,
    intake: 0,
})

const showAllocationDialog = ref(false)
const edit = ref(false)
const dialogTitle = ref('New Allocation')
const course = ref<iCourse>()

const newAllocation = () => {
    showAllocationDialog.value = true
    edit.value = false
}

const editAllocation = (allocation: iAllocation) => {

    form.id = allocation.id
    form.staff_subject = allocation.staff_subject_id
    form.intake = allocation.intake.id

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
</script>
<template>
    <Toast position="top-center" />
    <Dialog modal :header="dialogTitle" v-model:visible="showAllocationDialog" :style="{ width: '50vw' }"
        :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
        <form @submit.prevent="submit">
            <div class="mb-3 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <InputLabel value="Course" />
                    <Dropdown :options="courses" option-value="id" option-label="name" v-model="form.course" filter />
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
                    <Calendar v-model="form.start_date" />
                    <InputError :message="form.errors.start_date" />
                </div>
                <div>
                    <InputLabel value="End Date" />
                    <Calendar v-model="form.end_date" />
                    <InputError :message="form.errors.end_date" />
                </div>
            </div>
            <div class="mb-3">
                <InputLabel value="Name" />
                <InputText v-model="form.name" readonly />
                <InputError :message="form.errors.name" />
            </div>
            <div class="flex items-center justify-between mt-8">
                <Button type="submit" label="Save" size="small" rounded />
                <Button @click="cancel" label="Cancel" size="small" rounded outlined />
            </div>
        </form>
    </Dialog>
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
                    <div v-text="`${allocation.term.year_name}: ${allocation.subject.name} by ${allocation.staff.name}`"
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
                    <SecondaryButton @click="editAllocation(allocation)">
                        <Icon class="h-4" type="edit" />
                        <span class="hidden md:inline-block">Edit</span>
                    </SecondaryButton>
                </div>
            </ListItem>
            <Paginator :items="allocations" />
        </div>
        <pre v-text="instructors"></pre>
    </AuthenticatedLayout>
</template>
