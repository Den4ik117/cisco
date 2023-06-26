<template>
    <div class="flex flex-col gap-4">
        <input
            class="outline-none px-3 py-2 text-xs bg-slate-800 rounded border border-slate-600 focus:border-blue-500"
            ref="input"
            type="text"
            v-model="value"
            autocomplete="off"
            spellcheck="false"
            placeholder="Введите вопрос"
        >

        <button
            v-show="value"
            class="bg-blue-500 hover:bg-blue-600 disabled:hover:bg-blue-500 disabled:opacity-75 rounded px-3 py-2 text-xs font-medium"
            type="button"
            @click="reset"
        >Стереть и найти ещё</button>

        <template v-if="results.length > 0">
            <ul v-show="results.length > 0" class="flex flex-col">
                <li class="border-b border-gray-500 text-gray-500 text-xs font-medium">
                    <div class="grid grid-cols-1 p-2">
                        <small>Название и ответ</small>
                    </div>
                </li>
                <li v-for="result in results" :key="result.id" class="text-gray-200 text-xs">
                    <div class="grid grid-cols-1 px-2 py-3 gap-y-2 hover:bg-slate-800 cursor-pointer border-b border-slate-700">
                        <span v-html="result.name"></span>
                        <ol class="ml-4 flex flex-col gap-2 list-decimal list-inside">
                            <li v-for="option in result.options" :key="option.id">{{ option.name }}</li>
                        </ol>
                    </div>
                </li>
            </ul>
        </template>

        <span v-if="status === 1" class="text-sm text-center text-gray-500">Загрузка...</span>
        <span v-if="status === 2" class="text-sm text-center text-red-500">Ничего не найдено</span>
    </div>
</template>

<script>
import { defineComponent } from 'vue';
import { debounce } from 'lodash';

export default defineComponent({
    props: {
        tasks: {
            type: Array,
            required: true
        },
    },
    data() {
        return {
            value: '',
            results: [],
            status: 0,
        };
    },
    watch: {
        value: debounce(function (value) {
            this.getAnswers(value);
        }, 1000),
    },
    methods: {
        getAnswers(value) {
            if (this.status === 1) return;

            this.status = 1;

            this.results = this.tasks.filter(task => {
                return task.name.toLowerCase().indexOf(value.toLowerCase()) > -1;
            }).map(task => {
                const newValue = `<span class="text-yellow-500 font-medium">${value}</span>`;
                const highlighted = task.name.replace(new RegExp(value, 'i'), newValue);

                return {
                    id: task.id,
                    name: highlighted,
                    options: task.options,
                };
            }).slice(0, 10);

            this.status = this.results.length > 0 ? 0 : 2;
        },
        reset() {
            this.value = '';
            this.$refs.input.focus();
        },
    },
    mounted() {
        setTimeout(() => {
            this.$refs.input.focus();
        }, 100);
    }
});
</script>
