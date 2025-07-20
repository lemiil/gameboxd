<script setup>
import {Head} from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";
import GameMetaInfo from "@/Components/GameShow/GameMetaInfo.vue";
import ReviewBlock from "@/Components/GameShow/ReviewBlock.vue";

import {ref, computed} from "vue";

const {game} = defineProps({
    game: {
        type: Object,
    },
});

const normalizeUrl = (url) => {
    return url?.startsWith("//") ? "https:" + url : url;
};

const formattedDate = computed(() => {
    if (!game.release_date) return "TBD";
    const date = new Date(game.release_date);
    const options = {year: "numeric", month: "long", day: "numeric"};
    return date.toLocaleDateString(undefined, options);
});

const zoomedImage = ref(null);
const openImage = (url) => {
    zoomedImage.value = normalizeUrl(url);
};
const closeImage = () => {
    zoomedImage.value = null;
};

</script>

<template>
    <Head :title="game.name"/>
    <MainLayout>
        <div class="relative">
            <div
                v-if="game.screenshots?.[0]?.url"
                class="absolute top-0 left-0 w-full h-[50vh] bg-cover bg-center opacity-20 z-0 transition-opacity duration-1000"
                :style="{ backgroundImage: `url(${normalizeUrl(game.screenshots[0].url)})` }"
            >
                <div class="absolute bottom-0 left-0 w-full h-1/3 bg-gradient-to-t from-[#1e1e20] to-transparent"></div>
            </div>

            <div class="relative z-20 max-w-6xl mx-auto mt-10 p-8">
                <div class="flex flex-col mt-40 md:flex-row gap-6">
                    <div class="flex flex-col items-center">
                        <div class="flex justify-center">
                            <img
                                v-if="game.cover_url"
                                :src="normalizeUrl(game.cover_url)"
                                :alt="`${game.name} Cover`"
                                class="w-full max-w-[175px] h-60 border-2 border-gray-700 rounded"
                            />
                            <div
                                v-else
                                class="h-60 w-40 max-w-[400px] flex items-center justify-center border-2 border-dashed border-gray-700 rounded bg-gray-800 text-gray-400 text-center"
                            >
                                {{ game.name }}
                            </div>
                        </div>
                        <ReviewBlock :game="game"/>
                    </div>


                    <div class="flex flex-col md:flex-row gap-6 md:flex-1">
                        <div class="w-full flex flex-col items-center text-center space-y-4 max-w-3xl mx-auto">
                            <h1 class="text-3xl font-bold">{{ game.name }}</h1>

                            <div class="border-b-2 pb-2">
                                <span class="text-gray-300">Released on </span>
                                <strong> {{ formattedDate }} </strong>
                                <div v-if="game.companies?.length">
                                    by
                                    <span
                                        v-for="(company, index) in game.companies"
                                        :key="company.id || index"
                                        class="mr-2"
                                    >
                                        <strong>
                                            {{ company.name }}<span
                                            v-if="index < game.companies.length - 1"
                                        >,</span
                                        >
                                        </strong>
                                    </span>
                                </div>
                            </div>

                            <p class="text-gray-200">{{ game.summary }}</p>
                        </div>

                        <GameMetaInfo
                            :genres="game.genres"
                            :themes="game.themes"
                            :platforms="game.platforms"
                            class="md:w-1/3 space-y-4"
                        />
                    </div>
                </div>

                <div class="mt-12">
                    <h2 class="text-3xl font-bold mb-6">Screenshots</h2>
                    <div
                        v-if="game.screenshots?.length"
                        class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4"
                    >
                        <img
                            v-for="(screenshot, index) in game.screenshots"
                            :key="index"
                            :src="normalizeUrl(screenshot.url)"
                            :alt="`Screenshot ${index + 1} of ${game.name}`"
                            class="cursor-zoom-in rounded border border-gray-600 object-cover w-full h-48"
                            @click="openImage(screenshot.url)"
                        />
                    </div>
                    <p v-else class="italic text-gray-400">No screenshots available.</p>
                </div>

                <div
                    v-if="zoomedImage"
                    class="fixed inset-0 bg-black bg-opacity-80 flex items-center justify-center z-50"
                    @click="closeImage"
                >
                    <img :src="zoomedImage" class="max-w-full max-h-full rounded-lg shadow-lg"/>
                </div>
            </div>
        </div>
    </MainLayout>
</template>
