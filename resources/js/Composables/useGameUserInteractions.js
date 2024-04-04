import { ref } from "vue";

export function useGameUserInteractions(gameId) {
  const userInteractions = ref([]);
  const isLoading = ref(false);
  const error = ref(null);

  const fetchGameUserInteractions = async () => {
    isLoading.value = true;
    error.value = null;
    try {
      const response = await axios.get(route("api.games.userInteractions", gameId));
      userInteractions.value = response.data;
    } catch (e) {
      error.value = e;
    } finally {
      isLoading.value = false;
    }
  };

  return { userInteractions, isLoading, error, fetchGameUserInteractions };
}