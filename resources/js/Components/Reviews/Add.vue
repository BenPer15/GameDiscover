<script setup>
import { router, usePage } from "@inertiajs/vue3";
import { computed, ref } from "vue";

import { reactive } from "vue";
import PrimaryButton from "../PrimaryButton.vue";
import TextInput from "../TextInput.vue";

const props = defineProps({
    gameId: Number,
});

const loadingSave = ref(false);
const page = usePage();
const auth = computed(() => page.props.auth);

const form = reactive({
    content: null,
    igdb_id: props.gameId,
});

const userReview = computed(() => {
    return page.props.game.reviews.find(
        ({ user }) => user.id === auth.value.user.id
    );
});

function submit() {
    loadingSave.value = true;
    if (!!userReview.value) {
        router.put(route("games.updateReview", userReview.value.id), form, {
            onFinish: () => (loadingSave.value = false),
        });
    } else {
        router.post(route("games.storeReview"), form, {
            onFinish: () => (loadingSave.value = false),
        });
    }
}
</script>

<template>
    <form @submit.prevent="submit" class="flex items-center gap-2">
        <img
            :src="
                'https://api.dicebear.com/8.x/bottts-neutral/svg?seed=' +
                auth.user.name
            "
            alt="avatar"
            class="w-8 h-8 rounded-full"
        />

        <TextInput v-model="form.content" placeholder="Add your review" />
        <PrimaryButton
            type="submit"
            class="flex items-center justify-center w-8 h-8 !rounded-full"
            :class="{ 'opacity-55': loadingSave }"
        >
            <i v-if="!loadingSave" class="bx bx-pencil"></i>
            <i v-else class="bx bx-loader-alt animate-spin"></i>
        </PrimaryButton>
    </form>
</template>
