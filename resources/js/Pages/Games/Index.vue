<script setup>
import GameCard from "@/Components/GameCard.vue";
import SortButton from "@/Components/SortButton.vue";
import BasicLayout from "@/Layouts/BasicLayout.vue";
import { Head } from "@inertiajs/vue3";

const props = defineProps({
    games: {
        type: Array,
        required: true,
    },
    query: {
        type: String,
        required: true,
    },
    sort: {
        type: String,
        required: true,
    },
    asc: {
        type: Boolean,
        required: true,
    },
});
</script>

<template>
    <Head :title="query" />
    <BasicLayout>
        <div class="mx-auto my-12 max-w-7xl">
            <div class="flex justify-between mb-2">
                <span class="text-sm text-gray-500"
                    >{{ games.length }} results for "{{ query }}"
                </span>
                <div class="flex gap-2">
                    <SortButton
                        :href="
                            route('games.searchAll', {
                                q: query,
                                sort: null,
                                asc: asc === '1' ? null : 1,
                            })
                        "
                        :asc="asc === '1'"
                        :active="sort === null"
                    >
                        Rating
                    </SortButton>
                    <SortButton
                        :href="
                            route('games.searchAll', {
                                q: query,
                                sort: 'name',
                                asc: asc === '1' ? null : 1,
                            })
                        "
                        :asc="asc === '1'"
                        :active="sort === 'name'"
                    >
                        Name
                    </SortButton>
                    <SortButton
                        :href="
                            route('games.searchAll', {
                                q: query,
                                sort: 'first_release_date',
                                asc: asc === '1' ? null : 1,
                            })
                        "
                        :asc="asc === '1'"
                        :active="sort === 'first_release_date'"
                    >
                        Release date
                    </SortButton>
                </div>
            </div>
            <div class="grid grid-cols-6 gap-4">
                <GameCard v-for="game in games" :key="game.id" :game="game" />
            </div>
        </div>
    </BasicLayout>
</template>
