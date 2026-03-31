<template>
  <footer class="bg-gray-900 text-white py-8 md:py-12">
    <div class="max-w-6xl mx-auto px-4">
      <!-- Google Map Section -->
      <div v-if="googleMapEmbed" class="mb-8">
        <h3 class="text-xl font-bold text-center mb-4">Find Us</h3>
        <div class="rounded-lg overflow-hidden shadow-lg max-w-md mx-auto" v-html="googleMapEmbed"></div>
      </div>

      <div class="text-center">
        <p class="text-base md:text-lg">© {{ new Date().getFullYear() }} River Rafting Adventure. All rights reserved.</p>
        <p class="text-gray-400 mt-2 text-sm md:text-base">Admin? <a href="/login" class="text-blue-400 hover:underline">Login here</a></p>
      </div>
    </div>
  </footer>
</template>

<script setup>
defineProps({
  googleMapEmbed: {
    type: String,
    default: '',
  },
})
</script>
