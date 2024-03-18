<script setup>
defineProps({
    type: {
        type: String,
        default: "button",
    },
    active: {
        type: Boolean,
        default: false,
    },
    size: {
        type: String,
        default: "md",
    },
    class: String,
});
</script>

<template>
    <button
        :type="type"
        class="inline-flex items-center uppercase transition duration-150 ease-in-out rounded-lg shadow-sm bg-dark-light disabled:opacity-25 hover:bg-gray-300 hover:text-dark focus:outline-none focus:ring-gray-300 focus:ring-opacity-ring-opacity-50"
        :class="class"
        v-bind:class="{
            'bg-gray-300 text-dark': active,
        }"
    >
        <slot />
    </button>
</template>
