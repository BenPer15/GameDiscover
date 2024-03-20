<script setup>
import { onMounted, ref } from "vue";

const model = defineModel({
    type: String,
    required: true,
});

defineProps({
    class: String,
    type: String,
    icon: String,
    placeholder: String,
    required: Boolean,
    autofocus: Boolean,
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
        class="flex items-center w-full gap-2 text-white shadow-sm rounded-xl bg-dark-light"
    >
        <i v-if="icon" :class="icon" class="ml-4 text-xs bx" />
        <input
            class="w-full text-xs font-normal bg-transparent border-none focus:border-transparent focus:ring-0 placeholder:text-white placeholder:text-xs"
            v-model="model"
            :type="type"
            :placeholder="placeholder"
            :required="required"
            :autofocus="autofocus"
            ref="input"
        />
    </div>
</template>
