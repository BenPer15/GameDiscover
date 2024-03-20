<script setup>
import { Link } from "@inertiajs/vue3";
defineProps({
    icon: String,
    route: String,
});
</script>

<template>
    <Link
        :href="route"
        class="flex items-center gap-2 p-2 transition-all rounded-lg cursor-pointer hover:bg-dark-light"
    >
        <i :class="'text-base text-gray-400 bx ' + icon"></i>
        <slot />
    </Link>
</template>
