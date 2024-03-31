<script setup>
import { ref } from "vue";
import SecondaryButton from "./SecondaryButton.vue";
const showing = ref(false);
defineProps({
    stream: Object,
    height: {
        type: Number,
        default: 320,
    },
    width: {
        type: Number,
        default: 550,
    },
});
const parent = window.location.hostname;

const showLive = () => {
    showing.value = !showing.value;
};
</script>

<template>
    <div class="flex flex-col w-full gap-3 mb-4">
        <h2 class="text-2xl font-bold">Live Twitch</h2>
        <div v-if="!showing" class="flex flex-col w-full gap-3">
            <div class="text-2xs">
                <span
                    v-if="stream.type === 'live'"
                    class="p-1 font-bold text-white bg-red-600 rounded py-0.5 mr-2 animate-pulse"
                >
                    Live
                </span>
                <span class="font-bold">{{ stream.user_name }}</span> is
                currently live on Twitch
            </div>
            <SecondaryButton @click="showLive"> Watch Live </SecondaryButton>
        </div>

        <div v-if="showing">
            <iframe
                :src="
                    'https://player.twitch.tv/?channel=' +
                    stream.user_name +
                    '&html5&parent=' +
                    parent +
                    '&muted=false'
                "
                :height="height"
                :width="width"
                allowfullscreen
                class="w-full"
            >
            </iframe>
            <a
                :href="'https://www.twitch.tv/' + stream.user_name"
                class="text-2xs"
                target="_blank"
            >
                <span
                    v-if="stream.type === 'live'"
                    class="p-1 font-bold text-white bg-red-600 rounded py-0.5 mr-2 animate-pulse"
                >
                    Live
                </span>
                <span class="font-bold">{{ stream.user_name }} : </span>
                <span>{{ stream.title }}</span>
            </a>
        </div>
    </div>
</template>
