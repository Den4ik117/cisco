<template>
    <div class="flex items-center gap-4 py-1">
        <a class="bg-slate-800 hover:bg-slate-700 rounded p-1" :href="href">
            <img src="../../../images/ArrowLeft.svg" alt="Вернуться назад" width="24" height="24">
        </a>

        <div class="flex flex-col text-xs">
            <span class="font-bold">{{ text }}</span>
            <div class="flex items-center gap-1">
                <img src="../../../images/DenisZagvozdinLogo.svg" alt="Логотип Дениса Загвоздина" width="12" height="12">
                <small class="font-bold">Denis Zagvozdin</small>
            </div>
        </div>
    </div>
</template>

<script>
import { defineComponent } from 'vue';

export default defineComponent({
    props: {
        href: {
            type: String,
            required: true
        },
        text: {
            type: String,
            required: true
        },
    },
});
</script>
