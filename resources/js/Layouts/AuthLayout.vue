<script setup>
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
</script>

<template>
    <div class="flex items-center justify-center w-dvw h-dvh">
        <div class="flex w-2/3 shadow-xl rounded-xl h-2/3">
            <div
                class="flex items-center justify-center w-1/3 h-full bg-gradient-to-t from-black to-dark rounded-s-lg"
            >
                <ApplicationLogo />
            </div>

            <div class="flex items-center justify-center w-2/3 bg-dark">
                <slot />
            </div>
        </div>
    </div>
</template>
