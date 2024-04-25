<script setup lang="ts">
import { computed, ref, watch, watchEffect } from 'vue';
import { Link } from '@inertiajs/vue3';
import { iMenuItem } from '../interfaces/index';
import Icon from '@/Components/Icons/Icon.vue';

const props = defineProps<{
    icon: string;
    href: string;
    active: boolean;
    items: iMenuItem[] | null
}>();

const activeState = ref(false)

const classes = computed(() =>
    activeState.value
        ? 'bg-gray-100 dark:bg-gray-600 text-primary-500 dark:text-gray-200 before:h-4 before:w-4 before:bg-transparent before:absolute before:-top-4 before:right-0 before:shadow-[5px_5px_0_5px] before:rounded-[0_0_100vw_0] before:shadow-gray-100 dark:before:shadow-gray-600 after:h-4 after:w-4 after:bg-transparent after:absolute after:-bottom-4 after:right-0 after:shadow-[5px_-5px_0_5px] after:rounded-[0_100vw_0_0] after:shadow-gray-100 dark:after:shadow-gray-600'
        : 'bg-primary-500 dark:bg-gray-800 text-gray-100'
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

const submenu = ref<HTMLDivElement>()

watchEffect(() => {
    if (submenu.value) {
        const el = submenu.value
        let parentTop = el.parentElement?.offsetTop ?? 0
        let parentHeight = el.parentElement?.offsetHeight ?? 0
        let elTop = el.offsetTop ?? 0
        let elHeight = el.offsetHeight ?? 0
        let winHeight = window.innerHeight - 64

        let bottom = parentTop + parentHeight + elTop + elHeight

        if (bottom > winHeight && elHeight < winHeight) {
            el.style.top = `${(winHeight - bottom) + 64}px`
        }
        if (bottom > winHeight && elHeight > winHeight) {
            el.style.top = `-${parentTop - 32}px`
        }
    }
})
</script>

<template>
    <component :is="items?.length ? 'span' : Link" :href="href"
        class="ml-4 md:ml-8 my-0 flex items-center leading-5 transition duration-150 ease-in-out relative rounded-l-full"
        :class="classes">
        <Icon class="h-6 w-6 object-contain ml-4 flex-none" :type="icon" />
        <span @click="showSubmenu = !showSubmenu"
            class="cursor-pointer pl-3 pr-6 py-4 flex justify-between flex-1 text-base font-light uppercase ">
            <slot />
            <Icon v-if="items?.length" :type="showSubmenu ? 'pi-chevron-up' : 'pi-chevron-down'"
                class="transition duration-300" />
        </span>
        <Transition enter-active-class="transition ease-out duration-200" enter-from-class="opacity-0 scale-95"
            enter-to-class="opacity-100 scale-100" leave-active-class="transition ease-in duration-75"
            leave-from-class="opacity-100 scale-100" leave-to-class="opacity-0 scale-95">
            <div ref="submenu" v-if="items?.length && showSubmenu"
                class="absolute z-20 left-0 top-[100%] bg-gray-100 dark:bg-gray-600 text-gray-800 dark:text-400 p-3 ml-6 w-[calc(100%_-_2.5rem)] rounded-lg flex flex-col gap-1"
                v-click-away="onClickAway">
                <Link
                    class="w-full text-sm font-light px-3 py-2 transition-all duration-300 rounded bg-gray-50 dark:bg-gray-700 hover:bg-primary-500 hover:text-gray-100"
                    :class="{ 'bg-primary-500 text-gray-100': route().current(name), 'dark:text-gray-300': !route().current(name) }"
                    :href="route(name)" v-for="{ name, caption } in items">
                {{ caption }}
                </Link>
            </div>
        </Transition>
    </component>
</template>
