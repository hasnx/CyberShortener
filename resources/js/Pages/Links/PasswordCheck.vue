<template>
    <CyberpunkLayout>
        <div class="max-w-md mx-auto">
            <div class="bg-gray-800 p-6 rounded-lg border border-yellow-400">
                <h1 class="text-2xl text-yellow-400 font-bold mb-4">
                    Protected Link
                </h1>

                <form
                    :action="route('links.check-password', shortCode)"
                    method="POST"
                >
                    <input type="hidden" name="_token" :value="csrf" />

                    <div class="space-y-4">
                        <div
                            v-if="$page.props.errors?.password"
                            class="text-red-400"
                        >
                            {{ $page.props.errors.password }}
                        </div>

                        <div>
                            <label class="block text-gray-100 mb-2"
                                >Password Required</label
                            >
                            <input
                                name="password"
                                type="password"
                                class="w-full bg-gray-800 border border-yellow-400 text-gray-100 rounded-md px-4 py-2 focus:ring-2 focus:ring-yellow-400 placeholder-gray-500"
                                placeholder="Enter password to access link"
                                required
                            />
                        </div>

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
                            >
                                Continue
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </CyberpunkLayout>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
import CyberpunkLayout from "@/Layouts/CyberpunkLayout.vue";

const props = defineProps({
    shortCode: String,
    csrf: String,
});
</script>
