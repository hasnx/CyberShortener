<template>
    <button
        @click="copyToClipboard"
        class="ml-2 px-3 py-1 bg-gray-800 text-yellow-400 border border-yellow-400 rounded-md hover:bg-gray-700 transition-colors duration-300"
    >
        <span v-if="copied">Copied!</span>
        <span v-else>Copy</span>
    </button>
</template>

<script setup>
import { ref } from "vue";

const props = defineProps({
    text: {
        type: String,
        required: true,
    },
});

const copied = ref(false);

const copyToClipboard = async () => {
    await navigator.clipboard.writeText(props.text);
    copied.value = true;
    setTimeout(() => {
        copied.value = false;
    }, 2000);
};
</script>
