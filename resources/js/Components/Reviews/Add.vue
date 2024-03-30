<script setup>
import { useForm, usePage } from "@inertiajs/vue3";
import { computed, ref } from "vue";

import GameCover from "../GameCover.vue";
import InputError from "../InputError.vue";
import InputLabel from "../InputLabel.vue";
import PrimaryButton from "../PrimaryButton.vue";
import SecondaryButton from "../SecondaryButton.vue";

const props = defineProps({
    gameId: Number,
    closeModal: Function,
    editedReview: {
        type: Object,
        default: null,
    },
});

const loadingSave = ref(false);
const page = usePage();
const auth = computed(() => page.props.auth);

const form = useForm({
    platform: props.editedReview?.platform || null,
    content: props.editedReview?.content || "",
    igdb_id: props.gameId,
});

const userReview = computed(() => {
    return page.props.game.reviews.find(
        ({ user }) => user.id === auth.value.user.id
    );
});

function submit() {
    loadingSave.value = true;
    if (props.editedReview) {
        form.put(route("games.updateReview", props.editedReview.id), {
            onFinish: () => {
                loadingSave.value = false;
                props.closeModal();
            },
        });
    } else {
        form.post(route("games.storeReview"), {
            onFinish: () => {
                loadingSave.value = false;
                props.closeModal();
            },
        });
    }
}
</script>

<template>
    <div class="flex flex-col w-full h-full gap-4 p-4 text-white">
        <div
            class="flex items-start gap-4 p-4 border border-dark-lighter rounded-xl"
        >
            <GameCover
                :url="page.props.game.coverImg"
                :title="page.props.game.name"
                size="w-24 h-32"
            />
            <h1 class="text-2xl font-bold">
                {{ page.props.game.name }}
                <span class="text-base"
                    >({{ page.props.game.year_release_date }})</span
                >
            </h1>
        </div>

        <form @submit.prevent="submit" class="">
            <div
                class="flex flex-col items-start gap-4 p-4 border border-dark-lighter rounded-xl"
            >
                <div class="w-full">
                    <InputLabel
                        for="platform"
                        value="Platform"
                        class="text-xs text-white"
                    />
                    <select
                        class="w-full gap-2 text-xs font-normal text-white border-none shadow-sm rounded-xl bg-dark-lighter focus:border-transparent focus:ring-0"
                        id="platform"
                        v-model="form.platform"
                    >
                        <option :value="null" disabled selected>
                            Select a platform
                        </option>
                        <option
                            v-for="platform in page.props.game.platforms"
                            :value="platform.abbreviation"
                            :key="platform.id"
                        >
                            {{ platform.abbreviation }}
                        </option>
                    </select>
                </div>

                <div class="w-full">
                    <InputLabel
                        for="content"
                        value="Review"
                        class="text-xs text-white"
                    />
                    <textarea
                        class="w-full gap-2 text-xs font-normal text-white border-none shadow-sm resize-none rounded-xl bg-dark-lighter focus:border-transparent focus:ring-0 placeholder:text-gray-400 placeholder:text-xs"
                        id="content"
                        type="textarea"
                        v-model="form.content"
                        rows="5"
                        onInput="this.parentNode.dataset.clonedVal = this.value"
                        placeholder="Share your thoughts about this game"
                    />
                    <InputError class="mt-2" :message="form.errors.content" />
                </div>
            </div>

            <div class="flex items-start gap-4 p-4">
                <SecondaryButton @click="closeModal">Cancel</SecondaryButton>
                <PrimaryButton
                    type="submit"
                    :class="{ 'opacity-55': loadingSave }"
                    :disabled="loadingSave"
                >
                    <i v-if="loadingSave" class="animate-spin bx bx-loader"></i>
                    Review this game
                </PrimaryButton>
            </div>
        </form>
    </div>
</template>
