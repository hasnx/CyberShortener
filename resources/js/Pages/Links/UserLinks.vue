<template>
    <CyberpunkLayout>
        <div class="space-y-8">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl text-yellow-400 font-bold glitch">
                    My Links
                </h1>
                <Link
                    :href="route('home')"
                    class="px-4 py-2 bg-gray-800 text-yellow-400 border border-yellow-400 rounded-md hover:bg-gray-700 transition-colors duration-300"
                >
                    Create New Link
                </Link>
            </div>

            <div class="space-y-4">
                <div
                    v-for="link in links.data"
                    :key="link.id"
                    class="bg-gray-800 p-6 rounded-lg border border-yellow-400"
                >
                    <div class="space-y-4">
                        <!-- Link Info -->
                        <div class="flex justify-between">
                            <div class="space-y-2">
                                <p class="text-gray-100">
                                    Original URL:
                                    <a
                                        :href="link.original_url"
                                        class="text-blue-300 hover:text-yellow-400 ml-2"
                                        target="_blank"
                                        >{{ link.original_url }}</a
                                    >
                                </p>
                                <div class="flex items-center space-x-2">
                                    <a
                                        :href="link.short_url"
                                        class="text-blue-300 hover:text-yellow-400"
                                        target="_blank"
                                        >{{ link.short_url }}</a
                                    >
                                    <CopyButton :text="link.short_url" />
                                    <ShareButton
                                        :url="link.short_url"
                                        :title="`Shortened URL for: ${link.original_url}`"
                                    />
                                    <Link
                                        :href="
                                            route(
                                                'links.stats',
                                                link.short_code
                                            )
                                        "
                                        class="px-3 py-1 bg-gray-800 text-yellow-400 border border-yellow-400 rounded-md hover:bg-gray-700 transition-colors duration-300"
                                    >
                                        Stats
                                    </Link>
                                </div>
                            </div>
                            <div class="text-right space-y-2">
                                <p class="text-yellow-400 text-xl">
                                    {{ link.visits }} visits
                                </p>
                                <p v-if="link.expires_at" class="text-gray-400">
                                    Expires: {{ formatDate(link.expires_at) }}
                                </p>
                                <p v-if="link.is_custom" class="text-blue-300">
                                    Custom URL
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <Pagination :links="links.links" />
        </div>
    </CyberpunkLayout>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
import CyberpunkLayout from "@/Layouts/CyberpunkLayout.vue";
import CopyButton from "@/Components/CopyButton.vue";
import ShareButton from "@/Components/ShareButton.vue";
import Pagination from "@/Components/Pagination.vue";

const props = defineProps({
    links: {
        type: Object,
        required: true,
    },
});

const formatDate = (date) => {
    return new Date(date).toLocaleDateString("en-US", {
        year: "numeric",
        month: "short",
        day: "numeric",
    });
};
</script>
