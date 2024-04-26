<script lang="ts" setup>
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout.vue'
import { iInstructor, iTerms, iNotification, iTerm } from '../../interfaces/index';
import Paginator from '../../Components/Paginator.vue';
import SecondaryButton from '../../Components/SecondaryButton.vue';
import { ref, watch, computed } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import { debounce } from 'lodash';
import Icon from '../../Components/Icons/Icon.vue';
import InputText from 'primevue/inputtext';
import ListItem from '../../Components/ListItem.vue';
import Toast from 'primevue/toast';
import { useToast } from 'primevue/usetoast';
import InputLabel from '../../Components/InputLabel.vue';
import InputError from '../../Components/InputError.vue';
import Dropdown from 'primevue/dropdown';
import PrimaryButton from '../../Components/PrimaryButton.vue';
import Calendar from 'primevue/calendar';

const props = defineProps<{
    terms: iTerms
    notification: iNotification
    search: string
}>()

const toast = useToast()

const form = useForm<iTerm>({
    id: null,
    year: null,
    name: "",
    start_date: null,
    end_date: null,
})

const edit = ref(false)

const newTerm = () => {
    form.reset()
    form.clearErrors()
    edit.value = false
}

const editTerm = (term: iTerm) => {
    form.reset()
    form.clearErrors()

    form.id = term.id
    form.name = term.name
    selectedYear.value = new Date(`1-Jan-${term.year}`)
    form.start_date = term.start_date
    form.end_date = term.end_date

    edit.value = true
}

const cancel = () => {
    form.reset()
    form.clearErrors()
    selectedYear.value = new Date()
    edit.value = false
}

const submit = () => {
    if (edit.value) {
        form.patch(route('terms-update', form.id), {
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
            only: ['notification', 'terms']
        })
    } else {
        form.post(route('terms-store'), {
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
            only: ['notification', 'terms']
        })
    }
}

const searchVal = ref(props.search)

watch(() => searchVal.value, debounce((value: string) => {

    let data = {}

    if (value) {
        data = { search: value }
    }

    router.get(route('terms'), data, {
        only: ['terms', 'search'],
        preserveScroll: true,
        preserveState: true
    })
}, 500))

const selectedYear = ref<Date | string | number | null>(form.year)

watch(() => selectedYear.value, (value) => {
    let dt = value ? new Date(value) : new Date()
    form.year = dt.getFullYear()
})

</script>
<template>
    <Toast position="top-center" />
    <AuthenticatedLayout title="Terms">
        <div class="flex items-center justify-between gap-2 pb-3 md:pb-8 ">
            <SecondaryButton @click="newTerm">
                <Icon type="add" />
                <span class="hidden md:inline-flex">New Term</span>
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
                <ListItem v-for="term in terms.data" class="px-4 py-2 rounded-lg shadow-lg bg-white">
                    <div>
                        <div v-text="`${term.year} - ${term.name}`"
                            class="uppercase text-base font-semibold text-gray-800 dark:text-primary-500"></div>
                        <div class="flex gap-2 flex-col md:flex-row text-sm">
                            <div class="flex items-center gap-1">
                                <span class="font-medium text-gray-700 dark:text-gray-200">Start Date:</span>
                                <span v-text="term.start_date" class="text-gray-500 dark:text-gray-400"></span>
                            </div>
                            <div class="flex items-center gap-1">
                                <span class="text-xs font-medium text-gray-700 dark:text-gray-200">End Date:</span>
                                <span v-text="term.end_date" class="text-xs text-gray-500 dark:text-gray-400"></span>
                            </div>
                        </div>
                    </div>
                    <div>
                        <SecondaryButton @click="editTerm(term)">
                            <Icon type="edit" class="h-4 w-4" />
                            edit
                        </SecondaryButton>
                    </div>
                </ListItem>
                <Paginator :items="terms" />
            </div>
            <div class="shadow rounded-lg bg-white p-4">
                <form @submit.prevent="submit">
                    <div class="mb-4">
                        <InputLabel value="Year" />
                        <Calendar view="year" dateFormat="yy" v-model="selectedYear" />
                        <InputError :message="form.errors.year" />
                    </div>
                    <div class="mb-4">
                        <InputLabel value="Term name" />
                        <InputText v-model="form.name" />
                        <InputError :message="form.errors.name" />
                    </div>
                    <div class="mb-4">
                        <InputLabel value="Start Date" />
                        <Calendar v-model="form.start_date" />
                        <InputError :message="form.errors.start_date" />
                    </div>
                    <div class="flex justify-between">
                        <PrimaryButton :class="{ 'opacity-30': form.processing }" :disabled="form.processing">Save
                        </PrimaryButton>
                        <SecondaryButton v-if="edit" type="button" @click="cancel">Cancel</SecondaryButton>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
