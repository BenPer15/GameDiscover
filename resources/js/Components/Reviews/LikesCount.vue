<script setup>
import { usePage } from "@inertiajs/vue3";
import { onMounted, ref } from "vue";

const countLikes = ref(0);
const userLikedReview = ref(false);
const auth = ref(null);
const props = defineProps({
    likes: Object,
    review_id: Number,
});

const page = usePage();

onMounted(() => {
    auth.value = page.props.auth;
    countLikes.value = props.likes.length;
    userLikedReview.value = props.likes.find(
        (like) => like.user_id === auth.value.user.id
    );
});

const likeReview = () => {
    if (!!userLikedReview.value) {
        axios
            .post(route("games.review.destroyLike", userLikedReview.value.id))
            .then(() => {
                userLikedReview.value = false;
                countLikes.value -= 1;
            });
    } else {
        axios
            .post(route("games.review.storeLike"), {
                review_id: props.review_id,
            })
            .then(() => {
                userLikedReview.value = true;
                countLikes.value += 1;
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

        <span>{{ countLikes }} Likes</span>
    </button>
</template>
