<template>
  <div class="min-h-screen bg-gray-100">
    <AdminNav current-page="gallery" />

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
              <div v-if="form.category !== 'videos'">
                <label class="block text-sm font-medium text-gray-700 mb-1">Image</label>
                <input
                  type="file"
                  accept="image/*"
                  @change="form.image_file = $event.target.files[0]"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md"
                />
                <p v-if="form.image_path" class="text-sm text-gray-500 mt-1">Current: {{ form.image_path }}</p>
              </div>
              <div v-if="form.category === 'videos'">
                <label class="block text-sm font-medium text-gray-700 mb-1">Video Embed URL</label>
                <input
                  v-model="form.video_url"
                  type="text"
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
                v-if="item.image_url"
                :src="item.image_url"
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
import axios from 'axios'
import AdminNav from '@/Components/AdminNav.vue'

const props = defineProps({
  galleryImages: Array,
})

const form = useForm({
  title: '',
  image_file: null,
  image_path: '',
  category: 'photos',
  video_url: '',
  sort_order: 0,
  is_active: true,
})

function submitForm() {
  // For videos category, validate video_url is provided
  if (form.category === 'videos' && !form.video_url) {
    alert('Please enter a video embed URL for videos category')
    return
  }

  const formData = new FormData()
  formData.append('title', form.title)
  formData.append('category', form.category)
  formData.append('video_url', form.video_url)
  formData.append('sort_order', form.sort_order)
  formData.append('is_active', form.is_active ? '1' : '0')

  if (form.image_file) {
    formData.append('image_file', form.image_file)
  }

  if (form.image_path) {
    formData.append('image_path', form.image_path)
  }

  axios.post('/admin/gallery', formData, {
    headers: {
      'Content-Type': 'multipart/form-data'
    }
  }).then(() => {
    form.title = ''
    form.image_file = null
    form.image_path = ''
    form.category = 'photos'
    form.video_url = ''
    form.sort_order = 0
    form.is_active = true
  }).catch((error) => {
    console.error('Error:', error)
    alert('Error: ' + (error.response?.data?.message || error.message))
  })
}
</script>
