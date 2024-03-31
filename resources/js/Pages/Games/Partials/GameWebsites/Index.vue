<script setup>
import Logo from "./Logo.vue";

defineProps({
    websites: Array,
});
</script>

<template>
    <div class="w-full">
        <h2 class="text-lg font-semibold">Websites</h2>
        <div class="flex flex-col gap-2 mt-2">
            <a
                v-for="website in websites.sort(
                    (a, b) => a.category - b.category
                )"
                :key="website.id"
                :href="website.url"
                target="_blank"
            >
                <Logo :category="website.category" />
            </a>
        </div>
    </div>
</template>
