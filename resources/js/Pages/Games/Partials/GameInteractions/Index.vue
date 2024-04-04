<script setup>
import { defineProps, ref, watch } from "vue";
import Button from "./Button.vue";

const props = defineProps({
    gameUserInteractions: Object,
    igdbId: Number,
});

const userInteractions = ref(props.gameUserInteractions);

watch(
    () => props.gameUserInteractions,
    (newVal) => {
        userInteractions.value = newVal;
    }
);

const setStatus = (status) => {
    const data = {
        status:
            status === userInteractions.value.currentUser?.status
                ? null
                : status,
        igdb_id: props.igdbId,
    };

    if (userInteractions.value.currentUser) {
        axios
            .put(
                route(
                    "games.updateStatus",
                    userInteractions.value.currentUser.id
                ),
                data
            )
            .then((response) => {
                userInteractions.value = response.data;
            });
    } else {
        axios.post(route("games.storeStatus"), data).then((response) => {
            userInteractions.value = response.data;
        });
    }
};
</script>

<template>
    {{ gUI }}
    <div class="flex rounded-lg bg-dark-light">
        <Button
            class="gap-1 rounded-l-lg"
            icon="gift"
            :click="() => setStatus('wishlisted')"
            :active="userInteractions?.currentUser?.status === 'wishlisted'"
        >
            <span>Wishlist</span>
            {{ userInteractions.totalWishlisted }}
        </Button>

        <div class="py-2 my-auto border-r border-dark-lighter h-2/3" />

        <Button
            :click="() => setStatus('playing')"
            class="gap-1"
            icon="joystick"
            :game="userInteractions"
            :active="userInteractions.currentUser?.status === 'playing'"
        >
            <span>Playing</span>
            {{ userInteractions.totalPlaying }}
        </Button>

        <div class="py-2 my-auto border-r border-dark-lighter h-2/3" />

        <Button
            :click="() => setStatus('played')"
            class="gap-1 rounded-r-lg"
            :game="game"
            icon="check-circle"
            :active="
                ['played', 'completed', 'aborded'].includes(
                    userInteractions.currentUser?.status
                )
            "
        >
            <span class="first-letter:uppercase">{{
                ["played", "completed", "aborded"].includes(
                    userInteractions.currentUser?.status
                )
                    ? userInteractions.currentUser?.status
                    : "Played"
            }}</span>
            {{ userInteractions.totalPlayed }}
        </Button>
    </div>
</template>
