<template>
  <div class="min-h-screen bg-gray-100">
    <AdminNav current-page="time-slots" />

    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
      <div class="px-4 py-6 sm:px-0">
        <div class="flex justify-between items-center mb-6">
          <h2 class="text-2xl font-bold text-gray-900">Time Slots</h2>
        </div>

        <!-- Add Time Slot Form -->
        <div class="bg-white rounded-lg shadow mb-6 p-6">
          <h3 class="text-lg font-medium text-gray-900 mb-4">Add New Time Slot</h3>
          <form @submit.prevent="submitForm">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Start Time</label>
                <input
                  v-model="form.start_time"
                  type="time"
                  required
                  class="w-full px-3 py-2 border border-gray-300 rounded-md"
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">End Time</label>
                <input
                  v-model="form.end_time"
                  type="time"
                  required
                  class="w-full px-3 py-2 border border-gray-300 rounded-md"
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Max People</label>
                <input
                  v-model="form.max_people"
                  type="number"
                  min="1"
                  required
                  class="w-full px-3 py-2 border border-gray-300 rounded-md"
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Price ($)</label>
                <input
                  v-model="form.price"
                  type="number"
                  step="0.01"
                  min="0"
                  required
                  class="w-full px-3 py-2 border border-gray-300 rounded-md"
                />
              </div>
              <div class="flex items-end">
                <button
                  type="submit"
                  class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700"
                >
                  Add Slot
                </button>
              </div>
            </div>
          </form>
        </div>

        <!-- Time Slots Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Start Time</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">End Time</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Max People</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="slot in timeSlots" :key="slot.id">
                <td class="px-6 py-4">{{ slot.start_time }}</td>
                <td class="px-6 py-4">{{ slot.end_time }}</td>
                <td class="px-6 py-4">{{ slot.max_people }}</td>
                <td class="px-6 py-4">${{ slot.price }}</td>
                <td class="px-6 py-4">
                  <span :class="slot.is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'" class="px-2 py-1 text-xs rounded-full">
                    {{ slot.is_active ? 'Active' : 'Inactive' }}
                  </span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import AdminNav from '@/Components/AdminNav.vue'
import { useForm } from '@inertiajs/vue3'

const props = defineProps({
  timeSlots: Array,
})

const form = useForm({
  start_time: '',
  end_time: '',
  max_people: 20,
  price: 50,
  is_active: true,
})

function submitForm() {
  form.post('/admin/time-slots', {
    onSuccess: () => {
      form.reset()
    }
  })
}
</script>
