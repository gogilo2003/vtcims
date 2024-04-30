<script lang="ts" setup>
import { ref, watch } from "vue";
import Toast from "primevue/toast";
import { iStudent } from "@/interfaces/index";
import Modal from '../../Components/Modal.vue'
import Icon from '../../Components/Icons/Icon.vue'

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
    <Modal :show="visible" maxWidth="5xl">
        <template #header>
            <div>Student Details</div>
            <button @click="close(false)">
                <Icon class="h-5 w-5" type="close" />
            </button>
        </template>
        <div>
            <div class="shadow border border-stone-100 dark:border-gray-500 rounded-lg">
                <div class="relative flex flex-col md:flex-row items-center">
                    <div class="absolute right-4 top-1 rounded-full" :class="{
        'bg-lime-600': student?.status?.toLowerCase() == 'completed',
        'bg-blue-700': student?.status?.toLowerCase() == 'in session',
        'bg-red-600': student?.status?.toLowerCase() == 'dropout',
        'bg-orange-600': student?.status?.toLowerCase() == 'on attachment'
    }">
                        <div class="text-gray-300 px-3 py-2 uppercase font-semibold" v-text="student?.status"></div>
                    </div>
                    <div class="p-4 flex-none w-40 h-40">
                        <img :src="student?.photo_url" alt="" class="w-full h-full object-cover">
                    </div>
                    <div class="relative flex-1 py-2 md:pr-3 w-full px-4 md:pl-0">
                        <h3 v-text="`${student?.first_name}${student?.middle_name ? ' ' + student?.middle_name : ''} ${student?.surname}`"
                            class="text-lg font-semibold mt-3 mb-3 border-b uppercase text-center md:text-left dark:text-gray-300">
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <div class="flex flex-col p-3 rounded border">
                                <span
                                    class="text-xs font-bold uppercase whitespace-nowrap dark:text-stone-400">Admission
                                    No</span>
                                <span v-text="student?.admission_no" class="text-stone-700 dark:text-stone-200"></span>
                            </div>
                            <div class="flex flex-col p-3 rounded border">
                                <span
                                    class="text-xs font-bold uppercase whitespace-nowrap dark:text-stone-400">Course</span>
                                <span v-text="student?.intake?.course"
                                    class="text-stone-700 dark:text-stone-200"></span>
                            </div>
                            <div class="flex flex-col p-3 rounded border">
                                <span
                                    class="text-xs font-bold uppercase whitespace-nowrap dark:text-stone-400">Intake</span>
                                <span v-text="student?.intake?.name" class="text-stone-700 dark:text-stone-200"></span>
                            </div>
                            <div class="flex flex-col p-3 rounded border">
                                <span class="text-xs font-bold uppercase whitespace-nowrap dark:text-stone-400">Date of
                                    Admission</span>
                                <span v-text="student?.date_of_admission"
                                    class="text-stone-700 dark:text-stone-200"></span>
                            </div>
                            <div class="flex flex-col p-3 rounded border">
                                <span
                                    class="text-xs font-bold uppercase whitespace-nowrap dark:text-stone-400">Age</span>
                                <span v-text="student?.age" class="text-stone-700 dark:text-stone-200"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-6 grid grid-cols-1 lg:grid-cols-2 gap-4">
                <div class="shadow rounded-lg overflow-hidden border border-gray-500 dark:border-gray-500">
                    <div
                        class="text-base font-semibold px-3 py-2 border-b border-primary-500 dark:border-gray-500 text-primary-500 dark:text-gray-400">
                        Examinations
                        Summary</div>
                    <table class="table table-striped text-gray-700 dark:text-gray-400">
                        <thead>
                            <tr class="text-left border-b border-gray-200 dark:border-gray-500">
                                <th class="py-2 px-3">Subject</th>
                                <th class="py-2 px-3 text-right">Mean</th>
                                <th class="py-2 px-3 text-right">Min</th>
                                <th class="py-2 px-3 text-right">Max</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b border-gray-200 dark:border-gray-500"
                                v-for="examination in student?.examinations">
                                <td class="py-2 px-3" v-text="examination.subject"></td>
                                <td class="text-right py-2 px-3" v-text="examination.average"></td>
                                <td class="text-right py-2 px-3" v-text="examination.min"></td>
                                <td class="text-right py-2 px-3" v-text="examination.max"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="shadow rounded-lg border border-gray-500">
                    <div
                        class="text-base font-semibold px-3 py-2 border-b border-gray-700 dark:border-gray-500 text-gray-700 dark:text-gray-400">
                        Fee Payment Summary</div>
                    <div class="p-4">
                        <div>
                            Current Term Paid:<span>122312</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Modal>
</template>
