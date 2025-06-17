<script setup>

import { Link } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';
import Main from "@/Pages/Main.vue";

const theme = ref('light');

const toggleTheme = () => {
    theme.value = theme.value === 'light' ? 'dark' : 'light';
    document.documentElement.classList.toggle('dark', theme.value === 'dark');
    localStorage.setItem('theme', theme.value);
};

onMounted(() => {
    const savedTheme = localStorage.getItem('theme');
    const darkThemeMq = window.matchMedia("(prefers-color-scheme: dark)");

    if (savedTheme) {
        theme.value = savedTheme;
    } else if (darkThemeMq.matches) {
        theme.value = 'dark';
    }

    document.documentElement.classList.toggle('dark', theme.value === 'dark');
});

</script>

<template>
    <div class="min-h-screen flex flex-col bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200 transition-colors duration-300">
        <!-- header -->
        <header class="bg-gray-100 dark:bg-gray-800 shadow-sm">
            <div class="max-w-6xl mx-auto px-4 py-4 flex justify-between items-center">
                <h1 class="text-2xl font-bold tracking-tight">Gameboxd</h1>

                <!-- mobile -->
<!--                <button-->
<!--                    v-if="$page.props.auth.user"-->
<!--                    @click="isMenuOpen = !isMenuOpen"-->
<!--                    class="lg:hidden text-2xl focus:outline-none"-->
<!--                    aria-label="Toggle menu"-->
<!--                >-->
<!--                    ☰-->
<!--                </button>-->

                <!-- right nav -->
                <div class="hidden lg:flex items-center gap-6">
                    <button
                        @click="toggleTheme"
                        class="text-xl focus:outline-none rounded transition"
                        :aria-label="theme === 'light' ? 'Switch to dark mode' : 'Switch to light mode'"
                        :title="theme === 'light' ? 'Switch to dark mode' : 'Switch to light mode'"
                    >
                        <span v-if="theme === 'light'">🌙</span>
                        <span v-else>☀️</span>
                    </button>

                    <nav class="flex items-center gap-4 text-sm font-medium">
                        <template v-if="$page.props.auth.user">
                            <Link :href="route('dashboard')" class="hover:underline">
                                {{ $page.props.auth.user.name || 'Dashboard' }}
                            </Link>
                            <Link :href="route('logout')" method="post" as="button" class="hover:underline">
                                Log out
                            </Link>
                        </template>
                        <template v-else>
                            <Link :href="route('login')" class="hover:underline">Log in</Link>
                            <Link :href="route('register')" class="hover:underline">Register</Link>
                        </template>
                    </nav>
                </div>
            </div>

            <!-- mobile menu -->
            <div
                v-if="isMenuOpen"
                class="lg:hidden px-4 pb-4 max-w-4xl mx-auto"
            >
                <div class="flex flex-col gap-4 text-sm font-medium">
                    <Link :href="route('dashboard')" class="hover:underline">
                        {{ $page.props.auth.user.name || 'Dashboard' }}
                    </Link>
                    <Link :href="route('logout')" method="post" as="button" class="hover:underline">
                        Log out
                    </Link>
                </div>
            </div>
        </header>

        <!-- main -->
        <main class="flex-1 max-w-4xl mx-auto px-4 py-10">
            <slot />
        </main>

        <!-- footer -->
        <footer class="bg-gray-100 dark:bg-gray-800 py-6 mt-auto shadow-inner">
            <div class="max-w-4xl mx-auto text-center text-sm text-gray-500 dark:text-gray-400 space-y-2">
                <nav class="flex justify-center space-x-6">
                    <Link href="#" class="hover:underline">Home</Link>
                    <Link href="#" class="hover:underline">About</Link>
                    <Link href="#" class="hover:underline">Contact</Link>
                </nav>
                <p>&copy; 2025 lemiil</p>
            </div>
        </footer>
    </div>
</template>

<style scoped>

</style>
