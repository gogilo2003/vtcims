<script lang="ts" setup>
import { ref, watch } from "vue";
import Dialog from 'primevue/dialog';
import Toast from "primevue/toast";
import { iStudent } from "@/interfaces/index";

const props = defineProps<{
    show: boolean,
    student: iStudent
}>()
const emit = defineEmits(['closed', 'saved'])

const close = (value: boolean) => {
    emit('closed', value)
}

const visible = ref(false)

watch(() => props.show, (value) => {
    visible.value = value
})

</script>
<template>
    <Toast position="top-center" />
    <Dialog v-model:visible="visible" :closeable="true" @update:visible="close" modal :style="{ width: '50rem' }"
        :breakpoints="{ '1199px': '75vw', '575px': '90vw' }" header="Student Details">
        <div>
            <div class="shadow border border-stone-100 dark:border-gray-500 rounded-lg">
                <div class="relative flex flex-col md:flex-row items-center">
                    <div class="absolute right-4 top-2 rounded-full" :class="{
                        'bg-lime-600': student.status.toLowerCase() == 'completed',
                        'bg-blue-700': student.status.toLowerCase() == 'in session',
                        'bg-red-600': student.status.toLowerCase() == 'dropout',
                        'bg-orange-600': student.status.toLowerCase() == 'on attachment'
                    }">
                        <div class="text-gray-300 px-3 py-2 uppercase font-semibold" v-text="student.status"></div>
                    </div>
                    <div class="p-4 flex-none w-40 h-40">
                        <img :src="student.photo_url" alt="" class="w-full h-full object-cover">
                    </div>
                    <div class="relative flex-1 py-2 md:pr-3 w-full px-4 md:pl-0">
                        <h3 v-text="student.name"
                            class="text-lg font-semibold my-1 border-b uppercase text-center md:text-left dark:text-gray-300">
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <div class="flex flex-col p-3 rounded border">
                                <span class="text-xs font-bold uppercase whitespace-nowrap dark:text-stone-400">Admission
                                    No</span>
                                <span v-text="student.admission_no" class="text-stone-700 dark:text-stone-200"></span>
                            </div>
                            <div class="flex flex-col p-3 rounded border">
                                <span
                                    class="text-xs font-bold uppercase whitespace-nowrap dark:text-stone-400">Course</span>
                                <span v-text="student.course_name" class="text-stone-700 dark:text-stone-200"></span>
                            </div>
                            <div class="flex flex-col p-3 rounded border">
                                <span
                                    class="text-xs font-bold uppercase whitespace-nowrap dark:text-stone-400">Intake</span>
                                <span v-text="student.intake_name" class="text-stone-700 dark:text-stone-200"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-6 grid grid-cols-1 lg:grid-cols-2">
                <div class="shadow rounded-lg overflow-hidden border border-primary-default dark:border-gray-500">
                    <div
                        class="text-base font-semibold px-3 py-2 border-b border-gray-700 dark:border-gray-500 text-gray-700 dark:text-gray-400">
                        Examinations
                        Summary</div>
                    <table class="table table-striped text-gray-700 dark:text-gray-400">
                        <thead>
                            <tr class="text-left border-b border-gray-200 dark:border-gray-500">
                                <th class="py-2 px-3">Subject</th>
                                <th class="py-2 px-3 text-right">Score</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b border-gray-200 dark:border-gray-500"
                                v-for="examination in student.examinations">
                                <td class="py-2 px-3" v-text="examination.subject"></td>
                                <td class="text-right py-2 px-3" v-text="examination.score"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </Dialog>
</template>
