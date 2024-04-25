<script lang="ts" setup>
import Modal from '../../Components/Modal.vue';
import { computed } from 'vue';
import { iExamination } from '../../interfaces/index';
import Icon from '@/Components/Icons/Icon.vue';

const emit = defineEmits(['closed'])
const props = defineProps<{
    examination: iExamination | null
    show: boolean | false
}>()

const edit = computed(() => props.examination?.id ? true : false)
const title = computed(() => props.examination?.id ? "Edit Examination" : "New Examination")

const close = () => {
    emit('closed', false)
}

</script>
<template>
    <Modal :show="show">
        <template #header>
            <div v-text="title"></div>
            <button @click="close">
                <Icon class="h-5 w-5" type="close" />
            </button>
        </template>
        <pre v-text="examination"></pre>
    </Modal>
</template>
