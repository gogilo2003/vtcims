<script lang="ts" setup>
import Modal from '../../Components/Modal.vue';
import { iAllocation, iItem } from '../../interfaces/index';
import Icon from '../../Components/Icons/Icon.vue';
import InputLabel from '../../Components/InputLabel.vue';
import { ref, computed } from 'vue';
import Dropdown from 'primevue/dropdown';
import Calendar from 'primevue/calendar';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { usePage } from '@inertiajs/vue3';

const props = defineProps<{
    allocation: iAllocation
    show: boolean
}>()

const emit = defineEmits(['closed'])
const close = () => {
    emit('closed', false)
}

const terms = computed((): iItem[] => usePage().props.terms)
const duration = ref('week');
const startAt = ref();
const month = ref();
const term = ref();

const download = () => {
    let link = document.createElement('a');
    let query: {
        allocation: number
        duration: string
        start_at?: Date
        month?: string
        term?: number
    } = { allocation: props.allocation.id, duration: duration.value }

    if (startAt.value && duration.value == 'week') {
        query = { ...query, start_at: startAt.value }
    }
    if (month.value && duration.value == 'month') {
        query = { ...query, month: month.value }
    }
    if (term.value && duration.value == 'term') {
        query = { ...query, term: term.value }
    }

    link.href = route('attendances-download-pdf', query)
    link.target = '_BLANK'
    link.click()
}

const options = [
    // { text: "Day", value: "day" },
    { text: "Week", value: "week" },
    { text: "Month", value: "month" },
    { text: "Term", value: "term" }
]

const computedClasses = computed(() => {
    let classes = 'grid-cols-1 md:grid-cols-2'
    if (duration.value === 'day' || !duration.value) {
        classes = 'grid-cols-1'
    }

    return classes
})

const durationLabel = computed(() => {
    if (duration.value == 'week') {
        return 'Start Date'
    } else if (duration.value == 'month') {
        return 'Select Month'
    } else {
        return 'Select Term'
    }
})

</script>

<template>
    <Modal :show="show" maxWidth="lg">
        <template #header>
            <div class="text-gray-100 text-lg font-medium uppercase">Download PDF Template</div>
            <button class="text-gray-200 hover:text-gray-100 transition-colors duration-150" @click="close">
                <Icon class="h-5 w-5" type="close" />
            </button>
        </template>
        <div>
            <div class="my-2 shadow-sm rounded-md p-2 bg-gray-100 flex flex-col gap-2 divide-y">
                <div class="flex gap-0 md:gap-2 flex-col md:flex-row">
                    <span class="font-medium">Subject</span>
                    <span class="capitalize font-light text-sm"
                        v-text="`${allocation.subject.code}-${allocation.subject.name}`"></span>
                </div>
                <div class="flex gap-0 md:gap-2 flex-col md:flex-row">
                    <span class="font-medium">Instructor</span>
                    <span class="capitalize font-light text-sm" v-text="allocation.instructor.name"></span>
                </div>
                <div class="flex gap-0 md:gap-2 flex-col md:flex-row">
                    <span class="font-medium">Year/Term</span>
                    <span class="capitalize font-light text-sm"
                        v-text="`${allocation.term.year}-${allocation.term.name}`"></span>
                </div>
                <div class="flex gap-0 flex-col">
                    <span class="font-medium">Intakes</span>
                    <span class="capitalize font-light text-sm"
                        v-text="allocation.intakes.map(item => item.name).join(', ')"></span>
                </div>
            </div>
            <div class="grid gap-2" :class="computedClasses">
                <div>
                    <InputLabel value="Duration" />
                    <Dropdown v-model="duration" :options="options" optionLabel="text" optionValue="value" />
                </div>
                <div v-if="duration !== 'day' || !duration">
                    <InputLabel :value="durationLabel" />
                    <Calendar v-if="duration == 'week'" view="date" dateFormat="D, d M, yy" v-model="startAt"
                        :manualInput="false" showIcon iconDisplay="input"
                        :pt="{ input: { class: 'font-sans leading-none text-surface-600 dark:text-surface-200 placeholder:text-surface-400 dark:placeholder:text-surface-500 bg-surface-0 dark:bg-surface-900 border border-surface-300 dark:border-surface-600 m-0 p-3 text-sm w-full appearance-none rounded-md rounded-md flex-1 pr-9 transition-colors duration-200 hover:border-primary-500 dark:hover:border-primary-400 focus:outline-none focus:outline-offset-0 focus:ring focus:ring-primary-500/50 dark:focus:ring-primary-400/50' }, }" />
                    <Calendar v-if="duration == 'month'" view="month" dateFormat="MM" v-model="month"
                        :manualInput="false" showIcon iconDisplay="input"
                        :pt="{ input: { class: 'font-sans leading-none text-surface-600 dark:text-surface-200 placeholder:text-surface-400 dark:placeholder:text-surface-500 bg-surface-0 dark:bg-surface-900 border border-surface-300 dark:border-surface-600 m-0 p-3 text-sm w-full appearance-none rounded-md rounded-md flex-1 pr-9 transition-colors duration-200 hover:border-primary-500 dark:hover:border-primary-400 focus:outline-none focus:outline-offset-0 focus:ring focus:ring-primary-500/50 dark:focus:ring-primary-400/50' }, }" />
                    <Dropdown v-if="duration == 'term'" v-model="term" :options="terms" optionValue="id"
                        optionLabel="name" filter />
                </div>
            </div>
        </div>
        <div class="my-3 p-3">
            <PrimaryButton @click="download" class="gap-1 items-center">
                <Icon class="w-5 h-5" type="download" />
                <span>Download</span>
            </PrimaryButton>
        </div>
    </Modal>
</template>
