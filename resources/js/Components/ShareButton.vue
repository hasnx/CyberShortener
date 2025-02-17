<template>
    <button
        v-if="canShare"
        @click="share"
        class="ml-2 px-3 py-1 bg-gray-800 text-yellow-400 border border-yellow-400 rounded-md hover:bg-gray-700 transition-colors duration-300"
    >
        Share
    </button>
</template>

<script setup>
import { ref, onMounted } from "vue";

const props = defineProps({
    url: {
        type: String,
        required: true,
    },
    title: {
        type: String,
        default: "Check out this shortened URL",
    },
});

const canShare = ref(false);

onMounted(() => {
    canShare.value = !!navigator.share;
});

const share = async () => {
    try {
        await navigator.share({
            title: props.title,
            url: props.url,
        });
    } catch (err) {
        console.error("Failed to share:", err);
    }
};
</script>
