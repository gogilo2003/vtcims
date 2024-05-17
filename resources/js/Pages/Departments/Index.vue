<script lang="ts" setup>
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout.vue'
import { iInstructor, iDepartments, iNotification, iDepartment } from '../../interfaces/index';
import Paginator from '../../Components/Paginator.vue';
import SecondaryButton from '../../Components/SecondaryButton.vue';
import { ref, watch } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import { debounce } from 'lodash';
import Icon from '../../Components/Icons/Icon.vue';
import InputText from 'primevue/inputtext';
import ListItem from '@/Components/ListItem.vue';
import Toast from 'primevue/toast';
import { useToast } from 'primevue/usetoast';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import Dropdown from 'primevue/dropdown';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps<{
    departments: iDepartments
    instructors: Array<iInstructor>
    notification: iNotification
    search?: string
}>()

const toast = useToast()

const form = useForm<iDepartment>({
    id: null,
    code: "",
    name: "",
    hod: null,

})

const edit = ref(false)

const newDepartment = () => {
    form.reset()
    form.clearErrors()
    edit.value = false
}

const editDepartment = (department: iDepartment) => {
    form.reset()
    form.clearErrors()

    form.id = department.id
    form.code = department.code
    form.name = department.name
    form.hod = department.hod?.id

    edit.value = true
}

const cancel = () => {
    form.reset()
    form.clearErrors()
    edit.value = false
}

const submit = () => {
    if (edit.value) {
        form.patch(route('departments-update', form.id), {
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
                    detail: 'An error occurred! please check the fields and try again',
                    life: 4000
                })
            },
            only: ['notification', 'departments', 'errors']
        })
    } else {
        form.post(route('departments-store'), {
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
                    detail: 'An error occurred! please check the fields and try again',
                    life: 4000
                })
            },
            only: ['notification', 'departments', 'errors']
        })
    }
}

const searchVal = ref(props.search)

watch(() => searchVal.value, debounce((value: string) => {

    let data = {}

    if (value) {
        data = { search: value }
    }

    router.get(route('departments'), data, {
        only: ['departments', 'search'],
        preserveScroll: true,
        preserveState: true
    })
}, 500))

</script>
<template>
    <Toast position="top-center" />
    <AuthenticatedLayout title="Departments">
        <div class="flex items-center justify-between gap-2 pb-3 md:pb-8 ">
            <SecondaryButton @click="newDepartment">
                <Icon type="add" />
                <span class="hidden md:inline-flex">New Department</span>
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
                <ListItem v-for="department in departments.data" class="px-4 py-2 rounded-lg shadow-lg bg-white">
                    <div>
                        <div v-text="department.name"
                            class="uppercase text-sm font-semibold text-gray-800 dark:text-primary-500"></div>
                        <div class="flex gap-2 flex-col md:flex-row">
                            <div class="flex items-center gap-1">
                                <span class="text-xs font-semibold text-gray-800 dark:text-gray-200">CODE:</span>
                                <span v-text="department.code" class="text-xs text-gray-500 dark:text-gray-400"></span>
                            </div>
                            <div class="flex items-center gap-1">
                                <span class="text-xs font-semibold text-gray-800 dark:text-gray-200">Head of
                                    Department:</span>
                                <span v-text="department.hod?.name"
                                    class="text-xs text-gray-500 dark:text-gray-400"></span>
                            </div>
                        </div>
                    </div>
                    <div>
                        <SecondaryButton @click="editDepartment(department)">
                            <Icon type="edit" class="h-4 w-4" />
                            edit
                        </SecondaryButton>
                    </div>
                </ListItem>
                <Paginator :items="departments" />
            </div>
            <div class="shadow rounded-lg bg-white dark:bg-gray-800 p-4">
                <form @submit.prevent="submit">
                    <div class="mb-4">
                        <InputLabel value="Department Code" />
                        <InputText v-model="form.code" />
                        <InputError :message="form.errors.code" />
                    </div>
                    <div class="mb-4">
                        <InputLabel value="Department name" />
                        <InputText v-model="form.name" />
                        <InputError :message="form.errors.name" />
                    </div>
                    <div class="mb-4">
                        <InputLabel value="HOD" />
                        <Dropdown :options="instructors" optionLabel="name" optionValue="id" v-model="form.hod" />
                        <InputError :message="form.errors.hod" />
                    </div>
                    <div class="flex justify-between">
                        <PrimaryButton :class="{ 'opacity-30': form.processing }" :disabled="form.processing">Save
                        </PrimaryButton>
                        <SecondaryButton type="button" @click="cancel">Cancel</SecondaryButton>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
