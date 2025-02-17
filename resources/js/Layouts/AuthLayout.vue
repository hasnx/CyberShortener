<template>
    <div class="min-h-screen bg-gray-900 flex flex-col">
        <!-- Navbar -->
        <nav class="bg-gray-800 border-b border-yellow-400">
            <div class="container mx-auto px-4">
                <div class="flex items-center h-16">
                    <Link
                        :href="route('home')"
                        class="flex items-center space-x-2"
                    >
                        <div
                            class="w-8 h-8 bg-yellow-400 rounded flex items-center justify-center"
                        >
                            <span class="text-black font-bold text-lg">CS</span>
                        </div>
                        <h1 class="text-2xl font-bold text-yellow-400 glitch">
                            CyberShortener
                        </h1>
                    </Link>
                </div>
            </div>
        </nav>

        <!-- Auth Container -->
        <div class="flex-1 flex items-center justify-center p-4">
            <div class="w-full max-w-md">
                <slot />
            </div>
        </div>

        <!-- Cyberpunk Background Elements -->
        <div class="fixed inset-0 -z-10 overflow-hidden">
            <div class="absolute inset-0 bg-grid-pattern opacity-5"></div>
            <div
                class="absolute top-0 left-0 w-full h-1/2 bg-gradient-to-b from-blue-500/10 to-transparent"
            ></div>
            <div
                class="absolute bottom-0 left-0 w-full h-1/2 bg-gradient-to-t from-yellow-500/10 to-transparent"
            ></div>
        </div>
    </div>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
</script>

<style scoped>
.bg-grid-pattern {
    background-image: linear-gradient(
            rgba(255, 187, 0, 0.1) 1px,
            transparent 1px
        ),
        linear-gradient(90deg, rgba(255, 187, 0, 0.1) 1px, transparent 1px);
    background-size: 20px 20px;
}
</style>
