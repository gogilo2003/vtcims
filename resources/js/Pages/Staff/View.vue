<script lang="ts" setup>
import { ref, watch } from "vue";
import Dialog from 'primevue/dialog';
import Toast from "primevue/toast";
import { iBogMember } from '../../interfaces/index';

const props = defineProps<{
    show: boolean,
    member?: iBogMember | null
}>()
const emit = defineEmits(['closed', 'saved'])

const close = (value: boolean) => {
    emit('closed', value)
}

const visible = ref(false)

watch(() => props.show, (value) => {
    visible.value = value
})

</script>
<template>
    <Toast position="top-center" />
    <Dialog v-model:visible="visible" :closeable="true" @update:visible="close" modal
        :pt="{ root: { class: ['w-full md:w-[50rem] lg:w-[60rem]'] } }" header="Student Details">
        <div>
            <div class="shadow border border-stone-100 dark:border-gray-500 rounded-lg">
                <div class="relative flex flex-col md:flex-row items-start">
                    <div class="p-4 flex-none w-full md:w-48 h-64 md:h-48 relative">
                        <img :src="member?.photo_url" alt=""
                            class="w-full h-full object-cover border p-1 shadow rounded-xl relative">
                        <div class="absolute left-[50%] -translate-x-[50%] bottom-0 rounded-full"
                            :class="{ 'bg-lime-600': member?.active, 'bg-orange-600': !member?.active }">
                            <div class="text-gray-300 px-3 py-2 uppercase font-semibold"
                                v-text="member?.active ? 'Current' : 'Former'"></div>
                        </div>
                    </div>
                    <div class="relative flex-1 py-2 md:pr-3 w-full px-4 md:pl-0">
                        <h3 v-text="`${member?.first_name} ${member?.middle_name} ${member?.surname}`"
                            class="text-lg font-semibold pb-2 mb-2 border-b uppercase text-center md:text-left dark:text-gray-300">
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <div class="flex flex-col p-3 rounded border">
                                <span class="text-xs font-bold uppercase whitespace-nowrap dark:text-stone-400">ID
                                    No</span>
                                <span v-text="member?.idno" class="text-stone-700 dark:text-stone-200"></span>
                            </div>
                            <div class="flex flex-col p-3 rounded border">
                                <span
                                    class="text-xs font-bold uppercase whitespace-nowrap dark:text-stone-400">Position</span>
                                <span v-text="member?.position?.name" class="text-stone-700 dark:text-stone-200"></span>
                            </div>
                            <div class="flex flex-col p-3 rounded border">
                                <span
                                    class="text-xs font-bold uppercase whitespace-nowrap dark:text-stone-400">Gender</span>
                                <span v-text="member?.gender" class="text-stone-700 dark:text-stone-200"></span>
                            </div>
                            <div class="flex flex-col p-3 rounded border">
                                <span
                                    class="text-xs font-bold uppercase whitespace-nowrap dark:text-stone-400">Phone</span>
                                <span v-text="member?.phone" class="text-stone-700 dark:text-stone-200"></span>
                            </div>
                            <div class="flex flex-col p-3 rounded border col-span-1 lg:col-span-1 md:col-span-2">
                                <span
                                    class="text-xs font-bold uppercase whitespace-nowrap dark:text-stone-400">Email</span>
                                <span v-text="member?.email" class="text-stone-700 dark:text-stone-200"></span>
                            </div>
                            <div class="flex flex-col p-3 rounded border col-span-1 lg:col-span-1 md:col-span-2">
                                <span class="text-xs font-bold uppercase whitespace-nowrap dark:text-stone-400">Postal
                                    Address</span>
                                <span
                                    v-text="`P.O. Box ${member?.box_no}${member?.post_code ? ' - ' + member?.post_code : ''} ${member?.town}`"
                                    class="text-stone-700 dark:text-stone-200"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Dialog>
</template>
