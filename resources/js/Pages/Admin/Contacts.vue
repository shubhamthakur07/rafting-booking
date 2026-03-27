<template>
  <div class="min-h-screen bg-gray-100">
    <AdminNav />

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900">
            <div class="flex justify-between items-center mb-6">
              <h2 class="text-2xl font-semibold">Contact Messages</h2>
              <div class="text-sm text-gray-500">
                {{ contacts.length }} {{ contacts.length === 1 ? 'message' : 'messages' }}
              </div>
            </div>

            <!-- Success Message -->
            <div v-if="$page.props.flash?.success" class="mb-4 p-4 bg-green-100 text-green-700 rounded">
              {{ $page.props.flash.success }}
            </div>

            <!-- Empty State -->
            <div v-if="contacts.length === 0" class="text-center py-12">
              <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
              </svg>
              <h3 class="mt-2 text-sm font-medium text-gray-900">No contact messages</h3>
              <p class="mt-1 text-sm text-gray-500">You'll see messages here when customers contact you.</p>
            </div>

            <!-- Messages List -->
            <div v-else class="space-y-4">
              <div
                v-for="contact in contacts"
                :key="contact.id"
                :class="[
                  'bg-white border rounded-lg shadow-sm p-6 transition-all duration-200 hover:shadow-md',
                  !contact.is_read ? 'border-l-4 border-blue-500 bg-blue-50' : 'border-gray-200'
                ]"
              >
                <div class="flex justify-between items-start">
                  <div class="flex-1">
                    <div class="flex items-center gap-4 mb-3">
                      <div class="flex items-center">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                          <span class="text-blue-600 font-bold text-sm">{{ contact.name.charAt(0).toUpperCase() }}</span>
                        </div>
                        <div>
                          <h3 class="font-semibold text-lg text-gray-900">{{ contact.name }}</h3>
                          <p class="text-gray-500 text-sm">{{ contact.email }}</p>
                        </div>
                      </div>
                      <span v-if="!contact.is_read" class="px-3 py-1 bg-blue-500 text-white text-xs rounded-full font-medium">
                        New
                      </span>
                    </div>

                    <div v-if="contact.phone" class="flex items-center text-gray-600 text-sm mb-3">
                      <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                      </svg>
                      {{ contact.phone }}
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4 mt-3">
                      <p class="text-gray-800 whitespace-pre-wrap">{{ contact.message }}</p>
                    </div>

                    <div class="flex items-center text-gray-400 text-xs mt-4">
                      <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                      </svg>
                      Received: {{ new Date(contact.created_at).toLocaleString() }}
                    </div>
                  </div>

                  <div class="flex flex-col gap-2 ml-6">
                    <button
                      v-if="!contact.is_read"
                      @click="markAsRead(contact.id)"
                      class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150"
                    >
                      <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                      </svg>
                      Mark as Read
                    </button>
                    <button
                      @click="deleteContact(contact.id)"
                      class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150"
                    >
                      <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                      </svg>
                      Delete
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3'
import AdminNav from '@/Components/AdminNav.vue';

const props = defineProps({
  contacts: Array
})

function markAsRead(contactId) {
  router.post('/admin/contacts/mark-read', { contact_id: contactId })
}

function deleteContact(contactId) {
  if (confirm('Are you sure you want to delete this message?')) {
    router.post('/admin/contacts/delete', { contact_id: contactId })
  }
}
</script>
