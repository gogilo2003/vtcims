<script lang="ts" setup>
import AuthenticatedLayout from '../../../Layouts/AuthenticatedLayout.vue';
import { iFeeTransactions, iItem, iNotification } from '../../../interfaces/index';
import Dropdown from 'primevue/dropdown';
import { useForm, router } from '@inertiajs/vue3';
import Modal from '@/Components/Modal.vue';
import { ref, computed, watch } from 'vue';
import ListItem from '@/Components/ListItem.vue';
import Paginator from '@/Components/Paginator.vue';
import { formatCurrency } from '@/helpers';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Icon from '@/Components/Icons/Icon.vue';
import { debounce } from 'lodash';
import InputText from 'primevue/inputtext';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import InputNumber from 'primevue/inputnumber';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Toast from 'primevue/toast';
import { useToast } from 'primevue/usetoast';

const props = defineProps<{
    transactions: iFeeTransactions
    students: iItem[]
    fees: iItem[]
    transaction_types: iItem[]
    transaction_modes: iItem[]
    notification: iNotification
    search?: string
}>()

const toast = useToast();

const searchVal = ref(props.search)

watch(() => searchVal.value, debounce((value: string) => {
    let options: { search?: string } = {}
    if (value) {
        options = { search: value }
    }
    router.get(route('accounts-transactions'), options, {
        preserveScroll: true,
        preserveState: true,
        only: ['transactions', 'search']
    })

}, 500))

const form = useForm<{
    id: number | null
    student: number | null
    fee: number | null
    amount: number | null
    mode: number | null
    type: number | null
}>({
    id: null,
    student: null,
    fee: null,
    amount: null,
    mode: null,
    type: null,
})

const showPaymentDialog = ref<boolean>(false)
const edit = ref<boolean>(false)

const title = computed<string>(() => {
    if (edit.value) {
        return "Edit Transaction"
    } else {
        return "New Transaction"
    }
})

const makePayment = () => {
    form.id = null
    form.student = null
    form.fee = null
    form.amount = null
    form.mode = null

    close()
    showPaymentDialog.value = true
}

const close = () => {
    form.reset()
    form.clearErrors()

    showPaymentDialog.value = false
}

const submit = () => {
    if (edit.value) {
        form.patch(route('accounts-transactions-update', form.id), {
            preserveScroll: true,
            preserveState: true,
            only: ['transactions', 'notification', 'errors'],
            onSuccess: () => {
                toast.add({
                    severity: 'success',
                    summary: 'Success',
                    detail: props.notification.success,
                    life: 4000
                })
                close()
            },
            onError: () => {
                toast.add({
                    severity: 'error',
                    summary: 'Error',
                    detail: props.notification.danger ?? 'An error occurred! Please check your fields and try again.',
                    life: 4000
                })
                close()
            },
        })
    } else {
        form.post(route('accounts-transactions-store'), {
            preserveScroll: true,
            preserveState: true,
            only: ['transactions', 'notification', 'errors'],
            onSuccess: () => {
                toast.add({
                    severity: 'success',
                    summary: 'Success',
                    detail: props.notification.success,
                    life: 4000
                })
                close()
            },
            onError: () => {
                toast.add({
                    severity: 'error',
                    summary: 'Error',
                    detail: props.notification.danger ?? 'An error occurred! Please check your fields and try again.',
                    life: 4000
                })
                close()
            },
        })
    }
}
</script>
<template>
    <Toast position="top-center" />
    <Modal :show="showPaymentDialog">
        <template #header>
            <div v-text="title"></div>
            <button @click="close">
                <Icon class="h-5 w-5" type="close" />
            </button>
        </template>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="mb-4">
                <InputLabel value="Select Student" />
                <Dropdown filter :options="students" optionValue="id" optionLabel="name" v-model="form.student" />
                <InputError :message="form.errors.student" />
            </div>
            <div class="mb-4">
                <InputLabel value="Select Fee" />
                <Dropdown filter :options="fees" optionValue="id" optionLabel="name" v-model="form.fee" />
                <InputError :message="form.errors.fee" />
            </div>
            <div class="mb-4">
                <InputLabel value="Select Transaction Type" />
                <Dropdown filter :options="transaction_types" optionValue="id" optionLabel="name" v-model="form.type" />
                <InputError :message="form.errors.type" />
            </div>
            <div class="mb-4">
                <InputLabel value="Select Transaction Mode" />
                <Dropdown filter :options="transaction_modes" optionValue="id" optionLabel="name" v-model="form.mode" />
                <InputError :message="form.errors.mode" />
            </div>
            <div class="mb-4 md:col-span-2">
                <InputLabel value="Amount" />
                <InputNumber :useGrouping="false" v-model="form.amount" />
                <InputError :message="form.errors.amount" />
            </div>
        </div>
        <template #footer>
            <PrimaryButton :class="{ 'opacity-30': form.processing }" :disabled="form.processing" @click="submit">Save
            </PrimaryButton>
            <SecondaryButton @click="close"></SecondaryButton>
        </template>
    </Modal>
    <AuthenticatedLayout title="Fee Transactions">
        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center gap-2">
                <SecondaryButton @click="makePayment">
                    <Icon class="h-5 w-5" type="money" />
                    <span class="hidden md:inline-flex">Pay Fees</span>
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
        <div class="flex flex-col gap-2">
            <ListItem v-for="transaction in transactions.data">
                <div>
                    <div v-text="`${transaction.student?.admission_no} - ${transaction.student?.name}`"></div>
                    <div class="flex flex-col md:flex-row md:gap-4 text-xs text-gray-800 dark:text-gray-400">
                        <span v-text="`Transaction Id: #${transaction.id}`"></span>
                        <span v-text="`Amount: ${formatCurrency(transaction.amount)}`"></span>
                        <span v-text="`Transaction Date: ${transaction.date}`"></span>
                    </div>
                    <div class="text-xs text-gray-600 dark:text-gray-500" v-text="transaction.particulars"></div>
                </div>
            </ListItem>
            <Paginator :items="transactions" />
        </div>
    </AuthenticatedLayout>
</template>
