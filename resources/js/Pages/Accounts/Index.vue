<script lang="ts" setup>
import { formatCurrency } from '@/helpers';
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout.vue'

const props = defineProps<{
    stats: {
        title: string
        amount: number
    }[]
}>()
</script>
<template>
    <AuthenticatedLayout title="Accounts Dashboard">
        <div class="flex gap-4 w-full">
            <div class="p-3 rounded-xl bg-white dark:bg-gray-800" v-for="{ title, amount } in stats">
                <div class="text-primary-500 text-2xl font-light" v-text="formatCurrency(amount)"></div>
                <div class="text-sm font-semibold uppercase" v-text="title"></div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
