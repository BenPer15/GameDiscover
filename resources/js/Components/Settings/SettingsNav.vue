<script setup>
import ItemSettingNav from "@/Components/Settings/ItemSettingNav.vue";
const items = [
    {
        title: "Basic information",
        icon: "bx-user",
        route: route("settings.profile.edit"),
    },
    {
        title: "Security",
        icon: "bx-lock",
        route: route("settings.security.edit"),
    },
    {
        title: "Game Preferences",
        icon: "bx-joystick",
        route: route("settings.profile.edit"),
    },
    {
        title: "Notification Settings",
        icon: "bx-bell",
        route: route("settings.profile.edit"),
    },
    {
        title: "Social Media & Gaming Platforms",
        icon: "bx-link",
        route: route("settings.profile.edit"),
    },
    {
        title: "Advanced Options",
        icon: "bx-cog",
        route: route("settings.advanced"),
    },
];
</script>

<template>
    <div>
        <div class="flex flex-col gap-2 p-2 py-3 text-sm rounded-xl">
            <ItemSettingNav
                v-for="(item, index) in items"
                :key="index"
                :icon="item.icon"
                :route="item.route"
            >
                {{ item.title }}
            </ItemSettingNav>
        </div>
    </div>
</template>
