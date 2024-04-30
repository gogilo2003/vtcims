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

const props = defineProps<{
    terms: iTerm[],
    term: iTerm,
    transcripts: iTranscript[]
    search?: string | null
}>()

const searchVal = ref<string | null | undefined>(props?.search)

watch(() => searchVal.value, debounce((value: string) => {

    let data = {}

    if (value) {
        data = { search: value }
    }

    router.get(route('examinations-transcripts'), data, {
        only: ['transcripts', 'search'],
        preserveScroll: true,
        preserveState: true
    })
}, 500))

const download = () => {
    let data: {
        search?: string | null,
        download?: number | null
    }
    data = { download: 1 }
    if (searchVal.value) {
        data = { ...data, search: searchVal.value }
    }
    window.open(route('examinations-transcripts', data))
}
</script>
<template>
    <AuthenticatedLayout title="Transcripts">
        <div class="flex items-center justify-between gap-2 pb-3 md:pb-8 ">
            <SecondaryButton @click="download()">
                <Icon class="w-5 h-5" type="download" />
                <span class="hidden md:inline-flex">Download</span>
            </SecondaryButton>
            <div>
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
