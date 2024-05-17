<script lang="ts" setup>
import Modal from '../../Components/Modal.vue';
import { computed, ref } from 'vue';
import { iExamination, iTest } from '../../interfaces/index';
import Icon from '../../Components/Icons/Icon.vue';
import ListItem from '../../Components/ListItem.vue';
import InputLabel from '../../Components/InputLabel.vue';
import InputError from '../../Components/InputError.vue';
import InputText from 'primevue/inputtext';
import InputNumber from 'primevue/inputnumber';
import Calendar from 'primevue/calendar';
import { router, useForm, usePage } from '@inertiajs/vue3';
import SecondaryButton from '../../Components/SecondaryButton.vue';
import PrimaryButton from '../../Components/PrimaryButton.vue';
import { useToast } from 'primevue/usetoast';

const emit = defineEmits(['closed'])
const props = defineProps<{
    examination: iExamination | null
    show: boolean | false
}>()

const toast = useToast()

const title = computed(() => {
    form.id = null
    form.title = null
    form.outof = null
    add.value = false
    return props.examination?.id ? "Edit Examination" : "New Examination"
})

const close = () => {
    emit('closed', false)
}

const tests = computed(() => props.examination?.tests)

const add = ref<boolean>(false)
const edit = computed(() => form.id ? true : false)

const form = useForm<{
    id: number | null
    outof: number | null
    title: string | null
    taken_on: Date | null
}>({
    id: null,
    outof: null,
    title: null,
    taken_on: null
})

const addTest = () => {
    form.id = null
    form.outof = null
    form.title = null
    form.taken_on = null
    add.value = true
}

const cancel = () => {
    form.id = null
    form.outof = null
    form.title = null
    form.taken_on = null
    add.value = false
}

const editTest = (test: iTest) => {
    form.id = test.id
    form.outof = parseFloat(test.outof)
    form.title = test.title
    form.taken_on = new Date(test.taken_on)
    add.value = true
}

const submit = () => {
    if (edit.value) {
        form.transform(data => {
            return {
                examination: props.examination?.id,
                ...data
            }
        }).patch(route('examinations-tests-update', form.id), {
            only: ['examinations', 'errors', 'notification'],
            onSuccess: () => {
                toast.add({
                    severity: 'success',
                    summary: 'success',
                    detail: usePage().props?.notification?.success,
                    life: 3000
                })
                cancel()
            },
            onError: () => {
                toast.add({
                    severity: 'error',
                    summary: 'Error',
                    detail: usePage().props?.notification?.danger ?? 'An error occurred! Please check your fields and try again',
                    life: 3000
                })
            }
        })
    } else {
        form.transform(data => {
            return {
                examination: props.examination?.id,
                ...data
            }
        }).post(route('examinations-tests-store'), {
            only: ['examinations', 'errors', 'notification'],
            onSuccess: () => {
                toast.add({
                    severity: 'success',
                    summary: 'success',
                    detail: usePage().props?.notification?.success,
                    life: 3000
                })
                cancel()
            },
            onError: () => {
                toast.add({
                    severity: 'error',
                    summary: 'Error',
                    detail: usePage().props?.notification?.danger ?? 'An error occurred! Please check your fields and try again',
                    life: 3000
                })
            }
        })
    }
}

const deleteTest = (id: number) => {
    router.delete(route('examinations-tests-destroy', id), {
        only: ['examinations', 'errors', 'notification'],
        onSuccess: () => {
            toast.add({
                severity: 'success',
                summary: 'success',
                detail: usePage().props?.notification?.success,
                life: 3000
            })
            cancel()
        },
        onError: () => {
            toast.add({
                severity: 'error',
                summary: 'Error',
                detail: usePage().props?.notification?.danger ?? 'An error occurred! Please check your fields and try again',
                life: 3000
            })
        }
    })
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
        <div class="py-5 px-3">
            <div v-text="examination?.title"></div>
            <div class="text-xs text-gray-700 dark:text-gray-400"
                v-text="examination?.intakes?.map(intake => intake.name).join(', ')"></div>
            <div class="py-2">
                <SecondaryButton @click="addTest">
                    <Icon class="h-4 w-4" type="add" />
                    <span>Add Test</span>
                </SecondaryButton>
            </div>
        </div>
        <div v-if="tests?.length" class="flex flex-col gap-2 p-2 rounded bg-gray-100 dark:bg-gray-600">
            <ListItem v-for="test in tests">
                <div>
                    <div v-text="test.title"></div>
                    <div class="flex gap-2 flex-row">
                        <span>Out Of</span>
                        <span v-text="test.outof"></span>
                        <span v-text="test.results"></span>
                    </div>
                </div>
                <div class="flex gap-1">
                    <SecondaryButton @click="editTest(test)">
                        <Icon class="h-4 w-4" type="edit" />
                        <span>Edit</span>
                    </SecondaryButton>
                    <SecondaryButton @click="deleteTest(test?.id)">
                        <Icon class="h-4 w-4" type="delete" />
                        <span>Delete</span>
                    </SecondaryButton>
                </div>
            </ListItem>
        </div>
        <div v-if="add">
            <form @submit.prevent="submit" class="border my-3 p-3 rounded">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <div>
                        <InputLabel value="Test title" />
                        <InputText v-model="form.title" />
                        <InputError :message="form.errors.title" />
                    </div>
                    <div>
                        <InputLabel value="Test outof" />
                        <InputNumber v-model="form.outof" />
                        <InputError :message="form.errors.outof" />
                    </div>
                    <div>
                        <InputLabel value="Test Date" />
                        <Calendar v-model="form.taken_on" />
                        <InputError :message="form.errors.taken_on" />
                    </div>
                </div>
                <div class="flex py-2 justify-between items-center">
                    <PrimaryButton :class="{ 'opacity-30': form.processing }" :disabled="form.processing">Save
                    </PrimaryButton>
                    <SecondaryButton @click.prevent="cancel">Cancel</SecondaryButton>
                </div>
            </form>
        </div>
    </Modal>
</template>
