<script lang="ts" setup>
import { iTerm, iTranscript } from '../../../interfaces/index';
const props = defineProps<{
    term: iTerm
    transcript: iTranscript
}>()
const { admission_no, name, marks, total, average, grade, remark, course, intake } = props.transcript
</script>
<template>
    <div class=" bg-gray-50 px-16 py-24 h-[70.14rem]">
        <div class="flex flex-col items-center">
            <div class="rounded mb-4 border border-gray-500 p-4 w-full flex flex-col items-center">
                <div class="text-2xl uppercase font-normal" v-text="name"></div>
                <div class="grid grid-cols-4 gap-4 mt-6 whitespace-nowrap">
                    <div class="flex flex-col py-3 px-6 rounded-lg shadow bg-white text-center">
                        <span class="font-medium text-sm uppercase">Admission No</span>
                        <span class="text-sm font-light text-gray-800" v-text="admission_no"></span>
                    </div>
                    <div class="flex flex-col py-3 px-6 rounded-lg shadow bg-white text-center">
                        <span class="font-medium text-sm uppercase">Course</span>
                        <span class="text-sm font-light text-gray-800" v-text="course"></span>
                    </div>
                    <div class="flex flex-col py-3 px-6 rounded-lg shadow bg-white text-center">
                        <span class="font-medium text-sm uppercase">Intake</span>
                        <span class="text-sm font-light text-gray-800" v-text="intake"></span>
                    </div>
                    <div class="flex flex-col py-3 px-6 rounded-lg shadow bg-white text-center">
                        <span class="font-medium text-sm uppercase">Term</span>
                        <span class="text-sm font-light text-gray-800" v-text="`${term?.year}-${term?.name}`"></span>
                    </div>
                </div>
            </div>

            <div class="w-full">
                <table class="border-collapse border-t border-b w-full">
                    <thead>
                        <tr class="bg-gray-800 text-white">
                            <th class="px-3 py-2 border-l border-r">#</th>
                            <th class="px-3 py-2 border-r">Subject</th>
                            <th class="px-3 py-2 border-r">Score</th>
                            <th class="px-3 py-2 border-r">Grade</th>
                            <th class="px-3 py-2 border-r">Remark</th>
                            <th class="px-3 py-2 border-r">Average</th>
                            <th class="px-3 py-2 border-r">Min</th>
                            <th class="px-3 py-2 border-r">Max</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="even:bg-gray-100" v-for="(mark, index) in marks">
                            <td class="px-3 py-2 border-l border-r" v-text="index + 1"></td>
                            <td class="px-3 py-2 border-r" v-text="mark.subject"></td>
                            <td class="px-3 py-2 border-r text-center" v-text="mark.score"></td>
                            <td class="px-3 py-2 border-r text-center" v-text="mark.grade"></td>
                            <td class="px-3 py-2 border-r" v-text="mark.remark"></td>
                            <td class="px-3 py-2 border-r text-center" v-text="mark.average"></td>
                            <td class="px-3 py-2 border-r text-center" v-text="mark.min"></td>
                            <td class="px-3 py-2 border-r text-center" v-text="mark.max"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="w-full my-8">
                <div>Total Score: <span v-text="total"></span></div>
                <div>Mean Score: <span v-text="average"></span></div>
                <div>Mean Grade: <span v-text="grade"></span></div>
                <div>Overall Remark: <span v-text="remark"></span></div>
            </div>
            <div class="w-full grid gap-16 grid-cols-3 text-sm font-medium uppercase text-center mt-16">
                <div class="border-t border-dashed border-gray-800 py-5">HOD's Signature</div>
                <div class="border-t border-dashed border-gray-800 py-5">Principal's Signature</div>
                <div class="border-t border-dashed border-gray-800 py-5">Parent/Guardian's Signature</div>
            </div>
        </div>
    </div>
</template>
