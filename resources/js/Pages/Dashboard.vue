<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import StatCard from '@/Components/StatCard.vue';
import Chart from 'primevue/chart';
import { ref, onMounted } from "vue";

defineProps(['students'])

onMounted(() => {
    setTimeout(() => {
        chartData.value = setChartData();
    }, 3000);
    chartOptions.value = setChartOptions();
});

const chartData = ref();
const chartOptions = ref();

const setChartData = () => {
    const documentStyle = getComputedStyle(document.documentElement);

    return {
        labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
        datasets: [
            {
                label: 'Male',
                data: genData(),
                fill: false,
                borderColor: documentStyle.getPropertyValue('--blue-500'),
                tension: 0.4
            },
            {
                label: 'Female',
                data: genData(),
                fill: false,
                borderColor: documentStyle.getPropertyValue('--pink-500'),
                tension: 0.4
            },
            {
                label: 'Mean',
                data: genData(),
                fill: false,
                borderColor: documentStyle.getPropertyValue('--green-500'),
                tension: 0.4
            }
        ]
    };
};

const genData = () => {
    let randomNumbers = [];

    // Generate seven random integer numbers
    for (let i = 0; i < 7; i++) {
        // Generate a random integer between 1 and 100 (you can adjust the range as needed)
        let randomNumber = Math.floor(Math.random() * 100) + 1;

        // Push the generated random number to the array
        randomNumbers.push(randomNumber);
    }

    return randomNumbers
}
const setChartOptions = () => {
    const documentStyle = getComputedStyle(document.documentElement);
    const textColor = documentStyle.getPropertyValue('--text-color');
    const textColorSecondary = documentStyle.getPropertyValue('--text-color-secondary');
    const surfaceBorder = documentStyle.getPropertyValue('--surface-border');

    return {
        maintainAspectRatio: false,
        aspectRatio: 0.6,
        plugins: {
            legend: {
                labels: {
                    color: textColor
                }
            }
        },
        scales: {
            x: {
                ticks: {
                    color: textColorSecondary
                },
                grid: {
                    color: surfaceBorder
                }
            },
            y: {
                ticks: {
                    color: textColorSecondary
                },
                grid: {
                    color: surfaceBorder
                }
            }
        }
    };
}
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
            <div class="rounded-3xl p-6  h-full flex-1 max-w-full w-full bg-white/30 border">
                <Chart type="line" :data="chartData" :options="chartOptions" :pt="{
                    root: { class: 'w-[100%] h-full' }
                }" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
