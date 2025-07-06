
<script setup>
import { Head } from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";
import { ref, computed } from "vue";

const { game } = defineProps({
    game: {
        type: Object,
    },
});

const currentScreenshotIndex = ref(0);

const formattedDate = computed(() => {
    if (!game.release_date) return 'TBD';
    const date = new Date(game.release_date);
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return `Released on ${date.toLocaleDateString(undefined, options)}`;
});

const nextScreenshot = () => {
    currentScreenshotIndex.value = (currentScreenshotIndex.value + 1) % game.screenshots.length;
};

const prevScreenshot = () => {
    currentScreenshotIndex.value =
        (currentScreenshotIndex.value - 1 + game.screenshots.length) % game.screenshots.length;
};
</script>

<template>
    <pre>
</pre>
    <Head :title="game.name" />
    <MainLayout>
        <div class="relative">
            <div
                v-if="game.screenshots?.[0]?.url"
                class="absolute top-0 left-0 w-full h-[50vh] bg-cover bg-center opacity-30 z-0"
                :style="{
                    backgroundImage: `url(${game.screenshots[0].url.startsWith('//') ? 'https:' + game.screenshots[0].url : game.screenshots[0].url})`,
                }"
            >
                <div class="absolute bottom-0 left-0 w-full h-1/3 bg-gradient-to-t from-[#1e1e20] to-transparent"></div>
            </div>

            <div class="absolute top-0 left-0 w-full h-[50vh] bg-gradient-to-b from-black/60 to-transparent z-10"></div>

            <div class="relative z-20 max-w-5xl mx-auto p-4">
                <div class="flex flex-col mt-40 md:flex-row gap-6">
                    <div class="flex justify-center">
                        <img
                            v-if="game.cover_url"
                            :src="game.cover_url.startsWith('//') ? 'https:' + game.cover_url : game.cover_url"
                            :alt="`${game.name} Cover`"
                            class="h-60 w-full border-2 border-gray-700 rounded object-cover"
                        />
                        <div
                            v-else
                            class="h-60 w-40 flex items-center justify-center border-2 border-dashed border-gray-700 rounded bg-gray-800 text-gray-400 text-center"
                        >
                            {{ game.name }}
                        </div>
                    </div>

                    <div class="md:flex-1 space-y-3">
                        <h1 class="text-3xl font-bold mb-4">{{ game.name }}</h1>

                        <h2 class="text-2xl pb-3 border-b-2">
                            <strong>{{ formattedDate }}</strong>
                        </h2>

                        <p>{{ game.summary }}</p>

                        <div v-if="game.genres?.length">
                            <strong>Genres:</strong>
                            <span
                                v-for="(genre, index) in game.genres"
                                :key="genre.id || index"
                                class="mr-2"
                            >
                                {{ genre.name }}<span v-if="index < game.genres.length - 1">,</span>
                            </span>
                        </div>

                        <div v-if="game.themes?.length">
                            <strong>Themes:</strong>
                            <span
                                v-for="(theme, index) in game.themes"
                                :key="theme.id || index"
                                class="mr-2"
                            >
                                {{ theme.name }}<span v-if="index < game.themes.length - 1">,</span>
                            </span>
                        </div>

                        <div v-if="game.platforms?.length">
                            <strong>Platforms:</strong>
                            <span
                                v-for="(platform, index) in game.platforms"
                                :key="platform.id || index"
                                class="mr-2"
                            >
                                {{ platform.name }}<span v-if="index < game.platforms.length - 1">,</span>
                            </span>
                        </div>

                        <div v-if="game.companies?.length">
                            <strong>Companies:</strong>
                            <span
                                v-for="(company, index) in game.companies"
                                :key="company.id || index"
                                class="mr-2"
                            >
                                {{ company.name }}<span v-if="index < game.companies.length - 1">,</span>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="mt-8">
                    <div v-if="game.screenshots?.length" class="relative w-full max-w-4xl mx-auto">
                        <img
                            :src="game.screenshots[currentScreenshotIndex].url.startsWith('//') ? 'https:' + game.screenshots[currentScreenshotIndex].url : game.screenshots[currentScreenshotIndex].url"
                            :alt="`Screenshot ${currentScreenshotIndex + 1} of ${game.name}`"
                            class="w-full h-auto rounded border border-gray-300 object-cover"
                        />
                        <div class="absolute inset-0 flex items-center justify-between px-4">
                            <button @click="prevScreenshot" class="bg-black/50 hover:bg-black/70 text-white px-2 py-1 rounded">‹</button>
                            <button @click="nextScreenshot" class="bg-black/50 hover:bg-black/70 text-white px-2 py-1 rounded">›</button>
                        </div>
                        <p class="text-center mt-2 text-sm text-gray-400">
                            {{ currentScreenshotIndex + 1 }} / {{ game.screenshots.length }}
                        </p>
                    </div>
                    <p v-else class="italic text-gray-400">No screenshots available.</p>
                </div>
            </div>
        </div>
    </MainLayout>
</template>
