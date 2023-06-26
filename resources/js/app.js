import.meta.glob([
    '../images/*',
]);

import { createApp } from 'vue/dist/vue.esm-bundler';

import Marathon from './modules/marathon';
import PageHeader from './components/page-header';
import Test from './modules/test';
import AnswerFinder from './modules/answer-finder';

const app = createApp({
    components: {
        Marathon,
        PageHeader,
        Test,
        AnswerFinder,
    },
});

app.mount('#app');
