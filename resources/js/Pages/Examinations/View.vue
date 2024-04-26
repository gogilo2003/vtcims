<script lang="ts" setup>
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout.vue';
import { iExamination } from '../../interfaces/index';
import Toast from 'primevue/toast';
import { useForm, Link } from '@inertiajs/vue3';
import SecondaryButton from '../../Components/SecondaryButton.vue';
import Icon from '../../Components/Icons/Icon.vue';
import { ref, onMounted } from 'vue';
import InputNumber from 'primevue/inputnumber';
import { debounce } from 'lodash';

interface iResult {
    student_id: number
    marks: {
        result_id: number | null
        test_id: number
        score: number
    }[]
}

const props = defineProps<{
    examination: iExamination
}>()

onMounted(() => {
    form.results = props.examination.students
})

const form = useForm<{
    examination: number | null,
    results: iResult[]
}>({
    examination: null,
    results: []
});

const edit = ref(false)

const editMarks = () => {
    edit.value = true
}

const cancel = () => {
    edit.value = false
}
const submit = () => {
    form
}

const marksChanged = debounce((event, student_id: number, index, key) => {
    console.log(event.value, index, key, score.score);
}, 500)
</script>
<template>
    <Toast position="top-center" />
    <AuthenticatedLayout title="Examinations">
        <div class="shadow rounded-lg bg-white p-4">
            <div class="text-3xl font-light uppercase text-center pt-3 pb-1" v-text="examination.title"></div>
            <div class="text-sm font-light pb-2 text-center"
                v-text="examination?.intakes?.map(intake => intake.name).join(', ')">
            </div>
            <div class="flex justify-between">
                <div class="flex gap-2 py-4">
                    <Link :href="route('examinations')"
                        class="inline-flex gap-1 items-center px-2 md:px-3 py-2 bg-white dark:bg-gray-800 border border-lime-500 dark:border-gray-700 rounded-full font-semibold text-xs text-gray-700 dark:text-gray-200 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-lime-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                    <Icon class="h-4 w-4" type="arrow-left" />
                    Back to Examinations
                    </Link>
                    <SecondaryButton @click="editMarks">
                        <Icon class="h-4 w-4" type="done" />Enter Marks
                    </SecondaryButton>
                    <SecondaryButton>
                        <Icon class="h-4 w-4" type="pdf" />Download
                    </SecondaryButton>
                </div>
                <div class="flex gap-2 items-center">
                    <SecondaryButton v-if="edit" @click="cancel">
                        <Icon class="h-4 w-4" type="close" />Cancel
                    </SecondaryButton>
                    <SecondaryButton v-if="edit">
                        <Icon class="h-4 w-4" type="checkmark" />Save
                    </SecondaryButton>
                </div>
            </div>
            <table class="w-full border-collapse border text-sm uppercase">
                <thead class="font-medium text-gray-100 bg-gray-600">
                    <tr>
                        <th class="border px-3 py-2 text-left w-4">#</th>
                        <th class="border px-3 py-2 text-left w-40">Admission No</th>
                        <th class="border px-3 py-2 text-left">Name</th>
                        <th class="border px-3 py-2 w-48" v-for="{ title } in examination.tests" v-text="title">
                        </th>
                    </tr>
                </thead>
                <tbody class="text-light">
                    <tr class="even:bg-gray-100"
                        v-for="({ id, admission_no, name, results }, index) in examination.students">
                        <td class="px-3 py-2 border" v-text="`${index + 1}.`"></td>
                        <td class="px-3 py-2 border" v-text="admission_no"></td>
                        <td class="px-3 py-2 border" v-text="name"></td>
                        <td class="px-3 py-2 border text-right" v-for="(result, key) in results">
                            <InputNumber :key="key" :min="0" :max="100" :useGrouping="false" v-if="edit"
                                @input="marksChanged($event, id, result, index, key)" v-model="result.score" />
                            <span v-else v-text="result.score"></span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AuthenticatedLayout>
</template>
