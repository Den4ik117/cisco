import.meta.glob([
    '../images/*',
]);

import { createApp } from 'vue/dist/vue.esm-bundler';

import Marathon from './modules/marathon';

const app = createApp({
    components: {
        Marathon,
    },
});

app.mount('#app');
