<script setup lang="ts">
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps<{
    href: string;
    active?: boolean;
}>();

const classes = computed(() =>
    props.active
        ? 'bg-gray-100 text-gray-800'
        : 'bg-gray-800 text-gray-100'
);
</script>

<template>
    <Link :href="href"
        class="px-3 py-2 block items-center text-xl font-medium leading-5 transition duration-150 ease-in-out"
        :class="classes">
    <slot />
    </Link>
</template>
