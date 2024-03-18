<script setup>
import GameInfoDetail from "@/Components/GameInfoDetail.vue";

import BasicLayout from "@/Layouts/BasicLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { Transition, computed } from "vue";

const props = defineProps({
    game: {
        type: Array,
        required: true,
    },
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
        <div class="relative">
            <div
                class="absolute top-0 left-0 w-full bg-center bg-no-repeat bg-cover h-96"
                :style="{ backgroundImage: 'url(' + game.background + ')' }"
            >
                <div
                    class="absolute top-0 left-0 w-full h-full backdrop-blur"
                />
            </div>

            <div class="relative flex max-w-5xl mx-auto">
                <div class="flex gap-6 mt-40">
                    <div
                        class="bg-center bg-cover border border-white rounded-full shadow-lg w-60 h-60 bg-dark"
                        :style="{ backgroundImage: 'url(' + game.cover + ')' }"
                    />

                    <div class="flex flex-col max-w-2xl mt-12 space-y-4">
                        <h1 class="text-5xl font-bold gameDetail_title">
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
                    </div>
                </div>
            </div>
        </div>

        <Transition name="fade" mode="out-in">
            <div key="activeTab">
                <div class="grid max-w-5xl grid-cols-2 gap-4 mx-auto mt-8">
                    <div
                        class="flex flex-col gap-2 p-4 mt-4 text-sm bg-white rounded-xl text-dark h-fit"
                    >
                        <GameInfoDetail type="Genre">
                            <div class="flex flex-wrap justify-end gap-2">
                                <Link
                                    v-for="genre in sortedGenres"
                                    class="px-2 py-1 text-xs font-bold bg-white shadow rounded-xl text-dark"
                                >
                                    <span class="text-primary">#</span>
                                    {{ genre.name }}
                                </Link>
                            </div>
                        </GameInfoDetail>
                        <GameInfoDetail type="Release Date">
                            {{ game.release_date }}
                        </GameInfoDetail>
                        <GameInfoDetail type="Developer">
                            <span class="font-bold">{{ getDeveloper() }}</span>
                        </GameInfoDetail>
                        <GameInfoDetail type="Platforms">
                            <div class="flex flex-col justify-end text-end">
                                <Link v-for="platform in game.platforms">
                                    {{ platform.abbreviation }}
                                </Link>
                            </div>
                        </GameInfoDetail>
                    </div>
                </div>

                <div class="relative py-4 mt-8 overflow-hidden bg-white">
                    <div
                        class="flex h-full gap-4 overflow-x-auto"
                        ref="carousel"
                    >
                        <div
                            class="flex-[0_0_auto] h-full overflow-hidden"
                            v-for="(image, index) in game.images"
                            :key="index"
                        >
                            <div>
                                <img :src="image" :alt="index" class="h-80" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </Transition>
    </BasicLayout>
</template>
