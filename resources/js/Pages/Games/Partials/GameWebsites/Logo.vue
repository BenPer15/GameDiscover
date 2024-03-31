<script setup>
const props = defineProps({
    category: Number,
});

const logoEnum = {
    1: {
        src: "official",
        icon: "bx-link-external",
        color: "text-white",
    },
    2: { src: "fandom.png" },
    3: { src: "wiki.svg", bgColor: "bg-white", color: "text-white" },
    4: { src: "facebook.png" },
    5: { src: "x.png" },
    6: { src: "twitch.png" },
    8: { src: "instagram.png" },
    9: { src: "youtube.svg", bgColor: "bg-[#FF0000]" },
    10: { src: "apple.png" },
    11: { src: "apple.png" },
    12: { src: "googleplay.png" },
    13: { src: "steam.png" },
    14: { src: "reddit.png", bgColor: "bg-[#ff4502]" },
    15: { src: "itch" },
    16: { src: "epic.png" },
    17: { src: "gog.png" },
    18: { src: "discord.jpg", bgColor: "bg-[#5765f3]" },
};

const logo = logoEnum[props.category];
</script>

<template>
    <div
        class="flex items-center justify-center w-8 h-8 p-1 no-underline rounded-md"
        :class="logo.bgColor ?? 'bg-dark-lighter'"
    >
        <i v-if="logo.icon" :class="logo.color + ' bx ' + logo.icon"></i>

        <img
            v-else
            :src="'../storage/logo/' + logo.src"
            class="rounded-md"
            :alt="logo.src"
        />
    </div>
</template>
