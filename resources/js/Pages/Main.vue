<script setup>
import {Head, Link} from '@inertiajs/vue3';
import {onMounted, ref} from 'vue';

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
    if (savedTheme === 'dark') {
        theme.value = 'dark';
        document.documentElement.classList.add('dark');
    }
});
</script>

<template>
    <Head title="Main"/>

    <div
        class="min-h-screen flex flex-col bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200 transition-colors">
        <header class="bg-gray-100 dark:bg-gray-800 shadow">
            <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
                <h1 class="text-xl font-semibold">Gameboxd</h1>

                <div class="flex gap-4 items-center">
                    <button
                        @click="toggleTheme"
                        :title="theme === 'light' ? 'Switch to dark mode' : 'Switch to light mode'"
                    >
                        <span v-if="theme === 'light'">🌙</span>
                        <span v-else>☀️</span>
                    </button>


                    <nav v-if="canLogin" class="space-x-4">
                        <Link v-if="$page.props.auth.user" :href="route('dashboard')" class="hover:underline">
                            Insert name
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


        <main class="flex-1 max-w-7xl mx-auto px-4 py-8">
            <h2 class="text-2xl font-bold">Welcome</h2>
            <p class="mt-4">Blah blah blah...</p>
            <span>{{ theme }}</span>
        </main>


        <footer class="bg-gray-100 dark:bg-gray-800 text-center py-4 shadow-inner">
            <nav class="space-x-4">
                <Link href="#" class="hover:underline">Home</Link>
                <Link href="#" class="hover:underline">About</Link>
                <Link href="#" class="hover:underline">Contact</Link>
            </nav>
            <p class="mt-2">2025 Lemiil</p>
        </footer>
    </div>
</template>
