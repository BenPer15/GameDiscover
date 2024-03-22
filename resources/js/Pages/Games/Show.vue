<script setup>
import { Head } from "@inertiajs/vue3";

import BasicLayout from "@/Layouts/BasicLayout.vue";

import Gallery from "@/Components/Gallery/Index.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Reviews from "@/Components/Reviews/Index.vue";
import ShowMore from "@/Components/ShowMore.vue";
import TwitchLive from "@/Components/TwitchLive.vue";

import GameActions from "./Partials/Actions/Index.vue";
import GameInfo from "./Partials/Info/Index.vue";

const props = defineProps({
    game: Array,
});

const getDeveloper = () => {
    return props.game.involved_companies.find((company) => company.developer)
        ?.company.name;
};

const showGallery = () => {
    return props.game.medias.length > 0;
};
</script>

<template>
    <Head :title="game.name + ' (' + game.year_release_date + ')'" />
    <BasicLayout>
        <div
            v-if="game.background"
            class="absolute top-0 left-0 w-full bg-center bg-no-repeat bg-cover h-[32rem]"
            :style="{ backgroundImage: 'url(' + game.background + ')' }"
        >
            <div
                class="absolute top-0 left-0 w-full h-full bg-gradient-to-b from-transparent to-dark"
            ></div>
        </div>

        <div class="relative flex px-3 mx-auto max-w-7xl">
            <div class="flex w-full gap-6 mt-48">
                <div class="flex flex-col items-center w-1/6 gap-4">
                    <div
                        class="relative overflow-hidden rounded-md shadow w-44 h-60"
                    >
                        <div
                            class="absolute z-10 w-full h-full rounded-md gameCard__border"
                        />
                        <img
                            :src="game.coverImg"
                            :alt="game.title"
                            class="object-cover w-full h-full"
                        />
                    </div>
                    <PrimaryButton class="w-full capitalize"
                        >Discover this game</PrimaryButton
                    >
                    <GameActions :game="game" />
                </div>

                <div class="flex flex-col w-full gap-4 mt-12">
                    <h1 class="text-4xl font-bold gameDetail_title">
                        {{ game.name }}
                    </h1>
                    <p class="text-base">
                        Released on
                        <span class="font-bold">{{ game.release_date }}</span>
                        by
                        <span class="font-bold">{{ getDeveloper() }}</span>
                    </p>

                    <div class="flex justify-between w-full gap-5 mt-5">
                        <div class="flex flex-col w-2/3 gap-4">
                            <GameInfo :game="game" />
                            <ShowMore
                                v-if="game.summary"
                                :text="game.summary"
                            />

                            <Reviews
                                :review="game?.reviews"
                                :gameId="game?.id"
                            />
                        </div>

                        <div
                            v-if="showGallery()"
                            class="flex flex-col w-1/3 px-4"
                        >
                            <TwitchLive
                                v-if="game.stream"
                                :stream="game.stream"
                                height="150"
                                width="520"
                            />
                            <Gallery :medias="game.medias" maxLength="500" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </BasicLayout>
</template>
