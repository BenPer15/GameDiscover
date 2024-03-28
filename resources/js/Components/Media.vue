<script setup>
import { ref } from "vue";

const props = defineProps({
    media: Object,
    class: String,
});
const origin = window.location.origin;
const isHover = ref(false);

console.log(props.media);
</script>

<template>
    <div
        @mouseenter="isHover = true"
        @mouseleave="isHover = false"
        class="relative h-40 overflow-hidden transition-all duration-300 rounded-md shadow cursor-pointer hover:transform hover:scale-105"
    >
        <div class="absolute z-10 w-full h-full rounded-md gameCard__border" />

        <img
            :src="
                media.video_id
                    ? 'https://img.youtube.com/vi/' +
                      media.video_id +
                      '/maxresdefault.jpg'
                    : media.url
            "
            :alt="media.name"
            class="object-cover w-full h-full shadow-inner"
        />
        <div
            v-if="isHover"
            class="absolute top-0 left-0 flex items-center justify-center w-full h-full transition-all duration-300"
        >
            <i
                class="text-3xl bx"
                v-bind:class="{
                    'bxl-youtube': media.video_id,
                    'bx-zoom-in': !media.video_id,
                }"
            ></i>
        </div>

        <div
            v-if="media.type"
            class="absolute bottom-0 left-0 w-full p-2 pt-4 text-sm font-bold text-white rounded-md first-letter:uppercase"
        >
            {{ media.type }}
        </div>
    </div>
</template>
