<script setup lang="ts">
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout.vue';
import { iStaff, iStaffMembers, iItem, iPhoto } from '../../interfaces/index';
import SecondaryButton from '../../Components/SecondaryButton.vue';
import PrimaryButton from '../../Components/PrimaryButton.vue';
import Icon from '../../Components/Icons/Icon.vue'
import { ref, watch, onMounted } from 'vue';
import StaffMember from './StaffMember.vue';
import View from './View.vue';
import Picture from './Picture.vue';
import { router } from '@inertiajs/vue3';
import Paginator from '../../Components/Paginator.vue';
import { debounce } from 'lodash'
import InputText from 'primevue/inputtext';
import ListItem from '../../Components/ListItem.vue';
import InputLabel from '../../Components/InputLabel.vue';
import Modal from '../../Components/Modal.vue';
import Dropdown from 'primevue/dropdown';
import BasePreset from '../../../assets/primevue-lara-theme/dropdown'
import { usePassThrough } from 'primevue/passthrough';


const customPreset = usePassThrough(
    BasePreset,
    {
        root: {
            class: ['leading-none font-light text-2xl']
        }
    },
    {
        mergeSections: true,
        mergeProps: false
    }
);

const props = defineProps<{
    members: iStaffMembers,
    roles: Array<iItem>,
    statuses: Array<iItem>,
    employers: Array<iItem>,
    errors: Object,
    search?: string | null,
    notification: {
        success?: string | null
        danger?: string | null
    }
}>()

const edit = ref(false)
const show = ref(false)
const staffMember = ref<iStaff | null>()
const photo = ref<iPhoto | undefined>()

const newStaffMember = () => {
    staffMember.value = {
        id: null,
        idno: null,
        pfno: null,
        manno: null,
        photo: "",
        photo_url: "",
        surname: "",
        first_name: "",
        middle_name: "",
        box_no: "",
        post_code: "",
        town: "",
        email: "",
        phone: "",
        employer: "",
        gender: null,
        plwd: false,
        role: null,
        status: null,
        teach: null,
        user: null,
    }
    show.value = true
    edit.value = false
}

const editStaffMember = (STAFF_MEMBER: iStaff) => {
    staffMember.value = STAFF_MEMBER
    show.value = true
    edit.value = true
}

const viewStaffMember = (STAFF_MEMBER: iStaff) => {
    staffMember.value = STAFF_MEMBER
    showView.value = true
}

const onClose = (value: boolean) => {
    show.value = value
    edit.value = false
}

const onClosePic = (value: boolean) => {
    showPic.value = value
}

const onCloseView = (value: boolean) => {
    showView.value = value
}

const showPic = ref(false)
const showView = ref(false)

const searchVal = ref<string | null | undefined>(props.search)

watch(() => searchVal.value, debounce((value: string) => search(value), 500))

const uploadPic = (member: iStaff) => {
    photo.value = {
        id: member.id,
        url: member.photo_url,
        photo: member.photo
    }

    showPic.value = true

}

const search = (value?: string) => {

    let data = {}

    if (value) {
        data = { search: value }
    }

    router.get(route('staff-members'), data, {
        only: ['members', 'search'],
        preserveScroll: true,
        preserveState: true
    })
}

const showDownload = ref<boolean>(false)
const customTitle = ref<string>('Staff List')
const subTitle = ref<string>('')
const status = ref<number | null>(null)
const role = ref<number | null>(null)
const teach = ref<number | null>(null)
const employer = ref<number | null>(null)
const gender = ref<string | null>(null)
const plwd = ref<number | null>(null)

const download = (id?: number) => {
    let query = {}
    if (id) {
        query = { id, ...query }
    }
    if (customTitle.value) {
        query = { ...query, custom_title: customTitle.value }
    }
    if (subTitle.value) {
        query = { ...query, sub_title: subTitle.value }
    }
    if (status.value) {
        query = { ...query, status: status.value }
    }
    if (role.value) {
        query = { ...query, role: role.value }
    }
    if (teach.value) {
        query = { ...query, teach: teach.value }
    }
    if (employer.value) {
        query = { ...query, employer: employer.value }
    }
    if (gender.value) {
        query = { ...query, gender: gender.value }
    }
    if (plwd.value) {
        query = { ...query, plwd: plwd.value }
    }
    window.open(route('staff-members-download', query), '_BLANK')
}

</script>

