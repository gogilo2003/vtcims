<script lang="ts" setup>
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout.vue';
import { iStudentRoles, iStudentRole, iNotification } from '../../interfaces/index';
import { ref, watch, computed } from 'vue';
import InputText from 'primevue/inputtext';
import SecondaryButton from '../../Components/SecondaryButton.vue';
import Icon from '../../Components/Icons/Icon.vue';
import ListItem from '../../Components/ListItem.vue';
import { useForm } from '@inertiajs/vue3';
import Toast from 'primevue/toast';
import { useToast } from 'primevue/usetoast';
import InputLabel from '../../Components/InputLabel.vue';
import InputError from '../../Components/InputError.vue';
import PrimaryButton from '../../Components/PrimaryButton.vue';

const props = defineProps<{
    roles: iStudentRoles,
    search?: string
    notification: iNotification
    errors: Object
}>()

const toast = useToast()

const searchVal = ref(props.search)
const edit = ref<boolean>(false)

const form = useForm<{
    id: number | null
    name: string | null
    description?: string | null
}>({
    id: null,
    name: null,
    description: null,
})

const formTitle = computed(() => {
    if (edit.value) {
        return 'Edit Role'
    } else {
        return 'New Role'
    }
})

watch(() => searchVal.value, (value) => {
    console.log(value);

})

const newRole = (role: iStudentRole) => {
    form.id = null
    form.name = null
    form.description = null
    edit.value = false
}

const editRole = (role: iStudentRole) => {
    form.id = role.id
    form.name = role.name
    form.description = role.description
    edit.value = true
}

const cancel = () => {
    form.clearErrors()
    form.reset()
    edit.value = false
}

const submit = () => {
    if (edit.value) {
        form.patch(route('students-roles-update', form.id), {
            only: ['notification', 'errors', 'roles'],
            onSuccess: () => {
                toast.add({
                    severity: 'success',
                    summary: 'Success',
                    detail: props.notification?.success,
                    life: 4000
                })
                cancel()
            },
            onError: () => {
                toast.add({
                    severity: 'error',
                    summary: 'Error',
                    detail: props.notification?.danger ?? 'An error occurred! Please check your fields and try again',
                    life: 4000
                })
            },
        })
    } else {
        form.post(route('students-roles-store'), {
            only: ['notification', 'errors', 'roles'],
            onSuccess: () => {
                toast.add({
                    severity: 'success',
                    summary: 'Success',
                    detail: props.notification?.success,
                    life: 4000
                })
                cancel()
            },
            onError: () => {
                toast.add({
                    severity: 'error',
                    summary: 'Error',
                    detail: props.notification?.danger ?? 'An error occurred! Please check your fields and try again',
                    life: 4000
                })
            },
        })
    }
}
</script>
<template>
    <Toast position="top-center" />
    <AuthenticatedLayout title="Student Roles">
        <div class="pb-3 md:pb-8 flex gap-3 justify-between">
            <div class="flex gap-1">
                <SecondaryButton @click="newRole">
                    <Icon type="add" />
                    <span class="hidden md:inline-flex">New Role</span>
                </SecondaryButton>
            </div>
            <div>
                <span class="relative">
                    <i class="pi pi-search absolute top-[50%] -translate-y-[50%] left-2 opacity-50"></i>
                    <InputText v-model="searchVal" placeholder="Search" class="px-8 w-full"
                        :pt="{ root: { class: 'rounded-full focus:ring-primary-500 text-surface-600 dark:text-surface-200 bg-surface-0 dark:bg-surface-700' } }" />
                </span>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class=" md:col-span-2 flex gap-2 flex-col">
                <ListItem v-for="role in roles.data">
                    <div>
                        <div class="uppercase text-sm font-medium" v-text="role.name"></div>
                        <div class="text-xs font-normal text-gray-800" v-text="role.description"></div>
                    </div>
                    <div>
                        <SecondaryButton @click="editRole(role)">
                            <Icon class="h-4 w-4" type="edit" />
                            <span class="hidden md:inline-flex">Edit</span>
                        </SecondaryButton>
                    </div>
                </ListItem>
            </div>
            <div class="shadow bg-white dark:bg-gray-800 p-3 rounded-lg">
                <div class="text-xl font-light border-b pb-2 mb-3" v-text="formTitle"></div>
                <form @submit.prevent="submit">
                    <div class="mb-4">
                        <InputLabel value="Role name" />
                        <InputText v-model="form.name" />
                        <InputError :message="form.errors.name" />
                    </div>
                    <div class="mb-4">
                        <InputLabel value="Role description" />
                        <InputText v-model="form.description" />
                        <InputError :message="form.errors.description" />
                    </div>
                    <div class="flex justify-between">
                        <PrimaryButton :class="{ 'opacity-30': form.processing }" :disabled="form.processing">
                            Save
                        </PrimaryButton>
                        <SecondaryButton type="button" v-if="edit" @click="cancel">Cancel</SecondaryButton>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
