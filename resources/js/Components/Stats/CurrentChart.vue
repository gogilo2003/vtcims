<script lang="ts" setup>
import { ref, onMounted } from "vue";
import Chart from 'primevue/chart';

const props = defineProps({
    current: Object
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
    const documentStyle = getComputedStyle(document.body);

    return {
        labels: props.current.labels,
        datasets: [
            {
                data: props.current.data,
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
</script>

<template>
    <Chart type="pie" :data="chartData" :options="chartOptions" :pt="{
        root: { class: 'w-[100%] h-full' }
    }" />
</template>
