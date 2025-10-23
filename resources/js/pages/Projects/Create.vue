<template>
  <div>
    <h1 class="text-2xl font-bold mb-6">Create New Project</h1>

    <form @submit.prevent="submit">
      <div class="grid grid-cols-1 gap-6">
        <div>
          <label class="block text-sm font-medium text-gray-700">Project Name *</label>
          <input v-model="form.project_name" type="text" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" required>
          <div v-if="form.errors.project_name" class="text-red-500 text-sm mt-1">{{ form.errors.project_name }}</div>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Project Title</label>
          <input v-model="form.project_title" type="text" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
          <div v-if="form.errors.project_title" class="text-red-500 text-sm mt-1">{{ form.errors.project_title }}</div>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Description</label>
          <textarea v-model="form.project_description" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"></textarea>
          <div v-if="form.errors.project_description" class="text-red-500 text-sm mt-1">{{ form.errors.project_description }}</div>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Status</label>
          <select v-model="form.project_status" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
            <option v-for="(label, value) in statusOptions" :key="value" :value="value">{{ label }}</option>
          </select>
          <div v-if="form.errors.project_status" class="text-red-500 text-sm mt-1">{{ form.errors.project_status }}</div>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Project Update</label>
          <textarea v-model="form.project_update" rows="2" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"></textarea>
          <div v-if="form.errors.project_update" class="text-red-500 text-sm mt-1">{{ form.errors.project_update }}</div>
        </div>

        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700">Start Date</label>
            <input v-model="form.start_date" type="date" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
            <div v-if="form.errors.start_date" class="text-red-500 text-sm mt-1">{{ form.errors.start_date }}</div>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">End Date</label>
            <input v-model="form.end_date" type="text" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" placeholder="Date or text">
            <div v-if="form.errors.end_date" class="text-red-500 text-sm mt-1">{{ form.errors.end_date }}</div>
          </div>
        </div>

        <div class="flex justify-end space-x-3">
          <Link :href="route('projects.index')" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Cancel</Link>
          <button type="submit" :disabled="form.processing" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 disabled:opacity-50">
            {{ form.processing ? 'Creating...' : 'Create Project' }}
          </button>
        </div>
      </div>
    </form>
  </div>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3'

const props = defineProps({
  statusOptions: Object
})

const form = useForm({
  project_name: '',
  project_title: '',
  project_description: '',
  project_status: 'pending',
  project_update: '',
  start_date: '',
  end_date: ''
})

const submit = () => {
  form.post(route('projects.store'))
}
</script>
