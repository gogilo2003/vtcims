<script lang="ts" setup>
import { ref, watch, computed } from 'vue';
import Modal from '../../Components/Modal.vue'
import { iItem } from '../../interfaces/index';
import { router, usePage } from '@inertiajs/vue3';
import InputLabel from '../../Components/InputLabel.vue';
import SecondaryButton from '../../Components/SecondaryButton.vue';
import PrimaryButton from '../../Components/PrimaryButton.vue';
import Dropdown from 'primevue/dropdown';
import InputText from 'primevue/inputtext';
import MultiSelect from 'primevue/multiselect';
import Icon from '../../Components/Icons/Icon.vue';

defineProps({
    show: {
        type: Boolean,
        default: false
    }
})

const emit = defineEmits(['closed'])

const departments = computed(() => usePage().props.departments)
const courses = computed(() => usePage().props.courses)
const intakes = computed(() => usePage().props.intakes)
const programs = computed(() => usePage().props.programs)
const sponsors = computed(() => usePage().props.sponsors)
const roles = computed(() => usePage().props.roles)
const years = computed(() => usePage().props.years)

const downloadForm = ref<{
    t: string | ""// title
    st: string | ""// sub title
    d: iItem | null // department
    c: iItem | null // course
    i: iItem | null // intake
    g: number | null // gender
    su: string | "" // status
    sp: iItem | null // sponsor
    pr: iItem | null // program
    r: number | null // role
    da: string | null // date_of_admission
    age: number | null // age
    y: number[] | [] // age
}>({
    t: "",
    st: "",
    d: null,
    c: null,
    i: null,
    g: null,
    su: "",
    sp: null,
    pr: null,
    r: null,
    da: null,
    age: null,
    y: [],
})
const download = () => {

    let data = {};

    if (downloadForm.value.t) {
        data = { ...data, t: downloadForm.value.t }
    }
    if (downloadForm.value.st) {
        data = { ...data, st: downloadForm.value.st }
    }
    if (downloadForm.value.d?.id) {
        data = { ...data, d: downloadForm.value.d.id }
    }
    if (downloadForm.value.c?.id) {
        data = { ...data, c: downloadForm.value.c.id }
    }
    if (downloadForm.value.i?.id) {
        data = { ...data, i: downloadForm.value.i.id }
    }
    if (downloadForm.value.g) {
        data = { ...data, g: downloadForm.value.g }
    }
    if (downloadForm.value.su) {
        data = { ...data, su: downloadForm.value.su }
    }
    if (downloadForm.value.sp?.id) {
        data = { ...data, sp: downloadForm.value.sp.id }
    }
    if (downloadForm.value.pr?.id) {
        data = { ...data, pr: downloadForm.value.pr.id }
    }
    if (downloadForm.value.da) {
        data = { ...data, da: downloadForm.value.da }
    }
    if (downloadForm.value.age) {
        data = { ...data, age: downloadForm.value.age }
    }
    if (downloadForm.value.y) {
        data = { ...data, y: downloadForm.value.y.join(',') }
    }
    window.open(route('students-enrollment', data), '_BLANK')
}

watch(() => downloadForm.value.d, (value) => {
    downloadForm.value.t = ""
    if (downloadForm.value.d?.id) {
        let name = downloadForm.value.d?.name
        downloadForm.value.t = `Trainees from ${name} Department`
    }
    router.get(route('students'), { d: downloadForm.value.d?.id }, {
        only: ['courses'],
        preserveScroll: true,
        preserveState: true
    })
})
watch(() => downloadForm.value.c, (value) => {

    if (value?.id) {
        let name = downloadForm.value.c?.name

        downloadForm.value.t = `Trainees from ${name} Course`
    }
    router.get(route('students'), { d: downloadForm.value.d?.id, c: downloadForm.value.c?.id }, {
        only: ['intakes'],
        preserveScroll: true,
        preserveState: true
    })
})
watch(() => downloadForm.value.i, (value) => {

    if (value?.id) {
        let name = downloadForm.value.i?.name

        downloadForm.value.t = `Trainees from ${name} Intake`
    }
})
watch(() => downloadForm.value.su, (value) => {

    if (value) {
        let name = downloadForm.value.su

        downloadForm.value.t = `${name} Trainees`
    }
})

const close = () => {
    downloadForm.value.y = [];
    downloadForm.value.t = "";
    downloadForm.value.st = "";
    downloadForm.value.d = null;
    downloadForm.value.c = null;
    downloadForm.value.su = "";
    downloadForm.value.sp = null;
    downloadForm.value.pr = null;
    downloadForm.value.r = null;
    downloadForm.value.da = null;
    downloadForm.value.age = null;
    emit('closed', false)
}
</script>
<template>
    <Modal :show="show">
        <template #header>
            <div>Download Options</div>
            <button @click="close">
                <Icon class="h-4 w-4" type="close" />
            </button>
        </template>
        <div class="mb-4">
            <InputLabel value="Report Title" />
            <InputText v-model="downloadForm.t" />
        </div>
        <!-- <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="mb-4">
                <InputLabel value="Departments" />
                <Dropdown showClear v-model="downloadForm.d" :options="departments" optionLabel="name" />
            </div>
            <div class="mb-4">
                <InputLabel value="Courses" />
                <Dropdown showClear v-model="downloadForm.c" :options="courses" optionLabel="name" />
            </div>
            <div class="mb-4">
                <InputLabel value="Intakes" />
                <Dropdown showClear v-model="downloadForm.i" :options="intakes" optionValue="id" optionLabel="name" />
            </div>
            <div class="mb-4">
                <InputLabel value="Program" />
                <Dropdown showClear v-model="downloadForm.pr" :options="programs" optionLabel="name" />
            </div>
            <div class="mb-4">
                <InputLabel value="Sponsor" />
                <Dropdown showClear v-model="downloadForm.sp" :options="sponsors" optionLabel="name" />
            </div>
            <div class="mb-4">
                <InputLabel value="Gender" />
                <Dropdown showClear v-model="downloadForm.g"
                    :options="[{ id: 0, name: 'Male' }, { id: 1, name: 'Female' }]" optionValue="id"
                    optionLabel="name" />
            </div>
            <div class="mb-4">
                <InputLabel value="Status" />
                <Dropdown showClear v-model="downloadForm.su"
                    :options="['In session', 'On Attachment', 'Completed', 'Dropout']" />
            </div>
            <div class="mb-4">
                <InputLabel value="Roles" />
                <Dropdown showClear v-model="downloadForm.r" :options="roles" optionLabel="name" />
            </div> -->
        <div class="mb-4">
            <InputLabel value="Years" />
            <MultiSelect showClear v-model="downloadForm.y" :options="years" />
        </div>
        <!-- </div> -->
        <div class="flex justify-between">
            <PrimaryButton @click="download">Download</PrimaryButton>
            <SecondaryButton @click="close">Cancel</SecondaryButton>
        </div>
    </Modal>
</template>
