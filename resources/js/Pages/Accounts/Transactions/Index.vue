<script lang="ts" setup>
import AuthenticatedLayout from '../../../Layouts/AuthenticatedLayout.vue';
import { iFeeTransactions } from '../../../interfaces/index';
import Dropdown from 'primevue/dropdown';
import { useForm } from '@inertiajs/vue3';

const props = defineProps<{
    transactions: iFeeTransactions
    students: {
        id: number
        name: string
    }[]
}>()

const form = useForm<{
    id: number | null
    student: number | null
    fee: number | null
    amount: number | null
    particulars: number | null
}>({
    id: null,
    student: null,
    fee: null,
    amount: null,
    particulars: null,
})
</script>
<template>
    <AuthenticatedLayout title="Fee Transactions">
        <div class="">
            <Dropdown filter :options="students" optionValue="id" optionLabel="name" v-model="form.student" />
        </div>
        <div>
            <pre v-text="transactions.data"></pre>
        </div>

    </AuthenticatedLayout>
</template>
