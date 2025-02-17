<script setup>
import { Link } from "@inertiajs/vue3";
import CyberpunkLayout from "@/Layouts/CyberpunkLayout.vue";
import DeleteUserForm from "./Partials/DeleteUserForm.vue";
import LogoutOtherBrowserSessionsForm from "./Partials/LogoutOtherBrowserSessionsForm.vue";
import TwoFactorAuthenticationForm from "./Partials/TwoFactorAuthenticationForm.vue";
import UpdatePasswordForm from "./Partials/UpdatePasswordForm.vue";
import UpdateProfileInformationForm from "./Partials/UpdateProfileInformationForm.vue";

defineProps({
    confirmsTwoFactorAuthentication: Boolean,
    sessions: Array,
});
</script>

<template>
    <CyberpunkLayout>
        <div class="max-w-7xl mx-auto py-10 space-y-8">
            <!-- Profile Header -->
            <div class="cyber-panel">
                <div class="cyber-panel-header">
                    <span class="text-yellow-400">[</span>
                    PROFILE_SETTINGS
                    <span class="text-yellow-400">]</span>
                </div>
            </div>

            <!-- Profile Information -->
            <div class="cyber-panel">
                <div class="cyber-panel-header">
                    <span class="text-yellow-400">[</span>
                    PROFILE_INFO
                    <span class="text-yellow-400">]</span>
                </div>
                <div class="cyber-panel-content">
                    <UpdateProfileInformationForm
                        :user="$page.props.auth.user"
                    />
                </div>
            </div>

            <!-- Update Password -->
            <div class="cyber-panel">
                <div class="cyber-panel-header">
                    <span class="text-yellow-400">[</span>
                    UPDATE_PASSWORD
                    <span class="text-yellow-400">]</span>
                </div>
                <div class="cyber-panel-content">
                    <UpdatePasswordForm />
                </div>
            </div>

            <!-- Two Factor Authentication -->
            <div class="cyber-panel">
                <div class="cyber-panel-header">
                    <span class="text-yellow-400">[</span>
                    2FA_SECURITY
                    <span class="text-yellow-400">]</span>
                </div>
                <div class="cyber-panel-content">
                    <TwoFactorAuthenticationForm
                        :confirms-password="confirmsTwoFactorAuthentication"
                    />
                </div>
            </div>

            <!-- Browser Sessions -->
            <div class="cyber-panel">
                <div class="cyber-panel-header">
                    <span class="text-yellow-400">[</span>
                    BROWSER_SESSIONS
                    <span class="text-yellow-400">]</span>
                </div>
                <div class="cyber-panel-content">
                    <LogoutOtherBrowserSessionsForm :sessions="sessions" />
                </div>
            </div>

            <!-- Delete Account -->
            <div class="cyber-panel border-red-500">
                <div class="cyber-panel-header text-red-400">
                    <span class="text-red-400">[</span>
                    DANGER_ZONE
                    <span class="text-red-400">]</span>
                </div>
                <div class="cyber-panel-content">
                    <DeleteUserForm />
                </div>
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
    @apply p-6;
}

/* Special styling for danger zone */
.cyber-panel.border-red-500::before {
    background: linear-gradient(
        90deg,
        transparent,
        rgba(239, 68, 68, 0.5),
        transparent
    );
}
</style>
