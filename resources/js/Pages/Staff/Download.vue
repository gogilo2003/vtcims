<script lang="ts" setup>
import Modal from '../../Components/Modal.vue'
import SecondaryButton from '../../Components/SecondaryButton.vue';
import PrimaryButton from '../../Components/PrimaryButton.vue';
import { usePage } from '@inertiajs/vue3';
import { ref } from 'vue'
import InputLabel from '../../Components/InputLabel.vue';
import Dropdown from 'primevue/dropdown';
import InputText from 'primevue/inputtext';

const props = defineProps({
    show: {
        type: Boolean,
        default: false
    }
})

const emit = defineEmits(['closed'])

const { roles, statuses, employers } = usePage().props

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
        query = { ...query, tt: customTitle.value }
    }
    if (subTitle.value) {
        query = { ...query, st: subTitle.value }
    }
    if (status.value) {
        query = { ...query, su: status.value }
    }
    if (role.value) {
        query = { ...query, r: role.value }
    }
    if (teach.value) {
        query = { ...query, t: teach.value }
    }
    if (employer.value) {
        query = { ...query, e: employer.value }
    }
    if (gender.value) {
        query = { ...query, g: gender.value }
    }
    if (plwd.value) {
        query = { ...query, p: plwd.value }
    }
    window.open(route('staff-members-download', query), '_BLANK')
}

const close = () => {
    customTitle.value = 'Staff List'
    subTitle.value = ''
    status.value = null
    role.value = null
    teach.value = null
    employer.value = null
    gender.value = null
    plwd.value = null
    emit('closed', false)
}
</script>
<template>
    <Modal :show="show" maxWidth="lg">
        <template #header>
            <div class="text-white">Download</div>
            <button class="text-white h-4 w-4" @click="close(true)">
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
            <SecondaryButton @click="close">Close</SecondaryButton>
        </div>
    </Modal>
</template>
