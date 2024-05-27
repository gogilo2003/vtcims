<template>
    <Modal :show="show">
        <template #header>
            <div v-text="title"></div>
            <button @click="cancel">
                <Icon class="h-5 w-5" type="close" />
            </button>
        </template>
        <form @submit.prevent="submit">
            <div class="mb-4">
                <InputLabel value="Course" />
                <Dropdown filter :options="courses" optionValue="id" optionLabel="name" v-model="form.course" />
                <InputError :message="form.errors.course" />
            </div>
            <div class="mb-4">
                <InputLabel value="Term" />
                <Dropdown filter :options="terms" optionValue="id" optionLabel="name" v-model="form.term" />
                <InputError :message="form.errors.term" />
            </div>
            <div class="mb-4">
                <InputLabel value="Amount" />
                <InputNumber :useGrouping="false" v-model="form.amount" />
                <InputError :message="form.errors.amount" />
            </div>
            <div class="my-6 shadow bg-gray-50 border rounded-lg p-4">
                <table class="w-full">
                    <thead>
                        <tr class="text-left">
                            <th class="w-8">#</th>
                            <th class="">Title</th>
                            <th class="w-24">Share</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(vote_head, index) in form.vote_heads">
                            <td class="p-2" v-text="index + 1"></td>
                            <td class="p-2">
                                <InputText v-model="vote_head.title" />
                            </td>
                            <td class="p-2">
                                <InputNumber suffix="%" :useGrouping="false" v-model="vote_head.share" />
                            </td>
                            <td class="p-2">
                                <button @click="removeVoteHead(index)">
                                    <Icon class="h-4 w-4" type="delete" />
                                </button>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" class="text-right">
                                <button type="button" @click="addVoteHead">
                                    <Icon class="h-5 w-5" type="add" />
                                </button>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="flex justify-between">
                <PrimaryButton :class="{ 'opacity-30': form.processing }" :disabled="form.processing">Save
                </PrimaryButton>
                <SecondaryButton type="button" @click="cancel">Cancel</SecondaryButton>
            </div>
        </form>
    </Modal>
</template>
<script lang="ts" setup>
import Modal from '../../../Components/Modal.vue';
import Icon from '../../../Components/Icons/Icon.vue';
import { iFee, iVoteHead } from '../../../interfaces/index';
import { useToast } from 'primevue/usetoast';
import { useForm, usePage } from '@inertiajs/vue3';
import { computed, watchEffect } from 'vue';
import InputLabel from '../../../Components/InputLabel.vue';
import Dropdown from 'primevue/dropdown';
import InputError from '@/Components/InputError.vue';
import InputNumber from 'primevue/inputnumber';
import InputText from 'primevue/inputtext';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '../../../Components/SecondaryButton.vue';

const props = defineProps<{
    show: boolean
    fee: iFee | null
}>()

const emit = defineEmits(['closed'])

const terms = computed(() => usePage().props.terms)
const courses = computed(() => usePage().props.courses)

const toast = useToast()

const form = useForm<{
    id: number | null
    term: number | null
    course: number | null
    amount: number | null
    vote_heads: iVoteHead[]
}>({
    id: null,
    term: null,
    course: null,
    amount: null,
    vote_heads: []
})

watchEffect(() => {
    if (props.fee) {
        form.term = props.fee.term?.id
        form.course = props.fee.course?.id
        form.amount = props.fee.amount
        form.vote_heads = props.fee.vote_heads ?? []
    }
})

const removeVoteHead = (index: number) => {
    form.vote_heads.splice(index, 1)
}

const addVoteHead = () => {
    console.log('Add Vote Head');
    let vh: iVoteHead = {
        id: null,
        title: "",
        share: 0
    }
    form.vote_heads.push(vh)
}

const submit = () => {
    if (form.id) {
        form.patch(route('accounts-fees-update', form.id), {
            only: ['fees', 'errors', 'notification'],
            onSuccess: () => {
                toast.add({
                    severity: 'success',
                    summary: 'Success',
                    detail: usePage().props?.notification?.success,
                    life: 4000
                })
            },
            onError: () => {
                toast.add({
                    severity: 'error',
                    summary: 'Error',
                    detail: usePage().props?.notification?.danger ?? 'An error occurred! Please check you fields and try again',
                    life: 4000
                })
            }
        })
    } else {
        form.post(route('accounts-fees-store'), {
            only: ['fees', 'errors', 'notification'],
            onSuccess: () => {
                toast.add({
                    severity: 'success',
                    summary: 'Success',
                    detail: usePage().props?.notification?.success,
                    life: 4000
                })
            },
            onError: () => {
                toast.add({
                    severity: 'error',
                    summary: 'Error',
                    detail: usePage().props?.notification?.danger ?? 'An error occurred! Please check you fields and try again',
                    life: 4000
                })
            }
        })
    }
}

const title = computed<string>(() => {
    if (form.id) {
        return 'Edit fee'
    } else {
        return 'New Fee'
    }
})

const cancel = () => {
    form.reset()
    form.clearErrors()

    form.id = null
    form.term = null
    form.course = null
    form.amount = null
    form.vote_heads = []
    emit('closed')
}

</script>
