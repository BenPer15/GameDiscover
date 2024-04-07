import { ref } from "vue";

export function useGameStream(gameId) {
  const stream = ref([]);
  const isLoading = ref(false);
  const error = ref(null);

  const fetchGameStream = async () => {
    isLoading.value = true;
    error.value = null;
    try {
      const response = await axios.get(route("api.games.streams", gameId));
      stream.value = response.data;
    } catch (e) {
      error.value = e;
    } finally {
      isLoading.value = false;
    }
  };

  return { stream, isLoading, error, fetchGameStream };
}