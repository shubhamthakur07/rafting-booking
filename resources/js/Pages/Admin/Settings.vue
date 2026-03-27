<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AdminNav from '@/Components/AdminNav.vue';

const props = defineProps({
    googleMapEmbed: {
        type: String,
        default: '',
    },
});

const form = useForm({
    google_map_embed: props.googleMapEmbed,
});

function submitForm() {
    form.post(route('admin.settings.update'), {
        onSuccess: () => {
            // Form will be reset automatically on success
        },
    });
}
</script>

<template>
    <Head title="Site Settings" />

    <div class="min-h-screen bg-gray-100">
        <AdminNav current-page="settings" />

        <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <div class="px-4 py-6 sm:px-0">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold">Site Settings</h2>
                </div>

                <!-- Success Message -->
                <div v-if="$page.props.flash?.success" class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                    {{ $page.props.flash.success }}
                </div>

                <!-- Settings Form -->
                <form @submit.prevent="submitForm">
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2">
                            Google Map Embed Code
                        </label>
                        <p class="text-gray-500 text-sm mb-2">
                            To embed a Google Map, follow these steps:
                        </p>
                        <ol class="list-decimal list-inside text-gray-500 text-sm mb-4 space-y-1">
                            <li>Go to <a href="https://www.google.com/maps" target="_blank" class="text-blue-500 hover:underline">Google Maps</a></li>
                            <li>Search for your location</li>
                            <li>Click the "Share" button</li>
                            <li>Select "Embed a map" tab</li>
                            <li>Copy the iframe code and paste it below</li>
                        </ol>
                        <textarea
                            v-model="form.google_map_embed"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            rows="6"
                            placeholder='<iframe src="https://www.google.com/maps/embed?pb=..." width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>'
                        ></textarea>
                        <p v-if="form.errors.google_map_embed" class="text-red-500 text-xs mt-1">{{ form.errors.google_map_embed }}</p>
                        <p class="text-gray-400 text-xs mt-2">
                            Note: The embed code must use the "Embed a map" option from Google Maps. Regular map URLs will not work due to security restrictions.
                        </p>
                    </div>

                    <!-- Preview Section -->
                    <div v-if="form.google_map_embed" class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2">
                            Preview
                        </label>
                        <div class="border rounded-lg overflow-hidden bg-gray-50">
                            <div v-html="form.google_map_embed"></div>
                            <div class="p-4 text-center text-gray-500 text-sm">
                                <p>If the map doesn't display above, please ensure you're using the correct embed code from Google Maps.</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <button
                            type="submit"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                        >
                            Save Settings
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</template>
