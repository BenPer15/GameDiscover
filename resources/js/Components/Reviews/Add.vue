<script setup>
import { useForm, usePage } from "@inertiajs/vue3";
import { computed, ref } from "vue";

import Checkbox from "../Checkbox.vue";
import GameCover from "../GameCover.vue";
import InputLabel from "../InputLabel.vue";
import PrimaryButton from "../PrimaryButton.vue";
import SecondaryButton from "../SecondaryButton.vue";

const props = defineProps({
    game: Object,
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
    isSpoiler: props.editedReview?.is_spoiler || false,
    igdb_id: props.game.id,
});

const userReview = computed(() => {
    return props.game.reviews.find(
        ({ user }) => user.id === auth.value.user.id
    );
});

function submit() {
    loadingSave.value = true;
    if (props.editedReview) {
        form.put(route("games.updateReview", props.editedReview.id), {
            onFinish: () => {
                loadingSave.value = false;
            },
            onSuccess: () => {
                props.closeModal();
            },
        });
    } else {
        form.post(route("games.storeReview"), {
            onFinish: () => {
                loadingSave.value = false;
            },
            onSuccess: () => {
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
                :url="game.cover_url"
                :title="game.name"
                size="w-24 h-32"
            />
            <div class="flex flex-col w-full gap-2">
                <div class="text-2xl font-bold">
                    {{ game.name }}
                    <span class="text-base"
                        >({{ game.year_release_date }})</span
                    >
                </div>
                <div class="text-xs">
                    {{ game.summary }}
                </div>
            </div>
        </div>

        <form @submit.prevent="submit" class="">
            <div
                class="flex flex-col items-start gap-2 p-4 border border-dark-lighter rounded-xl"
            >
                <div class="w-full">
                    <InputLabel
                        for="platform"
                        value="Platform"
                        class="text-xs text-white bg-dark-light"
                    >
                        <select
                            class="relative w-full py-3 my-3 text-xs bg-transparent rounded-md border-dark-lighter focus:border-dark-lighter focus:ring-0 placeholder:text-gray-400 placeholder:text-xs"
                            id="platform"
                            v-model="form.platform"
                        >
                            <option :value="null" disabled selected>
                                Select a platform
                            </option>
                            <option
                                v-for="platform in game.platforms"
                                :value="platform.abbreviation"
                                :key="platform.id"
                            >
                                {{ platform.abbreviation }}
                            </option>
                        </select>
                    </InputLabel>
                </div>

                <div class="w-full">
                    <InputLabel
                        for="content"
                        value="Review"
                        class="text-xs text-white bg-dark-light"
                    >
                        <textarea
                            class="relative w-full py-3 my-3 text-xs bg-transparent rounded-md border-dark-lighter focus:border-dark-lighter focus:ring-0 placeholder:text-gray-400 placeholder:text-x"
                            id="content"
                            type="textarea"
                            v-model="form.content"
                            rows="5"
                            onInput="this.parentNode.dataset.clonedVal = this.value"
                            placeholder="Share your thoughts about this game"
                        />
                    </InputLabel>
                </div>

                <div class="flex items-center justify-center gap-2 text-xs">
                    <Checkbox
                        id="isSpoiler"
                        :checked="form.isSpoiler"
                        v-model="form.isSpoiler"
                        :value="true"
                    />
                    <label for="isSpoiler">This review contains spoilers</label>
                </div>

                <div v-if="Object.keys(form.errors).length">
                    <ul class="text-xs text-red-500">
                        <li v-for="error in form.errors" :key="error">
                            {{ error }}
                        </li>
                    </ul>
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
