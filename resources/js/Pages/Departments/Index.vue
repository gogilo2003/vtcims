<script lang="ts" setup>
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout.vue'
import { iInstructor, iDepartment, iLink } from '../../interfaces/index';
import Paginator from '../../Components/Paginator.vue';
import SecondaryButton from '../../Components/SecondaryButton.vue';
import { ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import { debounce } from 'lodash';
import Icon from '../../Components/Icons/Icon.vue';
import InputText from 'primevue/inputtext';

const props = defineProps<{
    departments: {
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
        data: Array<iDepartment>
    },
    instructors: Array<iInstructor>
    search: string
}>()

const newDepartment = () => { }

const searchVal = ref(props.search)

watch(() => searchVal.value, debounce((value: string) => {

    let data = {}

    if (value) {
        data = { search: value }
    }

    router.get(route('departments'), data, {
        only: ['departments', 'search'],
        preserveScroll: true,
        preserveState: true
    })
}, 500))

</script>
<template>
    <AuthenticatedLayout title="Departments">
        <div class="flex items-center justify-between gap-2 pb-3 md:pb-8 ">
            <SecondaryButton @click="newDepartment">
                <Icon type="add" />
                <span class="hidden md:inline-flex">New Department</span>
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
            <div v-for="department in departments.data" class="px-4 py-2 rounded-lg shadow-lg bg-white">
                <div>
                    <div v-text="department.name" class="uppercase text-sm font-semibold text-gray-800"></div>
                    <div class="flex gap-2 flex-col md:flex-row">
                        <div class="flex items-center gap-1">
                            <span class="text-xs font-semibold text-gray-800">CODE:</span>
                            <span v-text="department.code" class="text-xs text-gray-500"></span>
                        </div>
                        <div class="flex items-center gap-1">
                            <span class="text-xs font-semibold text-gray-800">Head of Department:</span>
                            <span v-text="department.hod.name" class="text-xs text-gray-500"></span>
                        </div>
                    </div>
                </div>
            </div>
            <Paginator :items="departments" />
        </div>
    </AuthenticatedLayout>
</template>
