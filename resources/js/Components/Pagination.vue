<template>
    <div v-if="links.length > 3" class="flex justify-center space-x-2">
        <template v-for="(link, key) in links" :key="key">
            <div
                v-if="link.url === null"
                class="px-4 py-2 text-gray-500 bg-gray-800 rounded-md"
                v-html="link.label"
            />
            <Link
                v-else
                :href="link.url"
                class="px-4 py-2 bg-gray-800 text-yellow-400 border border-yellow-400 rounded-md hover:bg-gray-700 transition-colors duration-300"
                :class="{ 'bg-yellow-400 text-black': link.active }"
                v-html="link.label"
            />
        </template>
    </div>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";

defineProps({
    links: {
        type: Array,
        required: true,
    },
});
</script>
