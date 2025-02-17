<template>
    <Modal :show="show" @close="$emit('close')">
        <div class="p-6 text-center space-y-4">
            <h3 class="text-xl text-yellow-400 font-bold">Create an Account</h3>
            <p class="text-gray-100">Register to access advanced features:</p>
            <ul class="text-left text-gray-100 space-y-2 mb-4">
                <li class="flex items-center">
                    <span class="text-yellow-400 mr-2">✓</span>
                    Custom short codes
                </li>
                <li class="flex items-center">
                    <span class="text-yellow-400 mr-2">✓</span>
                    Set expiration dates
                </li>
                <li class="flex items-center">
                    <span class="text-yellow-400 mr-2">✓</span>
                    Save your links
                </li>
                <li class="flex items-center">
                    <span class="text-yellow-400 mr-2">✓</span>
                    View link history
                </li>
            </ul>
            <div class="flex justify-center space-x-4">
                <Link
                    :href="route('register')"
                    class="px-4 py-2 bg-yellow-400 text-black rounded-md hover:bg-yellow-300 transition-colors duration-300"
                >
                    Register Now
                </Link>
                <Link
                    :href="route('login')"
                    class="px-4 py-2 bg-gray-800 text-yellow-400 border border-yellow-400 rounded-md hover:bg-gray-700 transition-colors duration-300"
                >
                    Login
                </Link>
            </div>
        </div>
    </Modal>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
import Modal from "@/Components/Modal.vue";

defineProps({
    show: {
        type: Boolean,
        default: false,
    },
});

defineEmits(["close"]);
</script>
