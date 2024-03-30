<script setup>
defineProps({
    value: String,
    class: String,
});
</script>

<template>
    <div class="relative">
        <label
            class="absolute z-10 px-2 top-1 left-4 bg-none text-[0.6rem] bg-dark"
            :class="class"
        >
            {{ value }}
        </label>
        <slot />
    </div>
</template>
