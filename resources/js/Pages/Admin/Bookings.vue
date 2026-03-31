<template>
  <div class="min-h-screen bg-gray-100">
    <AdminNav current-page="bookings" />

    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
      <div class="px-4 py-6 sm:px-0">
        <div class="flex justify-between items-center mb-6">
          <h2 class="text-2xl font-bold text-gray-900">Bookings</h2>
          <button
            @click="showCreateModal = true"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg"
          >
            Add Booking
          </button>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow p-4 mb-6">
          <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
              <select v-model="filters.booking_status" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                <option value="">All Statuses</option>
                <option value="pending">Pending</option>
                <option value="confirmed">Confirmed</option>
                <option value="cancelled">Cancelled</option>
                <option value="completed">Completed</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
              <input
                type="date"
                v-model="filters.date"
                class="w-full border border-gray-300 rounded-lg px-3 py-2"
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
              <input
                type="text"
                v-model="filters.search"
                placeholder="Name, email, or reference..."
                class="w-full border border-gray-300 rounded-lg px-3 py-2"
              />
            </div>
            <div class="flex items-end">
              <button
                @click="resetFilters"
                class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg"
              >
                Reset
              </button>
            </div>
          </div>
        </div>

        <!-- Bookings Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reference</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Package</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">People</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="booking in bookings.data" :key="booking.id">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                  {{ booking.booking_reference }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm font-medium text-gray-900">{{ booking.customer_name }}</div>
                  <div class="text-sm text-gray-500">{{ booking.customer_email }}</div>
                  <div class="text-sm text-gray-500">{{ booking.customer_phone }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900">{{ formatDate(booking.booking_date) }}</div>
                  <div class="text-sm text-gray-500">{{ booking.time_slot?.start_time }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ booking.package?.name || 'N/A' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ booking.number_of_people }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  ₹{{ booking.total_price }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span :class="getStatusClass(booking.booking_status)" class="px-2 py-1 text-xs font-medium rounded-full">
                    {{ getStatusLabel(booking.booking_status) }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  <button
                    @click="editBooking(booking)"
                    class="text-blue-600 hover:text-blue-900 mr-3"
                  >
                    Edit
                  </button>
                  <button
                    @click="deleteBooking(booking.id)"
                    class="text-red-600 hover:text-red-900"
                  >
                    Delete
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4 flex justify-center">
          <nav class="flex space-x-2">
            <button
              v-for="link in bookings.links"
              :key="link.label"
              @click="goToPage(link.url)"
              :disabled="!link.url"
              :class="[
                'px-3 py-2 text-sm rounded-lg',
                link.active ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50',
                !link.url ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer'
              ]"
              v-html="link.label"
            />
          </nav>
        </div>
      </div>
    </main>

    <!-- Create/Edit Modal -->
    <Modal :show="showCreateModal || showEditModal" @close="closeModal">
      <div class="p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">
          {{ showEditModal ? 'Edit Booking' : 'Create Booking' }}
        </h3>
        <form @submit.prevent="submitForm">
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
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
              <label class="block text-sm font-medium text-gray-700 mb-1">Customer Email</label>
              <input
                type="email"
                v-model="form.customer_email"
                class="w-full border border-gray-300 rounded-lg px-3 py-2"
                required
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Customer Phone</label>
              <input
                type="tel"
                v-model="form.customer_phone"
                class="w-full border border-gray-300 rounded-lg px-3 py-2"
                required
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Booking Date</label>
              <input
                type="date"
                v-model="form.booking_date"
                class="w-full border border-gray-300 rounded-lg px-3 py-2"
                required
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Time Slot</label>
              <select v-model="form.time_slot_id" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
                <option value="">Select Time Slot</option>
                <option v-for="slot in timeSlots" :key="slot.id" :value="slot.id">
                  {{ slot.start_time }} - {{ slot.end_time }}
                </option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Package</label>
              <select v-model="form.package_id" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
                <option value="">Select Package</option>
                <option v-for="pkg in packages" :key="pkg.id" :value="pkg.id">
                  {{ pkg.name }} ({{ pkg.km }} km) - ₹{{ pkg.price }}
                </option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Number of People</label>
              <input
                type="number"
                v-model="form.number_of_people"
                min="1"
                class="w-full border border-gray-300 rounded-lg px-3 py-2"
                required
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
              <select v-model="form.booking_status" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
                <option value="pending">Pending</option>
                <option value="confirmed">Confirmed</option>
                <option value="cancelled">Cancelled</option>
                <option value="completed">Completed</option>
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
import { ref, watch } from 'vue'
import { router } from '@inertiajs/vue3'
import AdminNav from '@/Components/AdminNav.vue'
import Modal from '@/Components/Modal.vue'

const props = defineProps({
  bookings: Object,
  timeSlots: Array,
  packages: Array,
  filters: Object
})

const showCreateModal = ref(false)
const showEditModal = ref(false)
const editingBooking = ref(null)

const filters = ref({
  booking_status: props.filters?.booking_status || '',
  date: props.filters?.date || '',
  search: props.filters?.search || ''
})

const form = ref({
  customer_name: '',
  customer_email: '',
  customer_phone: '',
  booking_date: '',
  time_slot_id: '',
  package_id: '',
  number_of_people: 1,
  booking_status: 'pending'
})

watch(filters, (newFilters) => {
  router.get('/admin/bookings', newFilters, { preserveState: true, preserveScroll: true })
}, { deep: true })

function resetFilters() {
  filters.value = { booking_status: '', date: '', search: '' }
}

function formatDate(date) {
  return new Date(date).toLocaleDateString('en-IN', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  })
}

function getStatusClass(booking_status) {
  const classes = {
    pending: 'bg-yellow-100 text-yellow-800',
    confirmed: 'bg-green-100 text-green-800',
    cancelled: 'bg-red-100 text-red-800',
    completed: 'bg-blue-100 text-blue-800'
  }
  return classes[booking_status] || 'bg-gray-100 text-gray-800'
}

function getStatusLabel(booking_status) {
  const labels = {
    pending: 'Pending',
    confirmed: 'Confirmed',
    cancelled: 'Cancelled',
    completed: 'Completed'
  }
  return labels[booking_status] || booking_status
}

function editBooking(booking) {
  editingBooking.value = booking
  form.value = {
    customer_name: booking.customer_name,
    customer_email: booking.customer_email,
    customer_phone: booking.customer_phone,
    booking_date: booking.booking_date ? booking.booking_date.split('T')[0] : '',
    time_slot_id: booking.time_slot_id,
    package_id: booking.package_id,
    number_of_people: booking.number_of_people,
    booking_status: booking.booking_status
  }
  showEditModal.value = true
}

function closeModal() {
  showCreateModal.value = false
  showEditModal.value = false
  editingBooking.value = null
  form.value = {
    customer_name: '',
    customer_email: '',
    customer_phone: '',
    booking_date: '',
    time_slot_id: '',
    package_id: '',
    number_of_people: 1,
    booking_status: 'pending'
  }
}

function submitForm() {
  if (showEditModal.value) {
    router.put(`/admin/bookings/${editingBooking.value.id}`, form.value, {
      onSuccess: () => closeModal()
    })
  } else {
    router.post('/admin/bookings', form.value, {
      onSuccess: () => closeModal()
    })
  }
}

function deleteBooking(id) {
  if (confirm('Are you sure you want to delete this booking?')) {
    router.delete(`/admin/bookings/${id}`)
  }
}

function goToPage(url) {
  if (url) {
    router.get(url)
  }
}
</script>
