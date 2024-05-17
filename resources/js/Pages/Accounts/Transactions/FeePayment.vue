<script lang="ts" setup>
import Dropdown from 'primevue/dropdown';
import { useForm, router, usePage } from '@inertiajs/vue3';
import Modal from '@/Components/Modal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import InputNumber from 'primevue/inputnumber';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Icon from '@/Components/Icons/Icon.vue';
import Toast from 'primevue/toast';
import { useToast } from 'primevue/usetoast';
import { computed } from 'vue';

const props = defineProps<{
    show: boolean,
    student?: number
}>()
const emit = defineEmits(['closed'])

const toast = useToast();

const form = useForm<{
    id: number | null
    student: number | null
    fee: number | null
    amount: number | null
    mode: number | null
    type: string | null
}>({
    id: null,
    student: null,
    fee: null,
    amount: null,
    mode: null,
    type: "FP",
})

const makePayment = () => {
    form.id = null
    form.student = props.student ?? null
    form.fee = null
    form.amount = null
    form.type = "FP"
    form.mode = null

    close()
}

const close = () => {
    form.reset()
    form.clearErrors()

    emit('closed')
}

const title = computed<string>(() => {
    if (form.id) {
        return "Edit Transaction"
    } else {
        return "New Transaction"
    }
})

const submit = () => {
    if (form.id) {
        form.patch(route('accounts-transactions-update', form.id), {
            preserveScroll: true,
            preserveState: true,
            only: ['transactions', 'notification', 'errors'],
            onSuccess: () => {
                toast.add({
                    severity: 'success',
                    summary: 'Success',
                    detail: usePage().props?.notification?.success,
                    life: 4000
                })
                close()
            },
            onError: () => {
                toast.add({
                    severity: 'error',
                    summary: 'Error',
                    detail: usePage().props?.notification?.danger ?? 'An error occurred! Please check your fields and try again.',
                    life: 4000
                })
            },
        })
    } else {
        form.post(route('accounts-transactions-store'), {
            preserveScroll: true,
            preserveState: true,
            only: ['transactions', 'notification', 'errors'],
            onSuccess: () => {
                toast.add({
                    severity: 'success',
                    summary: 'Success',
                    detail: usePage().props?.notification?.success,
                    life: 4000
                })
                close()
            },
            onError: () => {
                toast.add({
                    severity: 'error',
                    summary: 'Error',
                    detail: usePage().props?.notification?.danger ?? 'An error occurred! Please check your fields and try again.',
                    life: 4000
                })
            },
        })
    }
}
</script>
<template>
    <Modal :show="show">
        <template #header>
            <div v-text="title"></div>
            <button @click="close">
                <Icon class="h-5 w-5" type="close" />
            </button>
        </template>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="mb-4">
                <InputLabel value="Select Student" />
                <Dropdown filter :options="usePage().props?.students" optionValue="id" optionLabel="name"
                    v-model="form.student" />
                <InputError :message="form.errors.student" />
            </div>
            <div class="mb-4">
                <InputLabel value="Select Fee" />
                <Dropdown filter :options="usePage().props?.fees" optionValue="id" optionLabel="name"
                    v-model="form.fee" />
                <InputError :message="form.errors.fee" />
            </div>
            <!-- <div class="mb-4">
                <InputLabel value="Select Transaction Type" />
                <Dropdown filter :options="usePage().props?.transaction_types" optionValue="id" optionLabel="name"
                    v-model="form.type" />
                <InputError :message="form.errors.type" />
            </div> -->
            <div class="mb-4">
                <InputLabel value="Select Transaction Mode" />
                <Dropdown filter :options="usePage().props?.transaction_modes" optionValue="id" optionLabel="name"
                    v-model="form.mode" />
                <InputError :message="form.errors.mode" />
            </div>
            <div class="mb-4 md:col-span-3">
                <InputLabel value="Amount" />
                <InputNumber :useGrouping="false" v-model="form.amount" />
                <InputError :message="form.errors.amount" />
            </div>
        </div>
        <template #footer>
            <PrimaryButton :class="{ 'opacity-30': form.processing }" :disabled="form.processing" @click="submit">Save
            </PrimaryButton>
            <SecondaryButton @click="close">Close</SecondaryButton>
        </template>
    </Modal>
</template>
