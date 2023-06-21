<template>
    <div class="flex flex-col gap-4">
        <div class="max-w-full overflow-x-auto">
            <div class="flex gap-2 pb-2">
                <button
                    v-for="(task, index) in tasks"
                    :key="task.id"
                    class="block rounded h-8 w-8 flex-none font-medium"
                    :class="{
                        // 'bg-slate-700': task.id === currentTask,
                        'bg-green-500 hover:bg-green-600': task.is_success === true,
                        'bg-red-500 hover:bg-red-600': task.is_success === false,
                        'bg-slate-800 hover:bg-slate-700': task.is_success === null,
                    }"
                    @click="() => currentTask = task.id"
                >{{ index + 1 }}</button>
            </div>
        </div>

        <div v-if="tasks.length > 0 && task">
            <Task :task="task" @chosen="onChosen"/>
        </div>
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

                    this.currentTask = this.tasks[index + 1].id;
                });
        },
    },
    mounted() {
        this.fetchTasks();
    }
});
</script>
