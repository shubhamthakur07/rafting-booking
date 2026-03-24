<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <section class="relative h-[600px] flex items-center justify-center overflow-hidden">
      <div class="absolute inset-0 bg-gradient-to-b from-blue-900/70 to-blue-800/50 z-10"></div>
      <img
        src="https://images.unsplash.com/photo-1530866495561-507c9faab2ed?w=1920"
        alt="River Rafting"
        class="absolute inset-0 w-full h-full object-cover"
      />
      <div class="relative z-20 text-center text-white px-4 max-w-4xl mx-auto">
        <h1 class="text-5xl md:text-7xl font-bold mb-6 tracking-tight">
          River Rafting Adventure
        </h1>
        <p class="text-xl md:text-2xl mb-8 text-blue-100">
          Experience the thrill of a lifetime! Book your adventure today.
        </p>
        <a
          href="#booking"
          class="inline-block bg-orange-500 hover:bg-orange-600 text-white font-bold py-4 px-8 rounded-full text-lg transition-all transform hover:scale-105 shadow-lg"
        >
          Book Now
        </a>
      </div>
    </section>

    <!-- Features Section -->
    <section class="py-16 bg-white">
      <div class="max-w-6xl mx-auto px-4">
        <div class="grid md:grid-cols-3 gap-8">
          <div class="text-center p-6">
            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
              <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
              </svg>
            </div>
            <h3 class="text-xl font-bold mb-2">Thrilling Adventure</h3>
            <p class="text-gray-600">Navigate through exciting rapids with our expert guides</p>
          </div>
          <div class="text-center p-6">
            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
              <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
              </svg>
            </div>
            <h3 class="text-xl font-bold mb-2">Safe & Professional</h3>
            <p class="text-gray-600">Certified guides and top-quality safety equipment</p>
          </div>
          <div class="text-center p-6">
            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
              <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <h3 class="text-xl font-bold mb-2">Flexible Times</h3>
            <p class="text-gray-600">Multiple departure times to fit your schedule</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Booking Form Section -->
    <section id="booking" class="py-20 bg-gradient-to-br from-blue-50 to-blue-100">
      <div class="max-w-4xl mx-auto px-4">
        <div class="text-center mb-12">
          <h2 class="text-4xl font-bold text-gray-900 mb-4">Book Your Rafting Trip</h2>
          <p class="text-xl text-gray-600">Choose your preferred date and time</p>
        </div>

        <div class="bg-white rounded-2xl shadow-xl p-8">
          <form @submit.prevent="submitBooking">
            <!-- Date Selection -->
            <div class="mb-8">
              <label class="block text-sm font-medium text-gray-700 mb-2">Select Date</label>
              <input
                type="date"
                v-model="form.booking_date"
                :min="minDate"
                @change="loadAvailableSlots"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                required
              />
            </div>

            <!-- Time Slots -->
            <div v-if="form.booking_date" class="mb-8">
              <label class="block text-sm font-medium text-gray-700 mb-2">Select Time Slot</label>
              <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
                <button
                  v-for="slot in availableSlots"
                  :key="slot.id"
                  type="button"
                  @click="selectSlot(slot)"
                  :disabled="slot.available < 1"
                  :class="[
                    'p-4 border-2 rounded-lg transition-all text-left',
                    selectedSlot?.id === slot.id
                      ? 'border-blue-500 bg-blue-50'
                      : slot.available < 1
                        ? 'border-gray-200 bg-gray-100 cursor-not-allowed opacity-50'
                        : 'border-gray-200 hover:border-blue-300'
                  ]"
                >
                  <div class="font-semibold">{{ formatTime(slot.start_time) }}</div>
                  <div class="text-sm text-gray-600">${{ slot.price }} / person</div>
                  <div class="text-xs mt-1" :class="slot.available > 5 ? 'text-green-600' : 'text-orange-600'">
                    {{ slot.available }} spots left
                  </div>
                </button>
              </div>
              <p v-if="availableSlots.length === 0" class="text-center text-gray-500 mt-4">
                No slots available for this date. Please select another date.
              </p>
            </div>

            <!-- Number of People -->
            <div v-if="selectedSlot" class="mb-8">
              <label class="block text-sm font-medium text-gray-700 mb-2">Number of People</label>
              <select
                v-model="form.number_of_people"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                required
              >
                <option v-for="n in Math.min(10, selectedSlot.available)" :key="n" :value="n">
                  {{ n }} {{ n === 1 ? 'person' : 'people' }}
                </option>
              </select>
            </div>

            <!-- Customer Info -->
            <div v-if="selectedSlot" class="grid md:grid-cols-2 gap-6 mb-8">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                <input
                  type="text"
                  v-model="form.customer_name"
                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  required
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input
                  type="email"
                  v-model="form.customer_email"
                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  required
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                <input
                  type="tel"
                  v-model="form.customer_phone"
                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  required
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Notes (optional)</label>
                <input
                  type="text"
                  v-model="form.notes"
                  placeholder="Any special requirements?"
                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                />
              </div>
            </div>

            <!-- Payment Method -->
            <div v-if="selectedSlot" class="mb-8">
              <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
              <div class="grid md:grid-cols-2 gap-4">
                <label
                  :class="[
                    'p-4 border-2 rounded-lg cursor-pointer transition-all',
                    form.payment_method === 'online'
                      ? 'border-blue-500 bg-blue-50'
                      : 'border-gray-200 hover:border-blue-300'
                  ]"
                >
                  <input
                    type="radio"
                    v-model="form.payment_method"
                    value="online"
                    class="sr-only"
                  />
                  <div class="flex items-center">
                    <svg class="w-6 h-6 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                    <div>
                      <div class="font-semibold">Pay Online</div>
                      <div class="text-sm text-gray-600">Pay now with Stripe</div>
                    </div>
                  </div>
                </label>
                <label
                  :class="[
                    'p-4 border-2 rounded-lg cursor-pointer transition-all',
                    form.payment_method === 'at_site'
                      ? 'border-blue-500 bg-blue-50'
                      : 'border-gray-200 hover:border-blue-300'
                  ]"
                >
                  <input
                    type="radio"
                    v-model="form.payment_method"
                    value="at_site"
                    class="sr-only"
                  />
                  <div class="flex items-center">
                    <svg class="w-6 h-6 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <div>
                      <div class="font-semibold">Pay at Site</div>
                      <div class="text-sm text-gray-600">Pay when you arrive</div>
                    </div>
                  </div>
                </label>
              </div>
            </div>

            <!-- Price Summary -->
            <div v-if="selectedSlot" class="bg-gray-50 rounded-lg p-6 mb-8">
              <div class="flex justify-between items-center mb-2">
                <span class="text-gray-600">{{ selectedSlot.price }} x {{ form.number_of_people }} people</span>
                <span class="text-gray-900">${{ totalPrice.toFixed(2) }}</span>
              </div>
              <div class="flex justify-between items-center pt-2 border-t border-gray-200">
                <span class="font-semibold text-lg">Total</span>
                <span class="font-bold text-2xl text-blue-600">${{ totalPrice.toFixed(2) }}</span>
              </div>
            </div>

            <!-- Submit Button -->
            <button
              type="submit"
              :disabled="!canSubmit || loading"
              class="w-full bg-orange-500 hover:bg-orange-600 disabled:bg-gray-300 disabled:cursor-not-allowed text-white font-bold py-4 px-8 rounded-lg text-lg transition-all transform hover:scale-[1.02] shadow-lg"
            >
              <span v-if="loading">Processing...</span>
              <span v-else>{{ form.payment_method === 'online' ? 'Proceed to Payment' : 'Confirm Booking' }}</span>
            </button>
          </form>
        </div>
      </div>
    </section>

    <!-- Gallery Section -->
    <section v-if="galleryImages.length > 0" class="py-20 bg-white">
      <div class="max-w-6xl mx-auto px-4">
        <div class="text-center mb-12">
          <h2 class="text-4xl font-bold text-gray-900 mb-4">Photo Gallery</h2>
          <p class="text-xl text-gray-600">Relive the adventure through our photos</p>
        </div>
        <div class="grid md:grid-cols-3 lg:grid-cols-4 gap-4">
          <div
            v-for="(image, index) in galleryImages"
            :key="index"
            class="aspect-square overflow-hidden rounded-lg cursor-pointer group"
          >
            <img
              :src="image.image_url"
              :alt="image.title"
              class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110"
            />

          </div>
        </div>
      </div>
    </section>

    <!-- Videos Section -->
    <section v-if="videos.length > 0" class="py-20 bg-gray-900">
      <div class="max-w-6xl mx-auto px-4">
        <div class="text-center mb-12">
          <h2 class="text-4xl font-bold text-white mb-4">Video Highlights</h2>
          <p class="text-xl text-gray-300">Watch the thrill in action</p>
        </div>
        <div class="grid md:grid-cols-2 gap-8">
          <div
            v-for="(video, index) in videos"
            :key="index"
            class="aspect-video rounded-lg overflow-hidden bg-gray-800"
          >
            <iframe
              v-if="getVideoSrc(video.video_url)"
              :src="getVideoSrc(video.video_url)"
              class="w-full h-full"
              frameborder="0"
              allowfullscreen
            ></iframe>
          </div>
        </div>
      </div>
    </section>

    <!-- Testimonials Section -->
    <section v-if="testimonials.length > 0" class="py-20 bg-blue-50">
      <div class="max-w-6xl mx-auto px-4">
        <div class="text-center mb-12">
          <h2 class="text-4xl font-bold text-gray-900 mb-4">What Our Customers Say</h2>
          <p class="text-xl text-gray-600">Real experiences from real adventurers</p>
        </div>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
          <div
            v-for="testimonial in testimonials"
            :key="testimonial.id"
            class="bg-white rounded-xl p-6 shadow-md"
          >
            <div class="flex items-center mb-4">
              <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                <span class="text-blue-600 font-bold text-lg">{{ testimonial.customer_name.charAt(0) }}</span>
              </div>
              <div class="ml-4">
                <div class="font-semibold">{{ testimonial.customer_name }}</div>
                <div class="text-yellow-400 text-sm">{{ '★'.repeat(testimonial.rating) }}</div>
              </div>
            </div>
            <p class="text-gray-600 italic">"{{ testimonial.comment }}"</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-20 bg-white">
      <div class="max-w-4xl mx-auto px-4">
        <div class="text-center mb-12">
          <h2 class="text-4xl font-bold text-gray-900 mb-4">Contact Us</h2>
          <p class="text-xl text-gray-600">Have questions? We'd love to hear from you!</p>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-8">
          <form @submit.prevent="submitContact">
            <div class="grid md:grid-cols-2 gap-6 mb-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                <input
                  v-model="contactForm.name"
                  type="text"
                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  placeholder="Your name"
                  required
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input
                  v-model="contactForm.email"
                  type="email"
                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  placeholder="your@email.com"
                  required
                />
              </div>
            </div>

            <div class="mb-6">
              <label class="block text-sm font-medium text-gray-700 mb-2">Phone (Optional)</label>
              <input
                v-model="contactForm.phone"
                type="tel"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="Your phone number"
              />
            </div>

            <div class="mb-6">
              <label class="block text-sm font-medium text-gray-700 mb-2">Message</label>
              <textarea
                v-model="contactForm.message"
                rows="5"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="Your message..."
                required
              ></textarea>
            </div>

            <button
              type="submit"
              :disabled="contactSending"
              class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition-colors disabled:opacity-50"
            >
              {{ contactSending ? 'Sending...' : 'Send Message' }}
            </button>
          </form>

          <div v-if="contactSuccess" class="mt-4 p-4 bg-green-100 text-green-700 rounded-lg">
            {{ contactSuccess }}
          </div>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
      <div class="max-w-6xl mx-auto px-4 text-center">
        <p class="text-lg">© {{ new Date().getFullYear() }} River Rafting Adventure. All rights reserved.</p>
        <p class="text-gray-400 mt-2">Admin? <a href="/login" class="text-blue-400 hover:underline">Login here</a></p>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useForm } from '@inertiajs/vue3'

