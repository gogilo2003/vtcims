<script setup lang="ts">
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout.vue';
import { iBogMember, iBogMembers, iItem, iPhoto } from '../../interfaces/index';
import SecondaryButton from '../../Components/SecondaryButton.vue';
import Icon from '../../Components/Icons/Icon.vue'
import { ref, watch } from 'vue';
import BogMember from './BogMember.vue';
import View from './View.vue';
import Picture from './Picture.vue';
import { router } from '@inertiajs/vue3';
import Paginator from '../../Components/Paginator.vue';
import { debounce } from 'lodash'
import InputText from 'primevue/inputtext';
import ListItem from '../../Components/ListItem.vue';

const props = defineProps<{
    members: iBogMembers,
    positions: Array<iItem>,
    errors: Object,
    search: String
}>()

const edit = ref(false)
const show = ref(false)
const bogMember = ref<iBogMember>()
const photo = ref<iPhoto>()

const newBogMember = () => {
    bogMember.value = {
        id: null,
        photo: null,
        photo_url: null,
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

const editBogMember = (BOG_MEMBER: iBogMember) => {
    bogMember.value = BOG_MEMBER
    show.value = true
    edit.value = true
}

const viewBogMember = (BOG_MEMBER: iBogMember) => {
    bogMember.value = BOG_MEMBER
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

const searchVal = ref(props.search)

watch(() => searchVal.value, debounce((value: string) => {

    let data = {}

    if (value) {
        data = { search: value }
    }

    router.get(route('members'), data, {
        only: ['members', 'search'],
        preserveScroll: true,
        preserveState: true
    })
}, 500))

const uploadPic = (member: iBogMember) => {
    photo.value = {
        id: bogMember.id,
        url: bogMember.photo_url,
        photo: bogMember.photo
    }

    showPic.value = true

}

</script>

<template>
    <BogMember :show="show" :edit="edit" @closed="onClose" :member="member" />
    <Picture :show="showPic" @closed="onClosePic" :photo="photo" />
    <View :show="showView" @closed="onCloseView" :member="member" />

    <AuthenticatedLayout title="Bog Members">
        <div class="pb-3 md:pb-8 flex gap-3 justify-between">
            <SecondaryButton @click="newBogMember">
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
            <ListItem v-for="member in members.data">
                <div>
                    <div class="text-sm font-semibold uppercase"
                        v-text="`${bogMember.first_name} ${bogMember.middle_name} ${bogMember.surname}`"></div>
                    <div
                        class="flex flex-col md:flex-row md:items-center text-xs capitalize text-gray-600 dark:text-gray-400 divide-x">
                        <div class="flex gap-2 md:pr-2">
                            <span class="md:hidden font-semibold uppercase">ID Number:</span>
                            <span v-text="bogMember.idno" class="text-lime-700 dark:text-lime-500"></span>
                        </div>
                        <span class="md:px-2" v-text="bogMember.phone"></span>
                        <span class="md:px-2" v-text="bogMember.email"></span>
                        <span class="md:px-2"
                            v-text="`P.O. Box ${bogMember.box_no}${bogMember.post_code ? ' - ' + bogMember.post_code : ''}${bogMember.town ? ', ' + bogMember.town : ''}`"></span>
                    </div>
                </div>
                <div class="flex flex-wrap gap-1">
                    <SecondaryButton @click="editBogMember(member)">
                        <Icon class="h-4 w-4" type="edit" /><span class="hidden md:block">edit</span>
                    </SecondaryButton>
                    <SecondaryButton @click="uploadPic(member)">
                        <Icon class="h-4 w-4" type="picture" /><span class="hidden md:block">Picture</span>
                    </SecondaryButton>
                    <SecondaryButton @click="viewBogMember(member)">
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
