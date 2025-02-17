<script setup>
import { Link, useForm } from "@inertiajs/vue3";
import AuthLayout from "@/Layouts/AuthLayout.vue";

const form = useForm({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
    terms: false,
});

const submit = () => {
    form.post(route("register"), {
        onFinish: () => form.reset("password", "password_confirmation"),
    });
};
</script>

<template>
    <AuthLayout>
        <div
            class="bg-gray-800 border border-yellow-400 rounded-lg p-8 shadow-xl relative overflow-hidden"
        >
            <!-- Title -->
            <h2 class="text-2xl font-bold text-yellow-400 mb-6 glitch">
                Create Account
            </h2>

            <form @submit.prevent="submit">
                <div class="space-y-6">
                    <!-- Name -->
                    <div>
                        <label class="block text-gray-100 mb-2">Name</label>
                        <input
                            v-model="form.name"
                            type="text"
                            class="cyber-input"
                            required
                            autofocus
                            autocomplete="name"
                        />
                        <div v-if="form.errors.name" class="text-red-400 mt-1">
                            {{ form.errors.name }}
                        </div>
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-gray-100 mb-2">Email</label>
                        <input
                            v-model="form.email"
                            type="email"
                            class="cyber-input"
                            required
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
                            autocomplete="new-password"
                        />
                        <div
                            v-if="form.errors.password"
                            class="text-red-400 mt-1"
                        >
                            {{ form.errors.password }}
                        </div>
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label class="block text-gray-100 mb-2"
                            >Confirm Password</label
                        >
                        <input
                            v-model="form.password_confirmation"
                            type="password"
                            class="cyber-input"
                            required
                            autocomplete="new-password"
                        />
                    </div>

                    <!-- Terms -->
                    <div class="flex items-center">
                        <input
                            v-model="form.terms"
                            type="checkbox"
                            class="cyber-checkbox"
                            name="terms"
                            required
                        />
                        <div class="ml-2">
                            <span class="text-gray-100">I agree to the</span>
                            <a
                                href="#"
                                class="ml-1 text-blue-300 hover:text-yellow-400 transition-colors duration-300"
                            >
                                Terms of Service
                            </a>
                            <span class="text-gray-100">and</span>
                            <a
                                href="#"
                                class="ml-1 text-blue-300 hover:text-yellow-400 transition-colors duration-300"
                            >
                                Privacy Policy
                            </a>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-between">
                        <Link
                            :href="route('login')"
                            class="text-blue-300 hover:text-yellow-400 transition-colors duration-300"
                        >
                            Already registered?
                        </Link>

                        <button
                            type="submit"
                            class="cyber-button"
                            :class="{ 'opacity-50': form.processing }"
                            :disabled="form.processing"
                        >
                            <span v-if="form.processing">Processing...</span>
                            <span v-else>Register</span>
                        </button>
                    </div>
                </div>
            </form>

            <!-- Decorative Elements -->
            <div class="absolute top-0 right-0 w-32 h-32 -mr-16 -mt-16">
                <div
                    class="absolute inset-0 bg-yellow-400 opacity-10 transform rotate-45"
                ></div>
            </div>
            <div class="absolute bottom-0 left-0 w-24 h-24 -ml-12 -mb-12">
                <div
                    class="absolute inset-0 bg-blue-400 opacity-10 transform -rotate-45"
                ></div>
            </div>
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

/* Animated background pattern */
@keyframes gridMove {
    0% {
        background-position: 0 0;
    }
    100% {
        background-position: 50px 50px;
    }
}

/* Input focus animation */
@keyframes inputGlow {
    0%,
    100% {
        box-shadow: 0 0 15px rgba(251, 191, 36, 0.3);
    }
    50% {
        box-shadow: 0 0 25px rgba(251, 191, 36, 0.5);
    }
}

.cyber-input:focus {
    animation: inputGlow 2s infinite;
}
</style>
