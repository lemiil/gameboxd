<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import Account from "@/Pages/Settings/Settings/Account.vue";
import UpdateAvatar from "@/Pages/Settings/Settings/UpdateAvatar.vue";
import {ref} from "vue";

const sections = ref([
    {id: 'profile', name: 'Profile', description: 'Customize your avatar and profile', icon: '👤'},
    {id: 'notifications', name: 'Notifications', description: 'Customize your notificationss', icon: '🔔'},
    {id: 'account', name: 'Account', description: 'Update your email / password', icon: '🔒'},
    {id: 'blocked', name: 'Blocked', description: 'Update your blocked', icon: '🚬'},
]);

const activeSection = ref('profile');

const selectSection = (id) => {
    activeSection.value = id;
};
</script>

<template>
    <MainLayout>
        <div class="flex flex-col max-w-6xl mx-auto px-4 py-10">
            <h1 class="text-2xl font-bold mb-6">Settings</h1>

            <div class="flex min-h-[70vh] bg-gray-900 text-gray-200 rounded-lg overflow-hidden">
                <!-- Sidebar -->
                <aside class="w-64 bg-gray-800 border-r border-gray-700 p-4 flex flex-col">
                    <ul class="space-y-2 flex-1">
                        <li
                            v-for="section in sections"
                            :key="section.id"
                            @click="selectSection(section.id)"
                            :class="[
                            'cursor-pointer px-3 py-2 rounded hover:bg-gray-700 flex flex-col transition-colors',
                            activeSection === section.id ? 'bg-gray-700 font-semibold' : ''
                        ]"
                        >
                        <span class="flex items-center space-x-2">
                            <span>{{ section.icon }}</span>
                            <span>{{ section.name }}</span>
                        </span>
                            <small class="text-gray-400 text-xs mt-0.5">{{ section.description }}</small>
                        </li>
                    </ul>
                </aside>

                <section class="flex-1 p-6 bg-gray-900">
                    <div v-if="activeSection === 'profile'">
                        <h2 class="text-xl font-bold mb-4">Profile Settings</h2>
                        <UpdateAvatar></UpdateAvatar>
                    </div>
                    <div v-else-if="activeSection === 'notifications'">
                        <h2 class="text-xl font-bold mb-4">Notification Settings</h2>
                        <p>Lorem ipsum dolor sit
                            amet.</p>
                    </div>
                    <div v-else-if="activeSection === 'account'">
                        <h2 class="text-xl font-bold mb-4">Account Settings</h2>
                        <Account></Account>
                    </div>
                </section>
            </div>
        </div>
    </MainLayout>
</template>
