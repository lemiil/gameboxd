<script setup>
import {Head, Link, usePage}  from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";

const { props } = usePage();
const user = props.auth?.user || props.user;
</script>


<template>
    <Head title="Main" />
    <MainLayout>
        <h2 class="text-3xl font-semibold mb-4">Welcome</h2>
        <p class="text-lg text-gray-600 dark:text-gray-400 mb-6">
            <img :src="user.avatar_url" width="100" class="rounded-full" alt="Avatar">
        </p>
    </MainLayout>
</template>
