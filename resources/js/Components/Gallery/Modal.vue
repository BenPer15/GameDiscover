<script setup>
import Modal from "@/Components/Modal.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";

defineProps({
    show: Boolean,
    close: Function,
    media: Object,
    index: Number,
    currentImageIndex: Number,
    prevImageModal: Function,
    nextImageModal: Function,
    length: Number,
});
const origin = window.location.origin;
</script>

<template>
    <Modal :show="show" @close="close">
        <section class="flex m-auto bg-white">
            <img
                v-if="media.url"
                :src="media.url"
                :alt="'modal-image-' + index"
                class="p-4"
            />

            <div v-else class="flex items-center justify-center w-full h-96">
                <iframe
                    id="video_3_Youtube_api"
                    frameborder="0"
                    allowfullscreen=""
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                    :title="media.name"
                    width="640"
                    height="360"
                    :src="
                        'https://www.youtube.com/embed/' +
                        media.video_id +
                        '?controls=0&modestbranding=1&rel=0&showinfo=0&loop=0&fs=0&hl=en&iv_load_policy=3&enablejsapi=1&widgetid=1&origin=' +
                        origin +
                        '&autoplay=1'
                    "
                ></iframe>
            </div>

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
