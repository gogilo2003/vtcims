<script lang="ts" setup>
import NavLink from '@/Components/NavLink.vue';
import { useLinks } from '@/Composables/links';
import { links } from '../links';
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

defineProps<{
    show: boolean;
}>()

const permissions = computed(() => usePage().props.auth.user.permissions)

const menuLinks = useLinks(links, permissions.value)
</script>
<template>
    <div class="overflow-y-auto h-[calc(100svh_-_4rem)] sticky top-16 bg-primary-500 dark:bg-gray-800 py-16 flex flex-col gap-4 overflow-hidden transition-['width'] duration-500 ease-in-out"
        :class="{ 'w-72': show, 'w-0': !show }">
        <NavLink v-for="{ icon, name, caption, items } in menuLinks" :href="name ? route(name) : '#'"
            :active="name ? route().current(name) : false" :items="items" :icon="icon">
            {{ caption }}
        </NavLink>
    </div>
</template>
