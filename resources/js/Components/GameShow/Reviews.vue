<script setup>
import {ref, computed, onMounted} from "vue";
import axios from "axios";
import StarRating from "vue-star-rating";

const props = defineProps({
    game: {type: Object, required: true},
});

const latestReviews = ref([]);
const loading = ref(true);
const error = ref(null);

const expandedReviews = ref(new Set());

const isExpanded = (reviewId) => expandedReviews.value.has(reviewId);

const toggleExpand = (reviewId) => {
    if (expandedReviews.value.has(reviewId)) {
        expandedReviews.value.delete(reviewId);
    } else {
        expandedReviews.value.add(reviewId);
    }
};


const formatDate = (isoString) => {
    try {
        const d = new Date(isoString);
        return d.toLocaleString(undefined, {
            year: "numeric",
            month: "short",
            day: "numeric",
        });
    } catch {
        return isoString;
    }
};

const toggleLike = async (review) => {
    review.liked = !review.liked;
    try {
        const url = route("reviews.toggleLike", {review: review.id});
        await axios.post(url);
    } catch (e) {
        review.liked = !review.liked;
        console.error("Failed to toggle like", e);
    }
};

onMounted(async () => {
    try {
        const url = route("reviews.latest", {game: props.game.id});
        const {data} = await axios.get(url);
        latestReviews.value = data;
    } catch (e) {
        error.value = "reviews error";
        console.error(e);
    } finally {
        loading.value = false;
    }
});


const escapeHtml = (unsafeText) => {
    return unsafeText
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
};

const formatText = (text) => {
    return escapeHtml(text).replace(/\n/g, "<br>");
};


</script>

<template>
    <div class="mt-12">
        <h2 class="text-3xl font-bold mb-4">Reviews</h2>

        <div v-if="loading" class="text-gray-400">loading...</div>
        <div v-else-if="error" class="text-red-500">{{ error }}</div>
        <div v-else-if="latestReviews.length === 0" class="italic text-gray-400">
            There is no reviews. <a :href="placeholder" class="text-blue-500 underline">You
            can be first!</a>
        </div>
        <div v-else class="grid grid-cols-1 md:grid-cols-1 gap-6">
            <div
                v-for="review in latestReviews"
                :key="review.id"
                class="dark:bg-gray-800 border border-gray-600 rounded-2xl p-5 shadow-sm flex flex-col"
            >
                <div class="flex items-start justify-between">
                    <div class="flex items-center gap-3">
                        <img
                            :src="review.user.avatar_url || 'placeholder'"
                            alt="avatar"
                            class="w-12 h-12 rounded-full object-cover border"
                        />
                        <div class="flex flex-col">
                            <div class="font-semibold text-gray-900 dark:text-gray-100">
                                {{ review.user.name }}
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ formatDate(review.created_at) }}
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <div
                            v-if="review.status"
                            :class="[
                'text-xs font-medium px-2 py-1 rounded-full',
                review.status === 'approved'
                  ? 'bg-green-100 text-green-800'
                  : review.status === 'pending'
                  ? 'bg-yellow-100 text-yellow-800'
                  : review.status === 'rejected'
                  ? 'bg-red-100 text-red-800'
                  : 'bg-gray-100 text-gray-800',
              ]"
                        >
                            {{ review.status }}
                        </div>
                    </div>
                </div>

                <div class="mt-3 flex items-center gap-2">
                    <StarRating :rating="review.rating / 2" increment="0.5" :star-size="20" :read-only="true"
                                :show-rating="false"/>
                </div>

                <p
                    class="mt-3 text-gray-700 dark:text-gray-200 flex-1"
                    v-html="isExpanded(review.id)
                    ? formatText(review.text)
                    : formatText(review.text.slice(0, 150) + (review.text.length > 150 ? '...' : ''))">
                </p>


                <div v-if="review.text.length > 300" class="mt-1">
                    <button
                        @click="toggleExpand(review.id)"
                        class="text-gray-500 text-sm hover:text-red-600 transition"
                    >
                        {{ isExpanded(review.id) ? 'Show less' : 'Show more' }}
                    </button>
                </div>


                <div class="mt-4 flex items-center justify-between">
                    <button
                        @click="toggleLike(review)"
                        class="inline-flex items-center gap-1 text-sm font-medium focus:outline-none"
                    >
                        <svg
                            v-if="review.liked"
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 text-red-500"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"
                                clip-rule="evenodd"
                            />
                        </svg>
                        <svg
                            v-else
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 text-gray-400"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M3.172 5.172a4 4 0 015.656 0L12 8.343l3.172-3.171a4 4 0 115.656 5.656L12 21.657l-8.828-8.829a4 4 0 010-5.656z"
                            />
                        </svg>
                        <span class="ml-1">{{ review.liked ? "Liked" : "Like" }}</span>
                    </button>
                    <div class="text-xs text-gray-400">Open review</div>
                </div>
            </div>
        </div>
    </div>
</template>
