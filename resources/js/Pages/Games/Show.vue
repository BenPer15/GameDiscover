<script setup>
import { Head, usePage } from "@inertiajs/vue3";
import { defineAsyncComponent, onMounted } from "vue";

import GameCover from "@/Components/GameCover.vue";
import ShowMore from "@/Components/ShowMore.vue";
import { useGameData } from "@/Composables/useGameData";
import { useGameMedias } from "@/Composables/useGameMedias";
import { useGameUserInteractions } from "@/Composables/useGameUserInteractions";
import BasicLayout from "@/Layouts/BasicLayout.vue";

const Gallery = defineAsyncComponent(() =>
    import("@/Components/Gallery/Index.vue")
);
const Reviews = defineAsyncComponent(() =>
    import("@/Components/Reviews/Index.vue")
);

import TwitchLive from "@/Components/TwitchLive.vue";
import GameInteractions from "./Partials/GameInteractions/Index.vue";
import GameInfo from "./Partials/Info/Index.vue";

const props = defineProps({
    game_id: String,
});

const page = usePage();
const authUser = page.props.auth.user;

const { game, background, isLoading, error, fetchGameData } = useGameData(
    props.game_id
);
const { medias, fetchGameMedias } = useGameMedias(props.game_id);
const { userInteractions, fetchGameUserInteractions } = useGameUserInteractions(
    props.game_id
);

onMounted(() => {
    fetchGameData();
    fetchGameMedias();
    fetchGameUserInteractions();
});
const getDeveloper = (involved_companies) =>
    involved_companies?.find((company) => company.developer)?.company.name;
</script>

<template>
    <BasicLayout>
        <div
            v-if="isLoading || !game"
            class="absolute top-0 left-0 flex items-center justify-center w-full h-full"
        >
            <i class="text-4xl bx bx-loader-alt animate-spin" />
        </div>
        <div v-else-if="error">Une erreur est survenue.</div>
        <div v-else>
            <Head :title="game.name + ' (' + game.year_release_date + ')'" />
            <div
                v-show="background"
                class="absolute top-0 left-0 w-full bg-center bg-no-repeat bg-cover h-[32rem]"
                :style="{ backgroundImage: 'url(' + background + ')' }"
            >
                <div
                    class="absolute top-0 left-0 w-full h-full bg-gradient-to-b from-transparent to-dark"
                ></div>
            </div>

            <div class="relative flex px-3 mx-auto max-w-7xl">
                <div class="flex w-full gap-6 mt-48">
                    <div class="flex flex-col items-center w-1/6 gap-4">
                        <GameCover :url="game.cover_url" :title="game.name" />
                        <GameInteractions
                            :gameUserInteractions="userInteractions"
                            :igdbId="game.id"
                        />
                    </div>

                    <div class="flex flex-col w-full gap-4 mt-12">
                        <div class="flex items-start justify-between gap-5">
                            <div class="w-2/3">
                                <h1 class="text-4xl font-bold gameDetail_title">
                                    {{ game.name }}
                                </h1>
                                <p class="text-base">
                                    Released on
                                    <span class="font-bold">{{
                                        game.release_date
                                    }}</span>
                                    by
                                    <span class="font-bold">{{
                                        getDeveloper(game.involved_companies)
                                    }}</span>
                                </p>
                            </div>
                            <!-- <div
                            v-if="game.total_sentiment_score"
                            class="flex items-center justify-center w-1/3"
                        >
                            <div
                                class="flex flex-col items-center justify-center w-24 h-24 gap-1 font-bold border-4 rounded-full"
                                :class="'border-' +
                                    scoreColor +
                                    ' text-' +
                                    scoreColor
                                "
                            >
                                <i class="text-2xl bx" :class="scoreIcon" />
                                <span> {{ scoreLabel }}</span>
                            </div>
                        </div> -->
                        </div>

                        <div class="flex justify-between w-full gap-5 mt-5">
                            <div class="flex flex-col w-2/3 gap-4">
                                <GameInfo :game="game" />
                                <ShowMore
                                    v-if="game.summary"
                                    :text="game.summary"
                                />
                                <GameWebsites :gameId="game.id" />
                                <Reviews :game="game" />
                            </div>

                            <div class="flex flex-col w-1/3 px-4">
                                <TwitchLive
                                    :gameId="game.id"
                                    height="150"
                                    width="520"
                                />
                                <Gallery :medias="medias" maxLength="500" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </BasicLayout>
</template>
