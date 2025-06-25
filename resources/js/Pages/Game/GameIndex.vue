<script setup>
import { Head, Link } from '@inertiajs/vue3'
import MainLayout from '@/Layouts/MainLayout.vue'

const props = defineProps({
    games: Object,
})
</script>

<template>
    <Head title="Games" />

    <MainLayout>
        <h1 class="text-2xl font-bold mb-6">Games</h1>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4">
            <div
                v-for="game in games.data"
                :key="game.id"
                class="bg-white rounded shadow overflow-hidden"
            >
                <Link :href="route('game.show', { slug: game.slug })">
                    <div class="relative">
                        <img
                            :src="game.cover_url"
                            alt="Cover"
                            class="w-full h-72 object-cover"
                        />
                        <div
                            class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-60 text-white text-center text-sm p-2"
                        >
                            {{ game.name }}
                        </div>
                    </div>
                </Link>
            </div>
        </div>

        <div class="mt-6 flex justify-center">
            <div v-html="games.links" />
        </div>
    </MainLayout>
</template>
