<template>
    <CyberpunkLayout>
        <TransitionGroup
            enter-active-class="transition duration-300 ease-out"
            enter-from-class="transform -translate-y-2 opacity-0"
            enter-to-class="transform translate-y-0 opacity-100"
            leave-active-class="transition duration-200 ease-in"
            leave-from-class="transform translate-y-0 opacity-100"
            leave-to-class="transform -translate-y-2 opacity-0"
        >
            <!-- Error Message -->
            <div
                v-if="$page.props.errors?.url"
                key="error"
                class="mb-4 p-4 bg-red-900/50 border border-red-400 text-red-400 rounded-md"
            >
                {{ $page.props.errors.url }}
            </div>

            <!-- Success Message -->
            <div
                v-if="$page.props.flash?.success"
                key="success"
                class="mb-4 p-4 bg-green-900 border border-green-400 text-green-400 rounded-md"
            >
                {{ $page.props.flash.success }}
            </div>
        </TransitionGroup>

        <!-- URL Input Form -->
        <form @submit.prevent="submitUrl" class="mb-12">
            <div class="flex gap-4">
                <input
                    v-model="form.url"
                    type="url"
                    placeholder="Enter your URL to shorten"
                    class="flex-1 bg-gray-800 border border-yellow-400 text-gray-100 rounded-md px-4 py-2 focus:ring-2 focus:ring-yellow-400 placeholder-gray-500"
                    :class="{
                        'border-red-400 focus:ring-red-400':
                            $page.props.errors?.url,
                        'border-green-400 focus:ring-green-400':
                            form.url && isValidUrl(form.url),
                    }"
                    required
                />
                <button
                    type="submit"
                    class="bg-yellow-400 text-black px-6 py-2 rounded-md hover:bg-yellow-300 transition-colors duration-300 disabled:opacity-50"
                    :disabled="processing"
                >
                    <span v-if="processing">Processing...</span>
                    <span v-else>Shorten</span>
                </button>
            </div>
        </form>

        <!-- Recent Links -->
        <div class="space-y-4">
            <h2 class="text-xl text-yellow-400 font-bold mb-4 glitch">
                Recent Links
            </h2>
            <template v-if="links.length">
                <div
                    v-for="link in links"
                    :key="link.id"
                    class="bg-gray-800 p-4 rounded-lg border border-yellow-400"
                >
                    <div class="flex justify-between items-center">
                        <div class="space-y-1">
                            <p class="text-gray-100 truncate">
                                {{ link.original_url }}
                            </p>
                            <div class="flex items-center">
                                <a
                                    @click.prevent="
                                        copyToClipboard(link.short_url)
                                    "
                                    :href="link.short_url"
                                    class="text-blue-300 hover:text-yellow-400 transition-colors duration-300 cursor-pointer"
                                    target="_blank"
                                >
                                    {{ link.short_url }}
                                </a>
                                <div class="flex items-center">
                                    <CopyButton :text="link.short_url" />
                                    <ShareButton
                                        :url="link.short_url"
                                        :title="`Shortened URL for: ${link.original_url}`"
                                    />
                                </div>
                            </div>
                        </div>
                        <div class="text-yellow-400">
                            {{ link.visits }} visits
                        </div>
                    </div>
                </div>
            </template>
            <template v-else>
                <LinkSkeleton v-for="n in 3" :key="n" class="mb-4" />
            </template>
        </div>

        <!-- QR Code Modal -->
        <Modal :show="qrModal" @close="qrModal = false">
            <div class="text-center">
                <h3 class="text-yellow-400 text-lg mb-4">Scan QR Code</h3>
                <QRCode :url="selectedUrl" />
            </div>
        </Modal>
    </CyberpunkLayout>
</template>

<script setup>
import { ref, watch } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import CyberpunkLayout from "@/Layouts/CyberpunkLayout.vue";
import CopyButton from "@/Components/CopyButton.vue";
import Modal from "@/Components/Modal.vue";
import QRCode from "@/Components/QRCode.vue";
import LinkSkeleton from "@/Components/LinkSkeleton.vue";
import ShareButton from "@/Components/ShareButton.vue";

const page = usePage();

const props = defineProps({
    links: {
        type: Array,
        default: () => [],
    },
});

const form = ref({
    url: "",
});

const processing = ref(false);
const qrModal = ref(false);
const selectedUrl = ref("");

const submitUrl = () => {
    processing.value = true;
    router.post("/links", form.value, {
        preserveScroll: true,
        onSuccess: () => {
            form.value.url = "";
            processing.value = false;
        },
        onError: () => {
            processing.value = false;
        },
    });
};

const showQR = (url) => {
    selectedUrl.value = url;
    qrModal.value = true;
};

const isValidUrl = (string) => {
    try {
        new URL(string);
        return true;
    } catch (_) {
        return false;
    }
};

// Add this for debugging
watch(
    () => props.links,
    (newLinks) => {
        console.log("Links:", newLinks);
    },
    { immediate: true }
);
</script>

<style>
@import url("https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;600;700&display=swap");

/* Apply Orbitron font to all text */
* {
    font-family: "Orbitron", sans-serif;
}

/* Glitch effect */
.glitch {
    text-shadow: 0.05em 0 0 rgba(255, 0, 0, 0.75),
        -0.025em -0.05em 0 rgba(0, 255, 0, 0.75),
        0.025em 0.05em 0 rgba(0, 0, 255, 0.75);
    animation: glitch 500ms infinite;
}

@keyframes glitch {
    0% {
        text-shadow: 0.05em 0 0 rgba(255, 0, 0, 0.75),
            -0.05em -0.025em 0 rgba(0, 255, 0, 0.75),
            -0.025em 0.05em 0 rgba(0, 0, 255, 0.75);
    }
    14% {
        text-shadow: 0.05em 0 0 rgba(255, 0, 0, 0.75),
            -0.05em -0.025em 0 rgba(0, 255, 0, 0.75),
            -0.025em 0.05em 0 rgba(0, 0, 255, 0.75);
    }
    15% {
        text-shadow: -0.05em -0.025em 0 rgba(255, 0, 0, 0.75),
            0.025em 0.025em 0 rgba(0, 255, 0, 0.75),
            -0.05em -0.05em 0 rgba(0, 0, 255, 0.75);
    }
    49% {
        text-shadow: -0.05em -0.025em 0 rgba(255, 0, 0, 0.75),
            0.025em 0.025em 0 rgba(0, 255, 0, 0.75),
            -0.05em -0.05em 0 rgba(0, 0, 255, 0.75);
    }
    50% {
        text-shadow: 0.025em 0.05em 0 rgba(255, 0, 0, 0.75),
            0.05em 0 0 rgba(0, 255, 0, 0.75), 0 -0.05em 0 rgba(0, 0, 255, 0.75);
    }
    99% {
        text-shadow: 0.025em 0.05em 0 rgba(255, 0, 0, 0.75),
            0.05em 0 0 rgba(0, 255, 0, 0.75), 0 -0.05em 0 rgba(0, 0, 255, 0.75);
    }
    100% {
        text-shadow: -0.025em 0 0 rgba(255, 0, 0, 0.75),
            -0.025em -0.025em 0 rgba(0, 255, 0, 0.75),
            -0.025em -0.05em 0 rgba(0, 0, 255, 0.75);
    }
}
</style>
