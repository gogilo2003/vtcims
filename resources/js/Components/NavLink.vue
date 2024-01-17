<script setup lang="ts">
import { computed, ref, watchEffect } from 'vue';
import { Link } from '@inertiajs/vue3';
import { iMenuItem } from '../interfaces/index';
import Icon from '@/Components/Icons/Icon.vue';

const props = defineProps<{
    href: string;
    active: boolean;
    items: Array<iMenuItem>
}>();

const activeState = ref(false)

const classes = computed(() =>
    activeState.value
        ? 'bg-gray-100 dark:bg-gray-600 text-gray-800 dark:text-gray-200 before:h-4 before:w-4 before:bg-gray-800 before:absolute before:-top-4 before:right-0 before:shadow-[5px_5px_0_5px] before:rounded-[0_0_100vw_0] before:shadow-gray-100 dark:before:shadow-gray-600 after:h-4 after:w-4 after:bg-gray-800 after:absolute after:-bottom-4 after:right-0 after:shadow-[5px_-5px_0_5px] after:rounded-[0_100vw_0_0] after:shadow-gray-100 dark:after:shadow-gray-600'
        : 'bg-gray-800 text-gray-100'
);

watchEffect(() => {
    activeState.value = props.active
    if (props?.items?.length) {
        props?.items?.forEach(({ name }) => {
            if (route().current(name)) {
                activeState.value = true
            }
        });
    }
})

const showSubmenu = ref(false)

const onClickAway = () => {
    showSubmenu.value = false
}
</script>

<template>
    <component :is="items?.length ? 'span' : Link" :href="href"
        class="ml-4 md:ml-8 my-0 block items-center text-lg font-medium leading-5 transition duration-150 ease-in-out relative rounded-l-full"
        :class="classes">
        <span @click="showSubmenu = !showSubmenu" class="cursor-pointer px-6 py-4 flex justify-between">
            <slot />
            <Icon v-if="items?.length" :type="showSubmenu ? 'pi-chevron-up' : 'pi-chevron-down'"
                class="transition duration-300" />
        </span>
        <div v-if="items?.length && showSubmenu"
            class="absolute z-20 left-0 top-[100%] bg-gray-100 text-gray-800 p-3 ml-6 w-[calc(100%_-_2.5rem)] rounded-b-lg border-t border-gray-800 flex flex-col gap-1"
            v-click-away="onClickAway">
            <Link
                class="w-full text-base px-3 py-2 transition-all duration-300 rounded bg-gray-50 hover:bg-primary-500 hover:text-gray-100"
                :class="{ 'bg-primary-500 text-gray-100': route().current(name) }" :href="route(name)"
                v-for="{ name, caption } in items">{{
                    caption }}
            </Link>
        </div>
    </component>
</template>
