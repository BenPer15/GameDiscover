<script setup>
import { usePage } from "@inertiajs/vue3";
import { computed } from "vue";

import PrimaryButton from "../PrimaryButton.vue";
import TextInput from "../TextInput.vue";

defineProps({
    gameId: Number,
});

const page = usePage();
const auth = computed(() => page.props.auth);
</script>

<template>
    <form class="flex items-center gap-2">
        <img
            :src="
                'https://api.dicebear.com/8.x/bottts-neutral/svg?seed=' +
                auth.user.name
            "
            alt="avatar"
            class="w-8 h-8 rounded-full"
        />

        <TextInput placeholder="Add your review" />
        <PrimaryButton
            class="flex items-center justify-center w-8 h-8 !rounded-full"
        >
            <i class="bx bx-pencil"></i>
        </PrimaryButton>
    </form>
</template>
