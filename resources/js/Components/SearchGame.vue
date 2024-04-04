<script setup>
import { Link } from "@inertiajs/vue3";
import axios from "axios";
import { ref } from "vue";

import PrimaryButton from "./PrimaryButton.vue";
import TextInput from "./TextInput.vue";

const searchQuery = ref("");
const results = ref([]);

let searchTimeout = null;
let cancelToken;

const searchGames = () => {
    if (searchQuery.value.length >= 2) {
        results.value = [];

        if (cancelToken) {
            cancelToken.cancel("Cancelled due to new request");
        }
        cancelToken = axios.CancelToken.source();

        if (searchTimeout !== null) {
            clearTimeout(searchTimeout);
        }

        searchTimeout = setTimeout(() => {
            axios
                .get("/api/search", {
                    params: { q: searchQuery.value },
                    cancelToken: cancelToken.token,
                })
                .then((response) => {
                    results.value = response.data;
                })
                .catch((error) => {
                    if (axios.isCancel(error)) {
                        console.log("Request cancelled", error.message);
                    } else {
                        console.error("Error :", error);
                    }
                });
        }, 300);
    } else {
        results.value = [];
    }
};
</script>

<template>
    <div class="relative flex gap-4 min-w-96">
        <TextInput
            type="text"
            v-model="searchQuery"
            @input="searchGames"
            placeholder="Search"
            icon="bx-search"
            classInput="!bg-dark-light border-none text-white placeholder:!text-white placeholder:font-normal"
        />
        <div
            v-if="searchQuery.length > 0"
            class="absolute z-30 w-full p-4 space-y-4 text-black bg-white shadow-lg rounded-xl top-12"
        >
            <div v-if="searchQuery.length < 2">
                Please enter 2 characters or more
            </div>
            <div v-else>
                <div v-if="results.length > 0" class="space-y-4">
                    <Link
                        v-for="(game, index) in results"
                        :key="index"
                        :href="route('games.show', game.id)"
                        class="flex items-center gap-4"
                    >
                        <img
                            :src="game.cover_url"
                            :alt="game.name"
                            class="h-16 rounded-md"
                        />
                        <span>
                            {{ game.name }} ({{ game.year_release_date }})
                            <ul class="flex flex-wrap text-xs text-gray-500">
                                <li
                                    class="flex"
                                    v-for="(platform, index) in game.platforms"
                                    :key="platform.id"
                                >
                                    {{ platform.abbreviation }}
                                    <span
                                        v-if="index < game.platforms.length - 1"
                                        class="mr-1"
                                        >,
                                    </span>
                                </li>
                            </ul>
                        </span>
                    </Link>
                </div>
                <div v-else>
                    <div>No results found</div>
                </div>
            </div>

            <div
                class="flex items-center justify-between w-full pt-2 mt-2 text-sm font-normal border-t"
            >
                <Link :href="route('games.searchAll', { q: searchQuery })">
                    See all results
                </Link>
                <PrimaryButton>Advanced Search</PrimaryButton>
            </div>
        </div>
    </div>
</template>
