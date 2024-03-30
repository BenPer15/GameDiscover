<script setup>
import HeroSection from "@/Components/HeroSection.vue";
import BasicLayout from "@/Layouts/BasicLayout.vue";
import { Head } from "@inertiajs/vue3";

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
});
</script>

<template>
    <Head title="Home" />
    <BasicLayout>
        <main>
            <header class="w-full h-96">
                <div class="top-0 p-4 mx-auto max-w-7xl">
                    <HeroSection />
                </div>
            </header>
            <article class="relative">
                <section></section>
            </article>
        </main>
    </BasicLayout>
</template>
