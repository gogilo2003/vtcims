<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import StatCard from '@/Components/StatCard.vue';
import CurrentChart from '@/Components/Stats/CurrentChart.vue'
import StatusChart from '@/Components/Stats/StatusChart.vue'
import AnnualChart from '@/Components/Stats/AnnualChart.vue'

defineProps(['students', 'current', 'status', 'annual'])

</script>

<template>
    <AuthenticatedLayout title="Dashboard">
        <div class="flex flex-col gap-6 md:gap-16 h-full">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 flex-none">
                <StatCard>
                    <div class="flex flex-col items-center justify-center">
                        <div class="text-base md:text-xl font-medium">Male Students</div>
                        <div v-text="students.male" class=" text-3xl md:text-8xl"></div>
                    </div>
                </StatCard>
                <StatCard>
                    <div class="flex flex-col items-center justify-center">
                        <div class="text-base md:text-xl font-medium">Female Students</div>
                        <div v-text="students.female" class=" text-3xl md:text-8xl"></div>
                    </div>
                </StatCard>
                <StatCard>
                    <div class="flex flex-col items-center justify-center">
                        <div class="text-base md:text-xl font-medium">Total Students</div>
                        <div v-text="students.total" class=" text-3xl md:text-8xl"></div>
                    </div>
                </StatCard>
                <StatCard>
                    <div class="flex flex-col items-center justify-center">
                        <div class="text-base md:text-xl font-medium">Enrollments To Date</div>
                        <div v-text="students.all" class=" text-3xl md:text-8xl"></div>
                    </div>
                </StatCard>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="rounded-3xl p-6  h-full max-w-full w-full bg-white dark:bg-gray-700 dark:text-gray-300 border">
                    <CurrentChart :current="current" />
                </div>
                <div
                    class="rounded-3xl p-6  h-full flex-1 max-w-full w-full bg-white dark:bg-gray-700 dark:text-gray-300 border">
                    <StatusChart :data="status" />
                </div>
                <div
                    class="rounded-3xl p-6  h-full flex-1 max-w-full w-full bg-white dark:bg-gray-700 dark:text-gray-300 border md:col-span-2 lg:col-span-2">
                    <AnnualChart :data="annual" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
