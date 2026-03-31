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
          Our Packages
        </h1>
        <p class="text-lg md:text-xl text-blue-100">
          Choose the perfect rafting adventure for you
        </p>
      </div>
    </section>

    <!-- Packages Section -->
    <section class="py-12 md:py-20 bg-white">
      <div class="max-w-6xl mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
          <div
            v-for="pkg in packages"
            :key="pkg.id"
            class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300"
          >
            <div class="p-6">
              <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
              </div>
              <h3 class="text-xl font-bold text-center mb-2">{{ pkg.name }}</h3>
              <p class="text-gray-600 text-center mb-4">{{ pkg.km }} km adventure</p>
              <div class="text-center">
                <span class="text-3xl font-bold text-blue-600">₹{{ pkg.price }}</span>
                <span class="text-gray-600"> / person</span>
              </div>
              <ul class="mt-6 space-y-2">
                <li class="flex items-center text-gray-600">
                  <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                  </svg>
                  Professional guide
                </li>
                <li class="flex items-center text-gray-600">
                  <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                  </svg>
                  Safety equipment
                </li>
                <li class="flex items-center text-gray-600">
                  <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                  </svg>
                  Photos included
                </li>
              </ul>
              <a
                href="/#booking"
                class="mt-6 block w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 px-6 rounded-lg text-center transition-colors"
              >
                Book Now
              </a>
            </div>
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
import PublicNav from '@/Components/PublicNav.vue'
import Footer from '@/Components/Footer.vue'
import FloatingContact from '@/Components/FloatingContact.vue'

const props = defineProps({
  packages: Array,
  googleMapEmbed: String,
})
</script>
