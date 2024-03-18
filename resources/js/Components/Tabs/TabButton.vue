<script setup>
import { defineProps } from "vue";

// Déclaration des props
defineProps({
    isActive: Boolean,
    action: Function,
});
</script>

<template>
    <button
        @click="action"
        class="px-4 py-1 text-sm transition-all duration-300 bg-white border rounded-full shadow text-dark"
        v-bind:class="{ 'opacity-50': !isActive }"
    >
        <slot />
    </button>
</template>
