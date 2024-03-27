<script setup>
import { router, usePage } from "@inertiajs/vue3";
import { computed } from "vue";

const props = defineProps({
    likes: Object,
    review_id: Number,
});

const page = usePage();
const auth = computed(() => page.props.auth);

const userLikedReview = computed(() =>
    props.likes.find((like) => like.user_id === auth.value.user.id)
);

const likeReview = () => {
    if (!!userLikedReview.value) {
        router.post(
            route("games.review.destroyLike", userLikedReview.value.id)
        );
    } else {
        router.post(route("games.review.storeLike"), {
            review_id: props.review_id,
        });
    }
};
</script>

<template>
    <button
        @click="likeReview"
        class="flex items-center gap-1 text-gray-500"
        v-bind:class="{ 'text-red-400': !!userLikedReview }"
    >
        <i v-if="!!userLikedReview" class="bx bxs-heart"></i>
        <i v-else class="bx bx-heart"></i>

        <span>{{ likes.length }} Likes</span>
    </button>
</template>
