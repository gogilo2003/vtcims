<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import NavBar from './NavBar.vue';
import SideBar from './SideBar.vue';
defineProps({
    title: String
})

const showSidebar = ref(false)

const toggleState = (value: boolean) => {
    showSidebar.value = value
}
</script>

<template>
    <Head :title="title" />
    <div class="transition ease-in-out duration-500">
        <div class="min-h-screen bg-gray-100 transition ease-in-out duration-500">
            <NavBar :title="title" @toggle="toggleState" />

            <div class="flex transition ease-in-out duration-500">
                <SideBar class="flex-none" :show="showSidebar" />
                <!-- Page Content -->
                <main class="flex-1 transition ease-in-out duration-500 p-3 sm:p-6 lg:p-8">
                    <slot />
                </main>
            </div>
        </div>
    </div>
</template>