<template>
    <StaffMember :show="show" :edit="edit" @closed="onClose" :member="staffMember" />
    <Picture :show="showPic" @closed="onClosePic" :photo="photo" />
    <View :show="showView" @closed="onCloseView" :member="staffMember" />

    <Modal :show="showDownload" maxWidth="lg">
        <template #header>
            <div class="text-white">Download</div>
            <button class="text-white h-4 w-4" @click="showDownload = false">
                <Icon class="w-full h-full object-contain" type="close" />
            </button>
        </template>
        <div class="mb-4">
            <InputLabel value="Custom Title" />
            <InputText v-model="customTitle" class="w-full" />
        </div>
        <div class="mb-4">
            <InputLabel value="Sub Title" />
            <InputText v-model="subTitle" class="w-full" />
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <InputLabel value="Roles" />
                <Dropdown :options="roles" optionValue="id" optionLabel="name" v-model="role" :showClear="true" />
            </div>
            <div>
                <InputLabel value="Status" />
                <Dropdown :options="statuses" optionValue="id" optionLabel="name" v-model="status" :showClear="true" />
            </div>
            <div>
                <InputLabel value="Employer" />
                <Dropdown :options="employers" optionValue="id" optionLabel="name" v-model="employer"
                    :showClear="true" />
            </div>
            <div>
                <InputLabel value="Teach" />
                <Dropdown :options="[{ id: 1, name: 'Yes' }, { id: 0, name: 'No' }]" optionValue="id" optionLabel="name"
                    v-model="teach" :showClear="true" />
            </div>
            <div>
                <InputLabel value="Gender" />
                <Dropdown :options="['Male', 'Female']" v-model="gender" :showClear="true" />
            </div>
            <div>
                <InputLabel value="PLWD" />
                <Dropdown :options="[{ id: 1, name: 'Yes' }, { id: 0, name: 'No' }]" optionValue="id" optionLabel="name"
                    v-model="plwd" :showClear="true" />
            </div>
        </div>
        <div class="flex items-center justify-between pt-4">
            <PrimaryButton @click="download()">Download</PrimaryButton>
            <SecondaryButton @click="showDownload = false">Close</SecondaryButton>
        </div>
    </Modal>

    <AuthenticatedLayout title="Staff Members">
        <div class="pb-3 md:pb-8 flex gap-3 justify-between">
            <div class="flex items-center gap-2">
                <SecondaryButton @click="newStaffMember">
                    <Icon type="add" />
                    <span class="hidden md:inline-flex">New Staff Member</span>
                </SecondaryButton>
                <SecondaryButton @click="showDownload = true">
                    <Icon class="h-6 w-6" type="download" />
                    <span class="hidden md:inline-flex">Download</span>
                </SecondaryButton>
                <div class="w-56">
                    <Dropdown @change="" :options="employers" optionValue="id" optionLabel="name" v-model="employer" />
                </div>
            </div>
            <div>
                <span class="relative">
                    <i class="pi pi-search absolute -top-[40%] translate-y-[50%] left-2 opacity-50" />
                    <InputText v-model="searchVal" placeholder="Search" class="px-8 w-full"
                        :pt="{ root: { class: 'rounded-full focus:ring-primary-500 text-surface-600 dark:text-surface-200 bg-surface-0 dark:bg-surface-700' } }" />
                </span>
            </div>
        </div>
        <div class="flex flex-col gap-2">
            <ListItem v-for="( member ) in    members.data ">
                <div class="flex items-center gap-2">
                    <div class="flex-none h-20 w-20">
                        <img class="h-full w-full object-cover object-top rounded-full shadow border p-1"
                            :src="member.photo_url" alt="">
                    </div>
                    <div>
                        <div class="text-sm font-semibold uppercase"
                            v-text="`${member.first_name}${member.middle_name ? ' ' + member.middle_name : ''} ${member.surname}`">
                        </div>
                        <div
                            class="flex flex-col gap-1 md:gap-0 md:flex-row md:items-center text-xs text-gray-600 dark:text-gray-400 md:divide-x">
                            <div v-if="member.role" class="flex gap-2 md:pr-2">
                                <span class="md:hidden font-semibold uppercase">Position:</span>
                                <span v-text="member.role?.name" class="text-lime-700 dark:text-lime-500"></span>
                                <span v-text="member.teach ? '(Teach)' : ''"
                                    class="text-lime-700 dark:text-lime-500"></span>
                            </div>
                            <div v-if="member.phone" class="flex gap-2">
                                <span class="md:hidden font-semibold uppercase">Phone:</span>
                                <span v-if="member.phone" class="md:px-2" v-text="member.phone"></span>
                            </div>
                            <div v-if="member.email" class="flex gap-2">
                                <span class="md:hidden font-semibold uppercase">Email:</span>
                                <span v-if="member.email" class="md:px-2" v-text="member.email"></span>
                            </div>
                            <div v-if="member.box_no || member.post_code || member.town" class="flex gap-2">
                                <span class="md:hidden font-semibold uppercase">Postal Address:</span>
                                <span v-if="member.box_no || member.post_code || member.town" class="md:px-2"
                                    v-text="`P.O. Box ${member.box_no}${member.post_code ? ' - ' + member.post_code : ''}${member.town ? ', ' + member.town : ''}`"></span>
                            </div>
                            <div v-if="member.gender" class="flex gap-2">
                                <span class="md:hidden font-semibold uppercase">Gender:</span>
                                <span v-if="member.gender" class="md:px-2" v-text="member.gender"></span>
                            </div>
                            <div v-if="member.employer?.name" class="flex gap-2">
                                <span class="md:hidden font-semibold uppercase">Employer:</span>
                                <span v-if="member.employer?.name" class="md:px-2"
                                    v-text="member.employer?.name"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex flex-wrap gap-1 justify-center">
                    <SecondaryButton @click="editStaffMember(member)">
                        <Icon class="h-4 w-4" type="edit" /><span class="hidden md:block">edit</span>
                    </SecondaryButton>
                    <SecondaryButton @click="uploadPic(member)">
                        <Icon class="h-4 w-4" type="picture" /><span class="hidden md:block">Picture</span>
                    </SecondaryButton>
                    <SecondaryButton @click="viewStaffMember(member)">
                        <Icon class="h-4 w-4" type="person-details" /><span class="hidden md:block">Details</span>
                    </SecondaryButton>
                    <SecondaryButton>
                        <Icon class="h-4 w-4" type="delete" /><span class="hidden md:block">Delete</span>
                    </SecondaryButton>
                </div>
            </ListItem>
            <Paginator :items="members" />
        </div>
    </AuthenticatedLayout>
</template>
