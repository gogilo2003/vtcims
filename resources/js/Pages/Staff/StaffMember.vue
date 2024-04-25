<script lang="ts" setup>
import { computed, onMounted, ref, watch, watchEffect } from "vue";
import { useForm, usePage } from '@inertiajs/vue3';
import InputText from "primevue/inputtext";
import InputNumber from "primevue/inputnumber";
import { useToast } from 'primevue/usetoast';
import Toast from "primevue/toast";
import Dropdown from 'primevue/dropdown';
import { iStaff, iItem } from '../../interfaces/index';
import Icon from '../../Components/Icons/Icon.vue';
import Modal from '../../Components/Modal.vue'
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";

const props = defineProps<{
    show: boolean,
    edit: Boolean,
    member?: iStaff | null
}>()
const emit = defineEmits(['closed', 'saved'])

const form = useForm<iStaff>({
    id: null,
    idno: null,
    pfno: null,
    manno: null,
    surname: "",
    first_name: "",
    middle_name: "",
    box_no: "",
    post_code: "",
    town: "",
    email: "",
    phone: "",
    employer: "",
    job_group: null,
    designation: null,
    gender: null,
    plwd: false,
    role: null,
    status: null,
    teach: null,
})

const page = usePage()
const toast = useToast()

const roles = computed<iItem[]>((): Array<iItem> => page?.props?.roles)
const statuses = computed<iItem[]>((): Array<iItem> => page?.props?.statuses)
const employers = computed<iItem[]>((): Array<iItem> => page?.props?.employers)
const job_groups = computed<iItem[]>((): Array<iItem> => page?.props?.job_groups)
const designations = computed<iItem[]>((): Array<iItem> => page?.props?.designations)

const genderOptions = ref([
    { value: "Male", text: 'Male' },
    { value: "Female", text: 'Female' },
]);
const plwdOptions = ref([
    { value: 0, text: 'No' },
    { value: 1, text: 'Yes' },
]);

watch((): iStaff => props.member, (value: iStaff) => {
    form.id = value.id
    form.idno = value.idno
    form.pfno = value.pfno
    form.manno = value.manno
    form.surname = value.surname
    form.first_name = value.first_name
    form.middle_name = value.middle_name
    form.box_no = value.box_no
    form.post_code = value.post_code
    form.town = value.town
    form.email = value.email
    form.phone = value.phone
    form.employer = value.employer?.id
    form.gender = value.gender
    form.plwd = value.plwd
    form.role = value.role?.id
    form.status = value.status?.id
    form.teach = value.teach
    form.job_group = value.job_group?.id
    form.designation = value.designation?.id

})

const submit = async () => {

    if (props.edit) {
        form.patch(route('staff-members-update', form.id), {
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
        form.post(route('staff-members-store'), {
            only: ['members', 'notification', 'errors'],
            onSuccess: () => {
                form.reset()

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
const dialogTitle = computed(() => props.edit ? 'Edit Staff Member' : 'New Staff Member')

watch(() => props.show, (value) => {
    visible.value = value
})

</script>
<template>
    <Toast position="top-center" />
    <Modal :show="visible">
        <template #header>
            <div v-text="dialogTitle"></div>
            <button @click="close(false)">
                <Icon class="h-5 w-5" type="close" />
            </button>
        </template>
        <div class="">
            <form @submit.prevent="submit">
                <div class="card-body">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 py-6">
                        <div class="relative z-0">
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
                            <InputNumber class="w-full" :useGrouping="false" id="idno" v-model="form.idno" />
                            <span class="text-red-400" v-if="page.props.errors.idno"
                                v-text="page.props.errors.idno"></span>
                        </div>
                        <div class="relative z-0" :class="{ 'has-error': page.props.errors.pfno }">
                            <label :class="{ 'text-red-400': page.props.errors.pfno }" for="pfno">PF No.</label>
                            <InputNumber class="w-full" :useGrouping="false" id="pfno" v-model="form.pfno" />
                            <span class="text-red-400" v-if="page.props.errors.pfno"
                                v-text="page.props.errors.pfno"></span>
                        </div>
                        <div class="relative z-0" :class="{ 'has-error': page.props.errors.manno }">
                            <label :class="{ 'text-red-400': page.props.errors.manno }" for="manno">Man No.</label>
                            <InputNumber class="w-full" :useGrouping="false" id="manno" v-model="form.manno" />
                            <span class="text-red-400" v-if="page.props.errors.manno"
                                v-text="page.props.errors.manno"></span>
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
                        <div class="relative z-0 is-filled is-focused" :class="{ 'has-error': page.props.errors.role }">
                            <label :class="{ 'text-red-400': page.props.errors.role }" for="role">Staff
                                Role</label>
                            <Dropdown id="role" option-label="name" option-value="id" :options="roles"
                                v-model="form.role" />
                            <span class="text-red-400" v-if="page.props.errors.role"
                                v-text="page.props.errors.role"></span>
                        </div>
                        <div class="relative z-0 is-filled is-focused"
                            :class="{ 'has-error': page.props.errors.status }">
                            <label :class="{ 'text-red-400': page.props.errors.status }" for="status">Staff
                                Status</label>
                            <Dropdown id="status" option-label="name" option-value="id" :options="statuses"
                                v-model="form.status" />
                            <span class="text-red-400" v-if="page.props.errors.status"
                                v-text="page.props.errors.status"></span>
                        </div>
                        <div class="relative z-0 is-filled is-focused"
                            :class="{ 'has-error': page.props.errors.employer }">
                            <label :class="{ 'text-red-400': page.props.errors.employer }"
                                for="employer">Employer</label>
                            <Dropdown id="employer" :options="employers" v-model="form.employer" option-value="id"
                                option-label="name" />
                            <span class="text-red-400" v-if="page.props.errors.employer"
                                v-text="page.props.errors.active"></span>
                        </div>
                        <div class="relative z-0 is-filled is-focused"
                            :class="{ 'has-error': page.props.errors.teach }">
                            <label :class="{ 'text-red-400': page.props.errors.teach }" for="teach">Teaches</label>
                            <Dropdown id="teach" :options="[{ value: 1, text: 'Yes' }, { value: 0, text: 'No' }]"
                                v-model="form.teach" option-value="value" option-label="text" />
                            <span class="text-red-400" v-if="page.props.errors.teach"
                                v-text="page.props.errors.active"></span>
                        </div>
                        <div class="relative z-0 is-filled is-focused"
                            :class="{ 'has-error': page.props.errors.job_group }">
                            <label :class="{ 'text-red-400': page.props.errors.job_group }" for="job_group">Job
                                group</label>
                            <Dropdown id="job_group" :options="job_groups" v-model="form.job_group" option-value="id"
                                option-label="name" />
                            <span class="text-red-400" v-if="page.props.errors.job_group"
                                v-text="page.props.errors.active"></span>
                        </div>
                        <div class="relative z-0 is-filled is-focused"
                            :class="{ 'has-error': page.props.errors.designation }">
                            <label :class="{ 'text-red-400': page.props.errors.designation }"
                                for="designation">Designation</label>
                            <Dropdown id="designation" :options="designations" v-model="form.designation"
                                option-value="id" option-label="name" />
                            <span class="text-red-400" v-if="page.props.errors.designation"
                                v-text="page.props.errors.active"></span>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <template #footer>
            <PrimaryButton :class="{ 'opacity-30': form.processing }" :disabled="form.processing" @click="submit">Save
            </PrimaryButton>
            <SecondaryButton type="reset" @click="close(false)">Cancel</SecondaryButton>
        </template>
    </Modal>
</template>
