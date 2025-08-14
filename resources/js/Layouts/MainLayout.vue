<script setup>
import { Link } from '@inertiajs/vue3'
import { onMounted, ref, watch } from 'vue'
import axios from 'axios'
import { usePage } from '@inertiajs/vue3'

const page = usePage()
const theme = ref('light')
const searchQuery = ref('')
const searchResults = ref([])
let debounceTimer = null

onMounted(() => {
    theme.value = 'dark'
    document.documentElement.classList.toggle('dark', theme.value === 'dark')
})

watch(searchQuery, (newValue) => {
    clearTimeout(debounceTimer)
    if (!newValue) {
        searchResults.value = []
        return
    }
    debounceTimer = setTimeout(async () => {
        try {
            const { data } = await axios.get(route('game.search'), { params: { q: newValue } })
            searchResults.value = data
        } catch (error) {
            console.error(error)
        }
    }, 300)
})
</script>



<template>
    <div class="min-h-screen flex flex-col bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200 transition-colors duration-300">
        <!-- header -->
        <header class="bg-gray-100 dark:bg-gray-800 shadow-sm">
            <div class="max-w-6xl mx-auto px-4 py-4 flex justify-between items-center">
                <Link :href="route('main')" class="text-2xl font-bold tracking-tight">Gameboxd</Link>

                <!-- right nav -->
                <div class="hidden lg:flex items-center gap-6">
                    <nav class="flex items-center gap-4 text-sm font-medium">
                        <div class="relative">
                            <input
                                v-model="searchQuery"
                                type="text"
                                placeholder="Search..."
                                class="px-3 py-1 rounded-l border border-gray-300 focus:ring-1 focus:ring-red-600 dark:border-gray-700 dark:bg-gray-900"
                            />
                            <div
                                v-if="searchResults.length"
                                class="absolute left-0 right-0 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 mt-1 rounded shadow-lg z-50"
                            >
                                <ul class="max-h-60 overflow-y-auto">
                                    <li
                                        v-for="game in searchResults"
                                        :key="game.slug"
                                        class="px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700"
                                    >
                                        <Link :href="route('game.show', game.slug)">
                                            {{ game.name }}
                                        </Link>
                                    </li>
                                </ul>
                            </div>
                        </div>


                        <template v-if="$page.props.auth.user">

                            <!-- Никнейм -->
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
