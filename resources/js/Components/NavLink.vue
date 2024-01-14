<script setup lang="ts">
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps<{
    href: string;
    active?: boolean;
}>();

const classes = computed(() =>
    props.active
        ? 'bg-gray-100 text-gray-800 before:h-4 before:w-4 before:bg-gray-800 before:absolute before:-top-4 before:right-0 before:shadow-[5px_5px_0_5px] before:rounded-[0_0_100vw_0] before:shadow-gray-100 after:h-4 after:w-4 after:bg-gray-800 after:absolute after:-bottom-4 after:right-0 after:shadow-[5px_-5px_0_5px] after:rounded-[0_100vw_0_0] after:shadow-gray-100'
        : 'bg-gray-800 text-gray-100'
);
</script>

<template>
    <Link :href="href"
        class="ml-4 md:ml-8 px-6 py-4 block items-center text-lg font-medium leading-5 transition duration-150 ease-in-out relative rounded-l-full"
        :class="classes">
    <slot />
    </Link>
</template>
