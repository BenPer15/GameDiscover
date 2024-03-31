<script setup>
import PrimaryButton from "@/Components/PrimaryButton.vue";
import BasicLayout from "@/Layouts/BasicLayout.vue";
import { Head, router, useForm } from "@inertiajs/vue3";
import { ref } from "vue";

const props = defineProps({
    game: Array,
    matureSynopsis: String,
    age: Number,
});

const isNotMature = ref(props.age !== 0 && props.age < 18);
const form = useForm({
    day: null,
    month: null,
    year: null,
});

const currentYear = new Date().getFullYear() + 1;

const submitForm = () => {
    const birthdate = `${form.year}-${form.month}-${form.day}`;
    router.post(route("settings.profil.birthdate"), {
        birthdate,
    });
};
</script>

<template>
    <Head :title="game.name" />

    <BasicLayout>
        {{ $age }}
        <div
            class="relative flex max-w-4xl px-3 pb-12 mx-auto border rounded-lg mt-28 border-dark-lighter"
        >
            <div class="flex flex-col max-w-2xl gap-4 mx-auto text-center">
                <div class="p-0 mb-4 -mt-12">
                    <img
                        :src="game.background"
                        :alt="game.name"
                        class="w-1/2 mx-auto rounded-md"
                    />
                </div>

                <h1 class="text-4xl font-bold">{{ game.name }}</h1>
                <h2 class="p-4 text-base">
                    THIS GAME MAY CONTAIN CONTENT NOT APPROPRIATE FOR ALL AGES,
                    OR MAY NOT BE APPROPRIATE FOR VIEWING AT WORK.
                </h2>
                <p v-if="matureSynopsis">{{ matureSynopsis }}</p>

                <div v-if="isNotMature" class="space-y-4">
                    <p class="text-sm">
                        Sorry, you are not old enough to view this content.
                    </p>
                    <PrimaryButton @click="() => router.get('/')"
                        >Back to home</PrimaryButton
                    >
                </div>
                <div v-else>
                    <h3 class="text-sm">
                        To continue, please enter your date of birth
                    </h3>
                    <div class="flex items-center justify-center gap-4">
                        <select
                            class="relative w-full py-3 my-3 text-xs bg-transparent rounded-md border-dark-lighter focus:border-dark-lighter focus:ring-0 placeholder:text-gray-400 placeholder:text-xs"
                            v-model="form.day"
                        >
                            <option :value="null" disabled selected>JJ</option>
                            <option v-for="day in 31" :key="day" :value="day">
                                {{ day }}
                            </option>
                        </select>

                        <select
                            class="relative w-full py-3 my-3 text-xs bg-transparent rounded-md border-dark-lighter focus:border-dark-lighter focus:ring-0 placeholder:text-gray-400 placeholder:text-xs"
                            v-model="form.month"
                        >
                            <option :value="null" disabled selected>MM</option>
                            <option
                                v-for="month in 12"
                                :key="month"
                                :value="month"
                            >
                                {{ month }}
                            </option>
                        </select>

                        <select
                            class="relative w-full py-3 my-3 text-xs bg-transparent rounded-md border-dark-lighter focus:border-dark-lighter focus:ring-0 placeholder:text-gray-400 placeholder:text-xs"
                            v-model="form.year"
                        >
                            <option :value="null" disabled selected>
                                AAAA
                            </option>
                            <option
                                v-for="year in 150"
                                :key="year"
                                :value="currentYear - year"
                            >
                                {{ currentYear - year }}
                            </option>
                        </select>
                    </div>

                    <PrimaryButton @click="submitForm">CONTINUE</PrimaryButton>
                </div>
            </div>
        </div>
    </BasicLayout>
</template>
