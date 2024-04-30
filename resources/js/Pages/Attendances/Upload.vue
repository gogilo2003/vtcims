<script lang="ts" setup>
import Modal from '../../Components/Modal.vue'
import Icon from '../../Components/Icons/Icon.vue';
import { calculateSuggestedDate } from '../../helpers.js';
import { iAllocationLesson } from "../../interfaces/index";
import { useForm, usePage } from '@inertiajs/vue3';
import Calendar from 'primevue/calendar';
import { watch, ref } from 'vue';
import { useToast } from 'primevue/usetoast';
import SecondaryButton from '../../Components/SecondaryButton.vue';
import PrimaryButton from '../../Components/PrimaryButton.vue';
import InputError from '../../Components/InputError.vue';

const props = defineProps<{
    show: boolean
    lesson: iAllocationLesson
}>()
const emit = defineEmits(['closed'])

const toast = useToast()

const close = () => {
    form.reset()
    form.clearErrors()
    emit('closed', false)
}

const form = useForm({
    allocation: null,
    mark_at: "",
    file: null
})

watch(() => props?.lesson, () => {
    if (props?.lesson) {
        let days = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"]
        form.mark_at = calculateSuggestedDate(days.findIndex((item) => item == props?.lesson?.day))
    }
})

const upload = () => {
    form.transform((data) => {
        return {
            allocation: props?.lesson?.id,
            mark_at: data.mark_at,
            file: data.file
        }
    }).post(route('attendances-upload'), {
        preserveScroll: true,
        preserveState: true,
        only: ['notification', 'allocations', 'errors'],
        onSuccess: () => {
            if (usePage().props?.notification?.success) {
                toast.add({
                    severity: 'success',
                    summary: 'Success',
                    detail: usePage().props?.notification?.success,
                    life: 8000
                })
                close()
            } else {
                toast.add({
                    severity: 'error',
                    summary: 'Error',
                    detail: usePage().props?.notification?.danger,
                    life: 8000
                })
            }
        },
        onError: () => {
            toast.add({
                severity: 'error',
                summary: 'Error',
                detail: usePage().props?.notification?.danger ?? "An error occurred! Please check you fields and try again",
                life: 8000
            })
        }
    })
}
const selectedFile = ref('No file selected')
const inputFile = ref(null)
const selectFile = () => {
    inputFile.value?.click()
}

watch(() => form.file, (file) => {
    if (file?.name) {
        selectedFile.value = file?.name;
    } else {
        selectedFile.value = 'No file selected';
    }
})
</script>
<template>
    <Modal :show="show">
        <template #header>
            <div class="text-white text-lg uppercase">Upload</div>
            <button class="text-gray-200 hover:text-gray-100 transition-colors duration-150" @click="close">
                <Icon class="h-6 w-6" type="close" />
            </button>
        </template>
        <div class="">
            <div
                class="flex flex-col md:flex-row items-start md:items-end justify-between gap-2 mb-3 md:pb-4 flex-none">
                <div class="flex flex-col gap-2">
                    <div
                        class="flex items-start md:items-center gap-1 text-sm md:text-lg font-medium uppercase flex-nowrap">
                        <span v-text="`${lesson?.title} : ${lesson?.subject}`" class=""></span>
                    </div>
                    <div class=" grid grid-cols-1 md:grid-cols-2 gap-2">
                        <div class="flex flex-nowrap items-start md:items-center md:gap-1 uppercase text-xs md:text-sm">
                            <span class="font-semibold">Term:</span>
                            <span v-text="lesson?.term" class="text-gray-600 dark:text-gray-300"></span>
                        </div>
                        <div
                            class="hidden md:flex flex-col md:flex-row items-start md:items-center md:gap-1 uppercase text-xs md:text-sm">
                            <span class="font-semibold">Instructor:</span>
                            <span v-text="lesson?.instructor" class="text-gray-600 dark:text-gray-300"></span>
                        </div>
                    </div>
                    <div
                        class="hidden md:flex flex-col md:flex-row items-start md:items-center md:gap-1 uppercase text-xs md:text-sm">
                        <span class="font-semibold">Intakes:</span>
                        <span v-text="lesson?.intakes" class="text-gray-600 dark:text-gray-300"></span>
                    </div>
                </div>
            </div>
            <div v-if="form.errors.allocation" class="py-3">
                <InputError :message="form.errors.allocation" />
            </div>
        </div>
        <form @submit.prevent="upload">
            <div class="flex items-start gap-2 mb-6">
                <div class="flex-none">
                    <div class="">
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Date</label>
                        <Calendar v-model="form.mark_at" :manualInput="false" showIcon iconDisplay="input" :pt="{
        root: {
            class: 'inline-flex w-full relative'
        },
        input: {
            class: 'font-sans leading-none text-surface-600 dark:text-surface-200 placeholder:text-surface-400 dark:placeholder:text-surface-500 bg-surface-0 dark:bg-surface-900 border border-surface-300 dark:border-surface-600 m-0 px-3 py-2 text-xs font-medium uppercase w-full appearance-none rounded-2xl flex-1 pr-9 transition-colors duration-200 hover:border-primary-500 dark:hover:border-primary-400 focus:outline-none focus:outline-offset-0 focus:ring focus:ring-primary-500/50 dark:focus:ring-primary-400/50'
        }
    }" />
                    </div>
                    <InputError :message="form.errors.mark_at" class=" leading-3" />
                </div>
                <div class="flex-1">
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="file_input">Select
                        File</label>
                    <div class="flex items-center w-full bg-gray-200 gap-2 rounded-2xl">
                        <SecondaryButton type="button" @click="selectFile" class="flex-none z-10 relative text-base">
                            Browse
                        </SecondaryButton>
                        <div class="text-xs uppercase" v-text="selectedFile">
                        </div>
                    </div>
                    <input ref="inputFile" class="hidden" id="file_input" type="file"
                        @input="form.file = $event?.target?.files[0]" accept=".xls,.xlsx">
                    <progress v-if="form.progress" :value="form.progress.percentage" max="100">
                        {{ form.progress.percentage }}%
                    </progress>
                    <InputError :message="form.errors.file" />
                </div>
            </div>
            <div>
                <PrimaryButton type="submit" class="flex items-center gap-1">
                    <Icon class="h-5 w-5" type="upload" />
                    <span>Upload</span>
                </PrimaryButton>
            </div>
        </form>
    </Modal>
</template>
