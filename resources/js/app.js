import './bootstrap';
import '../css/app.css';
import '../css/styles/main.scss';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import naive from 'naive-ui'; // 1. Import Naive UI

createInertiaApp({
    resolve: name => {
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: true });
        return pages[`./Pages/${name}.vue`];
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(naive) // 2. Daftarkan Naive UI secara global
            .mount(el);
    },
});
