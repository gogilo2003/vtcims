<script lang="ts" setup>
import { computed, onMounted, ref, watch } from "vue";
import { useForm, usePage } from '@inertiajs/vue3';
import FileUpload from "primevue/fileupload";
import { useToast } from 'primevue/usetoast';
import Toast from "primevue/toast";
import { iPhoto } from "@/interfaces/index";
import Icon from "@/Components/Icons/Icon.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import Modal from "@/Components/Modal.vue";

const props = defineProps<{
    show: boolean
    photo: iPhoto | null
}>()
const emit = defineEmits(['closed', 'saved'])

const form = useForm<iPhoto>({
    id: null,
    photo: null,
})

const page = usePage()
const toast = useToast()

watch(() => props.photo, value => {
    form.id = value.id
    form.photo = value.photo
})

const submit = () => {

    form.post(route('students-photo', form.id), {
        onSuccess: () => {
            toast.add({
                severity: 'success',
                summary: 'Success',
                detail: page?.props?.notification?.success,
                life: 8000
            })
            close(false)
            resetErrors()
        },
        onError: () => {
            toast.add({
                severity: 'error',
                summary: 'Error',
                detail: "An error ocurred! Please check the values you provided and try again.",
                life: 8000
            })

        },
        only: ['errors', 'notification', 'students']
    })

}

const input = (event: any) => {
    form.photo = event.files[0]
}

const resetErrors = () => {
    form.clearErrors()
}

const close = (value: boolean) => {
    form.photo = null
    emit('closed', value)
}

const visible = ref(false)
const dialogTitle = computed(() => props.edit ? 'Edit Student Photo' : 'New Student Photo')

watch(() => props.show, (value) => {
    visible.value = value
})

watch(() => props.photo?.id, (value) => {
    form.id = value
})

</script>
<template>
    <Toast position="top-center" />
    <Modal :show="visible" max-width="sm">
        <template #header>
            <h4>Student Photo</h4>
            <button @click="close(false)">
                <Icon class="h-5 w-5" type="close" />
            </button>
        </template>
        <div class="">
            <form @submit.prevent="submit">
                <div class="mb-6">

                    <div class="relative">
                        <FileUpload :file-limit="1" :show-upload-button="false" :show-cancel-button="false"
                            :custom-upload="true" @upload.prevent="submit" :multiple="false" accept="image/*"
                            :maxFileSize="1000000" :pt="{
        unstyled: true,
        root: { class: 'relative z-0 flex items-center flex-col gap-3 justify-center' },
        content: { class: 'relative mt-4 z-0 p-3 pt-6 h-full w-full flex-1 border border-primary-300 rounded-lg min-h-24' },
        input: { class: 'hidden' },
        file: { class: 'flex flex-col items-center' },
        chooseButtonLabel: { class: 'hidden' },
        buttonbar: { class: 'bg-orange-500 absolute z-10 left-4 top-0 border border-primary-500 bg-white rounded-full flex gap-2 items-center' },
        chooseButton: { class: 'h-9 w-9 p-1 flex-none flex flex-col gap-2 justify-center items-center cursor-pointer' },
        thumbnail: { class: 'w-full max-h-56 object-contain' }
    }" @select="input($event)">
                            <template #chooseicon>
                                <Icon class=" text-lg" type="pi-paperclip" />
                            </template>
                            <template #empty>
                                <p>Drag and drop files to here to upload.</p>
                            </template>
                        </FileUpload>

                        <span v-if="page.props.errors.photo" v-text="page.props.errors.photo"
                            class="text-red-400"></span>
                    </div>
                </div>
            </form>
        </div>
        <template #footer>
            <PrimaryButton @click="submit" :loading="form.processing" :disabled="form.processing">Upload Photo
            </PrimaryButton>
            <SecondaryButton @click="close(false)">Cancel</SecondaryButton>
        </template>
    </Modal>
</template>
