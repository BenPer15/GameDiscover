<script setup>
import { onMounted, ref } from "vue";

const model = defineModel({
    type: String,
    required: true,
});

defineProps({
    type: String,
    icon: String,
    placeholder: String,
});

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute("autofocus")) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <div
        class="flex items-center w-full gap-2 text-white rounded-full shadow-sm bg-dark-light"
    >
        <i v-if="icon" :class="icon" class="ml-4 text-xs bx" />
        <input
            class="w-full text-xs font-normal bg-transparent border-none focus:border-transparent focus:ring-0 placeholder:text-white placeholder:text-xs"
            v-model="model"
            :type="type"
            :placeholder="placeholder"
            ref="input"
        />
    </div>
</template>
