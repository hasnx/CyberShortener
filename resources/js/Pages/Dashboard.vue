<script setup>
import { Link } from "@inertiajs/vue3";
import CyberpunkLayout from "@/Layouts/CyberpunkLayout.vue";
import { ref, onMounted, computed } from "vue";

const props = defineProps({
    stats: {
        type: Object,
        required: true,
        default: () => ({
            totalLinks: 0,
            totalClicks: 0,
            activeLinks: 0,
        }),
    },
    recentLinks: {
        type: Array,
        default: () => [],
    },
});

// Add formatDate function
const formatDate = (date) => {
    return new Date(date).toLocaleDateString("en-US", {
        year: "numeric",
        month: "short",
        day: "numeric",
        hour: "2-digit",
        minute: "2-digit",
    });
};

// Add copyToClipboard function
const copyToClipboard = async (text) => {
    try {
        await navigator.clipboard.writeText(text);
        // You could add a toast notification here
    } catch (err) {
        console.error("Failed to copy text: ", err);
    }
};

// Add a computed property to safely access stats
const safeStats = computed(() => {
    return {
        totalLinks: props.stats?.totalLinks ?? 0,
        totalClicks: props.stats?.totalClicks ?? 0,
        activeLinks: props.stats?.activeLinks ?? 0,
    };
});

const chartData = ref(null);
const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    scales: {
        y: {
            beginAtZero: true,
            grid: {
                color: "rgba(251, 191, 36, 0.1)",
            },
            ticks: {
                color: "#f3f4f6",
            },
        },
        x: {
            grid: {
                color: "rgba(251, 191, 36, 0.1)",
            },
            ticks: {
                color: "#f3f4f6",
            },
        },
    },
    plugins: {
        legend: {
            labels: {
                color: "#f3f4f6",
            },
        },
    },
};
</script>

<template>
    <CyberpunkLayout>
        <div class="space-y-8 p-6">
            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Total Links Card -->
                <div class="cyber-panel">
                    <div class="cyber-panel-header">
                        <span class="text-yellow-400">[</span>
                        TOTAL_LINKS
                        <span class="text-yellow-400">]</span>
                    </div>
                    <div class="cyber-panel-content">
                        <div class="text-5xl font-bold text-yellow-400 glitch">
                            {{ safeStats.totalLinks }}
                        </div>
                    </div>
                </div>

                <!-- Total Clicks Card -->
                <div class="cyber-panel">
                    <div class="cyber-panel-header">
                        <span class="text-blue-400">[</span>
                        TOTAL_CLICKS
                        <span class="text-blue-400">]</span>
                    </div>
                    <div class="cyber-panel-content">
                        <div class="text-5xl font-bold text-blue-400 glitch">
                            {{ safeStats.totalClicks }}
                        </div>
                    </div>
                </div>

                <!-- Active Links Card -->
                <div class="cyber-panel">
                    <div class="cyber-panel-header">
                        <span class="text-green-400">[</span>
                        ACTIVE_LINKS
                        <span class="text-green-400">]</span>
                    </div>
                    <div class="cyber-panel-content">
                        <div class="text-5xl font-bold text-green-400 glitch">
                            {{ safeStats.activeLinks }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Links Table -->
            <div class="cyber-panel">
                <div class="cyber-panel-header">
                    <span class="text-yellow-400">[</span>
                    RECENT_LINKS
                    <span class="text-yellow-400">]</span>
                </div>
                <div class="cyber-panel-content">
                    <table class="w-full cyber-table">
                        <thead>
                            <tr>
                                <th>ORIGINAL_URL</th>
                                <th>SHORT_URL</th>
                                <th>CLICKS</th>
                                <th>CREATED</th>
                                <th>ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="link in recentLinks" :key="link.id">
                                <td class="truncate max-w-xs">
                                    {{ link.original_url }}
                                </td>
                                <td>
                                    <a
                                        :href="link.short_url"
                                        class="cyber-link"
                                        target="_blank"
                                    >
                                        {{ link.short_url }}
                                    </a>
                                </td>
                                <td>{{ link.visits }}</td>
                                <td>{{ formatDate(link.created_at) }}</td>
                                <td>
                                    <div class="flex space-x-4">
                                        <Link
                                            :href="
                                                route(
                                                    'links.stats',
                                                    link.short_code
                                                )
                                            "
                                            class="cyber-button-small"
                                        >
                                            [STATS]
                                        </Link>
                                        <button
                                            @click="
                                                copyToClipboard(link.short_url)
                                            "
                                            class="cyber-button-small"
                                        >
                                            [COPY]
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="flex justify-end space-x-6">
                <Link :href="route('links.user')" class="cyber-button">
                    <span class="text-yellow-400">[</span>
                    VIEW_ALL_LINKS
                    <span class="text-yellow-400">]</span>
                </Link>
                <Link :href="route('home')" class="cyber-button-primary">
                    <span class="text-yellow-400">[</span>
                    CREATE_NEW_LINK
                    <span class="text-yellow-400">]</span>
                </Link>
            </div>
        </div>
    </CyberpunkLayout>
</template>

<style scoped>
.cyber-panel {
    @apply bg-gray-900 border border-yellow-400 rounded-lg overflow-hidden;
    box-shadow: 0 0 10px rgba(251, 191, 36, 0.1);
    position: relative;
}

.cyber-panel::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 1px;
    background: linear-gradient(
        90deg,
        transparent,
        rgba(251, 191, 36, 0.5),
        transparent
    );
}

.cyber-panel-header {
    @apply px-6 py-3 bg-gray-800 text-gray-100 font-bold tracking-wider uppercase text-lg;
    border-bottom: 1px solid rgba(251, 191, 36, 0.2);
}

.cyber-panel-content {
    @apply p-6 flex items-center justify-center;
}

.cyber-table {
    @apply w-full border-collapse;
}

.cyber-table th {
    @apply text-gray-100 font-bold tracking-wider uppercase text-sm py-4 px-4 text-left;
    border-bottom: 1px solid rgba(251, 191, 36, 0.2);
}

.cyber-table td {
    @apply text-gray-300 py-4 px-4 border-b border-gray-800;
}

.cyber-link {
    @apply text-blue-400 hover:text-yellow-400 transition-colors duration-300;
    text-shadow: 0 0 5px rgba(96, 165, 250, 0.5);
}

.cyber-button {
    @apply px-6 py-3 bg-gray-800 text-gray-100 rounded transition-all duration-300
           hover:bg-gray-700 uppercase tracking-wider font-bold;
    text-shadow: 0 0 5px rgba(251, 191, 36, 0.5);
}

.cyber-button-primary {
    @apply px-6 py-3 bg-yellow-400 text-black rounded transition-all duration-300
           hover:bg-yellow-300 uppercase tracking-wider font-bold;
    text-shadow: 0 0 5px rgba(0, 0, 0, 0.5);
}

.cyber-button-small {
    @apply px-3 py-1 text-blue-400 hover:text-yellow-400 transition-colors duration-300
           uppercase tracking-wider text-sm;
}

.glitch {
    text-shadow: 0 0 10px currentColor;
    animation: glow 2s infinite;
}

@keyframes glow {
    0%,
    100% {
        text-shadow: 0 0 10px currentColor;
    }
    50% {
        text-shadow: 0 0 20px currentColor;
    }
}
</style>
