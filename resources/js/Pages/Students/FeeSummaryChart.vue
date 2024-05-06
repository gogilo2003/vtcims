<script lang="ts" setup>
import { ref, onMounted, watchEffect } from "vue";
import Chart from 'primevue/chart';
import { formatCurrency } from "@/helpers";

const props = defineProps<{
    feeSummary?: {
        paid: number
        balance: number
        charged: number
    }
}>()

// watchEffect(() => {
//     setTimeout(() => {
//         chartData.value = setChartData();
//         chartOptions.value = setChartOptions();
//     }, 1000);
// });

const chartData = ref();
const chartOptions = ref();

const setChartData = () => {
    const documentStyle = getComputedStyle(document.body);

    return {
        labels: [
            `Fee Paid(${formatCurrency(props.feeSummary?.paid ?? 2)})`,
            `Fee Charged(${formatCurrency(props.feeSummary?.charged ?? 0)})`,
            `Balance(${formatCurrency(props.feeSummary?.balance ?? 0)})`
        ],
        datasets: [
            {
                data: [props.feeSummary?.paid, props.feeSummary?.charged, props.feeSummary?.balance],
                backgroundColor: [documentStyle.getPropertyValue('--blue-500'), documentStyle.getPropertyValue('--yellow-500'), documentStyle.getPropertyValue('--green-500')],
                hoverBackgroundColor: [documentStyle.getPropertyValue('--blue-400'), documentStyle.getPropertyValue('--yellow-400'), documentStyle.getPropertyValue('--green-400')]
            }
        ]
    };
};

const setChartOptions = () => {
    let dark = localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)
    const documentStyle = getComputedStyle(document.documentElement);
    const textColor = documentStyle.getPropertyValue(dark ? '--gray-300' : '--gray-800');

    return {
        plugins: {
            legend: {
                labels: {
                    usePointStyle: true,
                    color: textColor
                }
            }
        }
    };
};
const chart = ref(null)
watchEffect(() => {
    if (chart.value) {
        console.log('something');
        chartData.value = setChartData();
        chartOptions.value = setChartOptions();
    }
})
</script>

<template>
    <Chart ref="chart" type="doughnut" :data="chartData" :options="chartOptions" :pt="{
        root: { class: 'w-full h-full' }
    }" />
</template>
