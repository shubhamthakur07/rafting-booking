<template>
  <div class="p-6">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold">Contact Messages</h1>
      <span class="text-gray-600">{{ contacts.length }} message(s)</span>
    </div>

    <div v-if="contacts.length === 0" class="text-center py-12 text-gray-500">
      No contact messages yet.
    </div>

    <div v-else class="space-y-4">
      <div
        v-for="contact in contacts"
        :key="contact.id"
        :class="[
          'bg-white rounded-lg shadow p-6',
          !contact.is_read ? 'border-l-4 border-blue-500' : ''
        ]"
      >
        <div class="flex justify-between items-start">
          <div class="flex-1">
            <div class="flex items-center gap-4 mb-2">
              <h3 class="font-semibold text-lg">{{ contact.name }}</h3>
              <span v-if="!contact.is_read" class="px-2 py-1 bg-blue-100 text-blue-600 text-xs rounded-full">New</span>
            </div>
            <p class="text-gray-600 text-sm mb-2">{{ contact.email }}</p>
            <p v-if="contact.phone" class="text-gray-600 text-sm mb-2">Phone: {{ contact.phone }}</p>
            <p class="text-gray-800 mt-3">{{ contact.message }}</p>
            <p class="text-gray-400 text-xs mt-3">
              Received: {{ new Date(contact.created_at).toLocaleString() }}
            </p>
          </div>
          <div class="flex gap-2 ml-4">
            <button
              v-if="!contact.is_read"
              @click="markAsRead(contact.id)"
              class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 text-sm"
            >
              Mark as Read
            </button>
            <button
              @click="deleteContact(contact.id)"
              class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 text-sm"
            >
              Delete
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3'

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
