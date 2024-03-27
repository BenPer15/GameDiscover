<script setup>
import { useScore } from "@/Composables/useScore";
const props = defineProps({
    score: Number,
});

const { scoreColor, scoreLabel, scoreIcon } = useScore(props.score);
</script>

<template>
    <div class="flex items-center gap-2" :class="'text-' + scoreColor">
        <i class="text-xl bx" :class="scoreIcon" /> {{ scoreLabel }}
    </div>
</template>
