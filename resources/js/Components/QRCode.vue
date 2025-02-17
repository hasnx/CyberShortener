<template>
    <div class="qr-container" :class="styleClasses">
        <canvas ref="canvas"></canvas>
    </div>
</template>

<script setup>
import { ref, onMounted, computed, watch } from "vue";
import QRious from "qrious";

const props = defineProps({
    url: {
        type: String,
        required: true,
    },
    style: {
        type: String,
        default: "classic",
    },
    options: {
        type: Object,
        default: () => ({
            color: "#000000",
            backgroundColor: "#ffffff",
        }),
    },
});

const canvas = ref(null);
let qr = null;

const styleClasses = computed(() => ({
    "qr-classic": props.style === "classic",
    "qr-neon": props.style === "neon",
    "qr-matrix": props.style === "matrix",
    "qr-cyber": props.style === "cyber",
}));

const updateQR = () => {
    if (!qr) return;

    qr.foreground = props.options.color;
    qr.background = props.options.backgroundColor;
    qr.padding = props.options.margin || 0;
    qr.value = props.url;
};

onMounted(() => {
    qr = new QRious({
        element: canvas.value,
        value: props.url,
        size: 200,
        foreground: props.options.color,
        background: props.options.backgroundColor,
        padding: props.options.margin || 0,
    });
});

watch(() => props.options, updateQR, { deep: true });
watch(() => props.url, updateQR);
</script>

<style scoped>
.qr-container {
    padding: 1.5rem;
    border-radius: 0.75rem;
    position: relative;
    transition: all 0.3s ease;
    backdrop-filter: blur(4px);
}

.qr-container::before {
    content: "";
    position: absolute;
    inset: 0;
    border-radius: 0.75rem;
    padding: 2px;
    background: linear-gradient(
        45deg,
        rgba(255, 255, 255, 0.1),
        rgba(255, 255, 255, 0.2)
    );
    -webkit-mask: linear-gradient(#fff 0 0) content-box,
        linear-gradient(#fff 0 0);
    mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
    -webkit-mask-composite: xor;
    mask-composite: exclude;
    pointer-events: none;
}

/* Classic - Clean and Professional */
.qr-classic {
    background: white;
    box-shadow: 0 0 20px rgba(255, 255, 255, 0.1);
}

/* Neon - Cyberpunk Yellow */
.qr-neon {
    background: #1f2937;
    box-shadow: 0 0 20px #fbbf24, inset 0 0 15px rgba(251, 191, 36, 0.5);
    animation: neonPulse 2s infinite;
}

/* Cyber - High-tech Red */
.qr-cyber {
    background: #1f2937;
    box-shadow: 0 0 20px #f43f5e, inset 0 0 15px rgba(244, 63, 94, 0.5);
    animation: cyberGlitch 1.5s infinite;
}

/* Matrix - Digital Green */
.qr-matrix {
    background: #1f2937;
    box-shadow: 0 0 20px #10b981, inset 0 0 15px rgba(16, 185, 129, 0.5);
    animation: matrixScan 3s infinite;
}

/* Enhanced Animations */
@keyframes neonPulse {
    0%,
    100% {
        box-shadow: 0 0 20px #fbbf24, inset 0 0 15px rgba(251, 191, 36, 0.5);
        transform: scale(1);
    }
    50% {
        box-shadow: 0 0 30px #fbbf24, inset 0 0 20px rgba(251, 191, 36, 0.7);
        transform: scale(1.02);
    }
}

@keyframes cyberGlitch {
    0%,
    100% {
        transform: translate(0) scale(1);
        filter: brightness(1);
    }
    20% {
        transform: translate(-2px, 2px) scale(1.01);
        filter: brightness(1.2);
    }
    40% {
        transform: translate(2px, -2px) scale(0.99);
        filter: brightness(0.9);
    }
    60% {
        transform: translate(-1px, -1px) scale(1.01);
        filter: brightness(1.1);
    }
    80% {
        transform: translate(1px, 1px) scale(0.99);
        filter: brightness(0.95);
    }
}

@keyframes matrixScan {
    0% {
        box-shadow: 0 0 20px #10b981, inset 0 0 15px rgba(16, 185, 129, 0.5);
        transform: translateY(0);
    }
    50% {
        box-shadow: 0 -10px 25px #10b981, inset 0 0 20px rgba(16, 185, 129, 0.7);
        transform: translateY(-2px);
    }
    100% {
        box-shadow: 0 0 20px #10b981, inset 0 0 15px rgba(16, 185, 129, 0.5);
        transform: translateY(0);
    }
}

canvas {
    display: block;
    max-width: 100%;
    height: auto;
    border-radius: 0.5rem;
    transition: all 0.3s ease;
}

/* Add hover effects */
.qr-container:hover {
    transform: translateY(-2px);
}

.qr-container:hover canvas {
    filter: contrast(1.1);
}
</style>
