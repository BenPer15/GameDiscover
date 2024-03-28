<script setup>
import Media from "@/Components/Media.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { useGallery } from "@/Composables/useGallery";
import Modal from "./Modal.vue";

const props = defineProps({
    medias: Array,
});

const {
    currentIndex,
    showModal,
    currentImageIndex,
    displayedImages,
    totalPages,
    prevSlide,
    nextSlide,
    nextImageModal,
    prevImageModal,
    openModal,
    closeModal,
} = useGallery(props);
</script>

<template>
    <div class="flex flex-col w-full gap-4">
        <div class="flex items-center justify-between gap-2">
            <h2 class="text-2xl font-bold">
                Media <br />
                Gallery
            </h2>
            <div class="flex items-center justify-center gap-2">
                <span
                    v-for="page in totalPages"
                    :key="page"
                    @click="currentIndex = page - 1"
                    class="cursor-pointer text-primary"
                    v-bind:class="{
                        'opacity-50 text-white': currentIndex !== page - 1,
                    }"
                    ><i class="bx bxs-circle text-3xs"></i
                ></span>
            </div>
            <div class="flex items-center justify-center gap-2">
                <SecondaryButton
                    @click="prevSlide"
                    v-bind:class="{
                        'opacity-50 hover:bg-dark hover:text-inherit cursor-default':
                            currentIndex === 0,
                    }"
                >
                    <i class="bx bx-chevron-left"></i>
                </SecondaryButton>
                <SecondaryButton
                    @click="nextSlide"
                    v-bind:class="{
                        'opacity-50 hover:bg-dark hover:text-inherit cursor-default':
                            currentIndex === totalPages - 1,
                    }"
                >
                    <i class="bx bx-chevron-right"></i>
                </SecondaryButton>
            </div>
        </div>
        <div class="media-container">
            <div
                v-for="(media, index) in displayedImages"
                :key="index"
                @click="() => openModal(index + currentIndex * 3)"
                v-bind:class="{
                    'media-item--first': index === 0,
                    'media-item--second': index === 1,
                    'media-item--third': index === 2,
                }"
            >
                <Media :media="media" />
            </div>
        </div>
    </div>

    <Modal
        :show="showModal"
        :close="closeModal"
        :media="medias[currentImageIndex]"
        :currentImageIndex="currentImageIndex"
        :prevImageModal="prevImageModal"
        :nextImageModal="nextImageModal"
        :index="index"
        :length="medias.length"
    />
</template>
