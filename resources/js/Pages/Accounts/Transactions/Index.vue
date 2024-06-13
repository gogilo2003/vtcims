<script lang="ts" setup>
import AuthenticatedLayout from '../../../Layouts/AuthenticatedLayout.vue';
import { iFeeTransactions, iItem, iNotification } from '../../../interfaces/index';
import { useForm, router } from '@inertiajs/vue3';
import { ref, computed, watch } from 'vue';
import ListItem from '@/Components/ListItem.vue';
import Paginator from '@/Components/Paginator.vue';
import { formatCurrency } from '@/helpers';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Icon from '@/Components/Icons/Icon.vue';
import { debounce } from 'lodash';
import InputText from 'primevue/inputtext';
import FeePayment from './FeePayment.vue'
import Toast from 'primevue/toast';

const props = defineProps<{
    transactions: iFeeTransactions
    students: iItem[]
    fees: iItem[]
    transaction_types: iItem[]
    transaction_modes: iItem[]
    notification: iNotification
    search?: string
}>()


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


const showPaymentDialog = ref<boolean>(false)

const onClosePayment = () => {
    showPaymentDialog.value = false
}

const feePayment = () => {
    showPaymentDialog.value = true
}

const downloadReceipt = (id: number) => {
    let url = route('accounts-transactions-download', id)
    window.location = url
}
</script>
<template>
    <Toast position="top-center" />
    <FeePayment :show="showPaymentDialog" @closed="onClosePayment" />
    <AuthenticatedLayout title="Fee Transactions">
        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center gap-2">
                <SecondaryButton @click="feePayment">
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
                <div>
                    <SecondaryButton v-if="transaction.receipt" @click="downloadReceipt(transaction.id)">
                        <Icon class="h-4 w-4" type="download" />
                        <span>Receipt</span>
                    </SecondaryButton>
                </div>
            </ListItem>
            <Paginator :items="transactions" />
        </div>
    </AuthenticatedLayout>
</template>
