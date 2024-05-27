<script lang="ts" setup>
import AuthenticatedLayout from '../../../Layouts/AuthenticatedLayout.vue';
import { iItem, iFee, iNotification, iFees } from '../../../interfaces/index';
import SecondaryButton from '../../../Components/SecondaryButton.vue';
import Icon from '../../../Components/Icons/Icon.vue';
import InputText from 'primevue/inputtext';
import { ref, watch } from 'vue';
import { debounce } from 'lodash';
import { router, useForm } from '@inertiajs/vue3';
import Toast from 'primevue/toast';
import ListItem from '@/Components/ListItem.vue';
import Paginator from '@/Components/Paginator.vue';
import { formatCurrency } from '../../../helpers'
import Fee from './Fee.vue'

const props = defineProps<{
    courses: iItem[]
    terms: iItem[]
    fees: iFees
    search?: string | null
    notification: iNotification
}>()

const searchVal = ref<string | undefined | null>(props.search)

watch(() => searchVal.value, debounce((value: string) => {
    let options: { search?: string | null } = {}
    if (value) {
        options = { search: value }
    }
    router.get(route('accounts-fees'), options, {
        only: ['fees', 'search'],
        preserveScroll: true,
        preserveState: true
    })
}, 500))



const showDialog = ref<boolean>(false)
const selectedFee = ref<iFee | null>(null)

const editFee = (fee: iFee) => {
    selectedFee.value = fee
    showDialog.value = true
}
const newFee = () => {
    selectedFee.value = null
    showDialog.value = true
}

const onClose = () => {
    selectedFee.value = null
    showDialog.value = false
}

</script>
<template>
    <Toast position="top-center" />
    <Fee :show="showDialog" :fee="selectedFee" @closed="onClose" />
    <AuthenticatedLayout title="Fees">
        <div class="pb-8 flex justify-between items-center">
            <div>
                <SecondaryButton @click="newFee">
                    <Icon class="w-5 h-5" type="add" />
                    <span class="hidden md:inline-flex">New Fee</span>
                </SecondaryButton>
            </div>
            <div>
                <span class="relative">
                    <span class="pi pi-search absolute top-[50%] -translate-y-[50%] left-2 opacity-50"></span>
                    <InputText v-model="searchVal" placeholder="Search" class="px-8 w-full"
                        :pt="{ root: { class: 'rounded-full focus:ring-primary-500 text-surface-600 dark:text-surface-200 bg-surface-0 dark:bg-surface-700' } }" />
                </span>
            </div>
        </div>
        <div class="col-span-2">
            <div class="flex flex-col gap-2">
                <ListItem v-for="fee in fees.data">
                    <div>
                        <div class="text-sm font-normal text-gray-700 dark:text-gray-400"
                            v-text="`${fee?.term?.name} : ${fee?.course?.name}`"></div>
                        <div class="font" v-text="formatCurrency(fee.amount)"></div>
                    </div>
                    <div class="flex gap-2 items-center">
                        <SecondaryButton @click="editFee(fee)">
                            <Icon class="h-4 w-4" type="edit" />
                            <span class="hidden md:inline-flex">Edit</span>
                        </SecondaryButton>
                    </div>
                </ListItem>
            </div>
            <Paginator :items="fees" />
        </div>
    </AuthenticatedLayout>
</template>
