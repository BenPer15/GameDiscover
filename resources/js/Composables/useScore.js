import { computed } from "vue";

export const useScore = (score) => {

  const scoreIcon = computed(() => {
    if (score >= 90) return 'bxs-trophy';
    if (score >= 70) return 'bxs-like';
    if (score >= 50) return 'bxs-smile';
    if (score >= 30) return 'bxs-dislike';
    if (score >= 10) return 'bxs-bomb';
    return 'bxs-angry';
  });


  const scoreColor = computed(() => {
    if (score >= 90) return 'lime-400';
    if (score >= 70) return 'green-700';
    if (score >= 50) return 'yellow-500';
    if (score >= 30) return 'orange-500';
    if (score >= 10) return 'red-500';
    return 'red-800';
  });

  const scoreLabel = computed(() => {
    if (score >= 90) return 'Excellent';
    if (score >= 70) return 'Good';
    if (score >= 50) return 'Average';
    if (score >= 30) return 'Poor';
    if (score >= 10) return 'Bad';
    return 'Very Bad';
  });

  return {
    scoreLabel,
    scoreIcon,
    scoreColor: scoreColor.value
  }

}