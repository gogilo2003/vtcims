<script lang="ts" setup>
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout.vue'
import { iNotification, iProgram, iPrograms } from '../../interfaces/index';
import Paginator from '../../Components/Paginator.vue';
import SecondaryButton from '../../Components/SecondaryButton.vue';
import { ref, watch } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import { debounce } from 'lodash';
import Icon from '../../Components/Icons/Icon.vue';
import InputText from 'primevue/inputtext';
import ListItem from '@/Components/ListItem.vue';
import { useToast } from 'primevue/usetoast';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Toast from 'primevue/toast'

const props = defineProps<{
    programs: iPrograms
    search: string
    notification: iNotification
}>()

const toast = useToast()

const searchVal = ref(props.search)

watch(() => searchVal.value, debounce((value: string) => {

    let data = {}

    if (value) {
        data = { search: value }
    }

    router.get(route('programs'), data, {
        only: ['programs', 'search'],
        preserveScroll: true,
        preserveState: true
    })
}, 500))

const form = useForm<iProgram>({
    id: null,
    name: "",
    description: ""
})

const edit = ref(false)
const title = ref('New Program')

const newProgram = () => {
    cancel()
}

const editProgram = (program: iProgram) => {
    title.value = 'edit Program'
    form.id = program.id
    form.name = program.name
    form.description = program.description
    edit.value = true
}

const cancel = () => {

    title.value = 'New Program'
    form.clearErrors()
    form.reset()
    edit.value = false
}

const submit = () => {
    if (edit.value) {
        form.patch(route('programs-update', form.id), {
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => {
                console.log('success');

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
            only: ['notification', 'programs']
        })
    } else {
        form.post(route('programs-store'), {
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
            only: ['notification', 'programs']
        })
    }
}
</script>
<template>
    <Toast position="top-center" />
    <AuthenticatedLayout title="Programs">
        <div class="flex items-center justify-between gap-2 mb-3 md:pb-8 ">
            <SecondaryButton @click="newProgram">
                <Icon type="add" />
                <span class="hidden md:inline-flex">New Program</span>
            </SecondaryButton>
            <div>
                <span class="relative">
                    <i class="pi pi-search absolute -top-[40%] translate-y-[50%] left-2 opacity-50" />
                    <InputText v-model="searchVal" placeholder="Search" class="px-8 w-full"
                        :pt="{ root: { class: 'rounded-full focus:ring-primary-500 text-surface-600 dark:text-surface-200 bg-surface-0 dark:bg-surface-700' } }" />
                </span>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <div class="md:col-span-2 flex flex-col gap-2">
                <ListItem v-for="program in programs.data" class="px-4 py-2 rounded-lg shadow-lg bg-white">
                    <div>
                        <div v-text="program.name"
                            class="uppercase text-sm font-semibold text-gray-800 dark:text-primary-500">
                        </div>
                        <div v-text="program.description" class="text-xs text-gray-600 dark:text-gray-300"></div>
                    </div>
                    <div class="flex gap-2">
                        <SecondaryButton @click="editProgram(program)">
                            <Icon class="h-4 w-4" type="edit" /> Edit
                        </SecondaryButton>
                        <SecondaryButton @click="deleteProgram(program)">
                            <Icon class="h-4 w-4" type="delete" /> Delete
                        </SecondaryButton>
                    </div>
                </ListItem>
                <Paginator :items="programs" />
            </div>
            <div class="rounded-lg shadow bg-white p-4">
                <div class="font-light capitalize text-2xl" v-text="title"></div>
                <form @submit.prevent="submit">
                    <div class="mb-4">
                        <InputLabel value="Name" />
                        <InputText v-model="form.name" />
                        <InputError :message="form.errors.name" />
                    </div>
                    <div class="mb-4">
                        <InputLabel value="Description" />
                        <InputText v-model="form.description" />
                        <InputError :message="form.errors.description" />
                    </div>
                    <div class="flex justify-between">
                        <PrimaryButton>Save</PrimaryButton>
                        <SecondaryButton type="button" @click="cancel">Cancel</SecondaryButton>
                    </div>
                </form>
            </div>
        </div>

    </AuthenticatedLayout>
</template>
