<template>
    <div class="flex flex-col gap-4">
        <div class="flex items-center gap-4 py-1">
            <a class="bg-slate-800 hover:bg-slate-700 rounded p-1" href="/">
                <img class="" src="../../../images/ArrowLeft.svg" alt="Вернуться назад" width="24">
            </a>

            <div class="flex flex-col text-xs">
                <span class="font-bold">{{ task ? `Задание ${task.task_id}` : 'Марафон' }}</span>
                <div class="flex items-center gap-1">
                    <img src="../../../images/DenisZagvozdinLogo.svg" alt="Логотип Дениса Загвоздина" width="12">
                    <small class="font-bold">Denis Zagvozdin</small>
                </div>
            </div>
        </div>

        <div ref="container" class="max-w-full overflow-x-auto overflow-y-hidden">
            <div class="flex gap-2 pb-2">
                <button
                    v-for="(task, index) in tasks"
                    :key="task.id"
                    class="tabs__button block relative rounded h-8 w-8 flex-none font-medium"
                    :class="{
                        // 'bg-slate-700': task.id === currentTask,
                        'bg-green-500 hover:bg-green-600': task.is_success === true,
                        'bg-red-500 hover:bg-red-600': task.is_success === false,
                        'bg-slate-800 hover:bg-slate-700': task.is_success === null,
                        'active': currentTask === task.id,
                    }"
                    @click="() => currentTask = task.id"
                >{{ index + 1 }}</button>
            </div>
        </div>

        <div v-if="tasks.length > 0 && task">
            <Task :task="task" @chosen="onChosen"/>
        </div>

<!--        <div class="flex justify-between items-center gap-4">-->
<!--            <button class="bg-blue-500 hover:bg-blue-600 rounded p-1 disabled:opacity-70 disabled:hover:bg-blue-500" type="button" @click="previous" :disabled="!canPrevious">-->
<!--                <img src="../../../images/SimpleLeftArrow.svg" alt="Предыдущее задание" width="24">-->
<!--            </button>-->
<!--            <button class="bg-blue-500 hover:bg-blue-600 rounded p-1 disabled:opacity-70 disabled:hover:bg-blue-500" type="button" @click="next" :disabled="!canNext">-->
<!--                <img src="../../../images/SimpleRightArrow.svg" alt="Следующее задание" width="24">-->
<!--            </button>-->
<!--        </div>-->
    </div>
</template>

<script>
import { defineComponent } from 'vue';
import axios from 'axios';
import Task from './components/Task.vue';

export default defineComponent({
    components: {
        Task,
    },
    props: {
        marathon: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            currentTask: null,
            tasks: [],
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
            if (this.currentIndex < 4) return;

            this.$refs.container.scrollLeft = 40 * (this.currentIndex - 4);
        }
    },
    methods: {
        fetchTasks() {
            axios.get(`/api/marathons/${this.marathon.uuid}/tasks`)
                .then(response => response.data.data)
                .then(tasks => {
                    this.currentTask = tasks[0].id;
                    this.tasks = tasks;
                });
        },
        onChosen(answers) {
            // const index = this.tasks.findIndex(task => task.id === this.currentTask)

            // this.currentTask = this.tasks[index + 1].id;

            axios.patch(`/api/marathons/${this.marathon.uuid}/tasks/${this.currentTask}`, { answers })
                .then(response => response.data.data)
                .then(task => {
                    const index = this.tasks.findIndex(task => task.id === this.currentTask);

                    this.tasks[index] = task;

                    if (task.is_success) {
                        this.currentTask = this.tasks[index + 1].id;
                        // this.$refs.container.scrollLeft += 48;
                    }
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
    },
    mounted() {
        this.fetchTasks();

        let ts;

        document.addEventListener('touchstart', (e) => {
            ts = e.touches[0].clientX;
            // console.log(e.touches);
            // console.log(e.touches[0].clientX);
        });

        document.addEventListener('touchend', (e) => {
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
