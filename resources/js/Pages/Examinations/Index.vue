<script lang="ts" setup>
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout.vue';
import { iExaminations, iExamination } from '../../interfaces/index';
import ListItem from '../../Components/ListItem.vue';
import SecondaryButton from '../../Components/SecondaryButton.vue';
import Paginator from '../../Components/Paginator.vue';
import Icon from '../../Components/Icons/Icon.vue';
import { ref, watch } from 'vue';
import Examination from './Examination.vue';
import { router } from '@inertiajs/vue3';
import { debounce } from 'lodash';
import Toast from 'primevue/toast';
import InputText from 'primevue/inputtext';

const props = defineProps<{
    examinations: iExaminations
    search?: string | null
}>()

const selectedExamination = ref<iExamination | null>(null)
const show = ref(false)

const close = (value: boolean) => {
    show.value = value
}

const editExamination = (examination: iExamination) => {
    selectedExamination.value = examination
    show.value = true
}

const newExamination = () => {
    selectedExamination.value = null
    show.value = true
}

const markList = (id: number) => {
    window.open(route('examinations-marklist', { id }))
}

const viewExamination = (id: number) => {
    router.get(route('examinations-show', id))
}

const searchVal = ref(props.search)

watch(() => searchVal.value, debounce((value: string) => {

    let data: { search?: string | null } = {}

    if (value) {
        data = { search: value }
    }

    router.get(route('examinations'), data, {
        only: ['examinations', 'search'],
        preserveScroll: true,
        preserveState: true
    })
}, 500))


</script>
<template>
    <Toast position="top-center" />
    <Examination :show="show" :examination="selectedExamination" @closed="close" />
    <AuthenticatedLayout title="Examinations">
        <div class="flex items-center justify-between gap-2 pb-3 md:pb-8 ">
            <SecondaryButton @click="newExamination">
                <Icon type="add" />
                <span class="hidden md:inline-flex">New Examination</span>
            </SecondaryButton>
            <div>
                <span class="relative">
                    <span class="pi pi-search absolute top-[50%] -translate-y-[50%] left-2 opacity-50"></span>
                    <InputText v-model="searchVal" placeholder="Search" class="px-8 w-full"
                        :pt="{ root: { class: 'rounded-full focus:ring-primary-500 text-surface-600 dark:text-surface-200 bg-surface-0 dark:bg-surface-700' } }" />
                </span>
            </div>
        </div>
        <div></div>
        <div class="flex flex-col gap-3">
            <ListItem v-for="examination in examinations.data">
                <div>
                    <div class="font-medium" v-text="examination.title"></div>
                    <div class="text-xs text-gray-600 line-clamp-1"
                        :title="examination.intakes.map(intake => intake.name).join(', ')"
                        v-text="examination.intakes.map(intake => intake.name).join(', ')"></div>
                </div>
                <div class="flex gap-2">
                    <SecondaryButton @click="viewExamination(examination.id)">
                        <Icon class="h-4 w-4" type="done" /> View
                    </SecondaryButton>
                    <SecondaryButton @click="markList(examination.id)">
                        <Icon class="h-4 w-4" type="pdf" /> Download
                    </SecondaryButton>
                    <SecondaryButton @click="editExamination(examination)">
                        <Icon class="h-4 w-4" type="edit" /> Edit
                    </SecondaryButton>
                </div>
            </ListItem>
            <Paginator :items="examinations" />
        </div>
    </AuthenticatedLayout>
</template>
