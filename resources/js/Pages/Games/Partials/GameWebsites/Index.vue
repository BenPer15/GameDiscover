<script setup>
import Logo from "./Logo.vue";

defineProps({
    websites: Array,
});
</script>

<template>
    <div class="w-full my-8">
        <div class="flex flex-wrap gap-2 mt-2">
            <a
                v-for="website in websites.sort(
                    (a, b) => a.category - b.category
                )"
                :key="website.id"
                :href="website.url"
                target="_blank"
                class="w-1/5"
            >
                <Logo :category="website.category" />
            </a>
        </div>
    </div>
</template>
