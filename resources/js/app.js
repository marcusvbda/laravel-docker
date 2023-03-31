import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
import ComponentTest from './Components/ComponentTest.vue';

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

const addGlobalComponent = (vueApp) => {
    vueApp.component('ComponentTest', ComponentTest);
    return vueApp;
}

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        let vueApp = createApp({ render: () => h(App, props) })
        vueApp = addGlobalComponent(vueApp);
        return vueApp.use(plugin).use(ZiggyVue, Ziggy).mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});