<script lang="ts" setup>
import { ref, watch } from 'vue';
import { iNotification, iRole, iRoles } from '../../../interfaces';
import AuthenticatedLayout from '../../../Layouts/AuthenticatedLayout.vue';
import { debounce } from 'lodash';
import SecondaryButton from '../../../Components/SecondaryButton.vue';
import PrimaryButton from '../../../Components/PrimaryButton.vue';
import Icon from '../../../Components/Icons/Icon.vue';
import InputText from 'primevue/inputtext';
import { computed } from '@vue/reactivity';
import { router, useForm } from '@inertiajs/vue3';
import ListItem from '../../../Components/ListItem.vue';
import InputLabel from '../../../Components/InputLabel.vue';
import InputError from '../../../Components/InputError.vue';
import { useToast } from 'primevue/usetoast';
import MultiSelect from 'primevue/multiselect';
import Toast from 'primevue/toast'

const props = defineProps<{
    roles: iRoles
    permissions: any[]
    search?: string | null
    notification: iNotification
}>()

const form = useForm<{
    id: number | null
    name: string | null
    permissions: string[] | [],
}>({
    id: null,
    name: null,
    permissions: []
})

const toast = useToast();

const searchVal = ref<string | null | undefined>(props?.search)

watch(() => searchVal.value, debounce((value: string) => {
    let options: { search?: string } = {}
    if (value) {
        options = { search: value }
    }

    router.get(route('admin-roles'), options, {
        only: ['roles', 'search'],
        preserveScroll: true,
        preserveState: true,
    })
}, 500))

const newRole = () => {
    cancel()
}

const editRole = (role: iRole) => {
    form.id = role.id
    form.name = role.name
    form.permissions = role.permissions.map(item => item.name)
    form.clearErrors()
}

const title = computed(() => {
    if (form.id) {
        return "Edit Role"
    }
    return "New Role"
})

const submit = () => {
    if (form.id) {
        form.transform((data) => {
            return {
                ...data,
                _method: 'patch'
            }
        }).post(route('admin-roles-update', form.id), {
            only: ['roles', 'errors', 'notification'],
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
                    summary: props.notification.danger ?? 'An error occurred, please check and try again',
                    life: 4000
                })
            }
        })
    } else {
        form.post(route('admin-roles-store'), {
            only: ['roles', 'errors', 'notification'],
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
                    summary: props.notification.danger ?? 'An error occurred, please check and try again',
                    life: 4000
                })
            }
        })
    }
}

const cancel = () => {
    form.id = null
    form.name = null
    form.permissions = []
    form.clearErrors()
    form.reset()
}
</script>
<template>
    <Toast position="top-center" />
    <AuthenticatedLayout title="Roles">
        <div class="flex items-center justify-between gap-2 mb-3 md:pb-8 ">
            <SecondaryButton @click="newRole">
                <Icon type="add" />
                <span class="hidden md:inline-flex">New Role</span>
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
                <ListItem v-for="role in roles.data">
                    <div>
                        <div v-text="role.name"></div>
                        <div class="text-xs text-gray-500 line-clamp-1"
                            v-text="role.permissions.map(item => item.caption).join(', ')"></div>
                    </div>
                    <div>
                        <SecondaryButton @click="editRole(role)">
                            <Icon class="h-4 w-4" type="edit" />
                            <span>Edit</span>
                        </SecondaryButton>
                    </div>
                </ListItem>
            </div>
            <div class="bg-white dark:bg-gray-800 dark:border-gray-700 rounded-lg border shadow p-4">
                <div class="text-xl font-light capitalize pb-3 mb-4 border-b dark:border-gray-700" v-text="title"></div>
                <form @submit.prevent="submit">
                    <div class="mb-4">
                        <InputLabel value="Role Name" />
                        <InputText v-model="form.name" :invalid="form.errors.name ? true : false" />
                        <InputError :message="form.errors.name" />
                    </div>
                    <div class="mb-4">
                        <InputLabel value="Role Permissions" />
                        <MultiSelect filter :options="permissions" optionValue="name" optionLabel="caption"
                            v-model="form.permissions" :invalid="form.errors.permissions ? true : false" />
                        <InputError :message="form.errors.permissions" />
                    </div>
                    <div class="flex justify-between">
                        <PrimaryButton>Save</PrimaryButton>
                        <SecondaryButton @click.prevent="cancel">Cancel</SecondaryButton>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
