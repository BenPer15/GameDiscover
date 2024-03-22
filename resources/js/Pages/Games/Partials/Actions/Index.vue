<script setup>
import { router, usePage } from "@inertiajs/vue3";
import { defineProps, onUpdated, ref } from "vue";
import Button from "./Button.vue";

const props = defineProps({
    game: Object,
});

const game = ref(props.game);

const setStatus = (status) => {
    const data = {
        status: status === game.value.user?.status ? null : status,
        id: game.value.id,
    };
    if (game.value.user) {
        router.put(route("games.updateStatus", game.value.user.id), data, {
            only: ["game"],
        });
    } else {
        router.post(route("games.storeStatus"), data, { only: ["game"] });
    }
};

onUpdated(() => {
    game.value = usePage().props.game;
});
</script>

<template>
    <div class="flex rounded-lg bg-dark-light">
        <Button
            class="rounded-l-lg"
            icon="gift"
            :click="() => setStatus('wishlisted')"
            :active="game.user?.status === 'wishlisted'"
        >
            <span>Wishlist</span>
            {{ game.total_wishlisted }}
        </Button>

        <div class="py-2 my-auto border-r border-gray-600 h-2/3" />

        <Button
            :click="() => setStatus('playing')"
            icon="joystick"
            :game="game"
            :active="game.user?.status === 'playing'"
        >
            <span>Playing</span>
            {{ game.total_playing }}
        </Button>

        <div class="py-2 my-auto border-r border-gray-600 h-2/3" />

        <Button
            :click="() => setStatus('played')"
            class="rounded-r-lg"
            :game="game"
            icon="check-circle"
            :active="
                ['played', 'completed', 'aborded'].includes(game.user?.status)
            "
        >
            <span class="first-letter:uppercase">{{
                ["played", "completed", "aborded"].includes(game.user?.status)
                    ? game.user?.status
                    : "Played"
            }}</span>
            {{ game.total_played }}
        </Button>
    </div>
</template>
