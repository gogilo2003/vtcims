<script lang="ts" setup>
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout.vue'
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Icon from '@/Components/Icons/Icon.vue';
import { iTerm, iTerms } from '@/interfaces';
import { ref } from 'vue';
import InputText from 'primevue/inputtext';
import ListItem from '@/Components/ListItem.vue';
import Paginator from '@/Components/Paginator.vue';
import Term from './Term.vue'

const props = defineProps<{
    terms: iTerms
    search?: string | null
}>()

const searchVal = ref(props.search)

const show = ref(false)
const selectedTerm = ref<iTerm>({
    id: null,
    name: "",
    year: "",
    start_date: null,
    end_date: null,
})

const newTerm = () => {

    let start = new Date();
    let end = new Date(`${(start.getMonth() + 4).toString().padStart(2, '0')}-${start.getDate().toString().padStart(2, '0')}-${start.getFullYear()}`)

    selectedTerm.value = {
        id: null,
        name: "",
        year: "",
        start_date: start,
        end_date: end,
    }

    show.value = true
}
const editTerm = (term: iTerm) => {

    selectedTerm.value = {
        id: term.id,
        year: term.year,
        name: term.name,
        start_date: new Date(term.start_date),
        end_date: new Date(term.end_date),
    }

    show.value = true
}

const onClosed = (value: boolean) => {
    show.value = value
}
</script>
<template>
    <Term :show="show" @closed="onClosed" :term="selectedTerm" />
    <AuthenticatedLayout title="Terms">
        <div class="flex justify-between my-4 gap-2">
            <SecondaryButton @click="newTerm">
                <Icon class="h-6 w-6" type="add" /><span class="hidden md:inline-flex">New Term</span>
            </SecondaryButton>
            <div>
                <span class="relative">
                    <Icon type="search" class="h-4 w-4 absolute top-[50%] -translate-y-[50%] left-2 opacity-50" />
                    <InputText v-model="searchVal" placeholder="Search" class="px-8 w-full"
                        :pt="{ root: { class: 'rounded-full focus:ring-primary-500 text-surface-600 dark:text-surface-200 bg-surface-0 dark:bg-surface-700' } }" />
                </span>
            </div>
        </div>
        <div class="flex gap-2 flex-col">
            <ListItem v-for="term in terms.data">
                <div>
                    <div v-text="`${term.year} - ${term.name}`"></div>
                    <div class="flex gap-1 md:gap-2 flex-col md:flex-row text-sm">
                        <div class="font-medium text-gray-700">Term Dates:</div>
                        <div class="text-gray-500 flex gap-2">
                            <span v-text="term.start_date"></span><span>-</span>
                            <span v-text="term.end_date"></span>
                        </div>
                    </div>
                </div>
                <div class="flex gap-2">
                    <SecondaryButton @click="editTerm(term)">
                        <Icon class="h-4 w-4" type="edit" />
                        <span class="hidden md:inline-flex">Edit</span>
                    </SecondaryButton>
                </div>
            </ListItem>
            <Paginator :items="terms" />
        </div>
    </AuthenticatedLayout>
</template>
