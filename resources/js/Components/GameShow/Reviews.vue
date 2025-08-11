<script setup>
import {ref, onMounted} from "vue";
import {usePage, router} from "@inertiajs/vue3";
import axios from "axios";
import StarRating from "vue-star-rating";

const props = defineProps({
    game: {type: Object, required: true},
});

const page = usePage();
const isAuthenticated = page.props.auth?.user !== null;

const latestReviews = ref([]);
const loading = ref(true);
const error = ref(null);
const expandedReviews = ref(new Set());
const likedReviewIds = ref(new Set());

const userLiked = (reviewId) => likedReviewIds.value.has(reviewId);
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

const toggleUserLike = async (review) => {
    if (!isAuthenticated) {
        router.visit(route("login"));
        return;
    }

    const liked = userLiked(review.id);
    if (liked) {
        likedReviewIds.value.delete(review.id);
        review.likes_count--;
    } else {
        likedReviewIds.value.add(review.id);
        review.likes_count++;
    }

    try {
        const url = route("reviews.like", {review: review.id});
        await axios.post(url);
    } catch (e) {
        if (liked) {
            likedReviewIds.value.add(review.id);
            review.likes_count++;
        } else {
            likedReviewIds.value.delete(review.id);
            review.likes_count--;
        }
        console.error("Failed to toggle like", e);
    }
};

onMounted(async () => {
    try {
        const urlReviews = route("reviews.latest", { game: props.game.id });
        const { data: reviewsData } = await axios.get(urlReviews);

        latestReviews.value = reviewsData.data;

        if (isAuthenticated) {
            const reviewIds = latestReviews.value.map(r => r.id);
            if (reviewIds.length > 0) {
                const urlLikes = route("reviews.likes.status");
                const { data: likesData } = await axios.post(urlLikes, { review_ids: reviewIds });
                likedReviewIds.value = new Set(likesData.likedReviews);
            }
        }
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
    <pre> {{ latestReviews }}</pre>
    <div class="mt-12">
        <h2 class="text-3xl font-bold mb-4">Reviews</h2>

        <div v-if="loading" class="text-gray-400">loading...</div>
        <div v-else-if="error" class="text-red-500">{{ error }}</div>
        <div v-else-if="latestReviews.length === 0" class="text-gray-400">
            There is no reviews. <span class="text-red-500" @click="showPopup = true">You can be first!</span>
        </div>
        <div v-else class="grid grid-cols-1 md:grid-cols-1 gap-6">
            <div
                v-for="review in latestReviews.filter(r => r.text && r.text.trim() !== '')"
                :key="review.id"
                class="dark:bg-gray-800 border border-gray-600 rounded-2xl p-5 shadow-sm flex flex-col"
            >
                <div class="flex items-start justify-between">
                    <div class="flex items-center gap-3">
                        <img
                            :src="review.user.avatar_url || 'placeholderYEAH'"
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
                        @click="toggleUserLike(review)"
                        class="inline-flex items-center gap-1 text-sm font-medium focus:outline-none"
                    >
                        <svg
                            v-if="userLiked(review.id)"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="currentColor"
                            viewBox="0 0 24 24"
                            class="w-4 h-4 text-red-500"
                        >
                            <path
                                d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 6 4 4 6.5 4c1.54 0 3.04.99 3.57 2.36h.87C14.46 4.99 15.96 4 17.5 4 20 4 22 6 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"
                            />
                        </svg>
                        <svg
                            v-else
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            class="w-4 h-4"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 6 4 4 6.5 4c1.54 0 3.04.99 3.57 2.36h.87C14.46 4.99 15.96 4 17.5 4 20 4 22 6 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"
                            />
                        </svg>
                        <span>{{ review.likes_count }}</span>
                    </button>
                    <div class="text-xs text-gray-400">Open review</div>
                </div>
            </div>
        </div>
    </div>
</template>
