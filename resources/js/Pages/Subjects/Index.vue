<script lang="ts" setup>
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout.vue'
import { iInstructor, iSubject } from '../../interfaces/index';
import Paginator from '@/Components/Paginator.vue';

const props = defineProps<{
    subjects: {
        data: Array<iSubject>
    },
    instructors: Array<iInstructor>
}>()
</script>
<template>
    <AuthenticatedLayout title="Subjects">
        <div class="flex flex-col gap-2">
            <div v-for="subject in subjects.data" class="px-4 py-2 rounded-lg shadow">
                <div>
                    <div v-text="subject.name" class="uppercase text-sm font-semibold text-gray-800"></div>
                    <div class="flex gap-1">
                        <span class="text-xs font-semibold text-gray-800">COURSES:</span>
                        <span v-for="course in subject.courses" v-text="course.code" class="text-xs text-gray-500"></span>
                    </div>
                </div>
            </div>
            <Paginator :items="subjects" />
        </div>
    </AuthenticatedLayout>
</template>
