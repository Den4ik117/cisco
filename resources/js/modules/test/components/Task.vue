<template>
    <div class="flex flex-col gap-4">
        <div v-if="!task.task.image_content" class="flex gap-2 items-center">
            <span class="text-xs text-gray-500 whitespace-nowrap">Вопрос без изображения</span>
            <span class="h-px w-full bg-gray-500"></span>
        </div>

        <img v-else class="max-w-full" :src="task.task.image_content" :alt="`Фотография к задаче с номером ${task.id}`">

        <h2 class="text-sm font-medium">{{ task.task.name }}</h2>

        <ol v-if="oneAnswer" class="flex flex-col gap-2">
            <li v-for="(choice, index) in task.choices" class="">
                <label
                    :for="`answer-${choice.id}`"
                    class="grid grid-cols-[min-content_1fr] gap-2 rounded p-2 text-xs cursor-pointer select-none"
                    :class="classes(choice)"
                >
                    <input :id="`answer-${choice.id}`" class="hidden" type="radio" name="answers" :disabled="chosen" :value="choice.id" v-model="answers[0]">
                    <span>{{ index + 1 }}.</span>
                    <span>{{ choice.option.name }}</span>
                </label>
            </li>
        </ol>

        <ol v-if="multipleAnswers" class="flex flex-col gap-2">
            <li v-for="(choice, index) in task.choices" class="">
                <label
                    :for="`answer-${choice.id}`"
                    class="grid grid-cols-[min-content_1fr] gap-2 rounded p-2 text-xs cursor-pointer select-none"
                    :class="classes(choice)"
                >
                    <input :id="`answer-${choice.id}`" class="hidden" type="checkbox" name="answers" :disabled="chosen" :value="choice.id" v-model="answers">
                    <span>{{ index + 1 }}.</span>
                    <span>{{ choice.option.name }}</span>
                </label>
            </li>
        </ol>

        <button
            v-if="canApply"
            class="flex items-center justify-center gap-2 bg-blue-500 hover:bg-blue-600 disabled:hover:bg-blue-500 disabled:opacity-75 rounded px-4 py-2 text-xs font-medium"
            type="button"
            @click="apply"
            :disabled="loading"
        >
            <span>Подтвердить ответ</span>
            <Loader v-show="loading"/>
        </button>

        <template v-if="chosen">
            <div class="text-sm">Правильный ответ: {{ task.choices.filter(choice => choice.option.is_answer).map(choice => choice.option.name).join(', ') }}</div>

            <button class="gap-2 bg-blue-500 hover:bg-blue-600 disabled:hover:bg-blue-500 disabled:opacity-75 rounded px-4 py-2 text-xs font-medium" type="button" @click="next">
                Следующий вопрос
            </button>
        </template>
    </div>
</template>

<script>
import { defineComponent } from 'vue';
import Loader from '../../../components/loader';

const Types = {
    One: 'ONE_ANSWER',
    Multiple: 'MULTIPLE_ANSWERS',
}

export default defineComponent({
    components: {
        Loader,
    },
    props: {
        task: {
            type: Object,
            required: true
        },
        loading: {
            type: Boolean,
            required: true
        },
    },
    emits: ['chosen', 'previous', 'next'],
    computed: {
        oneAnswer() {
            return this.task.task.type === Types.One;
        },
        multipleAnswers() {
            return this.task.task.type === Types.Multiple;
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
            this.answers = [];
        },
    },
    data() {
        return {
            answers: [],
        };
    },
    methods: {
        classes(choice) {
            return this.chosen ? {
                'bg-green-500': choice.option.is_answer,
                'bg-red-500': !choice.option.is_answer && choice.is_chosen,
                'bg-slate-800': !choice.option.is_answer && !choice.is_chosen,
                'border-l-4 border-slate-600': choice.is_chosen,
                'border-l-4 border-transparent': !choice.is_chosen,
            } : {
                'bg-slate-800 hover:bg-slate-700 border-l-4 border-transparent': !this.answers.includes(choice.id),
                'bg-slate-700 border-l-4 border-slate-600': this.answers.includes(choice.id),
            };
        },
        apply() {
            if (this.canApply) {
                this.$emit('chosen', this.answers);
            }
        },
        toggleAnswer(number) {
            if (!this.task.choices || this.task.choices.length === 0) return;

            const answer = this.task.choices[number - 1];

            if (!answer) return;

            if (this.oneAnswer) {
                this.answers = [answer.id];

                return;
            }

            if (this.multipleAnswers) {
                if (this.answers.includes(answer.id)) {
                    this.answers = this.answers.filter(item => item !== answer.id);
                } else {
                    this.answers.push(answer.id);
                }
            }
        },
        next() {
            this.$emit('next');
        }
    },
    mounted() {
        document.addEventListener('keydown', (e) => {
            if (['1', '2', '3', '4', '5', '6', '7', '8', '9'].includes(e.key)) {
                this.toggleAnswer(+e.key);
            }

            if (e.key === 'Enter') {
                this.chosen ? this.next() : this.apply();
            }

            if (e.key === 'Escape') {
                this.answers = [];
            }

            if (e.key === 'ArrowLeft') {
                this.$emit('previous');
            }

            if (e.key === 'ArrowRight') {
                this.next();
            }
        });
    }
});
</script>
