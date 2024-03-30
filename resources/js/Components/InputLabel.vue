<script setup>
defineProps({
    value: {
        type: String,
    },
    class: {
        type: String,
        default:
            "block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300",
    },
});
</script>

<template>
    <label :class="class">
        <span v-if="value">{{ value }}</span>
        <span v-else><slot /></span>
    </label>
</template>
