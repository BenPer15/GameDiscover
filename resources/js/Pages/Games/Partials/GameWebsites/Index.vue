<script setup>
import Logo from "./Logo.vue";

defineProps({
    websites: Array,
});
</script>

<template>
    <div class="w-full">
        <h2 class="text-lg font-semibold">Websites</h2>
        <div class="flex flex-wrap gap-2 mt-2">
            <a
                v-for="website in websites"
                :key="website.id"
                :href="website.url"
                class="text-blue-500 hover:underline"
                target="_blank"
            >
                <Logo :category="website.category" />
            </a>
        </div>
    </div>
</template>
