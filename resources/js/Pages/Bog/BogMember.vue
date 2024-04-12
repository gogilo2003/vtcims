<script lang="ts" setup>
import { computed, onMounted, ref, watch } from "vue";
import { useForm, usePage } from '@inertiajs/vue3';
import Dialog from 'primevue/dialog';
import InputText from "primevue/inputtext";
import { useToast } from 'primevue/usetoast';
import Toast from "primevue/toast";
import Button from 'primevue/button';
import Dropdown from 'primevue/dropdown';
import Calendar from 'primevue/calendar';
import { iBogMember } from '../../interfaces/index';

const props = defineProps<{
    show: boolean,
    edit: Boolean,
    member: iBogMember
}>()
const emit = defineEmits(['closed', 'saved'])

const form = useForm({
    id: null,
    idno: null,
    gender: null,
    plwd: false,
    surname: "",
    first_name: "",
    middle_name: "",
    phone: "",
    email: "",
    box_no: null,
    post_code: null,
    town: null,
    position: null,
    active: null,
    term_start_at: null,
    term_end_at: null,
    term_count: 0,
})

const page = usePage()
const toast = useToast()

const positions = computed(() => page?.props?.positions)
const states = ref([
    {
        value: 0,
        text: "Active"
    },
    {
        value: 1,
        text: "Inactive"
    },
])

const genderOptions = ref([
    { value: "Male", text: 'Male' },
    { value: "Female", text: 'Female' },
]);
const plwdOptions = ref([
    { value: 0, text: 'No' },
    { value: 1, text: 'Yes' },
]);

watch(() => props.member, value => {
    form.id = value.id
    form.idno = value.idno
    form.gender = value.gender
    form.plwd = value.plwd
    form.surname = value.surname
    form.first_name = value.first_name
    form.middle_name = value.middle_name
    form.phone = value.phone
    form.email = value.email
    form.box_no = value.box_no
    form.post_code = value.post_code
    form.town = value.town
    form.position = value.position
    form.active = value.active
    form.term_start_at = value.term_start_at
    form.term_end_at = value.term_end_at
    form.term_count = value.term_count
})

const submit = async () => {

    if (props.edit) {
        form.patch(route('bog-members-update', form.id), {
            onSuccess: () => {
                toast.add({
                    severity: 'success',
                    summary: 'Success',
                    detail: page?.props?.notification?.success,
                    life: 3000
                })
                close(false)
                resetErrors()
            },
            onError: () => {
                toast.add({
                    severity: 'success',
                    summary: 'Success',
                    detail: "An error ocurred! Please check the values you provided and try again.",
                    life: 3000
                })

            }
        })
    } else {
        form.post(route('bog-members-store'), {
            only: ['members', 'notification', 'errors'],
            onSuccess: () => {
                form.id = null
                form.idno = null
                form.gender = null
                form.plwd = false
                form.surname = ""
                form.first_name = ""
                form.middle_name = ""
                form.phone = ""
                form.email = ""
                form.box_no = null
                form.post_code = null
                form.town = null
                form.position = null
                form.active = null
                form.term_start_at = null
                form.term_end_at = null
                form.term_count = 0

                toast.add({
                    severity: 'success',
                    summary: 'Success',
                    detail: page?.props?.notification?.success,
                    life: 8000
                })

                resetErrors()

                close(false)
            },
            onError: () => {
                toast.add({
                    severity: 'error',
                    summary: 'Error',
                    detail: "An error ocurred! Please check the values you provided and try again.",
                    life: 8000
                })

                resetErrors()

            }
        })
    }
}

const resetErrors = () => {
    form.clearErrors()
}
onMounted(() => {
})

const close = (value: boolean) => {
    emit('closed', value)
}

const visible = ref(false)
const dialogTitle = computed(() => props.edit ? 'Edit BOG Member' : 'New BOG Member')

watch(() => props.show, (value) => {
    visible.value = value
})

