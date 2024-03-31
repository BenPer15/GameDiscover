<script setup>
import { Link, usePage } from "@inertiajs/vue3";
import { computed } from "vue";
import ApplicationLogo from "./ApplicationLogo.vue";
import PrimaryButton from "./PrimaryButton.vue";
import SearchGame from "./SearchGame.vue";
import SecondaryButton from "./SecondaryButton.vue";

const page = usePage();
const auth = computed(() => page.props.auth);
</script>

<template>
    <nav
        class="relative z-20 text-white bg-gradient-to-b from-dark from-0% via-transparent via-100%"
    >
        <div
            class="flex items-center justify-between px-4 py-2 mx-auto rounded-full max-w-7xl"
        >
            <div class="flex items-center gap-4 font-bold">
                <ApplicationLogo />
                <SearchGame />
            </div>

            <div class="space-x-4" v-if="!auth.user">
                <Link :href="route('login')">
                    <PrimaryButton>Log in </PrimaryButton>
                </Link>
                <Link :href="route('register')">
                    <SecondaryButton> Register</SecondaryButton>
                </Link>
            </div>
            <div v-else class="flex items-center justify-end gap-2">
                <SecondaryButton class="p-2 text-base w-11 h-11">
                    <i class="bx bx-chat"></i>
                </SecondaryButton>

                <SecondaryButton class="p-2 text-base w-11 h-11">
                    <i class="bx bx-library"></i>
                </SecondaryButton>

                <SecondaryButton class="p-2 mr-2 text-base w-11 h-11">
                    <i class="bx bx-bell" />
                </SecondaryButton>

                <Link :href="route('settings.profile.edit')">
                    <img
                        :src="
                            'https://api.dicebear.com/8.x/adventurer-neutral/svg?seed=' +
                            auth.user.name
                        "
                        alt="avatar"
                        class="rounded-md w-11 h-11"
                    />
                </Link>
            </div>
        </div>
    </nav>
</template>
