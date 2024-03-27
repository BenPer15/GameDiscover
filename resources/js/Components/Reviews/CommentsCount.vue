<script setup>
defineProps({
    comments: Array,
});
</script>

<template>
    <div class="flex items-center gap-1 text-gray-500">
        <i class="bx bxs-chat"></i>

        <span>{{ comments.length }}</span>
    </div>
</template>
