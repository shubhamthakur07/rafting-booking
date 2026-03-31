<template>
  <div class="min-h-screen bg-gray-100">
    <AdminNav current-page="time-slots" />

    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
      <div class="px-4 py-6 sm:px-0">
        <div class="flex justify-between items-center mb-6">
          <h2 class="text-2xl font-bold text-gray-900">Time Slots</h2>
          <button
            @click="showCreateModal = true"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg"
          >
            Add Time Slot
          </button>
        </div>

        <!-- Time Slots Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Start Time</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">End Time</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Max People</th>
                <!-- <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th> -->
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="slot in timeSlots" :key="slot.id">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ slot.start_time }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ slot.end_time }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ slot.max_people }}
                </td>
                <!-- <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  ₹{{ slot.price }}
                </td> -->
                <td class="px-6 py-4 whitespace-nowrap">
                  <span :class="slot.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'" class="px-2 py-1 text-xs font-medium rounded-full">
                    {{ slot.is_active ? 'Active' : 'Inactive' }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  <button
                    @click="editSlot(slot)"
                    class="text-blue-600 hover:text-blue-900 mr-3"
                  >
                    Edit
                  </button>
                  <button
                    @click="deleteSlot(slot.id)"
                    class="text-red-600 hover:text-red-900"
                  >
                    Delete
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </main>

    <!-- Create/Edit Modal -->
    <Modal :show="showCreateModal || showEditModal" @close="closeModal">
      <div class="p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">
          {{ showEditModal ? 'Edit Time Slot' : 'Create Time Slot' }}
        </h3>
        <form @submit.prevent="submitForm">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Start Time</label>
              <input
                type="time"
                v-model="form.start_time"
                class="w-full border border-gray-300 rounded-lg px-3 py-2"
                required
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">End Time</label>
              <input
                type="time"
                v-model="form.end_time"
                class="w-full border border-gray-300 rounded-lg px-3 py-2"
                required
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Max People</label>
              <input
                type="number"
                v-model="form.max_people"
                min="1"
                class="w-full border border-gray-300 rounded-lg px-3 py-2"
                required
              />
            </div>
            <!-- <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Price (₹)</label>
              <input
                type="number"
                v-model="form.price"
                min="0"
                step="0.01"
                class="w-full border border-gray-300 rounded-lg px-3 py-2"
                required
              />
            </div> -->
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
  timeSlots: Array
})

const showCreateModal = ref(false)
const showEditModal = ref(false)
const editingSlot = ref(null)

const form = ref({
  start_time: '',
  end_time: '',
  max_people: 20,
  price: '1',
  is_active: true
})

function editSlot(slot) {
  editingSlot.value = slot
  form.value = {
    start_time: slot.start_time,
    end_time: slot.end_time,
    max_people: slot.max_people,
    price: slot.price,
    is_active: slot.is_active
  }
  showEditModal.value = true
}

function closeModal() {
  showCreateModal.value = false
  showEditModal.value = false
  editingSlot.value = null
  form.value = {
    start_time: '',
    end_time: '',
    max_people: 20,
    price: '',
    is_active: true
  }
}

function submitForm() {
  if (showEditModal.value) {
    router.put(`/admin/time-slots/${editingSlot.value.id}`, form.value, {
      onSuccess: () => closeModal()
    })
  } else {
    router.post('/admin/time-slots', form.value, {
      onSuccess: () => closeModal()
    })
  }
}

function deleteSlot(id) {
  if (confirm('Are you sure you want to delete this time slot?')) {
    router.delete(`/admin/time-slots/${id}`)
  }
}
</script>
