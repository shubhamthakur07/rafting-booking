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

        <!-- Desktop Navigation -->
        <div class="hidden md:flex items-center space-x-4">
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
          >Settings</a>
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

        <!-- Mobile menu button -->
        <div class="md:hidden flex items-center">
          <button
            @click="mobileMenuOpen = !mobileMenuOpen"
            class="inline-flex items-center justify-center p-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500"
          >
            <span class="sr-only">Open main menu</span>
            <!-- Hamburger icon -->
            <svg
              v-if="!mobileMenuOpen"
              class="h-6 w-6"
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
            <!-- Close icon -->
            <svg
              v-else
              class="h-6 w-6"
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>
    </div>

    <!-- Mobile Navigation -->
    <div v-if="mobileMenuOpen" class="md:hidden">
      <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 bg-white border-t border-gray-200">
        <a
          href="/admin"
          :class="[
            currentPage === 'dashboard' ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900',
            'block px-3 py-2 rounded-md text-base font-medium'
          ]"
        >Dashboard</a>
        <a
          href="/admin/bookings"
          :class="[
            currentPage === 'bookings' ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900',
            'block px-3 py-2 rounded-md text-base font-medium'
          ]"
        >Bookings</a>
        <a
          href="/admin/time-slots"
          :class="[
            currentPage === 'time-slots' ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900',
            'block px-3 py-2 rounded-md text-base font-medium'
          ]"
        >Time Slots</a>
        <a
          href="/admin/testimonials"
          :class="[
            currentPage === 'testimonials' ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900',
            'block px-3 py-2 rounded-md text-base font-medium'
          ]"
        >Testimonials</a>
        <a
          href="/admin/gallery"
          :class="[
            currentPage === 'gallery' ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900',
            'block px-3 py-2 rounded-md text-base font-medium'
          ]"
        >Gallery</a>
        <a
          href="/admin/contacts"
          :class="[
            currentPage === 'contacts' ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900',
            'block px-3 py-2 rounded-md text-base font-medium'
          ]"
        >Contacts</a>
        <a
          href="/admin/packages"
          :class="[
            currentPage === 'packages' ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900',
            'block px-3 py-2 rounded-md text-base font-medium'
          ]"
        >Packages</a>
        <a
          href="/admin/settings"
          :class="[
            currentPage === 'settings' ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900',
            'block px-3 py-2 rounded-md text-base font-medium'
          ]"
        >Settings</a>
        <form method="POST" action="/logout" class="block">
          <Link
            href="/logout"
            method="post"
            as="button"
            class="w-full text-left px-3 py-2 rounded-md text-base font-medium text-red-600 hover:bg-red-50 hover:text-red-800"
          >
            Logout
          </Link>
        </form>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'

const mobileMenuOpen = ref(false)

defineProps({
  currentPage: {
    type: String,
    default: 'dashboard'
  }
})
</script>
