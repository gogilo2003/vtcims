<script lang="ts" setup>
import NavLink from '@/Components/NavLink.vue';
import { links } from '../Pages/links';

</script>
<template>
    <div class="h-[calc(100svh_-_4rem)] sticky top-0 w-56 bg-gray-800 py-16">
        <NavLink v-for="{ name, caption } in links" :href="route(name)" :active="route().current(name)">
            {{ caption }}
        </NavLink>
    </div>
</template>
