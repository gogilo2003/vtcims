<script lang="ts" setup>
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout.vue';
import { iExamination, iNotification } from '../../interfaces/index';
import Toast from 'primevue/toast';
import { useForm, Link } from '@inertiajs/vue3';
import SecondaryButton from '../../Components/SecondaryButton.vue';
import Icon from '../../Components/Icons/Icon.vue';
import { ref, onMounted } from 'vue';
import InputNumber from 'primevue/inputnumber';
import { debounce } from 'lodash';
import { useToast } from 'primevue/usetoast';

const toast = useToast()

interface iResult {
    id: number | null
    test_id: number
    score: number | null
}

interface iStudent {
    student_id: number
    name: string,
    admission_no: string,
    marks: iResult[]
}

const props = defineProps<{
    examination: iExamination
    notification: iNotification
}>()

onMounted(() => {
    form.examination = props.examination.id
    form.students = props.examination?.students.map(stud => ({
        student_id: stud.id,
        name: stud.name,
        admission_no: stud.admission_no,
        marks: stud.results.map(res => ({
            id: res.id,
            test_id: res.test_id,
            score: res.score
        }))
    }))
})

const form = useForm<{
    examination: number | null,
    students: iStudent[] | null
}>({
    examination: null,
    students: null
});

const edit = ref(false)

const editMarks = () => {
    edit.value = true
}

const cancel = () => {
    edit.value = false
}
const submit = () => {
    form.post(route('examinations-store'), {
        only: ['examinations', 'notification', 'errors'],
        onSuccess: () => {
            toast.add({
                severity: 'success',
                summary: 'Success',
                detail: props.notification.success,
                life: 4000
            })
            cancel()
        }
    })
}

const markList = (blank: boolean = false) => {
    let options: { id: number | null, blank?: number } = { id: props.examination.id }
    let url = route('examinations-marklist', options)

    if (blank) {
        url = url + '?blank=true'
    }


    console.log(blank, url);

    window.open(url)
}


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
                    <SecondaryButton @click="markList(false)">
                        <Icon class="h-4 w-4" type="pdf" />Download
                    </SecondaryButton>
                    <SecondaryButton @click="markList(true)">
                        <Icon class="h-4 w-4" type="pdf" />Download Blank
                    </SecondaryButton>
                </div>
                <div class="flex gap-2 items-center">
                    <SecondaryButton v-if="edit" @click="cancel">
                        <Icon class="h-4 w-4" type="close" />Cancel
                    </SecondaryButton>
                    <SecondaryButton v-if="edit" @click="submit">
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
                    <tr class="even:bg-gray-100" v-for="({ id, admission_no, name, marks }, index) in form.students">
                        <td class="px-3 py-2 border" v-text="`${index + 1}.`"></td>
                        <td class="px-3 py-2 border" v-text="admission_no"></td>
                        <td class="px-3 py-2 border" v-text="name"></td>
                        <td class="px-3 py-2 border text-right" v-for="(result, key) in marks">
                            <InputNumber :key="key" :min="0" :max="100" :useGrouping="false" v-if="edit"
                                v-model="result.score" />
                            <span v-else v-text="result.score"></span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AuthenticatedLayout>
</template>
