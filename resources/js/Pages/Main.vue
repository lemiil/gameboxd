<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});

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
    <Head title="Main" />

    <div class="min-h-screen flex flex-col bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200 transition-colors duration-300">
        <!-- header -->
        <header class="bg-gray-100 dark:bg-gray-800 shadow-sm">
            <div class="container mx-auto px-4 py-4 flex justify-between items-center">
                <h1 class="text-2xl font-bold tracking-tight">Gameboxd</h1>

                <div class="flex items-center gap-6">
                    <!-- theme changer -->
                    <button
                        @click="toggleTheme"
                        class="text-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 rounded transition"
                        :aria-label="theme === 'light' ? 'Switch to dark mode' : 'Switch to light mode'"
                        :title="theme === 'light' ? 'Switch to dark mode' : 'Switch to light mode'"
                    >
                        <span v-if="theme === 'light'">🌙</span>
                        <span v-else>☀️</span>
                    </button>

                    <!-- nav -->
                    <nav v-if="canLogin" class="flex items-center gap-4 text-sm font-medium">
                        <Link
                            v-if="$page.props.auth.user"
                            :href="route('dashboard')"
                            class="hover:underline"
                        >
                            {{ $page.props.auth.user.name || 'Dashboard' }}
                        </Link>

                        <template v-else>
                            <Link :href="route('login')" class="hover:underline">
                                Log in
                            </Link>
                            <Link v-if="canRegister" :href="route('register')" class="hover:underline">
                                Register
                            </Link>
                        </template>
                    </nav>
                </div>
            </div>
        </header>

        <!-- main -->
        <main class="flex-1 container mx-auto px-4 py-10">
            <h2 class="text-3xl font-semibold mb-4">Welcome</h2>
            <p class="text-lg text-gray-600 dark:text-gray-400 mb-6">
                Blah blah blah...
            </p>
            <span class="text-sm text-gray-500 dark:text-gray-400">Current theme: {{ theme }}</span>
        </main>

        <!-- footer -->
        <footer class="bg-gray-100 dark:bg-gray-800 py-6 mt-auto shadow-inner">
            <div class="container mx-auto text-center text-sm text-gray-500 dark:text-gray-400 space-y-2">
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
