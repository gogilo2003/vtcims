<script lang="ts" setup>
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout.vue'
import { iProgram, iLink } from '../../interfaces/index';
import Paginator from '../../Components/Paginator.vue';
import SecondaryButton from '../../Components/SecondaryButton.vue';
import { ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import { debounce } from 'lodash';
import Icon from '../../Components/Icons/Icon.vue';
import InputText from 'primevue/inputtext';
import ListItem from '@/Components/ListItem.vue';

const props = defineProps<{
    programs: {
        current_page: number,
        first_page_url: string,
        from: number,
        last_page: number,
        last_page_url: string,
        links: Array<iLink>,
        next_page_url: string,
        path: string,
        per_page: number,
        prev_page_url: string,
        to: number,
        total: number
        data: Array<iProgram>
    }
    search: string
}>()

const newProgram = () => { }

const searchVal = ref(props.search)

watch(() => searchVal.value, debounce((value: string) => {

    let data = {}

    if (value) {
        data = { search: value }
    }

    router.get(route('programs'), data, {
        only: ['programs', 'search'],
        preserveScroll: true,
        preserveState: true
    })
}, 500))
</script>
<template>
    <AuthenticatedLayout title="Programs">
        <div class="flex items-center justify-between gap-2 mb-3 md:pb-8 ">
            <SecondaryButton @click="newProgram">
                <Icon type="add" />
                <span class="hidden md:inline-flex">New Program</span>
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
            <ListItem v-for="program in programs.data" class="px-4 py-2 rounded-lg shadow-lg bg-white">
                <div>
                    <div v-text="program.name" class="uppercase text-sm font-semibold text-gray-800 dark:text-primary-500">
                    </div>
                    <div v-text="program.description" class="text-xs text-gray-600 dark:text-gray-300"></div>
                </div>
            </ListItem>
            <Paginator :items="programs" />
        </div>
    </AuthenticatedLayout>
</template>
