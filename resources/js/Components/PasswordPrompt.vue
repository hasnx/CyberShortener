<template>
    <Modal :show="show" @close="$emit('close')">
        <div class="p-6 space-y-4">
            <h3 class="text-xl text-yellow-400 font-bold">Protected Link</h3>
            <p class="text-gray-100">
                This link is password protected. Please enter the password to
                continue.
            </p>

            <form @submit.prevent="verifyPassword">
                <div class="space-y-4">
                    <input
                        v-model="password"
                        type="password"
                        placeholder="Enter password"
                        class="w-full bg-gray-800 border border-yellow-400 text-gray-100 rounded-md px-4 py-2 focus:ring-2 focus:ring-yellow-400 placeholder-gray-500"
                        required
                    />
                    <div class="flex justify-end space-x-4">
                        <button
                            type="button"
                            @click="$emit('close')"
                            class="px-4 py-2 bg-gray-800 text-yellow-400 border border-yellow-400 rounded-md hover:bg-gray-700 transition-colors duration-300"
                        >
                            Cancel
                        </button>
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
    </Modal>
</template>

<script setup>
import { ref } from "vue";
import Modal from "@/Components/Modal.vue";

const props = defineProps({
    show: Boolean,
});

const emit = defineEmits(["close", "verified"]);
const password = ref("");

const verifyPassword = () => {
    emit("verified", password.value);
    password.value = "";
};
</script>
