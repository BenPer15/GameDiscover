import { router, usePage } from "@inertiajs/vue3";
import { ref } from "vue";

export function useGameData(gameId) {
  const game = ref(null);
  const background = ref(null);
  const isLoading = ref(false);
  const error = ref(null);

  const page = usePage();
  const age = page.props.user_age;

  const fetchGameData = async () => {
    isLoading.value = true;
    error.value = null;
    try {
      const response = await axios.get(route("api.games.show", gameId));
      const backgroundResponse = await axios.get(route("api.games.background", gameId));
      const gameValue = response.data;
      if (gameValue.matureContent && (age < 18 || age === 0)) {
        router.visit(route("games.matureContent", { q: gameValue.id }));
      }
      game.value = gameValue;
      background.value = backgroundResponse.data;
    } catch (e) {
      error.value = e;
    } finally {
      isLoading.value = false;
    }
  };

  return { game, background, isLoading, error, fetchGameData };
}