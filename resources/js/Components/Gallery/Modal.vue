<script setup>
import Modal from "@/Components/Modal.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";

defineProps({
    show: Boolean,
    close: Function,
    src: String,
    index: Number,
    currentImageIndex: Number,
    prevImageModal: Function,
    nextImageModal: Function,
    length: Number,
});
</script>

<template>
    <Modal :show="show" @close="close">
        <section class="flex m-auto bg-white">
            <img :src="src" :alt="'modal-image-' + index" class="p-4" />

            <SecondaryButton
                v-if="currentImageIndex > 0"
                @click="prevImageModal"
                class="absolute top-0 left-0 flex items-center justify-center h-full px-5 bg-transparent rounded-none hover:bg-opacity-35 hover:text-white"
            >
                <i class="text-4xl bx bx-chevron-left"></i>
            </SecondaryButton>
            <SecondaryButton
                v-if="currentImageIndex < length - 1"
                @click="nextImageModal"
                class="absolute top-0 right-0 flex items-center justify-center h-full px-5 bg-transparent rounded-none hover:bg-opacity-35 hover:text-white"
            >
                <i class="text-4xl bx bx-chevron-right"></i>
            </SecondaryButton>
        </section>
    </Modal>
</template>
