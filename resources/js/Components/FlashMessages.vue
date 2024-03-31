<script setup>
import { onMounted, ref } from "vue";
defineProps({
    message: Object,
    type: String,
});

const showNotif = ref(false);

onMounted(() => {
    showNotif.value = true;
    setTimeout(() => {
        showNotif.value = false;
    }, 6000);
});
</script>

<template>
    <div>
        <Transition mode="out-in" name="flash" tag="div" appear>
            <p
                v-if="showNotif"
                class="fixed z-50 flex items-center gap-2 p-2 px-5 text-sm font-semibold text-white rounded-md cursor-pointer bg-opacity-80 bg-dark-lighter top-20 right-6"
            >
                <i
                    class="text-xl bx"
                    :class="type === 'success' ? 'bx-check' : 'bxs-error'"
                ></i>
                {{ message }}
            </p>
        </Transition>
    </div>
</template>
