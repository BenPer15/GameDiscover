<script setup>
import { defineProps } from "vue";

const props = defineProps({
    game: Object,
    class: String,
    active: Boolean,
    icon: String,
    click: Function,
});
</script>
<template>
    <button
        @click="click"
        v-bind:class="{
            ' text-primary ': active,
        }"
        class="flex flex-col items-center justify-center p-2 px-3 hover:bg-dark-lighter"
        :class="class"
    >
        <i class="text-base bx" :class="(active ? 'bxs-' : 'bx-') + icon"></i>
        <slot />
    </button>
</template>
