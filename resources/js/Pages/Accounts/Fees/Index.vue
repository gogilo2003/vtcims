<script lang="ts" setup>
import AuthenticatedLayout from '../../../Layouts/AuthenticatedLayout.vue';
import { iItem, iFee, iNotification, iFees } from '../../../interfaces/index';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Icon from '@/Components/Icons/Icon.vue';
import InputText from 'primevue/inputtext';
import { ref, watch, computed } from 'vue';
import { debounce } from 'lodash';
import { router, useForm } from '@inertiajs/vue3';
import { useToast } from 'primevue/usetoast';
import Toast from 'primevue/toast';
import InputLabel from '../../../Components/InputLabel.vue';
import Dropdown from 'primevue/dropdown';
import InputError from '@/Components/InputError.vue';
import InputNumber from 'primevue/inputnumber';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import ListItem from '@/Components/ListItem.vue';
import Paginator from '@/Components/Paginator.vue';
import { formatCurrency } from '../../../helpers'

const props = defineProps<{
    courses: iItem[]
    terms: iItem[]
    fees: iFees
    search?: string | null
    notification: iNotification
}>()

const searchVal = ref<string | undefined | null>(props.search)

watch(() => searchVal.value, debounce((value: string) => {
    let options: { search?: string | null } = {}
    if (value) {
        options = { search: value }
    }
    router.get(route('accounts-fees'), options, {
        only: ['fees', 'search'],
        preserveScroll: true,
        preserveState: true
    })
}, 500))

const toast = useToast()
const form = useForm<{
    id: number | null
    term: number | null
    course: number | null
    amount: number | null
}>({
    id: null,
    term: null,
    course: null,
    amount: null
})

const edit = ref<boolean>(false)
const title = computed<string>(() => {
    if (edit.value) {
        return 'Edit fee'
    } else {
        return 'New Fee'
    }
})

const editFee = (fee: iFee) => {
    form.id = fee.id
    form.term = fee?.term?.id
    form.course = fee?.course?.id
    form.amount = fee?.amount

    edit.value = true
}
const newFee = () => {
    cancel()
}

const cancel = () => {
    form.reset()
    form.clearErrors()
    edit.value = false
}

const submit = () => {
    if (edit.value) {
        form.patch(route('accounts-fees-update', form.id), {
            only: ['fees', 'errors', 'notification'],
            onSuccess: () => {
                toast.add({
                    severity: 'success',
                    summary: 'Success',
                    detail: props.notification.success,
                    life: 4000
                })
            },
            onError: () => {
                toast.add({
                    severity: 'error',
                    summary: 'Error',
                    detail: props.notification.danger ?? 'An error occurred! Please check you fields and try again',
                    life: 4000
                })
            }
        })
    } else {
        form.post(route('accounts-fees-store'), {
            only: ['fees', 'errors', 'notification'],
            onSuccess: () => {
                toast.add({
                    severity: 'success',
                    summary: 'Success',
                    detail: props.notification.success,
                    life: 4000
                })
            },
            onError: () => {
                toast.add({
                    severity: 'error',
                    summary: 'Error',
                    detail: props.notification.danger ?? 'An error occurred! Please check you fields and try again',
                    life: 4000
                })
            }
        })
    }
}
</script>
<template>
    <Toast position="top-center" />
    <AuthenticatedLayout title="Fees">
        <div class="pb-8 flex justify-between items-center">
            <div>
                <SecondaryButton @click="newFee">
                    <Icon class="w-5 h-5" type="add" />
                    <span class="hidden md:inline-flex">New Fee</span>
                </SecondaryButton>
            </div>
            <div>
                <span class="relative">
                    <span class="pi pi-search absolute top-[50%] -translate-y-[50%] left-2 opacity-50"></span>
                    <InputText v-model="searchVal" placeholder="Search" class="px-8 w-full"
                        :pt="{ root: { class: 'rounded-full focus:ring-primary-500 text-surface-600 dark:text-surface-200 bg-surface-0 dark:bg-surface-700' } }" />
                </span>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="col-span-2">
                <div class="flex flex-col gap-2">
                    <ListItem v-for="fee in fees.data">
                        <div>
                            <div class="text-sm font-normal text-gray-700 dark:text-gray-400"
                                v-text="`${fee?.term?.name} : ${fee?.course?.name}`"></div>
                            <div class="font" v-text="formatCurrency(fee.amount)"></div>
                        </div>
                        <div class="flex gap-2 items-center">
                            <SecondaryButton @click="editFee(fee)">
                                <Icon class="h-4 w-4" type="edit" />
                                <span class="hidden md:inline-flex">Edit</span>
                            </SecondaryButton>
                        </div>
                    </ListItem>
                </div>
                <Paginator :items="fees" />
            </div>
            <div class="shadow rounded-lg bg-white dark:bg-gray-800 p-4">
                <div class="text-xl font-light border-b pb-2 mb-3 dark:border-gray-600" v-text="title"></div>
                <form @submit.prevent="submit">
                    <div class="mb-4">
                        <InputLabel value="Course" />
                        <Dropdown filter :options="courses" optionValue="id" optionLabel="name" v-model="form.course" />
                        <InputError :message="form.errors.course" />
                    </div>
                    <div class="mb-4">
                        <InputLabel value="Term" />
                        <Dropdown filter :options="terms" optionValue="id" optionLabel="name" v-model="form.term" />
                        <InputError :message="form.errors.term" />
                    </div>
                    <div class="mb-4">
                        <InputLabel value="Amount" />
                        <InputNumber :useGrouping="false" v-model="form.amount" />
                        <InputError :message="form.errors.amount" />
                    </div>
                    <div class="flex justify-between">
                        <PrimaryButton>Save</PrimaryButton>
                        <SecondaryButton type="button" v-if="edit" @click="cancel">Cancel</SecondaryButton>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
