<template>
  <div class="min-h-screen bg-gradient-to-br from-blue-50 to-blue-100 flex items-center justify-center py-12 px-4">
    <div class="max-w-2xl w-full">
      <!-- Success Message -->
      <div class="text-center mb-8">
        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
          <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
          </svg>
        </div>
        <h1 class="text-4xl font-bold text-gray-900 mb-2">Booking Confirmed!</h1>
        <p class="text-xl text-gray-600">Your river rafting adventure is booked.</p>
      </div>

      <!-- Booking Details Card -->
      <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
        <div class="bg-blue-600 px-6 py-4">
          <h2 class="text-white text-xl font-bold">Booking Details</h2>
        </div>

        <div class="p-6">
          <div class="flex items-center justify-between mb-6 pb-6 border-b border-gray-200">
            <div>
              <div class="text-sm text-gray-500">Booking Reference</div>
              <div class="text-2xl font-bold text-blue-600">{{ booking.booking_reference }}</div>
            </div>
            <div class="text-right">
              <div class="text-sm text-gray-500">Status</div>
              <span
                :class="[
                  'inline-block px-3 py-1 rounded-full text-sm font-semibold',
                  booking.payment_status === 'paid' ? 'bg-green-100 text-green-800' :
                  booking.payment_status === 'at_site' ? 'bg-orange-100 text-orange-800' :
                  'bg-yellow-100 text-yellow-800'
                ]"
              >
                {{ booking.payment_status === 'paid' ? 'Paid' : booking.payment_status === 'at_site' ? 'Pay at Site' : 'Pending' }}
              </span>
            </div>
          </div>

          <div class="grid md:grid-cols-2 gap-6 mb-6">
            <div>
              <div class="text-sm text-gray-500 mb-1">Date</div>
              <div class="font-semibold text-lg">{{ formatDate(booking.booking_date) }}</div>
            </div>
            <div>
              <div class="text-sm text-gray-500 mb-1">Time</div>
              <div class="font-semibold text-lg">{{ booking.time_slot?.start_time }}</div>
            </div>
            <div>
              <div class="text-sm text-gray-500 mb-1">Guests</div>
              <div class="font-semibold text-lg">{{ booking.number_of_people }} {{ booking.number_of_people === 1 ? 'person' : 'people' }}</div>
            </div>
            <div>
              <div class="text-sm text-gray-500 mb-1">Total Price</div>
              <div class="font-semibold text-lg text-green-600">${{ parseFloat(booking.total_price).toFixed(2) }}</div>
            </div>
          </div>

          <div class="grid md:grid-cols-2 gap-6 mb-6">
            <div>
              <div class="text-sm text-gray-500 mb-1">Name</div>
              <div>{{ booking.customer_name }}</div>
            </div>
            <div>
              <div class="text-sm text-gray-500 mb-1">Email</div>
              <div>{{ booking.customer_email }}</div>
            </div>
            <div>
              <div class="text-sm text-gray-500 mb-1">Phone</div>
              <div>{{ booking.customer_phone }}</div>
            </div>
          </div>

          <!-- QR Code Section -->
          <div v-if="booking.qr_code" class="mt-8 pt-6 border-t border-gray-200">
            <div class="text-center">
              <h3 class="text-lg font-semibold mb-4">Your QR Code Ticket</h3>
              <p class="text-sm text-gray-600 mb-4">Show this QR code when you arrive at the rafting site</p>
              <div class="inline-block bg-white p-4 rounded-lg border border-gray-200">
                <img
                  :src="'data:image/png;base64,' + booking.qr_code"
                  alt="QR Code"
                  class="w-48 h-48"
                />
              </div>
              <p class="text-xs text-gray-500 mt-2">Scan to check in</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Payment Instructions -->
      <div v-if="booking.payment_status === 'at_site'" class="mt-6 bg-orange-50 border border-orange-200 rounded-xl p-6">
        <div class="flex items-start">
          <div class="flex-shrink-0">
            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
          </div>
          <div class="ml-3">
            <h3 class="text-lg font-semibold text-orange-800">Pay at Site</h3>
            <p class="text-orange-700 mt-1">
              Please pay <strong>${{ parseFloat(booking.total_price).toFixed(2) }}</strong> when you arrive at the rafting site.
              Show your QR code ticket above for check-in.
            </p>
          </div>
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="mt-8 flex flex-col sm:flex-row gap-4">
        <a
          href="/"
          class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-3 px-6 rounded-lg text-center transition-colors"
        >
          Back to Home
        </a>
        <button
          @click="printTicket"
          class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors"
        >
          Print Ticket
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  booking: Object,
})

function formatDate(date) {
  return new Date(date).toLocaleDateString('en-US', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}

function printTicket() {
  window.print()
}
</script>
