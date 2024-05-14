<script lang="ts" setup>
import AuthenticatedLayout from '../../../Layouts/AuthenticatedLayout.vue';
import { iTerm, iTranscript } from '../../../interfaces/index';
import InputText from 'primevue/inputtext';
import SecondaryButton from '../../../Components/SecondaryButton.vue';
import Icon from '../../../Components/Icons/Icon.vue';
import Transcript from './Transcript.vue';
import { ref, watch } from 'vue';
import { debounce } from 'lodash';
import { router } from '@inertiajs/vue3';
import InputLabel from '@/Components/InputLabel.vue';
import Dropdown from 'primevue/dropdown';

const props = defineProps<{
    terms: iTerm[],
    term: iTerm,
    admission?: string | null,
    transcripts: iTranscript[]
    search?: string | null
}>()

const termVal = ref<number | null>(props.term.id)
const admissionVal = ref<string | null | undefined>(props?.admission)
const searchVal = ref<string | null | undefined>(props?.search)

watch(() => termVal.value, (value) => {
    let options: { search?: string, admission?: string, term?: number | null } = {}

    if (value) {
        options = { term: value }
    }

    if (termVal.value) {
        options = { ...options, term: termVal.value }
    }

    if (admissionVal.value) {
        options = { ...options, admission: admissionVal.value }
    }

    if (searchVal.value) {
        options = { ...options, search: searchVal.value }
    }

    router.get(route('examinations-transcripts'), options, {
        only: ['transcripts', 'search'],
        preserveScroll: true,
        preserveState: true
    })
})

watch(() => searchVal.value, debounce((value: string) => {

    let options = {}

    if (value) {
        options = { search: value }
    }

    if (termVal.value) {
        options = { ...options, term: termVal.value }
    }
    if (admissionVal.value) {
        options = { ...options, admission: admissionVal.value }
    }

    router.get(route('examinations-transcripts'), options, {
        only: ['transcripts', 'search'],
        preserveScroll: true,
        preserveState: true
    })
}, 500))

const searchStudent = (value: string) => {

    let options = {}

    if (value) {
        options = { admission: value }
    }
    if (searchVal.value) {
        options = { ...options, search: searchVal.value }
    }
    if (termVal.value) {
        options = { ...options, term: termVal.value }
    }

    router.get(route('examinations-transcripts'), options, {
        only: ['transcripts', 'admission'],
        preserveScroll: true,
        preserveState: true
    })
}

watch(() => admissionVal.value, debounce(searchStudent, 500))

const download = () => {
    let options: {
        search?: string | null
        admission?: string | null
        term?: number | null
        download?: number | null
    } = {}

    options = { download: 1 }

    if (searchVal.value) {
        options = { ...options, search: searchVal.value }
    }
    if (termVal.value) {
        options = { ...options, term: termVal.value }
    }
    if (admissionVal.value) {
        options = { ...options, admission: admissionVal.value }
    }
    window.open(route('examinations-transcripts', options))
}
</script>
<template>
    <AuthenticatedLayout title="Transcripts">
        <div class="flex items-center justify-between gap-2 pb-3 md:pb-8 ">
            <div class="flex gap-2">
                <SecondaryButton @click="download()">
                    <Icon class="w-5 h-5" type="download" />
                    <span class="">Download</span>
                </SecondaryButton>
            </div>
            <div class="flex gap-2 items-center whitespace-nowrap flex-col md:flex-row justify-center">
                <div class="flex gap-2 justify-center">
                    <div class="flex flex-col md:flex-row items-center">
                        <InputLabel value="Terms" class=" self-center" />
                        <Dropdown v-model="termVal" filter class="flex-1" :options="terms" optionValue="id"
                            optionLabel="year_name" />
                    </div>
                    <div class="flex flex-col md:flex-row items-center">
                        <InputLabel value="Admission No." class=" self-center" />
                        <InputText v-model="admissionVal" />
                    </div>
                </div>
                <div>
                    <span v-text="`${transcripts.length} Transcripts`"></span>
                </div>
            </div>
            <div class="flex-none">
                <span class="relative">
                    <span class="pi pi-search absolute top-[50%] -translate-y-[50%] left-2 opacity-50"></span>
                    <InputText v-model="searchVal" placeholder="Search" class="px-8 w-full"
                        :pt="{ root: { class: 'rounded-full focus:ring-primary-500 text-surface-600 dark:text-surface-200 bg-surface-0 dark:bg-surface-700' } }" />
                </span>
            </div>
        </div>
        <div class="h-[calc(100svh_-_12rem)] overflow-y-auto px-10 bg-gray-400">
            <div class="flex flex-col gap-8 max-w-[49.62rem] mx-auto">
                <Transcript v-for="transcript in transcripts" :transcript="transcript" :term="term" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
