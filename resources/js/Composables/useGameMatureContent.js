import { ref } from "vue";

export function useGameMatureContent(gameId) {
  const game = ref([]);
  const matureSynopsis = ref("");
  const isLoading = ref(false);
  const error = ref(null);

  const fetchGameMatureContent = async () => {
    isLoading.value = true;
    error.value = null;
    try {
      const response = await axios.get(route("api.games.matureContent", { gameId }));
      game.value = response.data;
      matureSynopsis.value = response.data.matureContent.synopsis;
    } catch (e) {
      error.value = e;
    } finally {
      isLoading.value = false;
    }
  };

  return { matureSynopsis, game, isLoading, error, fetchGameMatureContent };
}