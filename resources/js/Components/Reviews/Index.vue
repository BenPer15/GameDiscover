<script setup>
import { Link, usePage } from "@inertiajs/vue3";
import { computed } from "vue";
import Add from "./Add.vue";
import Review from "./Review.vue";

const page = usePage();
const auth = computed(() => page.props.auth);

const props = defineProps({
    reviews: Array,
    gameId: Number,
});

const currentUserHasReview = computed(
    () =>
        !!props.reviews.find((review) => review.user.id === auth.value.user.id)
);
</script>

<template>
    <div class="flex flex-col w-full gap-4" v-if="auth.user">
        <h2 class="text-2xl font-bold">
            Reviews <span class="text-sm">({{ reviews.length }})</span>
        </h2>
        <Review
            v-if="reviews.length"
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

        <Add v-if="!currentUserHasReview" :gameId="gameId" />
    </div>
    <div v-else>
        <p class="text-sm">
            <Link :href="route('login')" class="text-primary"> Sign in </Link>
            to share your review with the community.
        </p>
    </div>
</template>
