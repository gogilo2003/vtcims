<script setup>
import { ref, onMounted } from "vue";
import Chart from "primevue/chart";

const props = defineProps({
    data: Object
})

onMounted(() => {
    setTimeout(() => {
        chartData.value = setChartData();
        chartOptions.value = setChartOptions();
    }, 1000);
});

const chartData = ref();
const chartOptions = ref();

const setChartData = () => {
    return {
        labels: props.data.labels,
        datasets: props.data.datasets
    };
};
const setChartOptions = () => {
    let dark = localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)
    const documentStyle = getComputedStyle(document.documentElement);
    const textColor = documentStyle.getPropertyValue(dark ? '--gray-300' : '--gray-800');
    const textColorSecondary = documentStyle.getPropertyValue(dark ? '--gray-200' : '--gray-700');
    const surfaceBorder = documentStyle.getPropertyValue('--surface-border');

    return {
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
                beginAtZero: true,
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
    <Chart type="bar" :data="chartData" :options="chartOptions" :pt="{
        root: { class: 'w-[100%] h-full' }
    }" />
</template>
