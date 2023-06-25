import.meta.glob([
    '../images/*',
]);

import { createApp } from 'vue/dist/vue.esm-bundler';

import Marathon from './modules/marathon';
import PageHeader from './components/page-header';
import Test from './modules/test';

const app = createApp({
    components: {
        Marathon,
        PageHeader,
        Test,
    },
});

app.mount('#app');
