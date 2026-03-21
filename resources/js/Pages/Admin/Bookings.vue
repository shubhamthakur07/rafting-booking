<template>
  <div class="min-h-screen bg-gray-100">
    <nav class="bg-white shadow-sm border-b border-gray-200">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex items-center">
            <h1 class="text-xl font-bold text-gray-900">River Rafting Admin</h1>
          </div>
          <div class="flex items-center space-x-4">
            <a href="/admin" class="text-gray-600 hover:text-gray-900">Dashboard</a>
            <a href="/admin/bookings" class="text-blue-600 font-medium">Bookings</a>
            <a href="/admin/time-slots" class="text-gray-600 hover:text-gray-900">Time Slots</a>
            <a href="/admin/testimonials" class="text-gray-600 hover:text-gray-900">Testimonials</a>
            <a href="/admin/gallery" class="text-gray-600 hover:text-gray-900">Gallery</a>
            <form method="POST" action="/logout">
              <button type="submit" class="text-red-600 hover:text-red-800">Logout</button>
            </form>
          </div>
        </div>
      </div>
    </nav>

    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
      <div class="px-4 py-6 sm:px-0">
        <div class="flex justify-between items-center mb-6">
          <h2 class="text-2xl font-bold text-gray-900">Bookings</h2>

          <!-- QR Scanner Section -->
          <div class="flex items-center space-x-4">
            <div class="flex">
              <input
                v-model="scanReference"
                type="text"
                placeholder="Enter booking reference"
                class="px-4 py-2 border border-gray-300 rounded-l-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              />
              <button
                @click="handleCheckIn"
                :disabled="!scanReference || processing"
                class="px-4 py-2 bg-blue-600 text-white rounded-r-lg hover:bg-blue-700 disabled:bg-gray-400"
              >
                {{ processing ? 'Processing...' : 'Check In / Mark Paid' }}
              </button>
            </div>
          </div>
        </div>

        <!-- Success Message -->
        <div v-if="successMessage" class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
          {{ successMessage }}
        </div>

        <!-- Error Message -->
        <div v-if="errorMessage" class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
          {{ errorMessage }}
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow mb-6 p-4">
          <div class="flex flex-wrap items-center gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
              <select
                v-model="filters.status"
                @change="applyFilters"
                class="px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"
              >
                <option value="all">All</option>
                <option value="confirmed">Confirmed</option>
                <option value="checked_in">Checked In</option>
                <option value="completed">Completed</option>
                <option value="cancelled">Cancelled</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
              <input
                v-model="filters.date"
                type="date"
                @change="applyFilters"
                class="px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"
              />
            </div>
            <button
              @click="clearFilters"
              class="px-4 py-2 text-gray-600 hover:text-gray-800"
            >
              Clear Filters
            </button>
          </div>
        </div>

        <!-- Bookings Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reference</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Guests</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="booking in bookings.data" :key="booking.id">
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="font-medium text-blue-600">{{ booking.booking_reference }}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  {{ formatDate(booking.booking_date) }}
                  <span class="text-gray-500 text-sm ml-2">{{ booking.time_slot?.start_time }}</span>
                </td>
                <td class="px-6 py-4">
                  <div class="text-sm font-medium text-gray-900">{{ booking.customer_name }}</div>
                  <div class="text-sm text-gray-500">{{ booking.customer_email }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  {{ booking.number_of_people }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span
                    :class="[
                      'px-2 py-1 text-xs font-semibold rounded-full',
                      booking.payment_status === 'paid' ? 'bg-green-100 text-green-800' :
                      booking.payment_status === 'at_site' ? 'bg-orange-100 text-orange-800' :
                      'bg-yellow-100 text-yellow-800'
                    ]"
                  >
                    {{ booking.payment_status === 'paid' ? 'Paid' : booking.payment_status === 'at_site' ? 'Pay at Site' : 'Pending' }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span
                    :class="[
                      'px-2 py-1 text-xs font-semibold rounded-full',
                      booking.booking_status === 'confirmed' ? 'bg-blue-100 text-blue-800' :
                      booking.booking_status === 'checked_in' ? 'bg-purple-100 text-purple-800' :
                      booking.booking_status === 'completed' ? 'bg-green-100 text-green-800' :
                      'bg-red-100 text-red-800'
                    ]"
                  >
                    {{ booking.booking_status }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                  <div class="flex space-x-2">
                    <button
                      v-if="booking.booking_status === 'confirmed'"
                      @click="checkIn(booking.booking_reference)"
                      class="text-blue-600 hover:text-blue-800"
                    >
                      Check In
                    </button>
                    <button
                      v-if="booking.booking_status === 'checked_in'"
                      @click="complete(booking.booking_reference)"
                      class="text-green-600 hover:text-green-800"
                    >
                      Complete
                    </button>
                    <button
                      v-if="booking.payment_status === 'at_site' && booking.booking_status !== 'completed'"
                      @click="markPaid(booking.booking_reference)"
                      class="text-orange-600 hover:text-orange-800"
                    >
                      Mark Paid
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
          <p class="text-sm text-gray-600">
            Showing {{ bookings.from }} to {{ bookings.to }} of {{ bookings.total }} results
          </p>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'

const props = defineProps({
  bookings: Object,
  filters: Object,
})

const scanReference = ref('')
const processing = ref(false)
const successMessage = ref('')
const errorMessage = ref('')

const filters = useForm({
  status: props.filters?.status || 'all',
  date: props.filters?.date || '',
})

function formatDate(date) {
  return new Date(date).toLocaleDateString('en-US', {
    month: 'short',
    day: 'numeric',
    year: 'numeric'
  })
}

function applyFilters() {
  const params = new URLSearchParams()
  if (filters.status !== 'all') params.append('status', filters.status)
  if (filters.date) params.append('date', filters.date)

  const query = params.toString()
  window.location.href = `/admin/bookings${query ? '?' + query : ''}`
}

function clearFilters() {
  window.location.href = '/admin/bookings'
}

async function handleCheckIn() {
  if (!scanReference.value) return

  processing.value = true
  successMessage.value = ''
  errorMessage.value = ''

  try {
    const response = await fetch('/admin/check-in', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
      },
      body: JSON.stringify({ reference: scanReference.value })
    })

    const data = await response.json()

    if (response.ok) {
      successMessage.value = data.message
      scanReference.value = ''
      setTimeout(() => window.location.reload(), 1500)
    } else {
      errorMessage.value = data.error || 'An error occurred'
    }
  } catch (error) {
    errorMessage.value = 'Failed to process request'
  }

  processing.value = false
}

async function checkIn(reference) {
  try {
    const response = await fetch('/admin/check-in', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
      },
      body: JSON.stringify({ reference })
    })

    if (response.ok) {
      window.location.reload()
    }
  } catch (error) {
    console.error('Error:', error)
  }
}

async function markPaid(reference) {
  try {
    const response = await fetch('/admin/mark-paid', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
      },
      body: JSON.stringify({ reference })
    })

    if (response.ok) {
      window.location.reload()
    }
  } catch (error) {
    console.error('Error:', error)
  }
}

async function complete(reference) {
  try {
    const response = await fetch('/admin/complete', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
      },
      body: JSON.stringify({ reference })
    })

    if (response.ok) {
      window.location.reload()
    }
  } catch (error) {
    console.error('Error:', error)
  }
}
</script>
