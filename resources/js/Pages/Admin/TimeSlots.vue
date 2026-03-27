<template>
  <div class="min-h-screen bg-gray-100">
    <AdminNav current-page="time-slots" />

    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
      <div class="px-4 py-6 sm:px-0">
        <div class="flex justify-between items-center mb-6">
          <h2 class="text-2xl font-bold text-gray-900">Time Slots</h2>
        </div>

        <!-- Success Message -->
        <div v-if="$page.props.flash?.success" class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
          {{ $page.props.flash.success }}
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
              <!-- <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Price (₹)</label>
                <input
                  v-model="form.price"
                  type="number"
                  step="0.01"
                  min="0"
                  required
                  class="w-full px-3 py-2 border border-gray-300 rounded-md"
                />
              </div> -->
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
                <!-- <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th> -->
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="slot in timeSlots" :key="slot.id">
                <td class="px-6 py-4">{{ slot.start_time }}</td>
                <td class="px-6 py-4">{{ slot.end_time }}</td>
                <td class="px-6 py-4">{{ slot.max_people }}</td>
                <!-- <td class="px-6 py-4">₹{{ slot.price }}</td> -->
                <td class="px-6 py-4">
                  <span :class="slot.is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'" class="px-2 py-1 text-xs rounded-full">
                    {{ slot.is_active ? 'Active' : 'Inactive' }}
                  </span>
                </td>
                <td class="px-6 py-4">
                  <button
                    @click="editTimeSlot(slot)"
                    class="text-blue-600 hover:text-blue-900 mr-3"
                  >
                    Edit
                  </button>
                  <button
                    @click="deleteTimeSlot(slot.id)"
                    class="text-red-600 hover:text-red-900"
                  >
                    Delete
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Edit Modal -->
        <div v-if="showEditModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
          <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
              <h3 class="text-lg font-medium text-gray-900 mb-4">Edit Time Slot</h3>
              <form @submit.prevent="updateTimeSlot">
                <div class="mb-4">
                  <label class="block text-sm font-medium text-gray-700 mb-1">Start Time</label>
                  <input
                    v-model="editForm.start_time"
                    type="time"
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md"
                  />
                </div>
                <div class="mb-4">
                  <label class="block text-sm font-medium text-gray-700 mb-1">End Time</label>
                  <input
                    v-model="editForm.end_time"
                    type="time"
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md"
                  />
                </div>
                <div class="mb-4">
                  <label class="block text-sm font-medium text-gray-700 mb-1">Max People</label>
                  <input
                    v-model="editForm.max_people"
                    type="number"
                    min="1"
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md"
                  />
                </div>
                <!-- <div class="mb-4">
                  <label class="block text-sm font-medium text-gray-700 mb-1">Price (₹)</label>
                  <input
                    v-model="editForm.price"
                    type="number"
                    step="0.01"
                    min="0"
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md"
                  />
                </div> -->
                <div class="mb-4">
                  <label class="flex items-center">
                    <input
                      v-model="editForm.is_active"
                      type="checkbox"
                      class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                    />
                    <span class="ml-2 text-sm text-gray-600">Active</span>
                  </label>
                </div>
                <div class="flex justify-end space-x-3">
                  <button
                    type="button"
                    @click="closeEditModal"
                    class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400"
                  >
                    Cancel
                  </button>
                  <button
                    type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
                  >
                    Update
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import AdminNav from '@/Components/AdminNav.vue'
import { useForm, router } from '@inertiajs/vue3'

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

const showEditModal = ref(false)
const editingSlot = ref(null)

const editForm = useForm({
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

function editTimeSlot(slot) {
  editingSlot.value = slot
  editForm.start_time = slot.start_time
  editForm.end_time = slot.end_time
  editForm.max_people = slot.max_people
  editForm.price = slot.price
  editForm.is_active = slot.is_active
  showEditModal.value = true
}

function closeEditModal() {
  showEditModal.value = false
  editingSlot.value = null
  editForm.reset()
}

function updateTimeSlot() {
  editForm.put(`/admin/time-slots/${editingSlot.value.id}`, {
    onSuccess: () => {
      closeEditModal()
    }
  })
}

function deleteTimeSlot(slotId) {
  if (confirm('Are you sure you want to delete this time slot?')) {
    router.delete(`/admin/time-slots/${slotId}`)
  }
}
</script>
