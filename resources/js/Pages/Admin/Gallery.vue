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
            <div class="aspect-square bg-gray-200 relative">
              <img
                v-if="item.image_url"
                :src="item.image_url"
                :alt="item.title"
                class="w-full h-full object-cover"
              />
              <div v-if="item.category === 'videos'" class="absolute inset-0 flex items-center justify-center bg-gray-800">
                <span class="text-white text-4xl">▶</span>
              </div>
              <!-- Action Buttons -->
              <div class="absolute top-2 right-2 flex gap-1">
                <button
                  @click="openEditModal(item)"
                  class="bg-blue-600 text-white p-1 rounded-full hover:bg-blue-700"
                  title="Edit"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                  </svg>
                </button>
                <button
                  @click="deleteGalleryItem(item)"
                  class="bg-red-600 text-white p-1 rounded-full hover:bg-red-700"
                  title="Delete"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                  </svg>
                </button>
              </div>
            </div>
            <div class="p-4">
              <div class="font-medium">{{ item.title || 'Untitled' }}</div>
              <div class="flex items-center justify-between mt-1">
                <span class="text-sm text-gray-500">{{ item.category }}</span>
                <span v-if="item.is_active" class="text-xs bg-green-100 text-green-800 px-2 py-0.5 rounded">Active</span>
                <span v-else class="text-xs bg-gray-100 text-gray-800 px-2 py-0.5 rounded">Inactive</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Edit Modal -->
        <div v-if="showEditModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
          <div class="bg-white rounded-lg p-6 w-full max-w-md">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Edit Gallery Item</h3>
            <form @submit.prevent="submitEditForm">
              <div class="space-y-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                  <input
                    v-model="editForm.title"
                    type="text"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md"
                  />
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                  <select
                    v-model="editForm.category"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md"
                  >
                    <option value="photos">Photos</option>
                    <option value="videos">Videos</option>
                  </select>
                </div>
                <div v-if="editForm.category !== 'videos'">
                  <label class="block text-sm font-medium text-gray-700 mb-1">Image</label>
                  <input
                    type="file"
                    accept="image/*"
                    @change="editForm.image_file = $event.target.files[0]"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md"
                  />
                  <p v-if="editingItem?.image_path" class="text-sm text-gray-500 mt-1">Current: {{ editingItem.image_path }}</p>
                </div>
                <div v-if="editForm.category === 'videos'">
                  <label class="block text-sm font-medium text-gray-700 mb-1">Video Embed URL</label>
                  <input
                    v-model="editForm.video_url"
                    type="text"
                    placeholder="https://www.youtube.com/embed/..."
                    class="w-full px-3 py-2 border border-gray-300 rounded-md"
                  />
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                  <input
                    v-model="editForm.sort_order"
                    type="number"
                    min="0"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md"
                  />
                </div>
                <div class="flex items-center">
                  <input
                    v-model="editForm.is_active"
                    type="checkbox"
                    id="edit_is_active"
                    class="h-4 w-4 text-blue-600 border-gray-300 rounded"
                  />
                  <label for="edit_is_active" class="ml-2 text-sm text-gray-700">Active</label>
                </div>
              </div>
              <div class="mt-4 flex justify-end gap-2">
                <button
                  type="button"
                  @click="closeEditModal"
                  class="px-4 py-2 text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200"
                >
                  Cancel
                </button>
                <button
                  type="submit"
                  class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
                >
                  Save Changes
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref } from 'vue'
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

// Edit modal state
const showEditModal = ref(false)
const editingItem = ref(null)
const editForm = useForm({
  title: '',
  image_file: null,
  image_path: '',
  category: 'photos',
  video_url: '',
  sort_order: 0,
  is_active: true,
})

// Delete form
const deleteForm = useForm()

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

function openEditModal(item) {
  editingItem.value = item
  editForm.title = item.title || ''
  editForm.category = item.category || 'photos'
  editForm.video_url = item.video_url || ''
  editForm.image_path = item.image_path || ''
  editForm.sort_order = item.sort_order || 0
  editForm.is_active = item.is_active ?? true
  editForm.image_file = null
  showEditModal.value = true
}

function closeEditModal() {
  showEditModal.value = false
  editingItem.value = null
  editForm.reset()
}

function submitEditForm() {
  if (editForm.category === 'videos' && !editForm.video_url) {
    alert('Please enter a video embed URL for videos category')
    return
  }

  const formData = new FormData()
  formData.append('_method', 'PUT') // Method spoofing for Laravel
  formData.append('title', editForm.title)
  formData.append('category', editForm.category)
  formData.append('video_url', editForm.video_url)
  formData.append('sort_order', editForm.sort_order)
  formData.append('is_active', editForm.is_active ? '1' : '0')

  if (editForm.image_file) {
    formData.append('image_file', editForm.image_file)
  }

  if (editForm.image_path) {
    formData.append('image_path', editForm.image_path)
  }

  axios.post(`/admin/gallery/${editingItem.value.id}`, formData, {
    headers: {
      'Content-Type': 'multipart/form-data'
    }
  }).then(() => {
    closeEditModal()
  }).catch((error) => {
    console.error('Error:', error)
    alert('Error: ' + (error.response?.data?.message || error.message))
  })
}

function deleteGalleryItem(item) {
  if (confirm('Are you sure you want to delete this gallery item?')) {
    deleteForm.delete(`/admin/gallery/${item.id}`, {
      onSuccess: () => {
        // Page will automatically reload
      }
    })
  }
}
</script>
