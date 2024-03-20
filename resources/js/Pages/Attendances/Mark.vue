<script lang="ts" setup>
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout.vue'
import { iInstructor, iCourse, iAllocation, iAllocations, iIntake, iAttendance, iTerm, iSubject } from '../../interfaces/index';
import Paginator from '../../Components/Paginator.vue';
import SecondaryButton from '../../Components/SecondaryButton.vue';
import { ref, watch, onMounted, computed } from 'vue';
import { router, useForm, Link } from '@inertiajs/vue3';
import { debounce } from 'lodash';
import Icon from '../../Components/Icons/Icon.vue';
import InputText from 'primevue/inputtext';
import ListItem from '../../Components/ListItem.vue';
import Dialog from 'primevue/dialog';
import Dropdown from 'primevue/dropdown';
import InputError from '../../Components/InputError.vue';
import InputLabel from '../../Components/InputLabel.vue';
import Button from 'primevue/button';
import { useToast } from 'primevue/usetoast';
import Toast from 'primevue/toast';
import MultiSelect from 'primevue/multiselect';
import InputSwitch from 'primevue/inputswitch';


const props = defineProps<{
    lesson: iLesson,
    search: string,
    current: string,
    notification: Object,
    errors: Object
}>()

const toast = useToast()

const form = useForm({
    id: 0,
    allocation_lesson_id: 0,
    date: null,
    students: []
})

const showAttendanceDialog = ref(false)
const edit = ref(false)

const submit = () => {
    form.post(route('attendances-store'), {
        only: ['notification', 'attendances', 'errors'],
        onSuccess: () => {
            toast.add({
                severity: 'success',
                summary: 'Success',
                detail: props?.notification?.success,
                life: 8000
            })
            form.reset()
            showAttendanceDialog.value = false
        },
        onError: () => {
            toast.add({
                severity: 'error',
                summary: 'Error',
                detail: props?.notification?.danger ?? 'An error ocurred! Please try gain',
                life: 8000
            })
        }
    })
}

</script>
<template>
    <Toast position="top-center" />
    <AuthenticatedLayout title="Attendances">
        <div class="flex flex-col h-[calc(100svh_-_6rem)] md:h-[calc(100svh_-_8rem)]">
            <div
                class="flex flex-col md:flex-row items-start md:items-end justify-between gap-2 mb-3 md:pb-8 flex-none">
                <div class="flex flex-col gap-2">
                    <div
                        class="flex items-start md:items-center gap-1 text-sm md:text-2xl font-thin uppercase flex-nowrap">
                        <span v-text="lesson.title" class="whitespace-nowrap"></span>:
                        <span v-text="lesson.subject" class="whitespace-nowrap"></span>
                    </div>
                    <div class="flex flex-nowrap items-start md:items-center md:gap-1 uppercase text-xs md:text-sm">
                        <span class="font-semibold">Term:</span>
                        <span v-text="lesson.term" class="text-gray-600 dark:text-gray-300"></span>
                    </div>
                    <div
                        class="hidden md:flex flex-col md:flex-row items-start md:items-center md:gap-1 uppercase text-xs md:text-sm">
                        <span class="font-semibold">Instructor:</span>
                        <span v-text="lesson.instructor" class="text-gray-600 dark:text-gray-300"></span>
                    </div>
                    <div
                        class="hidden md:flex flex-col md:flex-row items-start md:items-center md:gap-1 uppercase text-xs md:text-sm">
                        <span class="font-semibold">Intakes:</span>
                        <span v-text="lesson.intakes" class="text-gray-600 dark:text-gray-300"></span>
                    </div>
                    <div class="flex flex-nowrap items-start md:items-center md:gap-1 uppercase text-xs md:text-sm">
                        <span class="font-semibold">Selected Students:</span>
                        <span v-text="form.students.length" class="text-gray-600 dark:text-gray-300"></span>
                    </div>
                </div>
                <div>
                    <Link
                        class="inline-flex gap-1 items-center px-2 md:px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-full font-semibold text-xs text-gray-700 dark:text-gray-200 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-lime-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150"
                        :href="route('attendances')">
                    <Icon class="w-6 h-6 object-contain" type="chevron-circle-back" />
                    <span class="inline-flex">Attendances</span>
                    </Link>
                </div>
            </div>
            <div class="flex-1 overflow-auto">
                <form @submit.prevent="submit">
                    <div class="flex flex-col gap-2">
                        <ListItem v-for="(student, index) in lesson.students" class="">
                            <div class="uppercase flex items-start flex-nowrap gap-1">
                                <div v-text="`${index + 1}.`" class="text-sm md:text-lg w-9 text-right ">
                                </div>
                                <div>
                                    <div v-text="student.name" class="font-thin text-sm md:text-lg"></div>
                                    <div
                                        class="flex items-center gap-1 text-xs font-medium text-gray-600 dark:text-gray-300">
                                        <div v-text="student.admission_no"></div>
                                        <div v-text="student.gender"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="ml-9 md:ml-0">
                                <label class="inline-flex items-center cursor-pointer">
                                    <input :value="student.id" type="checkbox" class="sr-only peer"
                                        v-model="form.students">
                                    <div
                                        class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 dark:peer-focus:ring-primary-600 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary-600">
                                    </div>
                                </label>
                            </div>
                        </ListItem>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
