<script setup>
import { Link, useForm } from "@inertiajs/vue3";
import AuthLayout from "@/Layouts/AuthLayout.vue";

const props = defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    email: "",
    password: "",
    remember: false,
});

const submit = () => {
    form.post(route("login"), {
        onFinish: () => form.reset("password"),
    });
};
</script>

<template>
    <AuthLayout>
        <div
            class="bg-gray-800 border border-yellow-400 rounded-lg p-8 shadow-xl relative overflow-hidden"
        >
            <h2 class="text-2xl font-bold text-yellow-400 mb-6 glitch">
                Login
            </h2>

            <form @submit.prevent="submit">
                <div class="space-y-6">
                    <!-- Email -->
                    <div>
                        <label class="block text-gray-100 mb-2">Email</label>
                        <input
                            v-model="form.email"
                            type="email"
                            class="cyber-input"
                            required
                            autofocus
                        />
                        <div v-if="form.errors.email" class="text-red-400 mt-1">
                            {{ form.errors.email }}
                        </div>
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block text-gray-100 mb-2">Password</label>
                        <input
                            v-model="form.password"
                            type="password"
                            class="cyber-input"
                            required
                        />
                        <div
                            v-if="form.errors.password"
                            class="text-red-400 mt-1"
                        >
                            {{ form.errors.password }}
                        </div>
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center">
                        <input
                            v-model="form.remember"
                            type="checkbox"
                            class="cyber-checkbox"
                        />
                        <span class="ml-2 text-gray-100">Remember me</span>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-between">
                        <Link
                            v-if="canResetPassword"
                            :href="route('password.request')"
                            class="text-blue-300 hover:text-yellow-400 transition-colors duration-300"
                        >
                            Forgot password?
                        </Link>

                        <button
                            type="submit"
                            class="cyber-button"
                            :class="{ 'opacity-50': form.processing }"
                            :disabled="form.processing"
                        >
                            <span v-if="form.processing">Processing...</span>
                            <span v-else>Login</span>
                        </button>
                    </div>

                    <!-- Register Link -->
                    <div class="text-center mt-4">
                        <span class="text-gray-100"
                            >Don't have an account?</span
                        >
                        <Link
                            :href="route('register')"
                            class="ml-1 text-blue-300 hover:text-yellow-400 transition-colors duration-300"
                        >
                            Register now
                        </Link>
                    </div>
                </div>
            </form>
        </div>
    </AuthLayout>
</template>

<style scoped>
.cyber-input {
    @apply w-full bg-gray-800 border border-yellow-400 text-gray-100 rounded-md px-4 py-2 focus:ring-2 focus:ring-yellow-400 placeholder-gray-500 transition-all duration-300;
}

.cyber-input:focus {
    @apply border-transparent;
    box-shadow: 0 0 15px rgba(251, 191, 36, 0.5);
}

.cyber-checkbox {
    @apply rounded border-yellow-400 bg-gray-800 text-yellow-400 focus:ring-yellow-400 focus:ring-offset-gray-900;
}

.cyber-button {
    @apply px-6 py-2 bg-yellow-400 text-black rounded-md hover:bg-yellow-300 transition-all duration-300;
}

.cyber-button:hover {
    box-shadow: 0 0 15px rgba(251, 191, 36, 0.5);
}

.glow-border {
    box-shadow: 0 0 15px rgba(251, 191, 36, 0.3);
    animation: borderPulse 2s infinite;
}

@keyframes borderPulse {
    0%,
    100% {
        opacity: 0.3;
    }
    50% {
        opacity: 0.6;
    }
}
</style>