const props = defineProps({
  timeSlots: Array,
  testimonials: Array,
  galleryImages: Array,
  videos: Array,
})

// Extract video src from URL or full iframe code
function getVideoSrc(videoUrl) {
  if (!videoUrl) return null

  let src = videoUrl

  // If it's a full iframe code, extract src
  if (videoUrl.includes('<iframe')) {
    const srcMatch = videoUrl.match(/src=["']([^"']+)["']/)
    if (srcMatch && srcMatch[1]) {
      src = srcMatch[1]
    } else {
      return null
    }
  }

  // Convert YouTube URLs to use no-cookie domain to avoid X-Frame-Options issue
  if (src.includes('youtube.com') && !src.includes('youtube-nocookie.com')) {
    src = src.replace('youtube.com', 'youtube-nocookie.com')
  }

  return src
}

const form = useForm({
  time_slot_id: null,
  booking_date: '',
  customer_name: '',
  customer_email: '',
  customer_phone: '',
  number_of_people: 1,
  payment_method: 'online',
  notes: '',
})

const loading = ref(false)
const availableSlots = ref([])
const selectedSlot = ref(null)

const minDate = computed(() => {
  const today = new Date()
  return today.toISOString().split('T')[0]
})

const totalPrice = computed(() => {
  if (!selectedSlot.value) return 0
  return selectedSlot.value.price * form.number_of_people
})

