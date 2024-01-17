<script lang="ts" setup>
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout.vue'
import { iInstructor, iSubject } from '../../interfaces/index';
import Paginator from '../../Components/Paginator.vue';
import SecondaryButton from '../../Components/SecondaryButton.vue';
import { ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import { debounce } from 'lodash';
import Icon from '../../Components/Icons/Icon.vue';
import InputText from 'primevue/inputtext';

const props = defineProps<{
    subjects: {
        data: Array<iSubject>
    }
    instructors: Array<iInstructor>
    search: string
}>()

const newSubject = () => { }

const searchVal = ref(props.search)

watch(() => searchVal.value, debounce((value: string) => {

    let data = {}

    if (value) {
        data = { search: value }
    }

    router.get(route('subjects'), data, {
        only: ['subjects', 'search'],
        preserveScroll: true,
        preserveState: true
    })
}, 500))
</script>
<template>
    <AuthenticatedLayout title="Subjects">
        <div class="flex items-center justify-between gap-2 mb-3">
            <SecondaryButton @click="newSubject">
                <Icon type="add" />
                <span class="hidden md:inline-flex">New Subject</span>
            </SecondaryButton>
            <div>
                <span class="relative">
                    <i class="pi pi-search absolute -top-[40%] translate-y-[50%] left-2 opacity-50" />
                    <InputText v-model="searchVal" placeholder="Search" class="px-8 w-full"
                        :pt="{ root: { class: 'rounded-full focus:ring-primary-500 text-surface-600 dark:text-surface-200 bg-surface-0 dark:bg-surface-700' } }" />
                </span>
            </div>
        </div>
        <div class="flex flex-col gap-2">
            <div v-for="subject in subjects.data" class="px-4 py-2 rounded-lg shadow-lg bg-white">
                <div>
                    <div v-text="subject.name" class="uppercase text-sm font-semibold text-gray-800"></div>
                    <div class="flex gap-1">
                        <span class="text-xs font-semibold text-gray-800">COURSES:</span>
                        <span v-for="course in subject.courses" v-text="course.code" class="text-xs text-gray-500"></span>
                    </div>
                </div>
            </div>
            <Paginator :items="subjects" />
        </div>
    </AuthenticatedLayout>
</template>
