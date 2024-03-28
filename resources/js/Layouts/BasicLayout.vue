<script setup>
import Nav from "@/Components/Nav.vue";
import { usePage } from "@inertiajs/vue3";
import { ref, watch } from "vue";

const flashes = ref([]);
watch(
    () => usePage().props.flash,
    (next) => {
        flashes.value.push(next);
    }
);
</script>

<template>
    <header>
        <Nav />
    </header>
    <main role="main" class="h-auto pb-4">
        <div class="h-auto mx-auto max-w-7xl">
            <slot />
        </div>
    </main>
    <footer></footer>
    <flash-message
        v-if="flashes.length > 0"
        v-for="(flash, index) in flashes"
        :key="index"
        :message="flash.message"
        :type="flash.type"
    />
</template>
