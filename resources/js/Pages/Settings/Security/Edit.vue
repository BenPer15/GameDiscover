<script setup>
import SettingsLayout from "@/Layouts/SettingsLayout.vue";
import UpdatePasswordForm from "./UpdatePasswordForm.vue";
import { Head } from "@inertiajs/vue3";

defineProps({
    status: {
        type: String,
    },
});
</script>

<template>
    <Head title="Security" />

    <SettingsLayout>
        <div class="w-full">
            <UpdatePasswordForm />
        </div>
    </SettingsLayout>
</template>
