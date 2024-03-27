<script setup>
import { router, usePage } from "@inertiajs/vue3";
import { defineProps, onUpdated, ref } from "vue";
import Button from "./Button.vue";

const props = defineProps({
    gameUserInteractions: Object,
    igdbId: Number,
});

const gUI = ref(props.gameUserInteractions);

const setStatus = (status) => {
    const data = {
        status: status === gUI.value.currentUser?.status ? null : status,
        igdb_id: props.igdbId,
    };
    if (gUI.value.currentUser) {
        router.put(
            route("games.updateStatus", gUI.value.currentUser.id),
            data,
            {
                only: ["gameUserInteraction"],
            }
        );
    } else {
        router.post(route("games.storeStatus"), data, {
            only: ["gameUserInteraction"],
        });
    }
};

onUpdated(() => {
    gUI.value = usePage().props.gameUserInteraction;
});
</script>

<template>
    <div class="flex rounded-lg bg-dark-light">
        <Button
            class="rounded-l-lg"
            icon="gift"
            :click="() => setStatus('wishlisted')"
            :active="gUI.currentUser?.status === 'wishlisted'"
        >
            <span>Wishlist</span>
            {{ gUI.totalWishlisted }}
        </Button>

        <div class="py-2 my-auto border-r border-gray-600 h-2/3" />

        <Button
            :click="() => setStatus('playing')"
            icon="joystick"
            :game="gUI"
            :active="gUI.currentUser?.status === 'playing'"
        >
            <span>Playing</span>
            {{ gUI.totalPlaying }}
        </Button>

        <div class="py-2 my-auto border-r border-gray-600 h-2/3" />

        <Button
            :click="() => setStatus('played')"
            class="rounded-r-lg"
            :game="game"
            icon="check-circle"
            :active="
                ['played', 'completed', 'aborded'].includes(
                    gUI.currentUser?.status
                )
            "
        >
            <span class="first-letter:uppercase">{{
                ["played", "completed", "aborded"].includes(
                    gUI.currentUser?.status
                )
                    ? gUI.currentUser?.status
                    : "Played"
            }}</span>
            {{ gUI.totalPlayed }}
        </Button>
    </div>
</template>
