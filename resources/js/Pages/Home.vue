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
        <header class="w-full h-96">
            <div class="top-0 max-w-5xl p-4 mx-auto">
                <HeroSection />
            </div>
        </header>
        <main class="w-full">
            <div class="grid max-w-5xl grid-cols-3 p-4 mx-auto">
                <div class="flex flex-col items-center justify-center">
                    <h2 class="text-xl font-bold text-center">
                        Top-Rated Games
                    </h2>
                </div>
                <div class="flex flex-col items-center justify-center">
                    <h2 class="text-xl font-bold text-center">
                        Personalized Game Recommendations
                    </h2>
                </div>
                <div class="flex flex-col items-center justify-center">
                    <h2 class="text-xl font-bold text-center">User Reviews</h2>
                </div>
            </div>
        </main>
        <footer></footer>
    </BasicLayout>
</template>
