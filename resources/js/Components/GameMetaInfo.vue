<script setup>
defineProps({
    genres: Array,
    themes: Array,
    platforms: Array,
});
</script>

<template>
    <div class="space-y-4">
        <div v-if="genres?.length">
            <strong class="block mb-1">Genres</strong>
            <div class="flex flex-wrap gap-2">
                <span
                    v-for="(genre, index) in genres"
                    :key="genre.id || index"
                    class="bg-gray-800 text-sm text-white px-3 py-1 rounded-md"
                >
                    {{ genre.name }}
                </span>
            </div>
        </div>

        <div v-if="themes?.length">
            <strong class="block mb-1">Themes</strong>
            <div class="flex flex-wrap gap-2">
                <span
                    v-for="(theme, index) in themes"
                    :key="theme.id || index"
                    class="bg-gray-800 text-sm text-white px-3 py-1 rounded-md"
                >
                    {{ theme.name }}
                </span>
            </div>
        </div>

        <div v-if="platforms?.length">
            <strong class="block mb-1">Platforms</strong>
            <div class="flex flex-wrap gap-2">
                <span
                    v-for="(platform, index) in platforms"
                    :key="platform.id || index"
                    class="bg-gray-800 text-sm text-white px-3 py-1 rounded-md"
                >
                    {{ platform.name }}
                </span>
            </div>
        </div>
    </div>
</template>
