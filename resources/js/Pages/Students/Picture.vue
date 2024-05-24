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
            <div class="flex items-center mb-4 p-0 gap-0 border-b border-primary-500">
                <span
                    class="m-0 py-2 px-3 border-b-0 cursor-pointer border border-primary-500 rounded-t-xl bg-gradient-to-b"
                    @click="currentTab = 'upload'"
                    :class="{ 'from-primary-500 to-primary-600 text-white': currentTab === 'upload' }">
                    <span class="pi pi-paperclip"></span>
                    Upload
                </span>
                <span
                    class="m-0 py-2 px-3 border-b-0 cursor-pointer border border-primary-500 rounded-t-xl bg-gradient-to-b"
                    @click="currentTab = 'camera'"
                    :class="{ 'from-primary-500 to-primary-600 text-white': currentTab === 'camera' }">
                    <span class="pi pi-camera"></span>
                    Camera
                </span>
            </div>

            <div v-if="currentTab === 'upload'">
                <form @submit.prevent="submit">
                    <div class="mb-6">
                        <div class="relative">
                            <FileUpload :file-limit="1" :show-upload-button="false" :show-cancel-button="false"
                                :custom-upload="true" @upload.prevent="submit" :multiple="false" accept="image/*"
                                :maxFileSize="5 * 1024 * 1024" :pt="ptOptions" @select="input($event)">
                                <template #chooseicon>
                                    <Icon class="text-primary-500 text-lg" type="pi-paperclip" />
                                </template>
                                <template #empty>
                                    <p>Drag and drop files to here to upload.</p>
                                </template>
                            </FileUpload>

                            <span v-if="page.props.errors.photo" v-text="page.props.errors.photo"
                                class="text-red-400"></span>
                        </div>
                        <div v-if="optimizedImageSize">
                            <p>Optimized Image Size: {{ optimizedImageSize }} KB</p>
                        </div>
                    </div>
                </form>
            </div>

            <div v-if="currentTab === 'camera'">
                <video ref="video" autoplay></video>
                <div class="flex items-center justify-center py-3">
                    <span @click="capturePhoto"
                        class="rounded-full border border-primary-500 p-2 h-16 w-16 flex items-center justify-center bg-white text-primary-500 dark:bg-gray-800 cursor-pointer">
                        <span class="text-3xl pi pi-camera"></span>
                    </span>
                </div>
                <img v-if="capturedImage" :src="capturedImage" alt="Captured Image" />
                <div v-if="capturedImage && optimizedImageSize">
                    <p>Optimized Image Size: {{ optimizedImageSize }} KB</p>
                </div>
            </div>
        </div>

        <div class="w-full h-1 rounded-xl bg-gray-800">
            <div class="bg-gray-50 h-1" :style="`width: ${form.progress?.toString().concat('%')}`"></div>
        </div>

        <template #footer>
            <PrimaryButton @click="submit" :class="{ 'opacity-30': form.processing }" :loading="form.processing"
                :disabled="form.processing">Upload Photo
            </PrimaryButton>
            <SecondaryButton @click="close(false)">Cancel</SecondaryButton>
        </template>
    </Modal>
</template>

<script lang="ts" setup>
import { computed, onMounted, ref, watch, onUnmounted } from "vue";
import { useForm, usePage } from '@inertiajs/vue3';
import FileUpload from "primevue/fileupload";
import { useToast } from 'primevue/usetoast';
import Toast from "primevue/toast";
import { iPhoto } from "@/interfaces/index";
import Icon from "@/Components/Icons/Icon.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import Modal from "@/Components/Modal.vue";
import pica from 'pica';

const props = defineProps<{
    show: boolean
    photo: iPhoto | null
}>()
const emit = defineEmits(['closed', 'saved'])

const form = useForm<{
    id: number | null,
    photo: File | null
}>({
    id: null,
    photo: null,
})

const page = usePage()
const toast = useToast()

const ptOptions = ref({
    unstyled: true,
    root: { class: 'relative z-0 flex items-center flex-col gap-3 justify-center' },
    content: { class: 'relative mt-4 z-0 p-3 pt-6 h-full w-full flex-1 border border-primary-300 rounded-lg min-h-24' },
    input: { class: 'hidden' },
    file: { class: 'flex flex-col items-center' },
    chooseButtonLabel: { class: 'hidden' },
    buttonbar: { class: 'bg-orange-500 absolute z-10 left-4 top-0 border border-primary-500 bg-white rounded-full flex gap-2 items-center' },
    chooseButton: { class: 'h-9 w-9 p-1 flex-none flex flex-col gap-2 justify-center items-center cursor-pointer' },
    thumbnail: { class: 'w-full max-h-56 object-contain' }
})

