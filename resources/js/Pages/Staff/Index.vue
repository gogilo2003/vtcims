<script setup lang="ts">
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout.vue';
import { iStaffMember, iStaffMembers, iItem, iPhoto } from '../../interfaces/index';
import SecondaryButton from '../../Components/SecondaryButton.vue';
import Icon from '../../Components/Icons/Icon.vue'
import { ref, watch } from 'vue';
import StaffMember from './StaffMember.vue';
import View from './View.vue';
import Picture from './Picture.vue';
import { router } from '@inertiajs/vue3';
import Paginator from '../../Components/Paginator.vue';
import { debounce } from 'lodash'
import InputText from 'primevue/inputtext';
import ListItem from '../../Components/ListItem.vue';

const props = defineProps<{
    members: iStaffMembers,
    positions: Array<iItem>,
    errors: Object,
    search?: string | null,
    notification: {
        success?: string | null
        danger?: string | null
    }
}>()

const edit = ref(false)
const show = ref(false)
const bogMember = ref<iStaffMember | null>()
const photo = ref<iPhoto | undefined>()

const newStaffMember = () => {
    bogMember.value = {
        id: null,
        photo: null,
        photo_url: "",
        idno: null,
        gender: null,
        plwd: false,
        surname: "",
        first_name: "",
        middle_name: "",
        phone: "",
        email: "",
        box_no: "",
        post_code: "",
        town: "",
        position: null,
        active: false,
        term_start_at: null,
        term_end_at: null,
        term_count: 0,
    }
    show.value = true
    edit.value = false
}

const editStaffMember = (STAFF_MEMBER: iStaffMember) => {
    bogMember.value = STAFF_MEMBER
    show.value = true
    edit.value = true
}

const viewStaffMember = (STAFF_MEMBER: iStaffMember) => {
    bogMember.value = STAFF_MEMBER
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

watch(() => searchVal.value, debounce((value: string) => {

    let data = {}

    if (value) {
        data = { search: value }
    }

    router.get(route('staff-members'), data, {
        only: ['members', 'search'],
        preserveScroll: true,
        preserveState: true
    })
}, 500))

const uploadPic = (member: iStaffMember) => {
    photo.value = {
        id: member.id,
        url: member.photo_url,
        photo: member.photo
    }

    showPic.value = true

}

</script>

<template>
    <StaffMember :show="show" :edit="edit" @closed="onClose" :member="bogMember" />
    <Picture :show="showPic" @closed="onClosePic" :photo="photo" />
    <View :show="showView" @closed="onCloseView" :member="bogMember" />

    <AuthenticatedLayout title="Bog Members">
        <div class="pb-3 md:pb-8 flex gap-3 justify-between">
            <SecondaryButton @click="newStaffMember">
                <Icon type="add" />
                <span class="hidden md:inline-flex">New Bog Member</span>
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
            <ListItem v-for="(member) in   members.data">
                <div class="flex items-center gap-2">
                    <div class="flex-none h-20 w-20">
                        <img class="h-full w-full object-cover rounded-full shadow border p-1" :src="member.photo_url"
                            alt="">
                    </div>
                    <div>
                        <div class="text-sm font-semibold uppercase"
                            v-text="`${member.first_name}${member.middle_name ? member.middle_name : ''} ${member.surname}`">
                        </div>
                        <div
                            class="flex flex-col md:flex-row md:items-center text-xs text-gray-600 dark:text-gray-400 divide-x">
                            <div v-if="member.role" class="flex gap-2 md:pr-2">
                                <span class="md:hidden font-semibold uppercase">Position:</span>
                                <span v-text="member.role.name" class="text-lime-700 dark:text-lime-500"></span>
                                <span v-text="member.role.teach ? '(Teach)' : ''"
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
                            <div v-if="member.employer" class="flex gap-2">
                                <span class="md:hidden font-semibold uppercase">Employer:</span>
                                <span v-if="member.employer" class="md:px-2" v-text="member.employer"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex flex-wrap gap-1 justify-end">
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
