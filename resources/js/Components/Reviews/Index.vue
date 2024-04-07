<script setup>
import Modal from "@/Components/Modal.vue";
import { useGameReviews } from "@/Composables/useGameReviews";
import { Link, usePage } from "@inertiajs/vue3";
import { computed, onMounted, ref } from "vue";
import PrimaryButton from "../PrimaryButton.vue";
import Add from "./Add.vue";
import Review from "./Review.vue";

defineProps({
    game: Object,
});

const page = usePage();
const auth = computed(() => page.props.auth);
const showAddReviewModal = ref(false);

const { reviews, isLoading, fetchGameReviews, addReview } = useGameReviews(
    page.props.game_id
);

onMounted(() => {
    if (auth.value.user) {
        fetchGameReviews();
    }
});

const currentUserHasReview = computed(() => {
    return reviews.value.find(
        (review) => review.user_id === auth.value.user.id
    );
});

const openAddReviewModal = () => {
    showAddReviewModal.value = true;
};

const closeModal = (review) => {
    showAddReviewModal.value = null;
    addReview(review);
};
</script>

<template>
    <div
        v-if="isLoading || !reviews"
        class="flex items-center justify-center h-screen"
    >
        <i class="text-4xl bx bx-loader-alt animate-spin" />
    </div>
    <div v-else>
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

            <PrimaryButton
                v-if="!currentUserHasReview"
                @click="openAddReviewModal"
            >
                Write a review
            </PrimaryButton>
        </div>
        <div v-else>
            <p class="text-sm">
                <Link :href="route('login')" class="text-primary">
                    Sign in
                </Link>
                to share your review with the community.
            </p>
        </div>

        <Modal :show="showAddReviewModal" @close="closeModal">
            <section class="flex w-full m-auto bg-dark-light">
                <Add :game="game" :closeModal="closeModal" />
            </section>
        </Modal>
    </div>
</template>
