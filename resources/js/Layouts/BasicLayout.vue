<script setup>
import Nav from "@/Components/Nav.vue";
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
</template>
