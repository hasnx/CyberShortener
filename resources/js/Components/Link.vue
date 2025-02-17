<template>
    <a
        :href="href"
        :class="classes"
        class="inline-flex items-center transition-colors duration-300"
        @click="visit"
    >
        <slot />
    </a>
</template>

<script setup>
import { computed } from "vue";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    href: {
        type: String,
        required: true,
    },
    class: {
        type: String,
        default: "",
    },
});

const classes = computed(() => props.class);

const visit = (event) => {
    if (shouldIntercept(event)) {
        event.preventDefault();
        Link.visit(props.href);
    }
};

const shouldIntercept = (event) => {
    return (
        !event.target.hasAttribute("download") &&
        !event.target.hasAttribute("target") &&
        !event.target.closest("[download]") &&
        !event.target.closest("[target]") &&
        !event.target.closest("[data-no-intercept]") &&
        event.button === 0 &&
        !event.altKey &&
        !event.ctrlKey &&
        !event.metaKey &&
        !event.shiftKey
    );
};
</script>
