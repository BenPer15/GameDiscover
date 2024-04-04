import { ref } from "vue";

export function useGameMedias(gameId) {
  const medias = ref([]);
  const isLoading = ref(false);
  const error = ref(null);

  const fetchGameMedias = async () => {
    isLoading.value = true;
    error.value = null;
    try {
      const response = await axios.get(route("api.games.medias", gameId));
      medias.value = response.data;
    } catch (e) {
      error.value = e;
    } finally {
      isLoading.value = false;
    }
  };

  return { medias, isLoading, error, fetchGameMedias };
}