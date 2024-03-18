<script setup>
import { Link } from "@inertiajs/vue3";
import SecondaryButton from "./SecondaryButton.vue";

const props = defineProps({
    href: {
        type: String,
        required: true,
    },
    active: {
        type: Boolean,
        required: false,
    },
    asc: {
        type: Boolean,
        required: false,
    },
});
</script>

<template>
    <Link :href="href">
        <SecondaryButton size="xs" :active="active" class="px-3 py-1">
            <slot />
            <div v-if="active">
                <span v-if="asc" class="ml-1"
                    ><i class="bx bx-caret-up"></i
                ></span>
                <span v-else class="ml-1"
                    ><i class="bx bx-caret-down"></i
                ></span>
            </div>
        </SecondaryButton>
    </Link>
</template>
