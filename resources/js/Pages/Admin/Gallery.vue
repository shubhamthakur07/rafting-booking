<template>
  <div class="min-h-screen bg-gray-100">
    <AdminNav current-page="gallery" />

    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
      <div class="px-4 py-6 sm:px-0">
        <div class="flex justify-between items-center mb-6">
          <h2 class="text-2xl font-bold text-gray-900">Gallery</h2>
          <button
            @click="showCreateModal = true"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg"
          >
            Add Item
          </button>
        </div>

        <!-- Gallery Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div
            v-for="image in galleryImages"
            :key="image.id"
            class="bg-white rounded-lg shadow overflow-hidden"
          >
            <div class="aspect-video bg-gray-200 relative">
              <img
                v-if="image.image_url"
                :src="image.image_url"
                :alt="image.title"
                class="w-full h-full object-cover"
              />
              <div v-else class="w-full h-full flex items-center justify-center text-gray-400">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
              </div>
              <div v-if="image.category === 'videos'" class="absolute top-2 right-2 bg-red-600 text-white px-2 py-1 text-xs font-medium rounded">
                Video
              </div>
            </div>
            <div class="p-4">
              <h3 class="text-lg font-medium text-gray-900 mb-2">{{ image.title }}</h3>
              <p class="text-sm text-gray-600 mb-3">{{ image.category === 'videos' ? 'Video' : 'Photo' }}</p>
              <div class="flex items-center justify-between">
                <span :class="image.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'" class="px-2 py-1 text-xs font-medium rounded-full">
                  {{ image.is_active ? 'Active' : 'Inactive' }}
                </span>
                <div class="flex space-x-2">
                  <button
                    @click="editImage(image)"
                    class="text-blue-600 hover:text-blue-900 text-sm"
                  >
                    Edit
                  </button>
                  <button
                    @click="deleteImage(image.id)"
                    class="text-red-600 hover:text-red-900 text-sm"
                  >
                    Delete
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>

    <!-- Create/Edit Modal -->
    <Modal :show="showCreateModal || showEditModal" @close="closeModal">
      <div class="p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">
          {{ showEditModal ? 'Edit Item' : 'Add Item' }}
        </h3>
        <form @submit.prevent="submitForm">
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
              <input
                type="text"
                v-model="form.title"
                class="w-full border border-gray-300 rounded-lg px-3 py-2"
                required
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
              <select v-model="form.category" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                <option value="photos">Photo</option>
                <option value="videos">Video</option>
              </select>
            </div>
            <div v-if="form.category === 'photos'">
              <label class="block text-sm font-medium text-gray-700 mb-1">Image</label>
              <input
                type="file"
                @change="handleImageUpload"
                accept="image/*"
                class="w-full border border-gray-300 rounded-lg px-3 py-2"
              />
              <p v-if="showEditModal && editingImage?.image_url" class="text-sm text-gray-500 mt-1">
                Current image will be kept if no new image is selected
              </p>
            </div>
            <div v-if="form.category === 'videos'">
              <label class="block text-sm font-medium text-gray-700 mb-1">Video URL (YouTube, Vimeo, or iframe code)</label>
              <textarea
                v-model="form.video_url"
                rows="3"
                class="w-full border border-gray-300 rounded-lg px-3 py-2"
                placeholder="https://www.youtube.com/watch?v=... or paste iframe code"
              ></textarea>
              <p class="text-sm text-gray-500 mt-1">
                You can paste a YouTube/Vimeo URL or the full iframe embed code
              </p>
            </div>
            <div v-if="form.category === 'videos'">
              <label class="block text-sm font-medium text-gray-700 mb-1">Thumbnail Image (optional)</label>
              <input
                type="file"
                @change="handleImageUpload"
                accept="image/*"
                class="w-full border border-gray-300 rounded-lg px-3 py-2"
              />
              <p class="text-sm text-gray-500 mt-1">
                Optional thumbnail image for the video
              </p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
              <select v-model="form.is_active" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                <option :value="true">Active</option>
                <option :value="false">Inactive</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
              <input
                type="number"
                v-model="form.sort_order"
                min="0"
                class="w-full border border-gray-300 rounded-lg px-3 py-2"
              />
            </div>
          </div>
          <div class="mt-6 flex justify-end space-x-3">
            <button
              type="button"
              @click="closeModal"
              class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg"
            >
              Cancel
            </button>
            <button
              type="submit"
              class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg"
            >
              {{ showEditModal ? 'Update' : 'Add' }}
            </button>
          </div>
        </form>
      </div>
    </Modal>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import AdminNav from '@/Components/AdminNav.vue'
import Modal from '@/Components/Modal.vue'

const props = defineProps({
  galleryImages: Array
})

const showCreateModal = ref(false)
const showEditModal = ref(false)
const editingImage = ref(null)

const form = ref({
  title: '',
  category: 'photos',
  image_file: null,
  video_url: '',
  is_active: true,
  sort_order: 0
})

function handleImageUpload(event) {
  form.value.image_file = event.target.files[0]
}

function editImage(image) {
  editingImage.value = image
  form.value = {
    title: image.title,
    category: image.category || 'photos',
    image_file: null,
    video_url: image.video_url || '',
    is_active: image.is_active,
    sort_order: image.sort_order || 0
  }
  showEditModal.value = true
}

function closeModal() {
  showCreateModal.value = false
  showEditModal.value = false
  editingImage.value = null
  form.value = {
    title: '',
    category: 'photos',
    image_file: null,
    video_url: '',
    is_active: true,
    sort_order: 0
  }
}

function submitForm() {
  const formData = new FormData()
  formData.append('title', form.value.title)
  formData.append('category', form.value.category)
  formData.append('video_url', form.value.video_url || '')
  formData.append('is_active', form.value.is_active ? 1 : 0)
  formData.append('sort_order', form.value.sort_order || 0)

  if (form.value.image_file) {
    formData.append('image_file', form.value.image_file)
  }

  if (showEditModal.value) {
    formData.append('_method', 'PUT')
    router.post(`/admin/gallery/${editingImage.value.id}`, formData, {
      onSuccess: () => closeModal()
    })
  } else {
    router.post('/admin/gallery', formData, {
      onSuccess: () => closeModal()
    })
  }
}

function deleteImage(id) {
  if (confirm('Are you sure you want to delete this item?')) {
    router.delete(`/admin/gallery/${id}`)
  }
}
</script>
