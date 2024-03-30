<script setup>
import { onMounted, ref } from "vue";

const model = defineModel({
    type: String,
    required: true,
});

defineProps({
    class: String,
    classInput: String,
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
        class="relative flex items-center w-full gap-2 text-white rounded-xl"
        :class="class"
    >
        <i v-if="icon" :class="icon" class="ml-4 text-xs bx" />
        <input
            class="w-full py-3 my-3 text-xs bg-transparent rounded-md border-dark-lighter focus:border-dark-lighter focus:ring-0 placeholder:text-gray-400 placeholder:text-xs"
            :class="classInput"
            v-model="model"
            :type="type"
            :placeholder="placeholder"
            :required="required"
            :autofocus="autofocus"
            ref="input"
        />
    </div>
</template>
