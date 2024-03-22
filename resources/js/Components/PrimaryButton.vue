<script setup>
defineProps({
    class: String,
});
</script>

<template>
    <button
        class="p-3 text-xs font-bold text-white uppercase transition duration-150 ease-in-out border border-transparent rounded-lg shadow-sm bg-primary hover:bg-primary-hover"
        :class="class"
    >
        <slot />
    </button>
</template>
