<script setup>
import { Head } from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";

defineProps({
    game: {
        type: Object,
    },
});
</script>

<template>
    <Head :title="game.name" />
    <MainLayout>
        <div class="max-w-5xl mx-auto p-4">
            <h1 class="text-3xl font-bold mb-4">{{ game.name }}</h1>

            <div class="flex flex-col md:flex-row gap-6">
                <div class="flex justify-center">
                    <img
                        :src="game.cover_url.startsWith('//') ? 'https:' + game.cover_url : game.cover_url"
                        :alt="game.name + ' Cover'"
                        class="w-40 sm:w-48 md:w-56 h-auto border-2 border-gray-700 rounded"
                    />
                </div>



                <div class="md:flex-1 space-y-4">
                    <p v-if="game.release_date" class="text-gray-600">
                        <strong>Release date:</strong> {{ new Date(game.release_date).toLocaleDateString() }}
                    </p>
                    <p v-else class="text-gray-600 italic">Release date: TBD</p>

                    <p>{{ game.summary }}</p>

                    <div>
                        <strong>Genres:</strong>
                        <span v-for="(genre, index) in game.genres" :key="index" class="mr-2">
                            {{ genre.name }}<span v-if="index < game.genres.length - 1">,</span>
                        </span>
                    </div>

                    <div>
                        <strong>Themes:</strong>
                        <span v-for="(theme, index) in game.themes" :key="index" class="mr-2">
                            {{ theme.name }}<span v-if="index < game.themes.length - 1">,</span>
                        </span>
                    </div>

                    <div>
                        <strong>Platforms:</strong>
                        <span v-for="(platform, index) in game.platforms" :key="index" class="mr-2">
                            {{ platform.name }}<span v-if="index < game.platforms.length - 1">,</span>
                        </span>
                    </div>

                    <div>
                        <strong>Companies:</strong>
                        <span v-for="(company, index) in game.companies" :key="index" class="mr-2">
                            {{ company.name }}<span v-if="index < game.companies.length - 1">,</span>
                        </span>
                    </div>
                </div>
            </div>

            <div v-if="game.screenshots && game.screenshots.length" class="mt-8">
                <h2 class="text-2xl font-semibold mb-4">Screenshots</h2>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4">
                    <img
                        v-for="screenshot in game.screenshots"
                        :key="screenshot.id"
                        :src="screenshot.url.startsWith('//') ? 'https:' + screenshot.url : screenshot.url"
                        :alt="`Screenshot ${screenshot.id} of ${game.name}`"
                        class="w-full h-auto rounded border border-gray-300 object-cover"
                    />
                </div>
            </div>
        </div>
    </MainLayout>
</template>

<style scoped>
</style>
