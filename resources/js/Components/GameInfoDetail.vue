<script setup>
defineProps({
    type: String,
});
</script>
<template>
    <div class="flex justify-between w-full gap-4">
        <span class="mr-4 text-gray-500">{{ type }}</span>
        <span class="flex justify-end text-end">
            <slot />
        </span>
    </div>
</template>
