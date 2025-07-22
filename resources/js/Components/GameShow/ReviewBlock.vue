<script setup>
import {ref} from "vue";
import axios from "axios";
import StarRating from 'vue-star-rating'
import {usePage} from '@inertiajs/vue3'

let rating = ref(0);
let liked = ref(false)
let showPopup = ref(false);
let status = ref('planned')
let reviewText = ref('')
let isSubmitting = ref(false);

const {game} = defineProps({
    game: {
        type: Object,
        required: true
    },
});

const user = usePage().props.auth.user

const submitReview = async () => {
    if (rating.value === 0 || !game.id) return;

    isSubmitting.value = true;
    try {
        await axios.post(`/games/${game.id}/reviews`, {
            rating: rating.value * 2,
            liked: liked.value,
            status: status.value,
            text: reviewText.value,
            game_id: game.id,
            user_id: user.id,
        });

        rating.value = 0;
        liked.value = false;
        status.value = 'planned';
        reviewText.value = '';
        showPopup.value = false;
    } catch (error) {
        console.error('Error submitting review:', error);
    } finally {
        isSubmitting.value = false;
    }
}
</script>


<template>
    <div class="review-block w-full" v-if="$page.props.auth.user">

        <button
            @click="showPopup = true"
            type="button"
            class="mt-3 bg-red-900 h-7 max-w-[175px] rounded flex w-full items-center justify-center"
        >
            Review
        </button>

        <div
            class="mt-3 w-full max-w-[175px] h-auto bg-gray-900 border border-gray-700 rounded flex items-center justify-start text-gray-400 text-sm p-2 group space-x-2"
        >
            <button
                @click="rating = 0;"
                class="opacity-0 group-hover:opacity-100 text-gray-500 hover:text-gray-300 transition-opacity"
            >
                X
            </button>

            <star-rating
                v-model:rating="rating"
                increment="0.5"
                :show-rating="false"
                :star-size="25"
            />
        </div>


        <select
            @click="submitReview"
            v-model="status"
            class="mt-3 bg-gray-900 border border-gray-700 text-gray-400 text-sm text-center rounded  w-full max-w-[175px] px-2"
        >
            <option value="played">Played</option>
            <option value="planned">Planned</option>
            <option value="dropped">Dropped</option>
            <option value="completed">Completed</option>
            <option value="shelved">Shelved</option>
        </select>

        <button
            @click="liked = !liked"

            class="mt-3 h-7 w-full max-w-[175px] bg-gray-900 border border-gray-700 rounded flex items-center justify-center text-gray-400 text-sm gap-1"
        >
            <svg
                v-if="liked"
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
            <span>{{ liked ? 'Liked' : 'Like' }}</span>
        </button>

        <div
            v-if="showPopup"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
            @click.self="showPopup = false"
        >
            <div class="bg-gray-900 border border-gray-700 rounded p-6 w-[350px] relative text-gray-300">

                <button
                    @click="showPopup = false"
                    class="absolute top-2 right-2 text-gray-400 hover:text-white text-xl"
                >
                    &times;
                </button>

                <h2 class="text-lg mb-2">Write a review</h2>

                <div class="mb-3 items-center justify-center flex">
                    <div
                        class="mt-3 w-full max-w-[175px] h-auto bg-gray-900 rounded flex items-center justify-start text-gray-400 text-sm p-2 group space-x-2"
                    >
                        <button
                            @click="rating = 0;"
                            class="opacity-0 group-hover:opacity-100 text-gray-500 hover:text-gray-300 transition-opacity"
                        >
                            X
                        </button>

                        <star-rating
                            v-model:rating="rating"
                            increment="0.5"
                            :show-rating="false"
                            :star-size="25"
                        />
                    </div>
                </div>

                <select
                    v-model="status"
                    class="mb-3 bg-gray-800 border border-gray-700 text-gray-300 text-sm rounded w-full px-2 py-1"
                >
                    <option value="played">Played</option>
                    <option value="planned">Planned</option>
                    <option value="dropped">Dropped</option>
                    <option value="completed">Completed</option>
                    <option value="shelved">Shelved</option>
                </select>

                <button
                    @click="liked = !liked"
                    class="mb-3 h-8 w-full bg-gray-800 border border-gray-700 rounded flex items-center justify-center text-gray-300 text-sm gap-1"
                >
                    <svg
                        v-if="liked"
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
                    <span>{{ liked ? 'Liked' : 'Like' }}</span>
                </button>

                <textarea
                    v-model="reviewText"
                    placeholder="Write your review here..."
                    class="w-full h-32 p-2 bg-gray-800 border border-gray-700 rounded text-sm resize-none"
                ></textarea>

                <button
                    class="mt-3 bg-red-800 hover:bg-red-700 transition-colors w-full rounded py-2 text-white text-sm"
                    :disabled="isSubmitting"
                    @click="submitReview"
                >
                    {{ isSubmitting ? 'Submitting...' : 'Submit Review' }}
                </button>

            </div>
        </div>
    </div>

    <div class="review-block-not-logged" v-else>
        <div
            class="mt-3 w-full max-w-[175px] h-auto bg-gray-900 border border-gray-700 rounded flex items-center justify-center text-gray-400 text-sm p-2"
        >
            Please Log in
        </div>
    </div>
</template>


<style scoped>

</style>
