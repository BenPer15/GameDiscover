<script setup>
import Nav from "@/Components/Nav.vue";
</script>

<template>
    <div class="h-dvh w-dvw">
        <Nav />
        <slot />
        <footer></footer>
    </div>
</template>
