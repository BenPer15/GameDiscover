<script setup>
import Add from "./Add.vue";

defineProps({
    reviews: Array,
    gameId: Number,
});
</script>

<template>
    <div class="flex flex-col w-full gap-4">
        <h2 class="text-2xl font-bold">Reviews</h2>
        <Review
            v-if="reviews"
            v-for="review in reviews"
            :key="review.id"
            :review="review"
        />
        <p v-else class="text-sm">
            Your review could light the way for fellow gamers.
            <span class="text-primary">
                the first to share your experience with this game!</span
            >
        </p>

        <Add :gameId="gameId" />
    </div>
</template>
