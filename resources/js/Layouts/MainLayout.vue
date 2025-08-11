<script setup>

import { Link } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';
import Main from "@/Pages/Main.vue";

import { usePage } from '@inertiajs/vue3'
const page = usePage()

const theme = ref('light');

onMounted(() => {
    theme.value = 'dark';
    document.documentElement.classList.toggle('dark', theme.value === 'dark');
});

</script>

<template>
    <div class="min-h-screen flex flex-col bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200 transition-colors duration-300">
        <!-- header -->
        <header class="bg-gray-100 dark:bg-gray-800 shadow-sm">
            <div class="max-w-6xl mx-auto px-4 py-4 flex justify-between items-center">
                <Link :href="route('main')" class="text-2xl font-bold tracking-tight">Gameboxd</Link>

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

        <main v-if="page.component !== 'Game/GameShow'" class="flex-1 max-w-6xl mx-auto px-4 py-10">
            <slot />
        </main>
        <slot v-else />

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
