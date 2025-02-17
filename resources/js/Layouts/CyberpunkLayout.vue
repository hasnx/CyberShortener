<template>
    <div class="min-h-screen bg-gray-900">
        <!-- Navbar -->
        <nav class="bg-gray-800 border-b border-yellow-400 p-4">
            <div class="container mx-auto">
                <h1 class="text-2xl font-bold text-yellow-400 glitch">
                    CyberShortener
                </h1>
                <div class="flex items-center space-x-4">
                    <Link
                        v-if="$page.props.auth.user"
                        :href="route('links.user')"
                        class="text-blue-300 hover:text-yellow-400 transition-colors duration-300"
                    >
                        My Links
                    </Link>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="container mx-auto px-4 py-8">
            <div class="max-w-3xl mx-auto">
                <slot />
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-gray-800 border-t border-yellow-400 p-4 mt-auto">
            <div class="container mx-auto text-center text-blue-300">
                &copy; 2024 CyberShortener
            </div>
        </footer>
    </div>
</template>

<script setup>
// No props or logic needed
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
