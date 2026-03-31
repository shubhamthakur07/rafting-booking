<template>
  <div class="min-h-screen bg-gray-100">
    <AdminNav current-page="packages" />

    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
      <div class="px-4 py-6 sm:px-0">
        <div class="flex justify-between items-center mb-6">
          <h2 class="text-2xl font-bold text-gray-900">Packages</h2>
          <button
            @click="showCreateModal = true"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg"
          >
            Add Package
          </button>
        </div>

        <!-- Packages Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div
            v-for="pkg in packages"
            :key="pkg.id"
            class="bg-white rounded-lg shadow overflow-hidden"
          >
            <div class="p-6">
              <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">{{ pkg.name }}</h3>
                <span :class="pkg.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'" class="px-2 py-1 text-xs font-medium rounded-full">
                  {{ pkg.is_active ? 'Active' : 'Inactive' }}
                </span>
              </div>
              <p class="text-gray-600 text-sm mb-4">{{ pkg.description }}</p>
              <div class="space-y-2 mb-4">
                <div class="flex justify-between text-sm">
                  <span class="text-gray-500">Price:</span>
                  <span class="font-medium text-gray-900">₹{{ pkg.price }}</span>
                </div>
                <div class="flex justify-between text-sm">
                  <span class="text-gray-500">Distance:</span>
                  <span class="font-medium text-gray-900">{{ pkg.km }} km</span>
                </div>

              </div>
              <div class="flex space-x-2">
                <button
                  @click="editPackage(pkg)"
                  class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg text-sm"
                >
                  Edit
                </button>
                <button
                  @click="deletePackage(pkg.id)"
                  class="flex-1 bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-lg text-sm"
                >
                  Delete
                </button>
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
          {{ showEditModal ? 'Edit Package' : 'Create Package' }}
        </h3>
        <form @submit.prevent="submitForm">
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
              <input
                type="text"
                v-model="form.name"
                class="w-full border border-gray-300 rounded-lg px-3 py-2"
                required
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
              <textarea
                v-model="form.description"
                rows="3"
                class="w-full border border-gray-300 rounded-lg px-3 py-2"
              ></textarea>
            </div>
            <div class="grid grid-cols-3 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Price (₹)</label>
                <input
                  type="number"
                  v-model="form.price"
                  min="0"
                  step="0.01"
                  class="w-full border border-gray-300 rounded-lg px-3 py-2"
                  required
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Distance (km)</label>
                <input
                  type="number"
                  v-model="form.km"
                  min="1"
                  class="w-full border border-gray-300 rounded-lg px-3 py-2"
                  required
                />
              </div>

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
  packages: Array
})

const showCreateModal = ref(false)
const showEditModal = ref(false)
const editingPackage = ref(null)

const form = ref({
  name: '',
  description: '',
  price: '',
  km: '',
  is_active: true
})

function editPackage(pkg) {
  editingPackage.value = pkg
  form.value = {
    name: pkg.name,
    description: pkg.description,
    price: pkg.price,
    km: pkg.km,
    is_active: pkg.is_active
  }
  showEditModal.value = true
}

function closeModal() {
  showCreateModal.value = false
  showEditModal.value = false
  editingPackage.value = null
  form.value = {
    name: '',
    description: '',
    price: '',
    km: '',
    is_active: true
  }
}

function submitForm() {
  if (showEditModal.value) {
    router.put(`/admin/packages/${editingPackage.value.id}`, form.value, {
      onSuccess: () => closeModal()
    })
  } else {
    router.post('/admin/packages', form.value, {
      onSuccess: () => closeModal()
    })
  }
}

function deletePackage(id) {
  if (confirm('Are you sure you want to delete this package?')) {
    router.delete(`/admin/packages/${id}`)
  }
}
</script>
