<template>
    <div class="flex items-center space-x-2">
        <div
            class="w-2 h-2 rounded-full"
            :class="{
                'bg-green-400': isActive,
                'bg-red-400': !isActive,
            }"
        ></div>
        <span
            class="text-sm"
            :class="{
                'text-green-400': isActive,
                'text-red-400': !isActive,
            }"
        >
            {{ statusText }}
        </span>
    </div>
</template>

<script setup>
import { computed } from "vue";

const props = defineProps({
    expiresAt: {
        type: String,
        required: true,
    },
});

const isActive = computed(() => {
    if (!props.expiresAt) return true;
    return new Date(props.expiresAt) > new Date();
});

const statusText = computed(() => {
    if (!props.expiresAt) return "Active";
    return isActive.value ? "Active" : "Expired";
});
</script>
