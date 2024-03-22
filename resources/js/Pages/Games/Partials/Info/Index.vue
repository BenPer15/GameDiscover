<script setup>
import { computed } from "vue";

const props = defineProps({
    game: Object,
});

const sortedGenres = computed(() => {
    return props.game.genres?.sort((a, b) => a.name.localeCompare(b.name));
});
</script>

<template>
    <div class="flex flex-col gap-2 text-2xs">
        <div class="flex items-center gap-4" v-if="game.platforms">
            <span class="w-20 font-bold uppercase">Available on</span>
            <div class="flex gap-2">
                <template v-for="platform in game.platforms">
                    <span>
                        {{ platform.abbreviation }}
                    </span>
                </template>
            </div>
        </div>
        <div class="flex items-center gap-4" v-if="sortedGenres">
            <span class="w-20 font-bold uppercase">Genres</span>
            <div class="flex gap-2">
                <template v-for="genre in sortedGenres" :key="genre.id">
                    <span
                        class="px-2 py-1 font-semibold bg-gray-800 rounded-md"
                    >
                        {{ genre.name }}
                    </span>
                </template>
            </div>
        </div>
        <div class="flex items-center gap-4" v-if="game.game_modes">
            <span class="w-20 font-bold uppercase">Game Modes</span>
            <div class="flex gap-2">
                <template v-for="mode in game.game_modes"
                    ><span
                        class="px-2 py-1 font-semibold bg-gray-800 rounded-md"
                    >
                        {{ mode.name }}
                    </span>
                </template>
            </div>
        </div>
    </div>
</template>
