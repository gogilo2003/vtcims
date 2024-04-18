<script lang="ts" setup>
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout.vue'
import Toast from 'primevue/toast'
import { useToast } from 'primevue/usetoast';
import { watch, ref } from "vue";
import { router, useForm } from '@inertiajs/vue3';
import { debounce } from 'lodash';
import { iBogPosition, iBogPositions, iNotification } from '../../interfaces/index';
import SecondaryButton from "../../Components/SecondaryButton.vue";
import PrimaryButton from "../../Components/PrimaryButton.vue";
import InputLabel from "../../Components/InputLabel.vue";
import InputError from "../../Components/InputError.vue";
import Icon from '../../Components/Icons/Icon.vue'
import InputText from 'primevue/inputtext';
import ListItem from '../../Components/ListItem.vue'
import Paginator from '../../Components/Paginator.vue'

const props = defineProps<{
    search: string
    positions: iBogPositions,
    notification: iNotification
}>()

const form = useForm<iBogPosition>({
    id: null,
    name: ""
})
const toast = useToast()

const edit = ref(false)
const detailTitle = ref('New BOG Position')

const newBogPosition = () => {
    form.id = null
    form.name = ""
    detailTitle.value = "New BOG Position"
    edit.value = false
}

const editBogPosition = (BOG_POSITION: iBogPosition) => {
    form.id = BOG_POSITION.id
    form.name = BOG_POSITION.name
    detailTitle.value = "Edit BOG Position"
    edit.value = true
}

const cancelEdit = () => {
    form.id = null
    form.name = ""
    detailTitle.value = "New BOG Position"
    edit.value = false
}

const searchVal = ref(props.search)

watch(() => searchVal.value, debounce((value: string) => {

    let data = {}

    if (value) {
        data = { search: value }
    }

    router.get(route('bog-positions'), data, {
        only: ['positions', 'search'],
        preserveScroll: true,
        preserveState: true
    })
}, 500))

const submit = () => {
    if (edit.value) {
        form.patch(route('bog-positions-update', form.id), {
            preserveScroll: true,
            preserveState: true,
            only: ['positions', 'search', 'notification', 'errors'],
            onSuccess: () => {
                toast.add({
                    severity: 'success',
                    summary: 'Success',
                    detail: props?.notification?.success
                })
            }
        })
    } else {
        form.post(route('bog-positions-store'), {
            preserveScroll: true,
            preserveState: true,
            only: ['positions', 'search', 'notification', 'errors'],
            onSuccess: () => {
                toast.add({
                    severity: 'success',
                    summary: 'Success',
                    detail: props?.notification?.success,
                    life: 3000
                })
                cancelEdit()
            }
        })
    }
}

const deletePosition = (id: number) => {
    router.delete(route('bog-positions-destroy', id), {
        preserveScroll: true,
        preserveState: true,
        only: ['positions', 'notification'],
        onSuccess: () => {
            toast.add({
                severity: 'success',
                summary: 'Success',
                detail: props.notification.success,
                life: 3000
            })
        }
    })
}
</script>
<template>
    <Toast position="top-center" />
    <AuthenticatedLayout title="BOG Positions">
        <div class="pb-3 md:pb-8 flex gap-3 justify-between">
            <div>
                <span class="relative">
                    <i class="pi pi-search absolute -top-[40%] translate-y-[50%] left-2 opacity-50" />
                    <InputText v-model="searchVal" placeholder="Search" class="px-8 w-full"
                        :pt="{ root: { class: 'rounded-full focus:ring-primary-500 text-surface-600 dark:text-surface-200 bg-surface-0 dark:bg-surface-700' } }" />
                </span>
            </div>
            <SecondaryButton @click="newBogPosition">
                <Icon type="add" />
                <span class="hidden md:inline-flex">New Bog Position</span>
            </SecondaryButton>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="flex flex-col gap-2 md:col-span-2">
                <ListItem v-for="position in positions.data">
                    <div>
                        <div class="text-base font-medium capitalize" v-text="position.name"></div>
                        <div class="text-xs font-light flex gap-1">
                            <span>Members</span>
                            <span v-text="position.members"></span>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-1">
                        <SecondaryButton @click="editBogPosition(position)">
                            <Icon class="h-4 w-4" type="edit" /><span class="hidden md:block">edit</span>
                        </SecondaryButton>
                        <SecondaryButton @click="deletePosition(position.id)">
                            <Icon class="h-4 w-4" type="delete" /><span class="hidden md:block">Delete</span>
                        </SecondaryButton>
                    </div>
                </ListItem>
                <Paginator :items="positions" />
            </div>
            <div class="p-3 bg-white dark:bg-gray-700 shadow rounded-lg border border-white dark:border-gray-600">
                <div class="text-lg font-light capitalize border-b pb-2 mb-2" v-text="detailTitle"></div>
                <div>
                    <form @submit.prevent="submit">
                        <div class="mb-6">
                            <InputLabel value="Position Name" />
                            <InputText v-model="form.name" />
                            <InputError :message="form.errors.name" />
                        </div>
                        <div>
                            <PrimaryButton :class="{ 'opacity-30': form.processing }" :disabled="form.processing">Save
                            </PrimaryButton>
                            <SecondaryButton type="button" @click="cancelEdit" v-if="edit">Cancel</SecondaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
