<template>
    <CyberpunkLayout>
        <div class="max-w-md mx-auto">
            <div class="bg-gray-800 p-6 rounded-lg border border-yellow-400">
                <h1 class="text-2xl text-yellow-400 font-bold mb-4">
                    Password Protected Link
                </h1>

                <form @submit.prevent="verifyPassword">
                    <div class="space-y-4">
                        <div
                            v-if="$page.props.errors.password"
                            class="text-red-400"
                        >
                            {{ $page.props.errors.password }}
                        </div>

                        <input
                            v-model="form.password"
                            type="password"
                            placeholder="Enter password"
                            class="w-full bg-gray-800 border border-yellow-400 text-gray-100 rounded-md px-4 py-2 focus:ring-2 focus:ring-yellow-400 placeholder-gray-500"
                            required
                        />

                        <div class="flex justify-end space-x-4">
                            <Link
                                :href="route('home')"
                                class="px-4 py-2 bg-gray-800 text-yellow-400 border border-yellow-400 rounded-md hover:bg-gray-700 transition-colors duration-300"
                            >
                                Cancel
                            </Link>
                            <button
                                type="submit"
                                class="px-4 py-2 bg-yellow-400 text-black rounded-md hover:bg-yellow-300 transition-colors duration-300"
                                :disabled="processing"
                            >
                                <span v-if="processing">Verifying...</span>
                                <span v-else>Continue</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </CyberpunkLayout>
</template>

<script setup>
import { ref } from "vue";
import { Link, router } from "@inertiajs/vue3";
import CyberpunkLayout from "@/Layouts/CyberpunkLayout.vue";

const props = defineProps({
    link_id: Number,
    short_code: String,
});

const form = ref({
    password: "",
});

const processing = ref(false);

const verifyPassword = () => {
    processing.value = true;
    router.post(route("links.verify-password", props.link_id), form.value, {
        onFinish: () => {
            processing.value = false;
        },
    });
};
</script>
