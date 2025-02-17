<template>
    <canvas ref="canvas"></canvas>
</template>

<script setup>
import { ref, onMounted, watch } from "vue";
import { Chart, registerables } from "chart.js";
Chart.register(...registerables);

const props = defineProps({
    data: {
        type: Object,
        required: true,
    },
});

const canvas = ref(null);
let chart = null;

const createChart = () => {
    if (chart) {
        chart.destroy();
    }

    chart = new Chart(canvas.value, {
        type: "doughnut",
        data: props.data,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: "right",
                    labels: {
                        color: "#F3F4F6",
                    },
                },
            },
        },
    });
};

onMounted(createChart);
watch(() => props.data, createChart, { deep: true });
</script>
