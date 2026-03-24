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
            <div class="flex items-center mb-4">
              <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                <span class="text-blue-600 font-bold">{{ testimonial.customer_name.charAt(0) }}</span>
              </div>
              <div class="ml-3">
                <div class="font-semibold">{{ testimonial.customer_name }}</div>
                <div class="text-yellow-400 text-sm">{{ '★'.repeat(testimonial.rating) }}</div>
              </div>
            </div>
            <p class="text-gray-600 text-sm italic">"{{ testimonial.comment }}"</p>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import AdminNav from '@/Components/AdminNav.vue'

const props = defineProps({
  testimonials: Array,
})

const form = useForm({
  customer_name: '',
  rating: 5,
  comment: '',
  is_active: true,
})

function submitForm() {
  form.post('/admin/testimonials', {
    onSuccess: () => {
      form.reset()
    }
  })
}
</script>
