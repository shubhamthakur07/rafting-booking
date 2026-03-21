<template>
  <div class="min-h-screen bg-gray-100">
    <nav class="bg-white shadow-sm border-b border-gray-200">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex items-center">
            <h1 class="text-xl font-bold text-gray-900">River Rafting Admin</h1>
          </div>
          <div class="flex items-center space-x-4">
            <a href="/admin" class="text-gray-600 hover:text-gray-900">Dashboard</a>
            <a href="/admin/bookings" class="text-gray-600 hover:text-gray-900">Bookings</a>
            <a href="/admin/time-slots" class="text-gray-600 hover:text-gray-900">Time Slots</a>
            <a href="/admin/testimonials" class="text-gray-600 hover:text-gray-900">Testimonials</a>
            <a href="/admin/gallery" class="text-blue-600 font-medium">Gallery</a>
            <form method="POST" action="/logout">
              <button type="submit" class="text-red-600 hover:text-red-800">Logout</button>
            </form>
          </div>
        </div>
      </div>
    </nav>

    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
      <div class="px-4 py-6 sm:px-0">
        <div class="flex justify-between items-center mb-6">
          <h2 class="text-2xl font-bold text-gray-900">Gallery</h2>
        </div>

        <!-- Add Gallery Item Form -->
        <div class="bg-white rounded-lg shadow mb-6 p-6">
          <h3 class="text-lg font-medium text-gray-900 mb-4">Add New Gallery Item</h3>
          <form @submit.prevent="submitForm">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                <input
                  v-model="form.title"
                  type="text"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md"
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                <select
                  v-model="form.category"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md"
                >
                  <option value="photos">Photos</option>
                  <option value="videos">Videos</option>
                </select>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Image URL</label>
                <input
                  v-model="form.image_path"
                  type="url"
                  required
                  placeholder="https://..."
                  class="w-full px-3 py-2 border border-gray-300 rounded-md"
                />
              </div>
              <div v-if="form.category === 'videos'">
                <label class="block text-sm font-medium text-gray-700 mb-1">Video Embed URL</label>
                <input
                  v-model="form.video_url"
                  type="url"
                  placeholder="https://www.youtube.com/embed/..."
                  class="w-full px-3 py-2 border border-gray-300 rounded-md"
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                <input
                  v-model="form.sort_order"
                  type="number"
                  min="0"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md"
                />
              </div>
              <div class="flex items-end">
                <button
                  type="submit"
                  class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700"
                >
                  Add Item
                </button>
              </div>
            </div>
          </form>
        </div>

        <!-- Gallery Grid -->
        <div class="grid md:grid-cols-3 lg:grid-cols-4 gap-4">
          <div v-for="item in galleryImages" :key="item.id" class="bg-white rounded-lg shadow overflow-hidden">
            <div class="aspect-square bg-gray-200">
              <img
                v-if="item.image_path"
                :src="item.image_path"
                :alt="item.title"
                class="w-full h-full object-cover"
              />
            </div>
            <div class="p-4">
              <div class="font-medium">{{ item.title || 'Untitled' }}</div>
              <div class="text-sm text-gray-500">{{ item.category }}</div>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'

const props = defineProps({
  galleryImages: Array,
})

const form = useForm({
  title: '',
  image_path: '',
  category: 'photos',
  video_url: '',
  sort_order: 0,
  is_active: true,
})

function submitForm() {
  form.post('/admin/gallery', {
    onSuccess: () => {
      form.reset()
    }
  })
}
</script>
