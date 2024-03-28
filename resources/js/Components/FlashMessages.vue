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
        <Transition
            mode="out-in"
            name="flash"
            tag="div"
            enter-active-class="animate__animated animate__fadeInUp"
            leave-active-class="animate__animated animate__fadeOutDown"
            appear
        >
            <p
                v-if="type === 'error' && showNotif"
                class="fixed px-5 py-1 font-semibold text-white bg-red-600 cursor-pointer bottom-3 right-3 rounded-xl"
            >
                {{ message }}
            </p>
        </Transition>
        <Transition
            mode="out-in"
            name="flash"
            tag="div"
            enter-active-class="animate__animated animate__fadeInUp"
            leave-active-class="animate__animated animate__fadeOutDown"
            appear
        >
            <p
                v-if="type === 'success' && showNotif"
                class="fixed px-5 py-1 font-semibold text-white bg-green-600 cursor-pointer bottom-3 right-3 rounded-xl"
            >
                {{ message }}
            </p>
        </Transition>
    </div>
</template>
