<script setup>
import { ref } from "vue";
import axios from "axios";
import {Head, usePage} from '@inertiajs/vue3';

const user = usePage().props.auth.user;

// Profile form
let name = ref(user.name);
let email = ref(user.email);
let profileErrors = ref({});

// Password form
let currentPassword = ref('');
let password = ref('');
let passwordErrors = ref({});

// Forgot password form
const forgotSubmitting = ref(false);
const passwordSubmitting = ref(false);
const profileSubmitting = ref(false);

const sendForgotPassword = async () => {
    forgotSubmitting.value = true;
    try {
        await axios.post('/forgot-password', { email: user.email });
        //todo add cute notif
        alert('Password reset link sent!');
    } catch (e) {
        console.error(e);
    } finally {
        forgotSubmitting.value = false;
    }
};

const updatePassword = async () => {
    passwordSubmitting.value = true;
    passwordErrors.value = {};
    try {
        await axios.put('/password', {
            current_password: currentPassword.value,
            password: password.value,
        });
        //todo add cute notif
        console.log('Password updated!');
        currentPassword.value = '';
        password.value = '';
    } catch (err) {
        if (err.response?.data?.errors) {
            passwordErrors.value = err.response.data.errors;
        }
    } finally {
        passwordSubmitting.value = false;
    }
};

const updateProfile = async () => {
    profileSubmitting.value = true;
    profileErrors.value = {};
    try {
        await axios.patch('/profile', {
            name: name.value,
            email: email.value
        });
        //todo add cute notif
        alert('Profile updated!');
    } catch (err) {
        if (err.response?.data?.errors) {
            profileErrors.value = err.response.data.errors;
        }
    } finally {
        profileSubmitting.value = false;
    }
};
</script>

<template>

        <div class="settings-container py-4 space-y-8 ">

            <!-- Profile Information -->
            <section>
                <header>
                    <h2 class="text-lg text-dark">Profile Information</h2>
                    <p class="text-sm text-gray-400">Update your account's profile information and email address</p>
                </header>

                <form @submit.prevent="updateProfile" class="mt-4 space-y-3">
                    <div>
                        <label class="block text-sm text-gray-300">Name</label>
                        <input type="text" v-model="name" class="w-full max-w-80 p-2 rounded bg-gray-800 border border-gray-700 text-gray-200"
                               :class="{'border-red-500': profileErrors.name}">
                        <p v-if="profileErrors.name" class="text-red-500 text-sm">{{ profileErrors.name[0] }}</p>
                    </div>

                    <div>
                        <label class="block text-sm text-gray-300">Email</label>
                        <input type="email" v-model="email" class="w-full max-w-80 p-2 rounded bg-gray-800 border border-gray-700 text-gray-200"
                               :class="{'border-red-500': profileErrors.email}">
                        <p v-if="profileErrors.email" class="text-red-500 text-sm">{{ profileErrors.email[0] }}</p>
                    </div>

                    <div v-if="!user.email_verified_at" class="text-sm text-red-500">
                        Your email address is not verified.
                        <button @click.prevent="$inertia.post('/email/verification-notification')" class="text-blue-400 ml-1 underline">Resend Verification Email</button>
                    </div>

                    <button type="submit" :disabled="profileSubmitting" class="w-full max-w-80 py-2 bg-dark text-white rounded mt-2">
                        {{ profileSubmitting ? 'Updating...' : 'Update Profile' }}
                    </button>
                </form>
            </section>

            <!-- Update Password -->
            <section>
                <header>
                    <h2 class="text-lg text-dark">Update Password</h2>
                    <p class="text-sm text-gray-400">Ensure your account is using a long, random password to stay secure</p>
                </header>

                <form @submit.prevent="updatePassword" class="mt-4 space-y-3">
                    <div>
                        <label class="block text-sm text-gray-300">Current Password</label>
                        <input type="password" v-model="currentPassword" class="w-full max-w-80 p-2 rounded bg-gray-800 border border-gray-700 text-gray-200"
                               :class="{'border-red-500': passwordErrors.current_password}">
                        <p v-if="passwordErrors.current_password" class="text-red-500 text-sm">{{ passwordErrors.current_password[0] }}</p>
                    </div>

                    <div>
                        <label class="block text-sm text-gray-300">New Password</label>
                        <input type="password" v-model="password" class="w-full max-w-80 p-2 rounded bg-gray-800 border border-gray-700 text-gray-200"
                               :class="{'border-red-500': passwordErrors.password}">
                        <p v-if="passwordErrors.password" class="text-red-500 text-sm">{{ passwordErrors.password[0] }}</p>
                    </div>

                    <button type="submit" :disabled="passwordSubmitting" class="w-full max-w-80 py-2 bg-dark text-white rounded mt-2">
                        {{ passwordSubmitting ? 'Updating...' : 'Update Password' }}
                    </button>
                </form>
            </section>

            <!-- Forgot Password -->
            <section>
                <header>
                    <h2 class="text-lg text-dark">Forgot password?</h2>
                    <p class="text-sm text-gray-400">Don’t worry, we will help you</p>
                </header>

                <button @click="sendForgotPassword" :disabled="forgotSubmitting" class="mt-4 w-full max-w-80 py-2 bg-dark text-white rounded">
                    {{ forgotSubmitting ? 'Sending...' : 'Forgot Password' }}
                </button>
            </section>

        </div>

</template>

<style scoped>
.bg-dark {
    background-color: #1f1f1f;
}
</style>
