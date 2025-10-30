<script setup>
import {ref} from "vue";
import axios from "axios";

const avatar = ref(null);
const preview = ref(null);
const uploading = ref(false); // track upload state

const handleFileChange = (e) => {
    const file = e.target.files[0];
    avatar.value = file;
    preview.value = URL.createObjectURL(file);
};

const submit = async () => {
    if (!avatar.value) return;

    const formData = new FormData();
    formData.append("avatar", avatar.value);

    uploading.value = true;

    try {
        await axios.post("/profile/avatar", formData, {
            headers: {
                "Content-Type": "multipart/form-data",
            },
        });
        avatar.value = null;
        preview.value = null;
    } catch (error) {
        console.error("avatar upload failed:", error.response?.data || error.message);
    } finally {
        console.log("avatar updated or at least should")
        uploading.value = false;
    }
};
</script>

<template>
    <div class="bg-gray-800 p-6 rounded-lg shadow space-y-4">
        <h3 class="text-lg font-semibold text-gray-100">Update Avatar</h3>
        <p class="text-gray-400 text-sm">Upload a new avatar for your profile.</p>

        <input
            type="file"
            accept="image/*"
            @change="handleFileChange"
            class="text-gray-200"
        />

        <div v-if="preview" class="mt-3">
            <img
                :src="preview"
                alt="Avatar Preview"
                class="w-32 h-32 rounded-full object-cover border border-gray-600"
            />
        </div>

        <button
            @click="submit"
            class="bg-red-700 hover:bg-red-800 text-white px-4 py-2 rounded"
            :disabled="uploading"
        >
            {{ uploading ? "Uploading..." : "Save" }}
        </button>
    </div>
</template>
