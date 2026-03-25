<template>
  <div class="min-h-screen bg-gray-100">
    <AdminNav current-page="testimonials" />

    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
      <div class="px-4 py-6 sm:px-0">
        <div class="flex justify-between items-center mb-6">
          <h2 class="text-2xl font-bold text-gray-900">Testimonials</h2>
        </div>

        <!-- Add Testimonial Form -->
        <div class="bg-white rounded-lg shadow mb-6 p-6">
          <h3 class="text-lg font-medium text-gray-900 mb-4">Add New Testimonial</h3>
          <form @submit.prevent="submitForm">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Customer Name</label>
                <input
                  v-model="form.customer_name"
                  type="text"
                  required
                  class="w-full px-3 py-2 border border-gray-300 rounded-md"
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Rating</label>
                <select
                  v-model="form.rating"
                  required
                  class="w-full px-3 py-2 border border-gray-300 rounded-md"
                >
                  <option :value="5">5 Stars</option>
                  <option :value="4">4 Stars</option>
                  <option :value="3">3 Stars</option>
                  <option :value="2">2 Stars</option>
                  <option :value="1">1 Star</option>
                </select>
              </div>
              <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Comment</label>
                <textarea
                  v-model="form.comment"
                  rows="3"
                  required
                  class="w-full px-3 py-2 border border-gray-300 rounded-md"
                ></textarea>
              </div>
              <div class="md:col-span-2">
                <button
                  type="submit"
                  class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700"
                >
                  Add Testimonial
                </button>
              </div>
            </div>
          </form>
        </div>

        <!-- Testimonials Grid -->
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div v-for="testimonial in testimonials" :key="testimonial.id" class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
              <div class="flex items-center">
                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                  <span class="text-blue-600 font-bold">{{ testimonial.customer_name.charAt(0) }}</span>
                </div>
                <div class="ml-3">
                  <div class="font-semibold">{{ testimonial.customer_name }}</div>
                  <div class="text-yellow-400 text-sm">{{ '★'.repeat(testimonial.rating) }}</div>
                </div>
              </div>
              <div class="flex space-x-2">
                <button
                  @click="openEditModal(testimonial)"
                  class="text-blue-600 hover:text-blue-800 p-1"
                  title="Edit"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                  </svg>
                </button>
                <button
                  @click="deleteTestimonial(testimonial)"
                  class="text-red-600 hover:text-red-800 p-1"
                  title="Delete"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                  </svg>
                </button>
              </div>
            </div>
            <p class="text-gray-600 text-sm italic">"{{ testimonial.comment }}"</p>
            <div class="mt-2">
              <span :class="testimonial.is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'" class="text-xs px-2 py-1 rounded">
                {{ testimonial.is_active ? 'Active' : 'Inactive' }}
              </span>
            </div>
          </div>
        </div>
      </div>
    </main>

    <!-- Edit Modal -->
    <div v-if="showEditModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Edit Testimonial</h3>
        <form @submit.prevent="submitEditForm">
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Customer Name</label>
              <input
                v-model="editForm.customer_name"
                type="text"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-md"
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Rating</label>
              <select
                v-model="editForm.rating"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-md"
              >
                <option :value="5">5 Stars</option>
                <option :value="4">4 Stars</option>
                <option :value="3">3 Stars</option>
                <option :value="2">2 Stars</option>
                <option :value="1">1 Star</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Comment</label>
              <textarea
                v-model="editForm.comment"
                rows="3"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-md"
              ></textarea>
            </div>
            <div class="flex items-center">
              <input
                v-model="editForm.is_active"
                type="checkbox"
                id="is_active"
                class="h-4 w-4 text-blue-600 border-gray-300 rounded"
              />
              <label for="is_active" class="ml-2 block text-sm text-gray-700">Active</label>
            </div>
          </div>
          <div class="mt-4 flex justify-end space-x-3">
            <button
              type="button"
              @click="closeEditModal"
              class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50"
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
</template>

<script setup>
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import AdminNav from '@/Components/AdminNav.vue'

const props = defineProps({
  testimonials: Array,
})

const showEditModal = ref(false)
const editingTestimonial = ref(null)

const form = useForm({
  customer_name: '',
  rating: 5,
  comment: '',
  is_active: true,
})

const editForm = useForm({
  customer_name: '',
  rating: 5,
  comment: '',
  is_active: true,
})

const deleteForm = useForm()

function submitForm() {
  form.post('/admin/testimonials', {
    onSuccess: () => {
      form.reset()
    }
  })
}

function openEditModal(testimonial) {
  editingTestimonial.value = testimonial
  editForm.customer_name = testimonial.customer_name
  editForm.rating = testimonial.rating
  editForm.comment = testimonial.comment
  editForm.is_active = testimonial.is_active
  showEditModal.value = true
}

function closeEditModal() {
  showEditModal.value = false
  editingTestimonial.value = null
  editForm.reset()
}

function submitEditForm() {
  editForm.put(`/admin/testimonials/${editingTestimonial.value.id}`, {
    onSuccess: () => {
      closeEditModal()
    }
  })
}

function deleteTestimonial(testimonial) {
  if (confirm('Are you sure you want to delete this testimonial?')) {
    deleteForm.delete(`/admin/testimonials/${testimonial.id}`)
  }
}
</script>
