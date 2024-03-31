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
console.log(flashes);
</script>

<template>
    <header>
        <Nav />
    </header>
    <div class="max-w-full pb-8">
        <slot />
    </div>

    <footer></footer>
    <flash-message
        v-if="flashes.length > 0"
        v-for="(flash, index) in flashes"
        :key="index"
        :flash="flash"
    />
</template>
