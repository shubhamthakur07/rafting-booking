<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
// import AdminLayout from '@/Layouts/AdminLayout.vue';
import AdminNav from '@/Components/AdminNav.vue';

const props = defineProps({
    packages: Array,
});

const showForm = ref(false);
const editingPackage = ref(null);

const form = useForm({
    name: '',
    km: '',
    price: '',
    description: '',
    is_active: true,
    sort_order: 0,
});

function openCreateForm() {
    editingPackage.value = null;
    form.reset();
    form.clearErrors();
    showForm.value = true;
}

function openEditForm(pkg) {
    editingPackage.value = pkg;
    form.name = pkg.name;
    form.km = pkg.km;
    form.price = pkg.price;
    form.description = pkg.description || '';
    form.is_active = pkg.is_active;
    form.sort_order = pkg.sort_order;
    form.clearErrors();
    showForm.value = true;
}

function closeForm() {
    showForm.value = false;
    editingPackage.value = null;
    form.reset();
    form.clearErrors();
}

function submitForm() {
    if (editingPackage.value) {
        form.put(route('admin.packages.update', editingPackage.value.id), {
            onSuccess: () => closeForm(),
        });
    } else {
        form.post(route('admin.packages.store'), {
            onSuccess: () => closeForm(),
        });
    }
}

function deletePackage(pkg) {
    if (confirm('Are you sure you want to delete this package?')) {
        useForm({}).delete(route('admin.packages.destroy', pkg.id));
    }
}
</script>

<template>
     <div class="min-h-screen bg-gray-100">


            <AdminNav />


        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-semibold">Manage Packages</h2>
                            <button
                                @click="openCreateForm"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150"
                            >
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Add Package
                            </button>
                        </div>

                        <!-- Success Message -->
                        <div v-if="$page.props.flash?.success" class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                            {{ $page.props.flash.success }}
                        </div>

                        <!-- Packages Table -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Distance</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sort Order</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="pkg in packages" :key="pkg.id">
                                        <td class="px-6 py-4 whitespace-nowrap">{{ pkg.name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ pkg.km }} km</td>
                                        <td class="px-6 py-4 whitespace-nowrap">₹{{ pkg.price }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span :class="pkg.is_active ? 'text-green-600' : 'text-red-600'">
                                                {{ pkg.is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ pkg.sort_order }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <button
                                                @click="openEditForm(pkg)"
                                                class="inline-flex items-center px-3 py-1.5 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150 mr-2"
                                            >
                                                Edit
                                            </button>
                                            <button
                                                @click="deletePackage(pkg)"
                                                class="inline-flex items-center px-3 py-1.5 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                            >
                                                Delete
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Create/Edit Form Modal -->
                        <div v-if="showForm" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
                            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                                <div class="mt-3">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                                        {{ editingPackage ? 'Edit Package' : 'Add Package' }}
                                    </h3>
                                    <form @submit.prevent="submitForm">
                                        <div class="mb-4">
                                            <label class="block text-gray-700 text-sm font-bold mb-2">Name</label>
                                            <input
                                                v-model="form.name"
                                                type="text"
                                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                                required
                                            />
                                            <p v-if="form.errors.name" class="text-red-500 text-xs mt-1">{{ form.errors.name }}</p>
                                        </div>
                                        <div class="mb-4">
                                            <label class="block text-gray-700 text-sm font-bold mb-2">Distance (km)</label>
                                            <input
                                                v-model="form.km"
                                                type="number"
                                                min="1"
                                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                                required
                                            />
                                            <p v-if="form.errors.km" class="text-red-500 text-xs mt-1">{{ form.errors.km }}</p>
                                        </div>
                                        <div class="mb-4">
                                            <label class="block text-gray-700 text-sm font-bold mb-2">Price (₹)</label>
                                            <input
                                                v-model="form.price"
                                                type="number"
                                                min="0"
                                                step="0.01"
                                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                                required
                                            />
                                            <p v-if="form.errors.price" class="text-red-500 text-xs mt-1">{{ form.errors.price }}</p>
                                        </div>
                                        <div class="mb-4">
                                            <label class="block text-gray-700 text-sm font-bold mb-2">Description</label>
                                            <textarea
                                                v-model="form.description"
                                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                                rows="3"
                                            ></textarea>
                                            <p v-if="form.errors.description" class="text-red-500 text-xs mt-1">{{ form.errors.description }}</p>
                                        </div>
                                        <div class="mb-4">
                                            <label class="block text-gray-700 text-sm font-bold mb-2">Sort Order</label>
                                            <input
                                                v-model="form.sort_order"
                                                type="number"
                                                min="0"
                                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                            />
                                            <p v-if="form.errors.sort_order" class="text-red-500 text-xs mt-1">{{ form.errors.sort_order }}</p>
                                        </div>
                                        <div class="mb-4">
                                            <label class="flex items-center">
                                                <input
                                                    v-model="form.is_active"
                                                    type="checkbox"
                                                    class="form-checkbox h-4 w-4 text-blue-600"
                                                />
                                                <span class="ml-2 text-gray-700 text-sm font-bold">Active</span>
                                            </label>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <button
                                                type="submit"
                                                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                            >
                                                {{ editingPackage ? 'Update' : 'Create' }}
                                            </button>
                                            <button
                                                type="button"
                                                @click="closeForm"
                                                class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                            >
                                                Cancel
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
</template>
