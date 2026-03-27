<template>
  <nav class="bg-white shadow-sm border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16">
        <div class="flex items-center">
          <a href="/" class="flex items-center">
            <img
              src="/logo.png"
              alt="Logo"
              class="h-10 w-auto mr-3"
              @error="$event.target.style.display='none'"
            />
          </a>
          <h1 class="text-xl font-bold text-gray-900">River Rafting Admin</h1>
        </div>
        <div class="flex items-center space-x-4">
          <a
            href="/admin"
            :class="[
              currentPage === 'dashboard' ? 'text-blue-600 font-medium' : 'text-gray-600 hover:text-gray-900'
            ]"
          >Dashboard</a>
          <a
            href="/admin/bookings"
            :class="[
              currentPage === 'bookings' ? 'text-blue-600 font-medium' : 'text-gray-600 hover:text-gray-900'
            ]"
          >Bookings</a>
          <a
            href="/admin/time-slots"
            :class="[
              currentPage === 'time-slots' ? 'text-blue-600 font-medium' : 'text-gray-600 hover:text-gray-900'
            ]"
          >Time Slots</a>
          <a
            href="/admin/testimonials"
            :class="[
              currentPage === 'testimonials' ? 'text-blue-600 font-medium' : 'text-gray-600 hover:text-gray-900'
            ]"
          >Testimonials</a>
          <a
            href="/admin/gallery"
            :class="[
              currentPage === 'gallery' ? 'text-blue-600 font-medium' : 'text-gray-600 hover:text-gray-900'
            ]"
          >Gallery</a>
          <a
            href="/admin/contacts"
            :class="[
              currentPage === 'contacts' ? 'text-blue-600 font-medium' : 'text-gray-600 hover:text-gray-900'
            ]"
          >Contacts</a>
          <a
            href="/admin/packages"
            :class="[
              currentPage === 'packages' ? 'text-blue-600 font-medium' : 'text-gray-600 hover:text-gray-900'
            ]"
          >Packages</a>
          <a
            href="/admin/settings"
            :class="[
              currentPage === 'settings' ? 'text-blue-600 font-medium' : 'text-gray-600 hover:text-gray-900'
            ]"
          >Map</a>
          <form method="POST" action="/logout">
                        <Link
                            href="/logout"
                            method="post"
                            as="button"
                            class="text-red-600 hover:text-red-800"
                        >
                            Logout
                        </Link>

          </form>
        </div>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
defineProps({
  currentPage: {
    type: String,
    default: 'dashboard'
  }
})
</script>
