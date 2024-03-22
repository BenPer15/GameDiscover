<script setup>
import { ref } from "vue";

const props = defineProps({
    text: String,
    maxLength: {
        type: Number,
        default: 500,
    },
});

const isExpanded = ref(props.text.length <= props.maxLength || false);

const toggle = () => {
    isExpanded.value = !isExpanded.value;
};

console.log();
</script>

<template>
    <div>
        <div
            class="flex text-sm text-justify transition duration-300 ease-linear text-slate-300"
        >
            <p v-if="isExpanded">
                {{ text }}
            </p>
            <p v-else>
                {{ text.substring(0, maxLength) + "..." }}
            </p>
        </div>
        <button
            v-if="text.length > maxLength"
            @click="toggle"
            class="flex flex-wrap items-center w-full gap-4 pt-1 mt-1 text-sm text-slate-300 hover:text-white"
        >
            <div class="max-w-full min-w-0 text-sm grow">
                <div class="my-auto h-[1px] border-none bg-slate-300" />
            </div>

            <div class="w-auto flex-[0_0_auto] max-w-full">
                {{ isExpanded ? "Show less" : "Show more" }}
            </div>
        </button>
    </div>
</template>
