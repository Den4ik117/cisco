<template>
    <div class="flex flex-col gap-4">
        <PageHeader href="/" :text="task ? `Задание ${task.task_id}` : 'Марафон'"/>

        <div ref="container" class="tabs max-w-full overflow-x-auto overflow-y-hidden">
            <div class="flex gap-2 pb-2">
                <button
                    v-for="(task, index) in tasks"
                    :key="task.id"
                    class="tabs__button block relative rounded h-8 w-8 flex-none font-medium"
                    :class="{
                        // 'bg-slate-700': task.id === currentTask,
                        'bg-green-500 hover:bg-green-600': task.is_success === true,
                        'bg-red-500 hover:bg-red-600': task.is_success === false,
                        'bg-slate-800 hover:bg-slate-700': task.is_success === null && currentTask !== task.id,
                        'bg-slate-700': task.is_success === null && currentTask === task.id,
                        'active': currentTask === task.id,
                    }"
                    @click="() => currentTask = task.id"
                >{{ index + 1 }}</button>
            </div>
        </div>

        <div v-if="tasks.length > 0 && task">
            <Task
                :task="task"
                :loading="loading"
                @chosen="onChosen"
                @previous="previous"
                @next="next"
            />
        </div>
    </div>
</template>

<script>
import { defineComponent } from 'vue';
import axios from 'axios';
import Task from './components/Task.vue';
import PageHeader from '../../components/page-header';

let firstRender = true;

export default defineComponent({
    components: {
        Task,
        PageHeader,
    },
    props: {
        test: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            currentTask: null,
            tasks: [],
            loading: false,
        };
    },
    computed: {
        task() {
            return this.tasks.find(task => task.id === this.currentTask);
        },
        currentIndex() {
            return this.tasks.findIndex(task => task.id === this.currentTask);
        },
        canPrevious() {
            return this.currentIndex > 0;
        },
        canNext() {
            return this.currentIndex < this.tasks.length - 1;
        },
    },
    watch: {
        currentTask() {
            this.changedTask();
        },
    },
    methods: {
        fetchTasks() {
            axios.get(`/api/tests/${this.test.uuid}/exercises`)
                .then(response => response.data.data)
                .then(exercises => {
                    this.tasks = exercises;
                    this.currentTask = this.test.last_exercise_id || exercises[0].id;
                });
        },
        onChosen(answers) {
            if (this.loading) return;

            this.loading = true;

            axios.patch(`/api/tests/${this.test.uuid}/exercises/${this.currentTask}`, { answers })
                .then(response => response.data.data)
                .then(task => {
                    const index = this.tasks.findIndex(item => item.id === task.id);

                    this.tasks[index] = task;

                    if (task.is_success && this.currentTask === task.id) {
                        this.currentTask = this.tasks[index + 1].id;
                        // this.$refs.container.scrollLeft += 48;
                    }
                })
                .finally(() => {
                    this.loading = false;
                });
        },
        previous() {
            if (!this.canPrevious) return;

            this.currentTask = this.tasks[this.currentIndex - 1].id;
        },
        next() {
            if (!this.canNext) return;

            this.currentTask = this.tasks[this.currentIndex + 1].id;
        },
        changedTask() {
            if (this.currentIndex >= 4) {
                if (firstRender) {
                    setTimeout(() => {
                        this.$refs.container.scrollLeft = 40 * (this.currentIndex - 4);
                    }, 100);
                } else this.$refs.container.scrollLeft = 40 * (this.currentIndex - 4);
            }

            if (this.currentTask && !firstRender) {
                axios.post(`/api/tests/${this.test.uuid}/exercises/${this.currentTask}`);
            }

            firstRender = false;
        },
    },
    mounted() {
        this.fetchTasks();

        let ts;

        document.addEventListener('touchstart', (e) => {
            ts = e.target.closest('.tabs') ? null : e.touches[0].clientX;
        });

        document.addEventListener('touchend', (e) => {
            if (!ts) return;

            const te = e.changedTouches[0].clientX;
            const diff = te - ts;

            if (Math.abs(diff) < 100) return;

            diff < 0 ? this.next() : this.previous();
        });
    }
});
</script>

<style>
.tabs__button.active::after {
    content: '';
    position: absolute;
    width: 10px;
    height: 10px;
    @apply bg-slate-900;
    top: calc(100% - 3px);
    left: calc(50% - 5px);
    transform: rotate(-45deg);
}
</style>
