<template>
    <div class="flex flex-col gap-4">
        <div class="flex gap-2 items-center">
            <span class="text-xs text-gray-500 whitespace-nowrap">Вопрос без изображения</span>
            <span class="h-px w-full bg-gray-500"></span>
        </div>

        <h2 class="text-sm font-medium">{{ task.name }}</h2>

        <ol v-if="oneAnswer" class="flex flex-col gap-2">
            <li v-for="(option, index) in task.options" class="">
                <label
                    :for="`answer-${option.id}`"
                    class="grid grid-cols-[min-content_1fr] gap-2 rounded p-2 text-xs cursor-pointer select-none"
                    :class="classes(option)"
                >
                    <input :id="`answer-${option.id}`" class="hidden" type="radio" name="answers" :disabled="chosen" :value="option.id" v-model="answers[0]">
                    <span>{{ index + 1 }}.</span>
                    <span>{{ option }}</span>
                </label>
            </li>
        </ol>

        <ol v-if="multipleAnswers" class="flex flex-col gap-2">
            <li v-for="(option, index) in task.options" class="">
                <label
                    :for="`answer-${option.id}`"
                    class="grid grid-cols-[min-content_1fr] gap-2 rounded p-2 text-xs cursor-pointer select-none"
                    :class="classes(option)"
                >
                    <input :id="`answer-${option.id}`" class="hidden" type="checkbox" name="answers" :disabled="chosen" :value="option.id" v-model="answers">
                    <span>{{ index + 1 }}.</span>
<!--                    <span>{{ option }}</span>-->
                    <span>{{ option.name }}</span>
                </label>
            </li>
        </ol>

        <button v-if="canApply" class="bg-blue-500 hover:bg-blue-600 rounded px-4 py-2 text-xs font-medium" type="button" @click="apply">Подтвердить ответ</button>

        <div v-if="chosen">
            <div class="text-sm">Правильный ответ: {{ task.options.filter(option => option.is_answer).map(option => option.name).join(', ') }}</div>
        </div>
    </div>
</template>

<script>
import { defineComponent } from 'vue';

const Types = {
    One: 'ONE_ANSWER',
    Multiple: 'MULTIPLE_ANSWERS',
}

export default defineComponent({
    props: {
        task: {
            type: Object,
            required: true
        }
    },
    emits: ['chosen'],
    computed: {
        oneAnswer() {
            return this.task.type === Types.One;
        },
        multipleAnswers() {
            return this.task.type === Types.Multiple;
        },
        chosen() {
            return this.task.is_success !== null;
        },
        canApply() {
            return !this.chosen && this.answers.length > 0 && this.answers[0];
        },
    },
    watch: {
        task() {
            // this.answer = '';
            this.answers = [];
        },
    },
    data() {
        return {
            // answer: '',
            answers: [],
            // choiceIsMade: false,
        };
    },
    methods: {
        classes(option) {
            return this.chosen ? {
                'bg-green-500': option.is_answer && option.is_chosen,
                'bg-red-500': (option.is_answer && !option.is_chosen) || (!option.is_answer && option.is_chosen),
                'bg-slate-800': !option.is_answer && !option.is_chosen,
            } : {
                'bg-slate-800 hover:bg-slate-700': true,
                'bg-slate-700': this.answers.includes(option.id),
            };
        },
        apply() {
            if (this.canApply) {
                this.$emit('chosen', this.answers);
            }
        }
    }
});
</script>