watch(() => props.photo, (value: iPhoto) => {
    form.id = value?.id
    form.photo = null
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
                detail: "An error occurred! Please check the values you provided and try again.",
                life: 8000
            })
        },
        only: ['errors', 'notification', 'students']
    })
}

const optimizedImageSize = ref<number | null>(null);

const input = async (event: any) => {
    const file = event.files[0];
    if (file) {
        const optimizedImage = await optimizeImage(file);
        form.photo = dataURLtoFile(optimizedImage, 'uploaded-photo.jpg');
    }
}

const resetErrors = () => {
    form.clearErrors()
}

const close = (value: boolean) => {
    form.photo = null
    form.reset()
    emit('closed', value)
}

const visible = ref(false)
const dialogTitle = computed(() => props.edit ? 'Edit Student Photo' : 'New Student Photo')

watch(() => props.show, (value) => {
    visible.value = value
})

watch(() => props.photo?.id, (value: number | null) => {
    form.id = value
})

// New webcam functionality
const currentTab = ref('upload');
const video = ref<HTMLVideoElement | null>(null);
const capturedImage = ref<string | null>(null);
let stream: MediaStream | null = null;

const capturePhoto = async () => {
    if (video.value) {
        const canvas = document.createElement('canvas');
        canvas.width = video.value.videoWidth;
        canvas.height = video.value.videoHeight;
        const context = canvas.getContext('2d');
        if (context) {
            context.drawImage(video.value, 0, 0, canvas.width, canvas.height);
            const imageDataUrl = canvas.toDataURL('image/png');
            const optimizedImage = await optimizeImage(imageDataUrl);
            capturedImage.value = optimizedImage;
            form.photo = dataURLtoFile(optimizedImage, 'captured-photo.jpg'); // Convert base64 to File object
        }
    }
};

const startWebcam = async () => {
    try {
        stream = await navigator.mediaDevices.getUserMedia({ video: true });
        if (video.value) {
            video.value.srcObject = stream;
        }
    } catch (error) {
        console.error('Error accessing webcam: ', error);
    }
};

const stopWebcam = () => {
    if (stream) {
        stream.getTracks().forEach(track => track.stop());
        stream = null;
    }
};

onMounted(() => {
    if (currentTab.value === 'camera') {
        startWebcam();
    }
});

watch(currentTab, (newTab) => {
    if (newTab === 'camera') {
        startWebcam();
    } else {
        stopWebcam();
    }
});

watch(() => props.show, (value) => {
    visible.value = value;
    if (value && currentTab.value === 'camera') {
        startWebcam();
    }
});

onUnmounted(() => {
    stopWebcam();
});

// Image Optimization Function
const optimizeImage = async (image: File | string): Promise<string> => {
    const picaInstance = pica();

    return new Promise((resolve, reject) => {
        const img = new Image();
        img.onload = async () => {
            const canvas = document.createElement('canvas');
            const maxWidth = 800;
            const maxHeight = 800;
            const aspectRatio = img.width / img.height;
            if (img.width > img.height) {
                canvas.width = maxWidth;
                canvas.height = maxWidth / aspectRatio;
            } else {
                canvas.width = maxHeight * aspectRatio;
                canvas.height = maxHeight;
            }

            try {
                await picaInstance.resize(img, canvas);
                canvas.toBlob((blob) => {
                    if (blob) {
                        const reader = new FileReader();
                        reader.onload = () => {
                            resolve(reader.result as string);
                        };
                        reader.readAsDataURL(blob);

                        // Update the size for display
                        optimizedImageSize.value = (blob.size / 1024).toFixed(2); // size in KB
                    } else {
                        reject('Canvas is empty');
                    }
                }, 'image/jpeg', 0.8);
            } catch (error) {
                reject(error);
            }
        };

        img.onerror = (error) => {
            reject(error);
        };

        if (typeof image === 'string') {
            img.src = image;
        } else {
            const reader = new FileReader();
            reader.onload = () => {
                img.src = reader.result as string;
            };
            reader.readAsDataURL(image);
        }
    });
};

// Helper function to convert base64 to File
const dataURLtoFile = (dataurl: string, filename: string): File => {
    const arr = dataurl.split(',');
    const mime = arr[0].match(/:(.*?);/)[1];
    const bstr = atob(arr[1]);
    let n = bstr.length;
    const u8arr = new Uint8Array(n);
    while (n--) {
        u8arr[n] = bstr.charCodeAt(n);
    }
    return new File([u8arr], filename, { type: mime });
};
</script>

<style scoped>
button {
    margin: 5px;
}

video {
    width: 100%;
    max-width: 400px;
}

img {
    width: 100%;
    max-width: 400px;
    display: block;
    margin-top: 10px;
}
</style>
