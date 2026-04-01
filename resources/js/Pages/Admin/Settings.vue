<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AdminNav from '@/Components/AdminNav.vue';

const props = defineProps({
    siteSettings: {
        type: Object,
        default: () => ({}),
    },
});

const form = useForm({
    site_name: props.siteSettings.site_name || 'River Rafting Adventure',
    logo_file: null,
    logo_url: props.siteSettings.logo_url || '',
    favicon_file: null,
    favicon_url: props.siteSettings.favicon_url || '/favicon.ico',
    phone_number: props.siteSettings.phone_number || '',
    whatsapp_number: props.siteSettings.whatsapp_number || '',
    email: props.siteSettings.email || '',
    address: props.siteSettings.address || '',
    google_map_embed: props.siteSettings.google_map_embed || '',
});

function handleLogoUpload(event) {
    form.logo_file = event.target.files[0];
}

function handleFaviconUpload(event) {
    form.favicon_file = event.target.files[0];
}

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
                <form @submit.prevent="submitForm" enctype="multipart/form-data">
                    <!-- General Settings Section -->
                    <div class="mb-8 p-6 bg-white rounded-lg shadow">
                        <h3 class="text-lg font-semibold mb-4 text-gray-800">General Settings</h3>

                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2">
                                Site Name
                            </label>
                            <p class="text-gray-500 text-sm mb-2">
                                Enter the name of your website (e.g., River Rafting Adventure)
                            </p>
                            <input
                                v-model="form.site_name"
                                type="text"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                placeholder="River Rafting Adventure"
                            />
                            <p v-if="form.errors.site_name" class="text-red-500 text-xs mt-1">{{ form.errors.site_name }}</p>
                        </div>

                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2">
                                Site Logo
                            </label>
                            <p class="text-gray-500 text-sm mb-2">
                                Upload your site logo (PNG, JPG, SVG). This will be used in the navigation and as favicon.
                            </p>
                            <p class="text-gray-500 text-sm mb-2">
                                Maximum file size: 2MB
                            </p>
                            <input
                                type="file"
                                @change="handleLogoUpload"
                                accept="image/*"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            />
                            <p v-if="form.errors.logo_file" class="text-red-500 text-xs mt-1">{{ form.errors.logo_file }}</p>
                            <div v-if="form.logo_url" class="mt-2">
                                <p class="text-sm text-gray-600 mb-1">Current Logo:</p>
                                <img :src="form.logo_url" alt="Current Logo" class="h-16 w-auto border rounded" />
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2">
                                Favicon
                            </label>
                            <p class="text-gray-500 text-sm mb-2">
                                Upload a favicon (ICO, PNG). Recommended size: 32x32 or 16x16 pixels.
                            </p>
                            <p class="text-gray-500 text-sm mb-2">
                                Maximum file size: 512KB
                            </p>
                            <input
                                type="file"
                                @change="handleFaviconUpload"
                                accept="image/*,.ico"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            />
                            <p v-if="form.errors.favicon_file" class="text-red-500 text-xs mt-1">{{ form.errors.favicon_file }}</p>
                            <div v-if="form.favicon_url" class="mt-2">
                                <p class="text-sm text-gray-600 mb-1">Current Favicon:</p>
                                <img :src="form.favicon_url" alt="Current Favicon" class="h-8 w-8 border rounded" />
                            </div>
                        </div>
                    </div>

                    <!-- Contact Settings Section -->
                    <div class="mb-8 p-6 bg-white rounded-lg shadow">
                        <h3 class="text-lg font-semibold mb-4 text-gray-800">Contact Settings</h3>

                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2">
                                Phone Number
                            </label>
                            <p class="text-gray-500 text-sm mb-2">
                                Enter the phone number for "Call Now" feature (e.g., +91 9876543210)
                            </p>
                            <input
                                v-model="form.phone_number"
                                type="tel"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                placeholder="+91 9876543210"
                            />
                            <p v-if="form.errors.phone_number" class="text-red-500 text-xs mt-1">{{ form.errors.phone_number }}</p>
                        </div>

                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2">
                                WhatsApp Number
                            </label>
                            <p class="text-gray-500 text-sm mb-2">
                                Enter the WhatsApp number (e.g., 919876543210 - without + or spaces)
                            </p>
                            <input
                                v-model="form.whatsapp_number"
                                type="tel"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                placeholder="919876543210"
                            />
                            <p v-if="form.errors.whatsapp_number" class="text-red-500 text-xs mt-1">{{ form.errors.whatsapp_number }}</p>
                        </div>

                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2">
                                Email Address
                            </label>
                            <p class="text-gray-500 text-sm mb-2">
                                Enter the contact email address
                            </p>
                            <input
                                v-model="form.email"
                                type="email"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                placeholder="info@example.com"
                            />
                            <p v-if="form.errors.email" class="text-red-500 text-xs mt-1">{{ form.errors.email }}</p>
                        </div>

                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2">
                                Address
                            </label>
                            <p class="text-gray-500 text-sm mb-2">
                                Enter the business address
                            </p>
                            <textarea
                                v-model="form.address"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                rows="3"
                                placeholder="123 Main Street, City, Country"
                            ></textarea>
                            <p v-if="form.errors.address" class="text-red-500 text-xs mt-1">{{ form.errors.address }}</p>
                        </div>
                    </div>

                    <!-- Google Map Section -->
                    <div class="mb-8 p-6 bg-white rounded-lg shadow">
                        <h3 class="text-lg font-semibold mb-4 text-gray-800">Google Map Settings</h3>

                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2">
                                Google Map Embed Code
                            </label>
                            <p class="text-gray-500 text-sm mb-2">
                                Enter the Google Map embed iframe code (e.g., <iframe src="https://www.google.com/maps/embed?pb=..."></iframe>)
                            </p>
                            <textarea
                                v-model="form.google_map_embed"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                rows="4"
                                placeholder='<iframe src="https://www.google.com/maps/embed?pb=..." width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>'
                            ></textarea>
                            <p v-if="form.errors.google_map_embed" class="text-red-500 text-xs mt-1">{{ form.errors.google_map_embed }}</p>
                        </div>

                        <!-- Map Preview -->
                        <div v-if="form.google_map_embed" class="mt-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Preview</label>
                            <div class="border rounded-lg overflow-hidden" v-html="form.google_map_embed"></div>
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
