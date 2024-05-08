import './bootstrap';
import '../css/app.css';
import 'primeicons/primeicons.css';
import 'primevue/resources/themes/lara-light-green/theme.css'

import { createApp, h, DefineComponent } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
import PrimeVue from 'primevue/config';
import ToastService from 'primevue/toastservice';
import VueClickAway from "vue3-click-away";
import lara from '../assets/primevue-lara-theme';

const laraOptions = {
    unstyled: true,
    pt: lara
}

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob<DefineComponent>('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(PrimeVue, laraOptions)
            .use(ToastService)
            .use(plugin)
            .use(ZiggyVue, Ziggy)
            .use(VueClickAway)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
