<script setup>
const props = defineProps({
    category: Number,
});

const logoEnum = {
    1: {
        src: "official",
        icon: "bx-link-external",
        name: "Official Website",
    },
    2: { src: "fandom.png", name: "Community Wiki" },
    3: {
        src: "wiki.svg",
        bgColor: "bg-white",
        name: "Wikipedia",
    },
    4: {
        src: "facebook.png",
        bgColor: "bg-[#3b5998]",
        name: "Facebook",
        isNotResized: true,
    },
    5: {
        src: "x.png",
        bgColor: "bg-black",
        name: "Twitter",
    },
    6: {
        src: "twitch.png",
        bgColor: "bg-[#6441a5]",
        name: "Twitch",
    },
    8: { src: "instagram.png", name: "Instagram", isNotResized: true },
    9: {
        src: "youtube.svg",
        bgColor: "bg-[#FF0000]",
        name: "YouTube",
        isNotResized: true,
    },
    10: { src: "apple.png", name: "App Store (IPhone)" },
    11: { src: "apple.png", name: "App Store (IPad)" },
    12: { src: "googleplay.png", name: "Google Play" },
    13: {
        src: "steam.png",
        bgColor: "bg-[#1b2838]",
        name: "Steam",
    },
    14: {
        src: "reddit.png",
        bgColor: "bg-[#ff4502]",
        name: "Subreddit",
        isNotResized: true,
    },
    15: { src: "itch" },
    16: {
        src: "epic.png",
        bgColor: "bg-[#313131]",
        name: "Epic",
    },
    17: {
        src: "gog.png",
        bgColor: "bg-[#863a3a]",
        name: "GOG",
    },
    18: {
        src: "discord.jpg",
        bgColor: "bg-[#5765f3]",
        name: "Discord",
        isNotResized: true,
    },
};

const logo = logoEnum[props.category];
</script>

<template>
    <div class="flex items-center justify-start gap-2 text-xs text-white group">
        <div
            class="flex items-center justify-center w-6 h-6 p-1 no-underline rounded-md opacity-80 group-hover:opacity-100"
            :class="logo.bgColor ?? 'bg-dark-lighter'"
        >
            <i v-if="logo.icon" :class="'bx ' + logo.icon"></i>

            <img
                v-else
                v-bind:class="{ 'h-3': !logo.isNotResized }"
                :src="'../storage/logo/' + logo.src"
                :alt="logo.src"
            />
        </div>
        <span class="group-hover:text-gray-300"> {{ logo.name }}</span>
    </div>
</template>
