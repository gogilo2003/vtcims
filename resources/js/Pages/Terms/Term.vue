<script lang="ts" setup>
import { computed } from 'vue';
import Modal from '../../Components/Modal.vue'
import PrimaryButton from '../../Components/PrimaryButton.vue'
import SecondaryButton from '../../Components/SecondaryButton.vue'
import { iTerm } from '../../interfaces';
import { useForm, usePage } from '@inertiajs/vue3'
import Icon from '../../Components/Icons/Icon.vue';
import Toast from 'primevue/toast'
import Calendar from 'primevue/calendar'
import InputText from 'primevue/inputtext'
import { useToast } from 'primevue/usetoast';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import { watch, ref } from 'vue';
import InputSwitch from 'primevue/inputswitch';

const props = defineProps<{
    term: iTerm
    show: boolean
}>()

const emit = defineEmits(['closed'])

const toast = useToast()

const title = computed(() => {
    if (props?.term?.id) {
        return 'Edit Term'
    }
    return 'New Term'
})

const form = useForm(
    {
        id: null,
        name: "",
        year: null,
        start_date: null,
        end_date: null,
        allocate: null
    }
)

watch(() => props.term, (value) => {
    selectedYear.value = value.year ? new Date(`01-01-${value?.year}`) : new Date()
    form.id = value?.id
    form.name = value?.name
    form.start_date = value?.start_date
    form.end_date = value?.end_date
})

const selectedYear = ref<Date>(new Date());

watch(() => selectedYear.value, (value: Date) => {
    form.year = value.getFullYear()
})

const submit = () => {
    form.clearErrors();
    if (form.id) {
        form.patch(route('terms-update', form.id), {
            only: ['terms', 'notification', 'errors'],
            onSuccess: () => {
                toast.add({
                    severity: 'success',
                    summary: 'Success',
                    detail: usePage().props?.notification?.success,
                    life: 4000
                })
                cancel()
            },
            onError: () => {
                toast.add({
                    severity: 'error',
                    summary: 'Error',
                    detail: usePage().props?.notification?.danger ?? 'An error occurred! Please check your fields and try again',
                    life: 4000
                })
            }
        })
    } else {
        form.post(route('terms'), {
            only: ['terms', 'notification', 'errors'],
            onSuccess: () => {
                toast.add({
                    severity: 'success',
                    summary: 'Success',
                    detail: usePage().props?.notification?.success,
                    life: 4000
                })
                cancel()
            },
            onError: () => {
                toast.add({
                    severity: 'error',
                    summary: 'Error',
                    detail: usePage().props?.notification?.danger ?? 'An error occurred! Please check your fields and try again',
                    life: 4000
                })
            }
        })
    }
}

const cancel = () => {
    form.reset()
    form.clearErrors()
    emit('closed', false)
}

</script>
<template>
    <Toast position="top-center" />
    <Modal :show="show" maxWidth="md">
        <template #header>
            <div class="text-medium uppercase" v-text="title"></div>
            <button @click="cancel">
                <Icon class="h-5 w-5" type="close" />
            </button>
        </template>
        <div class="shadow bg-white p-3">
            <div class="mb-4">
                <InputLabel value="Year" />
                <Calendar dateFormat="yy" view="year" v-model="selectedYear" />
                <InputError :message="form.errors.year" />
            </div>
            <div class="mb-4">
                <InputLabel value="Name" />
                <InputText v-model="form.name" />
                <InputError :message="form.errors.name" />
            </div>
            <div class="mb-4">
                <InputLabel value="Year" />
                <Calendar dateFormat="D, d M, yy" view="date" v-model="form.start_date" />
                <InputError :message="form.errors.start_date" />
            </div>
            <div class="mb-4">
                <InputLabel value="Year" />
                <Calendar dateFormat="D, d M, yy" view="date" v-model="form.end_date" />
                <InputError :message="form.errors.end_date" />
            </div>
            <div class="mb-4">
                <InputSwitch v-model="form.allocate" />
                <InputError :message="form.errors.allocate" />
            </div>
        </div>
        <template #footer>
            <PrimaryButton @click="submit" :class="{ 'opacity-30': form.processing }" :disabled="form.processing">
                Save
            </PrimaryButton>
            <SecondaryButton @click="cancel">Cancel</SecondaryButton>
        </template>
    </Modal>
</template>
