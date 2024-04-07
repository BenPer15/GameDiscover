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
    class: String,
});
</script>

<template>
    <button
        :type="type"
        class="p-3 text-xs font-bold text-white uppercase transition duration-150 ease-in-out border border-transparent rounded-lg shadow-sm bg-dark-light hover:bg-dark-lighter"
        :class="class"
        v-bind:class="{
            'bg-dark-lighter text-dark': active,
        }"
    >
        <slot />
    </button>
</template>