const canSubmit = computed(() => {
  return selectedSlot.value &&
         form.customer_name &&
         form.customer_email &&
         form.customer_phone &&
         form.number_of_people > 0
})

function formatTime(time) {
  if (!time) return ''
  const [hours, minutes] = time.split(':')
  const hour = parseInt(hours)
  const ampm = hour >= 12 ? 'PM' : 'AM'
  const hour12 = hour % 12 || 12
  return `${hour12}:${minutes} ${ampm}`
}

async function loadAvailableSlots() {
  if (!form.booking_date) return

  try {
    const response = await fetch(`/booking/available-slots?date=${form.booking_date}`)
    availableSlots.value = await response.json()
    selectedSlot.value = null
    form.time_slot_id = null
  } catch (error) {
    console.error('Error loading slots:', error)
  }
}

function selectSlot(slot) {
  if (slot.available < 1) return
  selectedSlot.value = slot
  form.time_slot_id = slot.id
}

async function submitBooking() {
  if (!canSubmit.value) return

  loading.value = true
  try {
    form.post('/booking', {
      onSuccess: () => {
        loading.value = false
      },
      onError: () => {
        loading.value = false
        alert('Booking failed. Please try again.')
      }
    })
  } catch (error) {
    loading.value = false
    console.error('Error submitting booking:', error)
  }
}

// Contact form
const contactForm = ref({
  name: '',
  email: '',
  phone: '',
  message: ''
})
const contactSending = ref(false)
const contactSuccess = ref('')

async function submitContact() {
  contactSending.value = true
  contactSuccess.value = ''

  try {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content
    if (!csrfToken) {
      alert('CSRF token not found. Please refresh the page.')
      return
    }

    const response = await fetch('/contact', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': csrfToken
      },
      body: JSON.stringify(contactForm.value)
    })

    const data = await response.json()

    if (response.ok) {
      contactSuccess.value = data.success || 'Thank you! Your message has been sent.'
      contactForm.value = { name: '', email: '', phone: '', message: '' }
    } else {
      alert('Failed to send message. Please try again.')
    }
  } catch (error) {
    console.error('Error submitting contact form:', error)
    alert('Failed to send message. Please try again.')
  } finally {
    contactSending.value = false
  }
}
</script>
