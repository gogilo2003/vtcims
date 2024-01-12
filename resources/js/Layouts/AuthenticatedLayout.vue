<script setup lang="ts">
import NavBar from './NavBar.vue';
import SideBar from './SideBar.vue';
defineProps({
    title: String
})
</script>

<template>
    <div>
        <div class="min-h-screen bg-gray-100">
            <NavBar :title="title" />

            <div class="flex">
                <SideBar class="flex-none" />
                <!-- Page Content -->
                <main class="flex-1">
                    <slot />
                </main>
            </div>
        </div>
    </div>
</template>
