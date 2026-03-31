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
          Photo Gallery
        </h1>
        <p class="text-lg md:text-xl text-blue-100">
          Relive the adventure through our photos
        </p>
      </div>
    </section>

    <!-- Gallery Section -->
    <section class="py-12 md:py-20 bg-white">
      <div class="max-w-6xl mx-auto px-4">
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 md:gap-4">
          <div
            v-for="(image, index) in galleryImages"
            :key="index"
            class="aspect-square rounded-lg cursor-pointer group"
          >
            <img
              :src="image.image_url"
              :alt="image.title"
              class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110 rounded-lg"
            />
            <p class="py-2 md:py-3 text-sm md:text-base font-semibold text-center text-gray-800">{{ image.title }}</p>
          </div>
        </div>
        <div v-if="galleryImages.length === 0" class="text-center py-12">
          <p class="text-gray-500 text-lg">No gallery images available yet.</p>
        </div>
      </div>
    </section>

    <!-- Videos Section -->
    <section v-if="videos.length > 0" class="py-12 md:py-20 bg-gray-900">
      <div class="max-w-6xl mx-auto px-4">
        <div class="text-center mb-8 md:mb-12">
          <h2 class="text-2xl md:text-4xl font-bold text-white mb-3 md:mb-4">Video Highlights</h2>
          <p class="text-base md:text-xl text-gray-300">Watch the thrill in action</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 md:gap-8">
          <div
            v-for="(video, index) in videos"
            :key="index"
            class="aspect-video rounded-lg bg-gray-800"
          >
            <iframe
              v-if="getVideoSrc(video.video_url)"
              :src="getVideoSrc(video.video_url)"
              class="w-full h-full"
              frameborder="0"
              allowfullscreen
            ></iframe>
            <p class="py-2 md:py-5 text-sm text-center text-white">{{ video.title }}</p>
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
  galleryImages: Array,
  videos: Array,
  googleMapEmbed: String,
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
</script>
