<script setup>
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    game: {
        type: Object,
        required: true,
    },
});
console.log(props.game);
</script>

<template>
    <Link
        :href="route('games.show', game.id)"
        class="relative overflow-hidden rounded-md shadow h-72 gameCard"
    >
        <div class="absolute w-full h-full rounded-md gameCard__border" />
        <img :src="game.cover_url" :alt="game.title" class="absolute z-[-2]" />
        <div
            class="absolute bottom-0 w-full h-20 z-[-1] gameCard__content flex justify-between flex-col p-2"
        >
            <h3 class="text-xs">
                {{ game.name.slice(0, 40) }}
            </h3>
            <div>
                <div class="flex items-center justify-end w-full gap-1 text-xs">
                    {{ game.total_rating }}
                    <i class="bx bxs-star" />
                </div>
                <div class="flex justify-between w-full text-xs">
                    <span class="text-gray-400">
                        {{ game.release_date }}
                    </span>
                    <div class="flex gap-2">
                        <div
                            v-if="game.platforms"
                            v-for="platform in game.platforms.slice(0, 4)"
                            :key="platform.id"
                            class="rounded-full gameCard__platform"
                        >
                            {{ platform.abbreviation }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Link>
</template>
