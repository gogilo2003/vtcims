<script lang="ts" setup>
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout.vue'
import { iInstructor, iLink, iCourse, iIntake } from '../../interfaces/index';
import Paginator from '../../Components/Paginator.vue';
import SecondaryButton from '../../Components/SecondaryButton.vue';
import { ref, watch } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import { debounce } from 'lodash';
import Icon from '../../Components/Icons/Icon.vue';
import InputText from 'primevue/inputtext';
import ListItem from '@/Components/ListItem.vue';
import Dialog from 'primevue/dialog';
import Dropdown from 'primevue/dropdown';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Calendar from 'primevue/calendar';
import Button from 'primevue/button';
import { useToast } from 'primevue/usetoast';
import { generateIntakeName } from '../../helpers';

const props = defineProps<{
    intakes: {
        current_page: number,
        first_page_url: string,
        from: number,
        last_page: number,
        last_page_url: string,
        links: Array<iLink>,
        next_page_url: string,
        path: string,
        per_page: number,
        prev_page_url: string,
        to: number,
        total: number
        data: Array<iIntake>
    },
    instructors: Array<iInstructor>
    courses: Array<iCourse>
    search: string,
    notification: Object,
    errors: Object
}>()

const toast = useToast()

const form = useForm({
    course: null,
    start_date: null,
    end_date: null,
    instructor: null,
    name: "",
})

const showIntakeDialog = ref(false)
const edit = ref(false)
const dialogTitle = ref('New Intake')
const course = ref<iCourse>()

const newIntake = () => {
    showIntakeDialog.value = true
}

const cancel = () => {
    showIntakeDialog.value = false
    edit.value = false
    dialogTitle.value = 'New Intake'
    form.reset();
}

const searchVal = ref(props.search)

watch(() => searchVal.value, debounce((value: string) => {

    let data = {}

    if (value) {
        data = { search: value }
    }

    router.get(route('intakes'), data, {
        only: ['intakes', 'search'],
        preserveScroll: true,
        preserveState: true
    })
}, 500))

watch(() => form.course, (value) => {
    course.value = props.courses.filter(item => item.id == value)[0]
    form.name = generateIntakeName(course.value.code, form.start_date)
})
watch(() => form.start_date, (value) => {
    course.value = props.courses.filter(item => item.id == form.course)[0]
    form.name = generateIntakeName(course.value.code, form.start_date)
})

const submit = () => {
    if (edit.value) {

    } else {
        form.post(route('intakes-store'), {
            onSuccess: () => {
                toast.add({
                    severity: 'success',
                    summary: 'Success',
                    detail: props?.notification?.success,
                    life: 8000
                })
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

}
</script>
<template>
    <Toast position="top-center" />
    <Dialog :header="dialogTitle" v-model:visible="showIntakeDialog" :style="{ width: '50vw' }"
        :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
        <form @submit.prevent="submit">
            <div class="mb-3 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <InputLabel value="Course" />
                    <Dropdown :options="courses" option-value="id" option-label="name" v-model="form.course" filter />
                    <InputError :message="form.errors.course" />
                </div>
                <div>
                    <InputLabel value="Instructor" />
                    <Dropdown :options="instructors" option-value="id" option-label="name" v-model="form.instructor"
                        filter />
                    <InputError :message="form.errors.instructor" />
                </div>
                <div>
                    <InputLabel value="Start Date" />
                    <Calendar v-model="form.start_date" />
                    <InputError :message="form.errors.start_date" />
                </div>
                <div>
                    <InputLabel value="End Date" />
                    <Calendar v-model="form.end_date" />
                    <InputError :message="form.errors.end_date" />
                </div>
            </div>
            <div class="mb-3">
                <InputLabel value="Name" />
                <InputText v-model="form.name" readonly />
                <InputError :message="form.errors.name" />
            </div>
            <div class="flex items-center justify-between mt-8">
                <Button type="submit" label="Save" size="small" rounded />
                <Button @click="cancel" label="Cancel" size="small" rounded outlined />
            </div>
        </form>
    </Dialog>
    <AuthenticatedLayout title="Intakes">
        <div class="flex items-center justify-between gap-2 mb-3 md:pb-8 ">
            <SecondaryButton @click="newIntake">
                <Icon type="add" />
                <span class="hidden md:inline-flex">New Intake</span>
            </SecondaryButton>
            <div>
                <span class="relative">
                    <i class="pi pi-search absolute -top-[40%] translate-y-[50%] left-2 opacity-50" />
                    <InputText v-model="searchVal" placeholder="Search" class="px-8 w-full"
                        :pt="{ root: { class: 'rounded-full focus:ring-primary-500 text-surface-600 dark:text-surface-200 bg-surface-0 dark:bg-surface-700' } }" />
                </span>
            </div>
        </div>
        <div class="flex flex-col gap-2">
            <ListItem v-for="intake in intakes.data" class="px-4 py-2 rounded-lg shadow-lg bg-white">
                <div>
                    <div v-text="intake.name"
                        class="uppercase text-sm font-semibold text-gray-800 dark:text-primary-default">
                    </div>
                    <div class="flex gap-2 flex-col md:flex-row divide-x">
                        <div class="flex items-center gap-1">
                            <span class="text-xs font-semibold text-gray-800 dark:text-gray-100">COURSE NAME:</span>
                            <span v-text="intake.course.name" class="text-xs text-gray-500 dark:text-gray-300"></span>
                        </div>
                        <div class="flex items-center gap-1 pl-2">
                            <span class="text-xs font-semibold text-gray-800 dark:text-gray-100">HEAD OF
                                DEPARTMENT:</span>
                            <span v-text="intake.staff.name" class="text-xs text-gray-500 dark:text-gray-300"></span>
                        </div>
                    </div>
                </div>
                <div>
                    <SecondaryButton>
                        <Icon class="h-4" type="edit" />
                        <span class="hidden md:inline-block">Edit</span>
                    </SecondaryButton>
                </div>
            </ListItem>
            <Paginator :items="intakes" />
        </div>
    </AuthenticatedLayout>
</template>
