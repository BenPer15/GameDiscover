<script setup>
import GameActions from "@/Components/Game/Actions/Index.vue";
import BasicLayout from "@/Layouts/BasicLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { computed } from "vue";

const props = defineProps({
    game: Array,
});

const getDeveloper = () => {
    return props.game.involved_companies.find((company) => company.developer)
        ?.company.name;
};

const sortedGenres = computed(() => {
    return props.game.genres.sort((a, b) => a.name.localeCompare(b.name));
});
</script>

<template>
    <Head :title="game.name" />
    <BasicLayout>
        <div>
            <div
                class="absolute top-0 left-0 w-full bg-center bg-no-repeat bg-cover h-[30rem]"
                :style="{ backgroundImage: 'url(' + game.background + ')' }"
            >
                <div
                    class="absolute top-0 left-0 w-full h-full backdrop-blur-sm"
                />
                <div
                    class="absolute top-0 left-0 w-full h-full bg-gradient-to-b from-transparent from-90% to-dark"
                ></div>
            </div>

            <div class="relative flex mx-auto max-w-7xl">
                <div class="flex w-full gap-6 mt-48">
                    <div
                        class="flex flex-col items-center justify-center w-1/6 gap-4"
                    >
                        <div
                            class="relative overflow-hidden rounded-md shadow w-44 h-60"
                        >
                            <div
                                class="absolute z-10 w-full h-full rounded-md gameCard__border"
                            />
                            <img
                                :src="game.coverImg"
                                :alt="game.title"
                                class="absolute object-cover w-full h-full"
                            />
                        </div>

                        <GameActions :game="game" />
                    </div>

                    <div class="flex flex-col w-full gap-4 mt-24">
                        <h1 class="text-4xl font-bold gameDetail_title">
                            {{ game.name }}
                        </h1>
                        <div>
                            <Link
                                v-for="genre in sortedGenres"
                                class="px-2 py-1 mr-2 text-xs font-bold lowercase bg-white rounded-xl text-dark"
                            >
                                <span class="text-primary">#</span>
                                {{ genre.name }}
                            </Link>
                        </div>
                        <p class="text-base">
                            Released on
                            <span class="font-bold">{{
                                game.release_date
                            }}</span>
                            by
                            <span class="font-bold">{{ getDeveloper() }}</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </BasicLayout>
</template>
