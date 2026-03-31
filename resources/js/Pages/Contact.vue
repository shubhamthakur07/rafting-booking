<template>
  <div class="min-h-screen bg-gray-50">
    <PublicNav />

    <!-- Hero Section -->
    <section class="relative h-[300px] md:h-[400px] flex items-center justify-center overflow-hidden pt-16">
      <div class="absolute inset-0 bg-gradient-to-b from-blue-900/70 to-blue-800/50 z-10"></div>
      <img
        src="https://images.unsplash.com/photo-1530866495561-507c9faab2ed?w=1920"
        alt="River Rafting"
        class="absolute inset-0 w-full h-full object-cover"
      />
      <div class="relative z-20 text-center text-white px-4 max-w-4xl mx-auto">
        <h1 class="text-3xl md:text-5xl font-bold mb-4 tracking-tight">
          Contact Us
        </h1>
        <p class="text-lg md:text-xl text-blue-100">
          Have questions? We'd love to hear from you!
        </p>
      </div>
    </section>

    <!-- Contact Section -->
    <section class="py-12 md:py-20 bg-white">
      <div class="max-w-4xl mx-auto px-4">
        <!-- Contact Information -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
          <div v-if="$page.props.siteSettings?.phoneNumber" class="bg-white rounded-xl shadow-lg p-6 text-center">
            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
              <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
              </svg>
            </div>
            <h3 class="text-lg font-semibold mb-2">Phone</h3>
            <p class="text-gray-600">{{ $page.props.siteSettings?.phoneNumber }}</p>
          </div>

          <div v-if="$page.props.siteSettings?.email" class="bg-white rounded-xl shadow-lg p-6 text-center">
            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
              <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
              </svg>
            </div>
            <h3 class="text-lg font-semibold mb-2">Email</h3>
            <p class="text-gray-600">{{ $page.props.siteSettings?.email }}</p>
          </div>

          <div v-if="$page.props.siteSettings?.address" class="bg-white rounded-xl shadow-lg p-6 text-center">
            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
              <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
            </div>
            <h3 class="text-lg font-semibold mb-2">Address</h3>
            <p class="text-gray-600">{{ $page.props.siteSettings?.address }}</p>
          </div>
        </div>

        <div class="bg-white rounded-xl md:rounded-2xl shadow-lg p-4 md:p-8">
          <form @submit.prevent="submitContact">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6 mb-4 md:mb-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                <input
                  v-model="contactForm.name"
                  type="text"
                  class="w-full px-3 md:px-4 py-2 md:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  placeholder="Your name"
                  required
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input
                  v-model="contactForm.email"
                  type="email"
                  class="w-full px-3 md:px-4 py-2 md:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  placeholder="your@email.com"
                  required
                />
              </div>
            </div>

            <div class="mb-4 md:mb-6">
              <label class="block text-sm font-medium text-gray-700 mb-2">Phone (Optional)</label>
              <input
                v-model="contactForm.phone"
                type="tel"
                class="w-full px-3 md:px-4 py-2 md:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="Your phone number"
              />
            </div>

            <div class="mb-4 md:mb-6">
              <label class="block text-sm font-medium text-gray-700 mb-2">Message</label>
              <textarea
                v-model="contactForm.message"
                rows="4"
                class="w-full px-3 md:px-4 py-2 md:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="Your message..."
                required
              ></textarea>
            </div>

            <button
              type="submit"
              :disabled="contactSending"
              class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 md:px-6 rounded-lg transition-colors disabled:opacity-50"
            >
              {{ contactSending ? 'Sending...' : 'Send Message' }}
            </button>
          </form>

          <div v-if="contactSuccess" class="mt-4 p-3 md:p-4 bg-green-100 text-green-700 rounded-lg text-sm md:text-base">
            {{ contactSuccess }}
          </div>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <Footer :googleMapEmbed="googleMapEmbed" />

    <!-- Floating Contact Buttons -->
    <FloatingContact
      :phone-number="$page.props.siteSettings?.phoneNumber"
      :whatsapp-number="$page.props.siteSettings?.whatsappNumber"
    />
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import PublicNav from '@/Components/PublicNav.vue'
import Footer from '@/Components/Footer.vue'
import FloatingContact from '@/Components/FloatingContact.vue'

const props = defineProps({
  googleMapEmbed: String,
})

const contactForm = useForm({
  name: '',
  email: '',
  phone: '',
  message: '',
})

const contactSending = ref(false)
const contactSuccess = ref('')

async function submitContact() {
  contactSending.value = true
  contactSuccess.value = ''

  try {
    await contactForm.post('/contact', {
      preserveScroll: true,
      onSuccess: () => {
        contactSuccess.value = 'Thank you for your message! We will get back to you soon.'
        contactForm.reset()
      },
    })
  } finally {
    contactSending.value = false
  }
}
</script>
