import { ref } from "vue";

export function useGameReviews(gameId) {
  const reviews = ref([]);
  const isLoading = ref(false);
  const error = ref(null);

  const fetchGameReviews = async () => {
    isLoading.value = true;
    error.value = null;
    try {
      const response = await axios.get(route("api.games.reviews", gameId));
      reviews.value = response.data;
    } catch (e) {
      error.value = e;
    } finally {
      isLoading.value = false;
    }
  };

  const addReview = (review) => {
    console.log(review);
    reviews.value.unshift(review);
  };

  return { reviews, isLoading, error, fetchGameReviews, addReview };
}