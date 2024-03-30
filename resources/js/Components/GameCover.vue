<script>
export default {
    props: {
        url: String,
        title: String,
        size: {
            type: String,
            default: "w-44 h-60",
        },
    },
};
</script>

<template>
    <div class="relative overflow-hidden rounded-md shadow" :class="size">
        <div class="absolute z-10 w-full h-full rounded-md gameCard__border" />
        <img :src="url" :alt="title" class="object-cover w-full h-full" />
    </div>
</template>
