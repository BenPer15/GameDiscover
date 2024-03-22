<script setup>
import { ref } from "vue";

defineProps({
    text: String,
    maxLength: {
        type: Number,
        default: 500,
    },
});

const isExpanded = ref(false);

const toggle = () => {
    isExpanded.value = !isExpanded.value;
};
</script>

<template>
    <div>
        <div
            class="flex text-sm text-justify transition duration-300 ease-linear"
        >
            <p v-if="isExpanded">
                {{ text }}
            </p>
            <p v-else>
                {{ text.substring(0, maxLength) + "..." }}
            </p>
        </div>
        <button
            @click="toggle"
            class="flex flex-wrap items-center w-full gap-4 pt-1 mt-1 text-sm"
        >
            <div class="max-w-full min-w-0 text-sm grow">
                <div class="my-auto h-[1px] border-none bg-slate-300" />
            </div>

            <div class="w-auto flex-[0_0_auto] max-w-full text-slate-300">
                {{ isExpanded ? "Show less" : "Show more" }}
            </div>
        </button>
    </div>
</template>