</script>
<template>
    <Toast position="top-center" />
    <Dialog v-model:visible="visible" :closeable="true" @update:visible="close" modal :header="dialogTitle"
        :style="{ width: '50rem' }" :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
        <div class="">
            <form @submit.prevent="submit">
                <div class="card-body">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 py-6">
                        <div class="p-float-label relative">
                            <label :class="{ 'text-red-400': page.props.errors.first_name }" for="first_name">First
                                Name</label>
                            <InputText id="first_name" v-model="form.first_name" />
                            <span v-if="page.props.errors.first_name" v-text="page.props.errors.first_name"
                                class="text-red-400"></span>
                        </div>
                        <div class="relative z-0" :class="{ 'has-error': page.props.errors.middle_name }">
                            <label :class="{ 'text-red-400': page.props.errors.middle_name }" for="middle_name">Middle
                                Name</label>
                            <InputText id="middle_name" v-model="form.middle_name" />
                            <span class="text-red-400" v-if="page.props.errors.middle_name"
                                v-text="page.props.errors.middle_name"></span>
                        </div>
                        <div class="relative z-0" :class="{ 'has-error': page.props.errors.surname }">
                            <label :class="{ 'text-red-400': page.props.errors.surname }" for="surname">Surname</label>
                            <InputText id="surname" v-model="form.surname" />
                            <span class="text-red-400" v-if="page.props.errors.surname"
                                v-text="page.props.errors.surname"></span>
                        </div>
                        <div class="relative z-0" :class="{ 'has-error': page.props.errors.phone }">
                            <label :class="{ 'text-red-400': page.props.errors.phone }" for="phone">Phone</label>
                            <InputText id="phone" v-model="form.phone" />
                            <span class="text-red-400" v-if="page.props.errors.phone"
                                v-text="page.props.errors.phone"></span>
                        </div>
                        <div class="relative z-0 md:col-span-2" :class="{ 'has-error': page.props.errors.email }">
                            <label :class="{ 'text-red-400': page.props.errors.email }" for="email">Email</label>
                            <InputText id="email" v-model="form.email" />
                            <span class="text-red-400" v-if="page.props.errors.email"
                                v-text="page.props.errors.email"></span>
                        </div>
                        <div class="relative z-0" :class="{ 'has-error': page.props.errors.box_no }">
                            <label :class="{ 'text-red-400': page.props.errors.box_no }" for="box_no">Box No</label>
                            <InputText id="box_no" v-model="form.box_no" />
                            <span class="text-red-400" v-if="page.props.errors.box_no"
                                v-text="page.props.errors.box_no"></span>
                        </div>
                        <div class="relative z-0" :class="{ 'has-error': page.props.errors.post_code }">
                            <label :class="{ 'text-red-400': page.props.errors.post_code }" for="post_code">Post
                                Code</label>
                            <InputText id="post_code" v-model="form.post_code" />
                            <span class="text-red-400" v-if="page.props.errors.post_code"
                                v-text="page.props.errors.post_code"></span>
                        </div>
                        <div class="relative z-0" :class="{ 'has-error': page.props.errors.town }">
                            <label :class="{ 'text-red-400': page.props.errors.town }" for="town">Town</label>
                            <InputText id="town" v-model="form.town" />
                            <span class="text-red-400" v-if="page.props.errors.town"
                                v-text="page.props.errors.town"></span>
                        </div>
                        <div class="relative z-0" :class="{ 'has-error': page.props.errors.idno }">
                            <label :class="{ 'text-red-400': page.props.errors.idno }" for="idno">IDNo</label>
                            <InputText id="idno" v-model="form.idno" />
                            <span class="text-red-400" v-if="page.props.errors.idno"
                                v-text="page.props.errors.idno"></span>
                        </div>
                        <div class="relative z-0 is-filled is-focused"
                            :class="{ 'has-error': page.props.errors.gender }">
                            <label :class="{ 'text-red-400': page.props.errors.gender }" for="gender">Gender</label>
                            <Dropdown :options="genderOptions" option-label="text" option-value="value"
                                v-model="form.gender" />
                            <span class="text-red-400" v-if="page.props.errors.gender"
                                v-text="page.props.errors.gender"></span>
                        </div>
                        <div class="relative z-0 is-filled is-focused" :class="{ 'has-error': page.props.errors.plwd }">
                            <label :class="{ 'text-red-400': page.props.errors.plwd }" for="plwd">PLWD</label>
                            <Dropdown :options="plwdOptions" option-label="text" option-value="value"
                                v-model="form.plwd" />
                            <span class="text-red-400" v-if="page.props.errors.plwd"
                                v-text="page.props.errors.plwd"></span>
                        </div>
                        <div class="relative z-0" :class="{ 'has-error': page.props.errors.term_start_at }">
                            <label :class="{ 'text-red-400': page.props.errors.term_start_at }" for="term_start_at">Term
                                Start Date</label>
                            <Calendar id="term_start_at" v-model="form.term_start_at" />
                            <span class="text-red-400" v-if="page.props.errors.term_start_at"
                                v-text="page.props.errors.term_start_at"></span>
                        </div>
                        <div class="relative z-0" :class="{ 'has-error': page.props.errors.term_end_at }">
                            <label :class="{ 'text-red-400': page.props.errors.term_end_at }" for="term_end_at">Term End
                                Date</label>
                            <Calendar id="term_end_at" v-model="form.term_end_at" />
                            <span class="text-red-400" v-if="page.props.errors.term_end_at"
                                v-text="page.props.errors.term_end_at"></span>
                        </div>
                        <div class="relative z-0" :class="{ 'has-error': page.props.errors.term_count }">
                            <label :class="{ 'text-red-400': page.props.errors.term_count }" for="term_count">Term
                                Count</label>
                            <InputText id="term_count" v-model="form.term_count" />
                            <span class="text-red-400" v-if="page.props.errors.term_count"
                                v-text="page.props.errors.term_count"></span>
                        </div>
                        <div class="relative z-0 is-filled is-focused"
                            :class="{ 'has-error': page.props.errors.student_role }">
                            <label :class="{ 'text-red-400': page.props.errors.student_role }" for="position">Member
                                Position</label>
                            <Dropdown id="position" option-label="name" option-value="id" :options="positions"
                                v-model="form.position" />
                            <span class="text-red-400" v-if="page.props.errors.position"
                                v-text="page.props.errors.position"></span>
                        </div>
                        <div class="relative z-0 is-filled is-focused"
                            :class="{ 'has-error': page.props.errors.status }">
                            <label :class="{ 'text-red-400': page.props.errors.status }" for="active">Status</label>
                            <Dropdown id="active" :options="states" v-model="form.active" option-value="value"
                                option-label="text" />
                            <span class="text-red-400" v-if="page.props.errors.active"
                                v-text="page.props.errors.active"></span>
                        </div>
                    </div>
                </div>
                <div class="flex gap-2 items-center justify-between">
                    <Button type="submit" label="Save" rounded />
                    <Button type="reset" @click="close(false)" label="Cancel" rounded outlined severity="danger" />
                </div>
            </form>
        </div>
    </Dialog>
</template>
