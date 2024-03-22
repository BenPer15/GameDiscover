import { computed, ref } from "vue";

export const useGallery = (props) => {
  const { medias } = props;
  const currentIndex = ref(0);
  const showModal = ref(false);
  const currentImageIndex = ref(null);

  const displayedImages = computed(() => {
    const start = currentIndex.value * 3;
    return medias.slice(start, start + 3);
  });

  const totalPages = computed(() => {
    return Math.ceil(medias.length / 3);
  });

  const prevSlide = () => {
    if (currentIndex.value > 0) currentIndex.value--;
  };

  const nextSlide = () => {
    if ((currentIndex.value + 1) * 3 < medias.length)
      currentIndex.value++;
  };

  const nextImageModal = () => {
    if (currentImageIndex.value < props.medias.length - 1) {
      currentImageIndex.value++;
      currentIndex.value = Math.floor(currentImageIndex.value / 3);
    }
  };

  const prevImageModal = () => {
    if (currentImageIndex.value > 0) {
      currentImageIndex.value--;
      currentIndex.value = Math.floor(currentImageIndex.value / 3);
    }
  };

  const openModal = (index) => {
    showModal.value = true;
    currentImageIndex.value = index;
  };

  const closeModal = () => {
    showModal.value = false;
    currentImageIndex.value = null;
  };


  return {
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
  };
};