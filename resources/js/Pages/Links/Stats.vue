<template>
    <CyberpunkLayout>
        <div class="space-y-8">
            <!-- Header -->
            <div class="bg-gray-800 p-6 rounded-lg border border-yellow-400">
                <h1 class="text-2xl text-yellow-400 font-bold mb-4">
                    Link Statistics
                </h1>
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
                    <p class="text-gray-100">
                        Short URL:
                        <a
                            :href="link.short_url"
                            class="text-blue-300 hover:text-yellow-400 ml-2"
                            target="_blank"
                            >{{ link.short_url }}</a
                        >
                    </p>
                    <p class="text-yellow-400 text-xl">
                        Total Visits: {{ link.visits }}
                    </p>
                </div>
            </div>

            <!-- Daily Visits Chart -->
            <div class="bg-gray-800 p-6 rounded-lg border border-yellow-400">
                <h2 class="text-xl text-yellow-400 font-bold mb-4">
                    Daily Visits
                </h2>
                <div class="h-64">
                    <LineChart :data="dailyVisitsChartData" />
                </div>
            </div>

            <!-- Browser Stats -->
            <div class="bg-gray-800 p-6 rounded-lg border border-yellow-400">
                <h2 class="text-xl text-yellow-400 font-bold mb-4">
                    Browser Statistics
                </h2>
                <div class="h-64">
                    <DoughnutChart :data="browserChartData" />
                </div>
            </div>

            <!-- Country Stats -->
            <div class="bg-gray-800 p-6 rounded-lg border border-yellow-400">
                <h2 class="text-xl text-yellow-400 font-bold mb-4">
                    Geographic Distribution
                </h2>
                <div class="h-64">
                    <BarChart :data="countryChartData" />
                </div>
            </div>

            <!-- Referrer Stats -->
            <div class="bg-gray-800 p-6 rounded-lg border border-yellow-400">
                <h2 class="text-xl text-yellow-400 font-bold mb-4">
                    Top Referrers
                </h2>
                <div class="space-y-2">
                    <div
                        v-for="(referrer, index) in link.referrer_stats"
                        :key="index"
                        class="flex justify-between items-center p-3 bg-gray-700 rounded"
                    >
                        <span class="text-gray-100">
                            {{ referrer.referrer || "Direct" }}
                        </span>
                        <span class="text-yellow-400"
                            >{{ referrer.count }} visits</span
                        >
                    </div>
                </div>
            </div>
        </div>
    </CyberpunkLayout>
</template>

<script setup>
import { computed } from "vue";
import { Link } from "@inertiajs/vue3";
import CyberpunkLayout from "@/Layouts/CyberpunkLayout.vue";
import LineChart from "@/Components/Charts/LineChart.vue";
import DoughnutChart from "@/Components/Charts/DoughnutChart.vue";
import BarChart from "@/Components/Charts/BarChart.vue";

const props = defineProps({
    link: {
        type: Object,
        required: true,
    },
});

// Chart data computeds
const dailyVisitsChartData = computed(() => ({
    labels: props.link.daily_visits.map((visit) => visit.date),
    datasets: [
        {
            label: "Visits",
            data: props.link.daily_visits.map((visit) => visit.count),
            borderColor: "#FBBF24",
            backgroundColor: "rgba(251, 191, 36, 0.1)",
        },
    ],
}));

const browserChartData = computed(() => ({
    labels: props.link.browser_stats.map((stat) => stat.browser),
    datasets: [
        {
            data: props.link.browser_stats.map((stat) => stat.count),
            backgroundColor: [
                "#FBBF24",
                "#60A5FA",
                "#34D399",
                "#F87171",
                "#A78BFA",
            ],
        },
    ],
}));

const countryChartData = computed(() => ({
    labels: props.link.country_stats.map((stat) => stat.country),
    datasets: [
        {
            label: "Visits by Country",
            data: props.link.country_stats.map((stat) => stat.count),
            backgroundColor: "#FBBF24",
        },
    ],
}));
</script>
