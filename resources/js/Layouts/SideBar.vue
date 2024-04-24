<script lang="ts" setup>
import NavLink from '@/Components/NavLink.vue';
import { links } from '../links';

defineProps<{
    show: boolean;
}>()

</script>
<template>
    <div class="overflow-y-auto h-[calc(100svh_-_4rem)] sticky top-16 bg-primary-500 dark:bg-gray-800 py-16 flex flex-col gap-4 overflow-hidden transition-['width'] duration-500 ease-in-out"
        :class="{ 'w-72': show, 'w-0': !show }">
        <NavLink v-for="{ icon, name, caption, items } in links" :href="name ? route(name) : '#'"
            :active="name ? route().current(name) : false" :items="items" :icon="icon">
            {{ caption }}
        </NavLink>
    </div>
</template>
