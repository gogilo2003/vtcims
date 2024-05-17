<script lang="ts" setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { iItem, iNotification, iUser, iUsers } from '../../../interfaces/index';
import { computed, ref, watch } from 'vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import InputText from 'primevue/inputtext';
import Icon from '@/Components/Icons/Icon.vue';
import { debounce } from 'lodash';
import { router, useForm } from '@inertiajs/vue3';
import ListItem from '@/Components/ListItem.vue';
import Paginator from '@/Components/Paginator.vue';
import { useToast } from 'primevue/usetoast';
import Toast from 'primevue/toast';
import ConfirmPopup from 'primevue/confirmpopup';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import Dropdown from 'primevue/dropdown';
import MultiSelect from 'primevue/multiselect';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { useConfirm } from "primevue/useconfirm";

const props = defineProps<{
    users: iUsers
    staff_members: iItem[]
    roles: iItem[]
    search?: string | null,
    notification: iNotification
}>()

const toast = useToast();
const confirm = useConfirm();

const form = useForm<{
    id: number | null
    name: string | null
    email: string | null
    staff: number | null
    roles: number[]
}>({
    id: null,
    name: null,
    email: null,
    staff: null,
    roles: [],
})

const searchVal = ref(props.search)

watch(() => searchVal.value, debounce((value: string) => {
    let options: { search?: string } = {}
    if (value) {
        options = { search: value }
    }
    router.get(route('admin-users'), options, {
        only: ['users'],
        preserveScroll: true,
        preserveState: true,
    })
}, 500))

const title = computed(() => {
    if (form.id) {
        return 'Edit Users'
    }
    return 'New User'
})

const newUser = () => {
    cancel()
}
const editUser = (user: iUser) => {
    form.clearErrors()
    form.id = user.id
    form.name = user.name
    form.email = user.email
    form.staff = user.staff
    form.roles = user.roles
}

const deleteUser = (event, id: number | null) => {
    confirm.require({
        target: event.currentTarget,
        message: "Are you sure you want to proceed?",
        icon: "pi pi-exclamation-triangle",
        rejectLabel: 'Cancel',
        acceptLabel: 'Delete',
        rejectClass: 'p-button-secondary p-button-outlined p-button-sm',
        accept: () => {
            if (id) {
                router.delete(route('admin-users-destroy', id), {
                    only: ['users', 'notification', 'errors'],
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
                            detail: props.notification.danger ?? 'An error occurred! Please check fields and try again',
                            life: 4000
                        })
                    }
                })
            } else {
                toast.add({
                    severity: 'warn',
                    summary: 'Warning',
                    detail: 'User id must be provided',
                    life: 4000
                })
            }
        },
        reject: () => {
            toast.add({
                severity: 'error',
                summary: 'Error',
                detail: 'Delete action canceled by user',
                life: 4000
            })
        },
    });
}

const cancel = () => {
    form.id = null
    form.name = null
    form.email = null
    form.staff = null
    form.roles = []
    form.clearErrors()
}

const submit = () => {
    if (form.id) {
        form.transform(data => {
            return {
                ...data,
                _method: 'patch'
            }
        }).post(route('admin-users-update', form.id), {
            only: ['users', 'notification', 'errors'],
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
                    detail: props.notification.danger ?? 'An error occurred! Please check fields and try again',
                    life: 4000
                })
            }
        })
    } else {
        form.post(route('admin-users-store'), {
            only: ['users', 'notification', 'errors'],
            onSuccess: () => {
                toast.add({
                    severity: 'success',
                    summary: 'Success',
                    detail: props.notification.success
                })
                cancel()
            },
            onError: () => {
                toast.add({
                    severity: 'error',
                    summary: 'Error',
                    detail: props.notification.danger ?? 'An error occurred! Please check fields and try again',
                    life: 4000
                })
            }
        })
    }
}
</script>
<template>
    <Toast position="top-center" />
    <ConfirmPopup></ConfirmPopup>
    <AuthenticatedLayout title="Users">
        <div class="flex items-center justify-between gap-2 mb-3 md:pb-4 ">
            <SecondaryButton @click="newUser">
                <Icon type="add" />
                <span class="hidden md:inline-flex">New User</span>
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
                <ListItem v-for="user in users.data">
                    <div>
                        <div class="uppercase" v-text="user.name"></div>
                        <div class="text-sm text-gray-800 dark:text-gray-400" v-text="user.email"></div>
                    </div>
                    <div class="flex items-center gap-2">
                        <SecondaryButton @click="editUser(user)">
                            <Icon class="h-4 w-4" type="edit" />
                            <span>Edit</span>
                        </SecondaryButton>
                        <SecondaryButton @click="deleteUser($event, user.id)">
                            <Icon class="h-4 w-4" type="delete" />
                            <span>Delete</span>
                        </SecondaryButton>
                    </div>
                </ListItem>
                <Paginator :items="users" />
            </div>
            <div class="shadow bg-white dark:bg-gray-800 border dark:border-gray-700 rounded-lg p-4">
                <div class="text-xl capitalize font-light border-b dark:border-gray-700 pb-3 mb-3" v-text="title"></div>
                <form @submit.prevent="submit">
                    <div class="mb-4">
                        <InputLabel value="Name" />
                        <InputText v-model="form.name" :invalid="form.errors.name ? true : false" />
                        <InputError :message="form.errors.name" />
                    </div>
                    <div class="mb-4">
                        <InputLabel value="Email" />
                        <InputText type="email" v-model="form.email" :invalid="form.errors.email ? true : false" />
                        <InputError :message="form.errors.email" />
                    </div>
                    <div class="mb-4">
                        <InputLabel value="Staff" />
                        <Dropdown filter :options="staff_members" optionLabel="name" optionValue="id"
                            v-model="form.staff" :invalid="form.errors.staff ? true : false" />
                        <InputError :message="form.errors.staff" />
                    </div>
                    <div class="mb-4">
                        <InputLabel value="Roles" />
                        <MultiSelect filter :options="roles" optionLabel="name" optionValue="id" v-model="form.roles"
                            :invalid="form.errors.roles ? true : false" />
                        <InputError :message="form.errors.roles" />
                    </div>
                    <div class="flex justify-between">
                        <PrimaryButton :class="{ 'opacity-30': form.processing }" :disabled="form.processing">Save
                        </PrimaryButton>
                        <SecondaryButton v-if="form.id">Cancel</SecondaryButton>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
