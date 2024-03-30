<script setup>
import Modal from "@/Components/Modal.vue";
import { Link, usePage } from "@inertiajs/vue3";
import { computed, ref } from "vue";
import PrimaryButton from "../PrimaryButton.vue";
import Add from "./Add.vue";
import Review from "./Review.vue";

const page = usePage();
const auth = computed(() => page.props.auth);

const props = defineProps({
    reviews: Array,
    gameId: Number,
});
const showAddReviewModal = ref(false);

const currentUserHasReview = computed(
    () =>
        !!props.reviews.find((review) => review.user.id === auth.value.user.id)
);

const openAddReviewModal = () => {
    showAddReviewModal.value = true;
};

const closeModal = () => {
    showAddReviewModal.value = null;
};
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

        <PrimaryButton v-if="!currentUserHasReview" @click="openAddReviewModal">
            Write a review
        </PrimaryButton>
    </div>
    <div v-else>
        <p class="text-sm">
            <Link :href="route('login')" class="text-primary"> Sign in </Link>
            to share your review with the community.
        </p>
    </div>

    <Modal :show="showAddReviewModal" @close="closeModal">
        <section class="flex w-full m-auto bg-dark-light">
            <Add :gameId="gameId" :closeModal="closeModal" />
        </section>
    </Modal>
</template>
