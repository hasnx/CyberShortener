<template>
    <div class="p-4 bg-white rounded-lg">
        <canvas ref="canvas"></canvas>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import QRious from "qrious";

const props = defineProps({
    url: {
        type: String,
        required: true,
    },
});

const canvas = ref(null);

onMounted(() => {
    new QRious({
        element: canvas.value,
        value: props.url,
        size: 128,
    });
});
</script>
