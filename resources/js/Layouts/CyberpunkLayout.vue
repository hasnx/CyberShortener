<template>
    <div class="min-h-screen bg-gray-900">
        <!-- Navbar -->
        <nav class="bg-gray-800 border-b border-yellow-400">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Main Navigation -->
                <div class="flex items-center justify-between h-16">
                    <!-- Left Side -->
                    <div class="flex items-center">
                        <Link
                            :href="route('home')"
                            class="flex items-center space-x-2"
                        >
                            <div
                                class="w-8 h-8 bg-yellow-400 rounded flex items-center justify-center"
                            >
                                <span class="text-black font-bold text-lg"
                                    >CS</span
                                >
                            </div>
                            <h1
                                class="text-2xl font-bold text-yellow-400 glitch"
                            >
                                CyberShortener
                            </h1>
                        </Link>
                    </div>

                    <!-- Center Navigation -->
                    <div class="hidden md:flex items-center space-x-8">
                        <Link
                            :href="route('home')"
                            class="nav-link"
                            :class="{ 'nav-link-active': $page.url === '/' }"
                        >
                            Home
                        </Link>
                        <Link
                            v-if="$page.props.auth.user"
                            :href="route('links.user')"
                            class="nav-link"
                            :class="{
                                'nav-link-active':
                                    $page.url.startsWith('/my-links'),
                            }"
                        >
                            My Links
                        </Link>
                        <Link
                            v-if="$page.props.auth.user"
                            :href="route('dashboard')"
                            class="nav-link"
                            :class="{
                                'nav-link-active':
                                    $page.url.startsWith('/dashboard'),
                            }"
                        >
                            Dashboard
                        </Link>
                    </div>

                    <!-- Right Side -->
                    <div class="flex items-center space-x-4">
                        <template v-if="$page.props.auth.user">
                            <!-- User Menu -->
                            <div class="relative" v-click-away="closeDropdown">
                                <button
                                    @click="toggleDropdown"
                                    class="flex items-center space-x-2 text-gray-100 hover:text-yellow-400 transition-colors duration-300"
                                >
                                    <span>{{
                                        $page.props.auth.user.name
                                    }}</span>
                                    <svg
                                        class="w-4 h-4"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M19 9l-7 7-7-7"
                                        />
                                    </svg>
                                </button>

                                <!-- Dropdown Menu -->
                                <div
                                    v-show="showDropdown"
                                    class="absolute right-0 mt-2 w-48 bg-gray-800 border border-yellow-400 rounded-md shadow-lg py-1 z-50"
                                >
                                    <Link
                                        :href="route('profile.show')"
                                        class="block px-4 py-2 text-gray-100 hover:bg-gray-700 hover:text-yellow-400"
                                    >
                                        Profile
                                    </Link>
                                    <button
                                        @click="logout"
                                        class="block w-full text-left px-4 py-2 text-gray-100 hover:bg-gray-700 hover:text-yellow-400"
                                    >
                                        Logout
                                    </button>
                                </div>
                            </div>
                        </template>
                        <template v-else>
                            <Link
                                :href="route('login')"
                                class="text-blue-300 hover:text-yellow-400 transition-colors duration-300"
                            >
                                Login
                            </Link>
                            <Link
                                :href="route('register')"
                                class="px-4 py-2 bg-yellow-400 text-black rounded-md hover:bg-yellow-300 transition-colors duration-300"
                            >
                                Register
                            </Link>
                        </template>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <slot />
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-gray-800 border-t border-yellow-400 p-4 mt-auto">
            <div
                class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-blue-300"
            >
                &copy; {{ new Date().getFullYear() }} CyberShortener
            </div>
        </footer>
    </div>
</template>

<script setup>
import { ref } from "vue";
import { Link } from "@inertiajs/vue3";
import { router } from "@inertiajs/vue3";

const showDropdown = ref(false);

const toggleDropdown = () => {
    showDropdown.value = !showDropdown.value;
};

const closeDropdown = () => {
    showDropdown.value = false;
};

const logout = () => {
    router.post(route("logout"));
};
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

.nav-link {
    @apply text-gray-100 hover:text-yellow-400 transition-colors duration-300 relative;
}

.nav-link::after {
    content: "";
    @apply absolute left-0 bottom-0 w-0 h-0.5 bg-yellow-400 transition-all duration-300;
}

.nav-link:hover::after {
    @apply w-full;
}

.nav-link-active {
    @apply text-yellow-400;
}

.nav-link-active::after {
    @apply w-full;
}

/* Add neon glow to active links */
.nav-link-active::before {
    content: "";
    @apply absolute inset-0 bg-yellow-400 opacity-10 rounded-md -z-10;
    filter: blur(8px);
}
</style>
