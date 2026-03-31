<template>
  <div class="min-h-screen bg-gray-100">
    <AdminNav current-page="testimonials" />

    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
      <div class="px-4 py-6 sm:px-0">
        <div class="flex justify-between items-center mb-6">
          <h2 class="text-2xl font-bold text-gray-900">Testimonials</h2>
          <button
            @click="showCreateModal = true"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg"
          >
            Add Testimonial
          </button>
        </div>

        <!-- Testimonials Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div
            v-for="testimonial in testimonials"
            :key="testimonial.id"
            class="bg-white rounded-lg shadow overflow-hidden"
          >
            <div class="p-6">
              <div class="flex items-center mb-4">
                <div class="flex-shrink-0">
                  <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <span class="text-blue-600 font-bold text-lg">{{ testimonial.customer_name.charAt(0) }}</span>
                  </div>
                </div>
                <div class="ml-4">
                  <h3 class="text-lg font-medium text-gray-900">{{ testimonial.customer_name }}</h3>
                  <div class="flex items-center">
                    <div class="flex text-yellow-400">
                      <svg v-for="i in 5" :key="i" :class="i <= testimonial.rating ? 'text-yellow-400' : 'text-gray-300'" class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                        <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                      </svg>
                    </div>
                    <span class="ml-2 text-sm text-gray-500">{{ testimonial.rating }}/5</span>
                  </div>
                </div>
              </div>
              <p class="text-gray-600 text-sm mb-4">{{ testimonial.comment }}</p>
              <div class="flex items-center justify-between">
                <span :class="testimonial.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'" class="px-2 py-1 text-xs font-medium rounded-full">
                  {{ testimonial.is_active ? 'Active' : 'Inactive' }}
                </span>
                <div class="flex space-x-2">
                  <button
                    @click="editTestimonial(testimonial)"
                    class="text-blue-600 hover:text-blue-900 text-sm"
                  >
                    Edit
                  </button>
                  <button
                    @click="deleteTestimonial(testimonial.id)"
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
          {{ showEditModal ? 'Edit Testimonial' : 'Create Testimonial' }}
        </h3>
        <form @submit.prevent="submitForm">
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Customer Name</label>
              <input
                type="text"
                v-model="form.customer_name"
                class="w-full border border-gray-300 rounded-lg px-3 py-2"
                required
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Rating</label>
              <select v-model="form.rating" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
                <option v-for="n in 5" :key="n" :value="n">{{ n }} Star{{ n > 1 ? 's' : '' }}</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Comment</label>
              <textarea
                v-model="form.comment"
                rows="4"
                class="w-full border border-gray-300 rounded-lg px-3 py-2"
                required
              ></textarea>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
              <select v-model="form.is_active" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                <option :value="true">Active</option>
                <option :value="false">Inactive</option>
              </select>
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
              {{ showEditModal ? 'Update' : 'Create' }}
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
  testimonials: Array
})

const showCreateModal = ref(false)
const showEditModal = ref(false)
const editingTestimonial = ref(null)

const form = ref({
  customer_name: '',
  rating: 5,
  comment: '',
  is_active: true
})

function editTestimonial(testimonial) {
  editingTestimonial.value = testimonial
  form.value = {
    customer_name: testimonial.customer_name,
    rating: testimonial.rating,
    comment: testimonial.comment,
    is_active: testimonial.is_active
  }
  showEditModal.value = true
}

function closeModal() {
  showCreateModal.value = false
  showEditModal.value = false
  editingTestimonial.value = null
  form.value = {
    customer_name: '',
    rating: 5,
    comment: '',
    is_active: true
  }
}

function submitForm() {
  if (showEditModal.value) {
    router.put(`/admin/testimonials/${editingTestimonial.value.id}`, form.value, {
      onSuccess: () => closeModal()
    })
  } else {
    router.post('/admin/testimonials', form.value, {
      onSuccess: () => closeModal()
    })
  }
}

function deleteTestimonial(id) {
  if (confirm('Are you sure you want to delete this testimonial?')) {
    router.delete(`/admin/testimonials/${id}`)
  }
}
</script>
