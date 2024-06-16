<script lang="ts" setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { iTradeAreas, iTradeArea } from '../../interfaces/index';
import { ref, computed, watch } from 'vue';
import { useForm, router } from '@inertiajs/vue3';
import Paginator from '@/Components/Paginator.vue';
import ListItem from '@/Components/ListItem.vue';
import { useToast } from 'primevue/usetoast';
import Toast from 'primevue/toast';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputText from 'primevue/inputtext';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Icon from '@/Components/Icons/Icon.vue';
import { debounce } from 'lodash';

const props = defineProps<{
    search?: string | null
    trade_areas: iTradeAreas
}>()

const toast = useToast()

const form = useForm<{
    id: number | null
    name: string | null
    description: string | null
}>({
    id: null,
    name: null,
    description: null,
})

const searchVal = ref(props.search)
const detailTitle = computed(() => form.id ? 'Edit Trade Area' : 'New Trade Area')

const newTradeArea = () => {
    form.reset()
    form.clearErrors()

    form.id = null
    form.name = null
    form.description = null
}

const editTradeArea = (tradeArea: iTradeArea) => {
    form.reset()
    form.clearErrors()

    form.id = tradeArea.id
    form.name = tradeArea.name
    form.description = tradeArea.description
}

const deleteTradeArea = (id: number) => {
    router.delete(route('staff-trade_areas-destroy', id), {
        onSuccess: () => {
            toast.add({
                severity: 'success',
                summary: 'Success',
                detail: 'Trade Area Deleted',
                life: 3000
            })
        },
        onError: () => {
            toast.add({
                severity: 'error',
                summary: 'Error',
                detail: 'An error occurred! Please try again.',
                life: 3000
            })
        }
    })
}

const cancelEdit = () => {
    form.reset()
    form.clearErrors()
    form.id = null
    form.name = null
    form.description = null
}

const submit = () => {
    if (form.id) {
        form.patch(route('staff-trade_areas-update', form.id), {
            onSuccess: () => {
                toast.add({
                    severity: 'success',
                    summary: 'Success',
                    detail: 'Trade Area Updated',
                    life: 3000
                })
            },
            onError: () => {
                toast.add({
                    severity: 'error',
                    summary: 'Error',
                    detail: 'An error occurred! Please try again.',
                    life: 3000
                })
            }
        })
    } else {
        form.post(route('staff-trade_areas-store'), {
            onSuccess: () => {
                toast.add({
                    severity: 'success',
                    summary: 'Success',
                    detail: 'Trade Area Created',
                    life: 3000
                })
            },
            onError: () => {
                toast.add({
                    severity: 'error',
                    summary: 'Error',
                    detail: 'An error occurred! Please try again.',
                    life: 3000
                })
            }
        })
    }
}

watch(
    () => searchVal.value, debounce(
        (value: string) => {

            let data = {}

            if (value) {
                data = { search: value }
            }

            router.get(route('staff-trade_areas'), data, {
                only: ['trade_areas', 'search'],
                preserveScroll: true,
                preserveState: true
            })
        },
        500
    )
)
</script>
<template>
    <Toast />
    <AuthenticatedLayout title="Trade Areas">
        <div class="pb-3 md:pb-8 flex gap-3 justify-between">
            <div>
                <span class="relative">
                    <span class="pi pi-search absolute top-[50%] -translate-y-[50%] left-2 opacity-50"></span>
                    <InputText v-model="searchVal" placeholder="Search" class="px-8 w-full"
                        :pt="{ root: { class: 'rounded-full focus:ring-primary-500 text-surface-600 dark:text-surface-200 bg-surface-0 dark:bg-surface-700' } }" />
                </span>
            </div>
            <SecondaryButton @click="newTradeArea">
                <Icon type="add" />
                <span class="hidden md:inline-flex">New Staff Status</span>
            </SecondaryButton>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="col-span-1 md:col-span-2">
                <div class="flex flex-col gap-2 md:col-span-2">
                    <ListItem v-for="trade_area in trade_areas.data">
                        <div>
                            <div class="text-base font-medium capitalize" v-text="trade_area.name"></div>
                            <div class="text-xs font-light flex gap-1">
                                <span>Staff</span>
                                <span v-text="trade_area.staff.length"></span>
                            </div>
                        </div>
                        <div class="flex flex-wrap gap-1">
                            <SecondaryButton @click="editTradeArea(trade_area)">
                                <Icon class="h-4 w-4" type="edit" /><span class="hidden md:block">edit</span>
                            </SecondaryButton>
                            <SecondaryButton @click="deleteTradeArea(trade_area.id)">
                                <Icon class="h-4 w-4" type="delete" /><span class="hidden md:block">Delete</span>
                            </SecondaryButton>
                        </div>
                    </ListItem>
                    <Paginator :items="trade_areas" />
                </div>
            </div>
            <div>
                <div class="p-3 bg-white dark:bg-gray-700 shadow rounded-lg border border-white dark:border-gray-600">
                    <div class="text-lg font-light capitalize border-b pb-2 mb-2" v-text="detailTitle"></div>
                    <div>
                        <form @submit.prevent="submit">
                            <div class="mb-6">
                                <InputLabel value="Name" />
                                <InputText v-model="form.name" />
                                <InputError :message="form.errors.name" />
                            </div>
                            <div class="mb-6">
                                <InputLabel value="Description" />
                                <InputText v-model="form.description" />
                                <InputError :message="form.errors.description" />
                            </div>
                            <div class="flex items-center justify-between">
                                <PrimaryButton :class="{ 'opacity-30': form.processing }" :disabled="form.processing">
                                    Save
                                </PrimaryButton>
                                <SecondaryButton type="button" @click="cancelEdit" v-if="form.id">Cancel
                                </SecondaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
