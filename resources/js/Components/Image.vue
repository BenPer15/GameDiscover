<script setup>
import { ref } from "vue";

defineProps({
    src: String,
    alt: String,
    text: String,
    class: String,
});

const isHover = ref(false);
</script>

<template>
    <div
        @mouseenter="isHover = true"
        @mouseleave="isHover = false"
        class="relative h-40 overflow-hidden transition-all duration-300 rounded-md shadow cursor-pointer hover:transform hover:scale-105"
    >
        <div class="absolute z-10 w-full h-full rounded-md gameCard__border" />
        <img
            :src="src"
            :alt="alt"
            class="object-cover w-full h-full shadow-inner"
        />
        <div
            v-if="isHover"
            class="absolute top-0 left-0 flex items-center justify-center w-full h-full transition-all duration-300"
        >
            <i class="text-3xl bx bx-zoom-in"></i>
        </div>

        <div
            v-if="text"
            class="absolute font-bold text-white bottom-1 left-1 first-letter:uppercase"
        >
            {{ text }}
        </div>
    </div>
</template>
