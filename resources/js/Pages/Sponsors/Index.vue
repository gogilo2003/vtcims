<script lang="ts" setup>
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout.vue'
import { iNotification, iSponsor, iSponsors } from '../../interfaces/index';
import Paginator from '../../Components/Paginator.vue';
import SecondaryButton from '../../Components/SecondaryButton.vue';
import { ref, watch } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import { debounce } from 'lodash';
import Icon from '../../Components/Icons/Icon.vue';
import InputText from 'primevue/inputtext';
import ListItem from '@/Components/ListItem.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Modal from '@/Components/Modal.vue';
import { useToast } from 'primevue/usetoast';
import Toast from 'primevue/toast'

const props = defineProps<{
    sponsors: iSponsors
    notification: iNotification
    search?: string
}>()

const toast = useToast()

const searchVal = ref(props.search)

watch(() => searchVal.value, debounce((value: string) => {

    let data = {}

    if (value) {
        data = { search: value }
    }

    router.get(route('sponsors'), data, {
        only: ['sponsors', 'search'],
        preserveScroll: true,
        preserveState: true
    })
}, 500))

const form = useForm<iSponsor>({
    id: null,
    name: "",
    contact_person: "",
    email: "",
    phone: "",
    box_no: "",
    post_code: "",
    town: "",
    address: "",
})
const show = ref(false)
const edit = ref(false)
const title = ref("New Sponsor")

const submit = () => {
    if (edit.value) {
        form.patch(route('sponsors-update', form.id), {
            preserveScroll: true,
            preserveState: true,
            only: ['notification', 'errors', 'sponsors'],
            onSuccess: () => {
                toast.add({
                    severity: 'success',
                    summary: 'Success',
                    detail: props.notification.success,
                    life: 4000
                })
                cancel()
            },
            onError: () => {
                toast.add({
                    severity: 'error',
                    summary: 'Error',
                    detail: "An error occurred! Please check the values input and try again",
                    life: 4000
                })
            }

        })
    } else {
        form.post(route('sponsors-store'), {
            preserveScroll: true,
            preserveState: true,
            only: ['notification', 'errors', 'sponsors'],
            onSuccess: () => {
                toast.add({
                    severity: 'success',
                    summary: 'Success',
                    detail: props.notification.success,
                    life: 4000
                })
                cancel()
            },
            onError: () => {
                toast.add({
                    severity: 'error',
                    summary: 'Error',
                    detail: "An error occurred! Please check the values input and try again",
                    life: 4000
                })
            }

        })
    }
}
const cancel = () => {
    form.reset()
    form.clearErrors()
    edit.value = false
    show.value = false
}

const newSponsor = () => {
    form.reset()
    form.clearErrors()
    title.value = "New Sponsor"
    show.value = true
    edit.value = false
}

const editSponsor = (sponsor: iSponsor) => {
    form.reset()
    form.clearErrors()

    form.id = sponsor.id
    form.name = sponsor.name
    form.contact_person = sponsor.contact_person
    form.email = sponsor.email
    form.phone = sponsor.phone
    form.box_no = sponsor.box_no
    form.post_code = sponsor.post_code
    form.town = sponsor.town
    form.address = sponsor.address

    title.value = "Edit Sponsor"
    show.value = true
    edit.value = true
}

</script>
<template>
    <Toast position="top-center" />
    <Modal :show="show">
        <template #header>
            <div v-text=title></div>
            <button @click="cancel">
                <Icon class="h-4 w-4" type="close" />
            </button>
        </template>
        <form @submit.prevent="submit">
            <div class="mb-4">
                <InputLabel value="Name" />
                <InputText v-model="form.name" />
                <InputError :message="form.errors.name" />
            </div>
            <div class="mb-4">
                <InputLabel value="Name of Contact Person" />
                <InputText v-model="form.contact_person" />
                <InputError :message="form.errors.contact_person" />
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="mb-4">
                    <InputLabel value="Email of Contact Person" />
                    <InputText v-model="form.email" />
                    <InputError :message="form.errors.email" />
                </div>
                <div class="mb-4">
                    <InputLabel value="Phone of Contact Person" />
                    <InputText v-model="form.phone" />
                    <InputError :message="form.errors.phone" />
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="mb-4">
                    <InputLabel value="Box Number" />
                    <InputText v-model="form.box_no" />
                    <InputError :message="form.errors.box_no" />
                </div>
                <div class="mb-4">
                    <InputLabel value="Post Code" />
                    <InputText v-model="form.post_code" />
                    <InputError :message="form.errors.post_code" />
                </div>
                <div class="mb-4">
                    <InputLabel value="Town" />
                    <InputText v-model="form.town" />
                    <InputError :message="form.errors.town" />
                </div>
            </div>
            <div class="mb-4">
                <InputLabel value="Location" />
                <InputText v-model="form.address" />
                <InputError :message="form.errors.address" />
            </div>
        </form>
        <template #footer>
            <PrimaryButton :class="{ 'opacity-30': form.processing }" :disabled="form.processing" @click="submit">Save
            </PrimaryButton>
            <SecondaryButton type="button" @click="cancel">Cancel</SecondaryButton>
        </template>
    </Modal>
    <AuthenticatedLayout title="Sponsors">
        <div class="flex items-center justify-between gap-2 mb-3 md:pb-8 ">
            <SecondaryButton @click="newSponsor">
                <Icon type="add" />
                <span class="hidden md:inline-flex">New Sponsor</span>
            </SecondaryButton>
            <div>
                <span class="relative">
                    <span class="pi pi-search absolute top-[50%] -translate-y-[50%] left-2 opacity-50"></span>
                    <InputText v-model="searchVal" placeholder="Search" class="px-8 w-full"
                        :pt="{ root: { class: 'rounded-full focus:ring-primary-500 text-surface-600 dark:text-surface-200 bg-surface-0 dark:bg-surface-700' } }" />
                </span>
            </div>
        </div>
        <div class="md:col-span-2 flex flex-col gap-2">
            <ListItem v-for="sponsor in sponsors.data" class="px-4 py-2 rounded-lg shadow-lg bg-white">
                <div>
                    <div v-text="sponsor.name"
                        class="uppercase text-sm font-semibold text-gray-800 dark:text-primary-500">
                    </div>
                    <div class="flex gap-2 divide-x">
                        <span v-if="sponsor.contact_person" v-text="sponsor.contact_person"
                            class="text-xs text-gray-600 dark:text-gray-300 first:pl-0 pl-2"></span>
                        <span v-if="sponsor.phone" v-text="sponsor.phone"
                            class="text-xs text-gray-600 dark:text-gray-300 first:pl-0 pl-2"></span>
                        <span v-if="sponsor.email" v-text="sponsor.email"
                            class="text-xs text-gray-600 dark:text-gray-300 first:pl-0 pl-2"></span>
                    </div>
                </div>
                <div class="flex gap-2">
                    <SecondaryButton @click="editSponsor(sponsor)">
                        <Icon type="edit" class="h-4 w-4" />
                        Edit
                    </SecondaryButton>
                </div>
            </ListItem>
            <Paginator :items="sponsors" />
        </div>
    </AuthenticatedLayout>
</template>
