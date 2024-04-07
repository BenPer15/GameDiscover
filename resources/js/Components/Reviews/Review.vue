<script setup>
import { usePage } from "@inertiajs/vue3";
import { onMounted, ref } from "vue";
import CommentsCount from "./CommentsCount.vue";
import LikesCount from "./LikesCount.vue";
import Score from "./Score.vue";

const props = defineProps({
    review: Object,
});

const auth = ref(null);
const isReviewOwner = ref(false);

const page = usePage();

const removeReview = () => {
    if (!isReviewOwner.value) return;

    if (confirm("Are you sure you want to delete this review?")) {
        axios.delete(route("games.destroyReview", props.review.id));
    }
};

onMounted(() => {
    auth.value = page.props.auth;
    console.log(auth.value);
    isReviewOwner.value = props.review.user.id === auth.value.user.id;
});
</script>

<template>
    <div class="flex items-start gap-4 pb-2 border-b border-gray-600">
        <div class="flex flex-col items-center gap-2">
            <img
                :src="
                    'https://api.dicebear.com/8.x/adventurer-neutral/svg?seed=' +
                    review.user.name
                "
                alt="avatar"
                class="object-cover rounded-md w-11 h-11"
            />
            <span class="text-xs font-bold">{{ review.user.name }}</span>
        </div>
        <div class="flex items-start justify-between w-full">
            <div class="flex flex-col items-start w-full">
                <div class="flex items-center gap-2">
                    <Score :score="review.sentiment_score" />
                    <span class="text-xs text-gray-500">{{
                        new Date(review.created_at).toLocaleDateString()
                    }}</span>
                </div>

                <p class="mt-4 text-justify text-gray-200">
                    {{ review.content }}
                </p>

                <div class="flex items-center gap-4 text-sm">
                    <LikesCount :likes="review.likes" :review_id="review.id" />
                    <CommentsCount :comments="review.comments" />
                </div>
            </div>
            <div v-if="isReviewOwner" class="flex items-center gap-2 text-sm">
                <button
                    class="flex items-center justify-center w-5 h-5 text-xs rounded-full bg-primary"
                >
                    <i class="bx bx-edit"></i>
                </button>
                <button
                    @click="removeReview"
                    class="flex items-center justify-center w-5 h-5 text-xs bg-red-500 rounded-full"
                >
                    <i class="bx bx-trash"></i>
                </button>
            </div>
        </div>
    </div>
</template>
