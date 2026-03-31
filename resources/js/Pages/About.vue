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
          About Us
        </h1>
        <p class="text-lg md:text-xl text-blue-100">
          Learn more about our adventure company
        </p>
      </div>
    </section>

    <!-- About Section -->
    <section class="py-12 md:py-20 bg-white">
      <div class="max-w-6xl mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 md:gap-12 items-center">
          <div>
            <h2 class="text-2xl md:text-4xl font-bold text-gray-900 mb-4 md:mb-6">
              Your Adventure Awaits
            </h2>
            <p class="text-base md:text-lg text-gray-600 mb-4 md:mb-6">
              Welcome to River Rafting Kullu Manali! We are a premier adventure tourism company dedicated to providing thrilling and safe river rafting experiences in the beautiful valleys of Kullu and Manali.
            </p>
            <p class="text-base md:text-lg text-gray-600 mb-4 md:mb-6">
              With over a decade of experience, our team of certified guides ensures that every trip is both exciting and secure. We pride ourselves on our commitment to safety, professionalism, and customer satisfaction.
            </p>
            <p class="text-base md:text-lg text-gray-600">
              Whether you're a first-time rafter or an experienced adventurer, we have packages suitable for all skill levels. Join us for an unforgettable experience on the rapids!
            </p>
          </div>
          <div class="rounded-xl overflow-hidden shadow-lg">
            <img
              src="https://images.unsplash.com/photo-1530866495561-507c9faab2ed?w=800"
              alt="River Rafting Adventure"
              class="w-full h-auto"
            />
          </div>
        </div>
      </div>
    </section>

    <!-- Features Section -->
    <section class="py-12 md:py-20 bg-blue-50">
      <div class="max-w-6xl mx-auto px-4">
        <div class="text-center mb-8 md:mb-12">
          <h2 class="text-2xl md:text-4xl font-bold text-gray-900 mb-3 md:mb-4">Why Choose Us?</h2>
          <p class="text-base md:text-xl text-gray-600">We're committed to providing the best rafting experience</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
          <div class="bg-white rounded-xl p-6 shadow-md">
            <div class="w-12 h-12 md:w-16 md:h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
              <svg class="w-6 h-6 md:w-8 md:h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
              </svg>
            </div>
            <h3 class="text-lg md:text-xl font-bold text-center mb-2">Safety First</h3>
            <p class="text-gray-600 text-center text-sm md:text-base">Certified guides and top-quality safety equipment for every trip</p>
          </div>
          <div class="bg-white rounded-xl p-6 shadow-md">
            <div class="w-12 h-12 md:w-16 md:h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
              <svg class="w-6 h-6 md:w-8 md:h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
              </svg>
            </div>
            <h3 class="text-lg md:text-xl font-bold text-center mb-2">Expert Guides</h3>
            <p class="text-gray-600 text-center text-sm md:text-base">Experienced and friendly guides who know every rapid</p>
          </div>
          <div class="bg-white rounded-xl p-6 shadow-md">
            <div class="w-12 h-12 md:w-16 md:h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
              <svg class="w-6 h-6 md:w-8 md:h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
              </svg>
            </div>
            <h3 class="text-lg md:text-xl font-bold text-center mb-2">Customer Focused</h3>
            <p class="text-gray-600 text-center text-sm md:text-base">Your satisfaction and enjoyment are our top priorities</p>
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
  googleMapEmbed: String,
})
</script>
