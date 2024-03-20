<script setup>
import SettingsNav from "@/Components/Settings/SettingsNav.vue";
import BasicLayout from "./BasicLayout.vue";
</script>
<template>
    <BasicLayout>
        <div
            class="grid grid-flow-row-dense grid-cols-4 gap-4 py-12 mx-auto max-w-7xl"
        >
            <SettingsNav />
            <div class="w-full col-span-3 p-4 rounded-lg shadow">
                <slot />
            </div>
        </div>
    </BasicLayout>
</template>
