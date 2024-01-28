<script lang="ts" setup>
import { ref, watch, onMounted } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';
import Icon from '@/Components/Icons/Icon.vue';
import InputSwitch from 'primevue/inputswitch';

const showingNavigationDropdown = ref(false);

defineProps({
    title: String
})

const emit = defineEmits(['toggle'])
const toggleState = ref(false)

const toggle = () => {
    toggleState.value = !toggleState.value
    emit('toggle', toggleState.value)
}

onMounted(() => {
    toggleState.value = localStorage.getItem('toggleState') == "1" ? true : false
    emit('toggle', toggleState.value)
    dark.value = localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)
})

watch(() => toggleState.value, value => {
    localStorage.setItem('toggleState', value ? "1" : "0")
})

const dark = ref()

watch(() => dark.value, (value) => {
    localStorage.setItem('theme', value ? 'dark' : 'light')

    if (value) {
        document.documentElement.classList.add('dark')
    } else {
        document.documentElement.classList.remove('dark')
    }
})

</script>
<template>
    <nav class="z-10 bg-white dark:bg-gray-600 border-b border-lime-500 dark:border-gray-800 sticky top-0">
        <!-- Primary Navigation Menu -->
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <!-- Logo -->
                    <div class="shrink-0 flex items-center gap-2">
                        <button @click="toggle">
                            <!-- <ApplicationLogo class="block h-9 w-auto fill-current text-gray-800" /> -->
                            <Icon :type="toggleState ? 'close' : 'menu'"
                                class="block h-7 w-auto text-gray-400 hover:text-gray-500 dark:text-gray-300 dark:hover:text-gray-400" />
                        </button>
                        <div class="text-sm uppercase font-light text-gray-900 dark:text-gray-300" v-text="title" />
                    </div>
                </div>
                <div class="flex items-center">
                    <InputSwitch v-model="dark" />
                    <div class="hidden sm:flex sm:items-center sm:ml-6">

                        <!-- Settings Dropdown -->
                        <div class="ml-3 relative">
                            <Dropdown align="right" width="48">
                                <template #trigger>
                                    <span class="inline-flex rounded-md">
                                        <button type="button"
                                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-300 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-400 focus:outline-none transition ease-in-out duration-150">
                                            {{ $page.props.auth.user.name }}

                                            <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </span>
                                </template>

                                <template #content>
                                    <DropdownLink :href="route('profile.edit')"> Profile </DropdownLink>
                                    <DropdownLink :href="route('logout')" method="post" as="button">
                                        Log Out
                                    </DropdownLink>
                                </template>
                            </Dropdown>
                        </div>
                    </div>

                    <!-- Hamburger -->
                    <div class="-mr-2 flex items-center sm:hidden">
                        <button @click="showingNavigationDropdown = !showingNavigationDropdown"
                            class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 focus:outline-none focus:gray-lime-500 transition duration-150 ease-in-out">
                            <Icon type="person" class="block h-7 w-auto" />
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Responsive Navigation Menu -->
        <div :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }"
            class="sm:hidden absolute top-16 left-0 right-0 bg-gray-50">
            <!-- Responsive Settings Options -->
            <div class="pt-4 py-1 border-t border-b-4 border-lime-500">
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800">
                        {{ $page.props.auth.user.name }}
                    </div>
                    <div class="font-medium text-sm text-gray-500">{{ $page.props.auth.user.email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <ResponsiveNavLink :href="route('profile.edit')"> Profile </ResponsiveNavLink>
                    <ResponsiveNavLink :href="route('logout')" method="post" as="button">
                        Log Out
                    </ResponsiveNavLink>
                </div>
            </div>
        </div>
    </nav>
</template>
