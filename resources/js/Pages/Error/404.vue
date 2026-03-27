<script setup>
import { Head, Link } from '@inertiajs/vue3';

function goBack() {
    window.history.back();
}
</script>

<template>
    <Head title="Page Not Found" />
    <div class="min-h-screen flex items-center justify-center bg-gray-50 dark:bg-gray-900 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8 text-center">
            <div>
                <h1 class="text-9xl font-bold text-indigo-600 dark:text-indigo-400">404</h1>
                <h2 class="mt-6 text-3xl font-extrabold text-gray-900 dark:text-mute">
                    Page Not Found
                </h2>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                    Sorry, we couldn't find the page you're looking for.
                </p>
            </div>
            <div class="mt-8 space-y-4">
                <Link
                    :href="route('home')"
                    class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200"
                >
                    Go to Homepage
                </Link>
                <button
                    @click="goBack"
                    class="w-full flex justify-center py-3 px-4 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200"
                >
                    Go Back
                </button>
            </div>
        </div>
    </div>
</template>
