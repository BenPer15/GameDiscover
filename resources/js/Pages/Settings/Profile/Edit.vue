<script setup>
import SettingsLayout from "@/Layouts/SettingsLayout.vue";
import { Head } from "@inertiajs/vue3";
import UpdateProfileInformationForm from "./UpdateProfileInformationForm.vue";

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});
</script>

<template>
    <Head title="Basic information" />

    <SettingsLayout>
        <div class="w-full">
            <UpdateProfileInformationForm
                :must-verify-email="mustVerifyEmail"
                :status="status"
                class="max-w-xl"
            />
        </div>
    </SettingsLayout>
</template>
./UpdateProfileInformationForm.vue
