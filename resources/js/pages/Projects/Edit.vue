<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import { projects, index, update } from '@/routes/projects' // 👈 import route helpers
import { BreadcrumbItem } from '@/types'

const props = defineProps({
  project: Object,
  statusOptions: Object
})

// Use Inertia form helper
const form = useForm({
  project_name: props.project.project_name,
  project_title: props.project.project_title,
  project_description: props.project.project_description,
  project_status: props.project.project_status,
  project_update: props.project.project_update,
  start_date: props.project.start_date,
  end_date: props.project.end_date
})

// 🔧 Use the `update()` route helper instead of manual route()
const submit = () => {
  form.put(update(props.project.id).url, {
    preserveScroll: true,
    onSuccess: () => {
      // success toast (optional)
      console.log('✅ Project updated successfully!')
    },
    onError: (errors) => {
      console.error(errors)
    }
  })
}

// Breadcrumbs
const breadcrumbItems: BreadcrumbItem[] = [
  {
    title: 'Projects',
    href: index().url,
  },
  {
    title: 'Edit Project',
  },
]
</script>

<template>
  <AppLayout :breadcrumbs="breadcrumbItems">
    <Head title="Edit Project" />

    <!-- Back Button -->
    <Link
      :href="index().url"
      class="inline-block mb-6 bg-gray-200 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-300 transition"
    >
      ← Back to Projects
    </Link>

    <div class="max-w-3xl mx-auto my-10">
      <div class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 p-8 rounded-xl shadow-lg text-white">
        <h1 class="text-3xl font-bold mb-6 text-center">Edit Project</h1>

        <form @submit.prevent="submit" class="space-y-5">
          <!-- Project Code (Read Only) -->
          <div>
            <label class="block mb-1 font-semibold">Project Code</label>
            <input
              :value="props.project.project_code"
              type="text"
              disabled
              class="w-full px-4 py-2 rounded-lg border-2 border-white bg-gray-200 text-gray-700 cursor-not-allowed"
            />
          </div>

          <!-- Project Name -->
          <div>
            <label class="block mb-1 font-semibold">Project Name *</label>
            <input
              v-model="form.project_name"
              type="text"
              class="w-full px-4 py-2 rounded-lg border-2 border-white bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-300"
              placeholder="Enter project name"
              required
            />
            <div v-if="form.errors.project_name" class="text-red-200 text-sm mt-1">{{ form.errors.project_name }}</div>
          </div>

          <!-- Project Title -->
          <div>
            <label class="block mb-1 font-semibold">Project Title</label>
            <input
              v-model="form.project_title"
              type="text"
              class="w-full px-4 py-2 rounded-lg border-2 border-white bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-purple-300"
              placeholder="Optional title"
            />
            <div v-if="form.errors.project_title" class="text-red-200 text-sm mt-1">{{ form.errors.project_title }}</div>
          </div>

          <!-- Description -->
          <div>
            <label class="block mb-1 font-semibold">Description</label>
            <textarea
              v-model="form.project_description"
              rows="3"
              class="w-full px-4 py-2 rounded-lg border-2 border-white bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-pink-300"
              placeholder="Describe your project"
            ></textarea>
            <div v-if="form.errors.project_description" class="text-red-200 text-sm mt-1">{{ form.errors.project_description }}</div>
          </div>

          <!-- Project Update -->
          <div>
            <label class="block mb-1 font-semibold">Project Update</label>
            <textarea
              v-model="form.project_update"
              rows="2"
              class="w-full px-4 py-2 rounded-lg border-2 border-white bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-purple-300"
              placeholder="Enter recent update or progress"
            ></textarea>
            <div v-if="form.errors.project_update" class="text-red-200 text-sm mt-1">{{ form.errors.project_update }}</div>
          </div>

          <!-- Status -->
          <div>
            <label class="block mb-1 font-semibold">Status *</label>
            <select
              v-model="form.project_status"
              class="w-full px-4 py-2 rounded-lg border-2 border-white bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-300"
            >
              <option v-for="(label, key) in props.statusOptions" :key="key" :value="key">{{ label }}</option>
            </select>
            <div v-if="form.errors.project_status" class="text-red-200 text-sm mt-1">{{ form.errors.project_status }}</div>
          </div>

          <!-- Dates -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block mb-1 font-semibold">Start Date</label>
              <input
                v-model="form.start_date"
                type="date"
                class="w-full px-4 py-2 rounded-lg border-2 border-white bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-purple-300"
              />
              <div v-if="form.errors.start_date" class="text-red-200 text-sm mt-1">{{ form.errors.start_date }}</div>
            </div>

            <div>
              <label class="block mb-1 font-semibold">End Date</label>
              <input
                v-model="form.end_date"
                type="text"
                class="w-full px-4 py-2 rounded-lg border-2 border-white bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-pink-300"
                placeholder="Date or text"
              />
              <div v-if="form.errors.end_date" class="text-red-200 text-sm mt-1">{{ form.errors.end_date }}</div>
            </div>
          </div>

          <!-- Buttons -->
          <div class="flex justify-center space-x-3 pt-4">
            <Link
              :href="index().url"
              class="bg-white text-gray-800 font-semibold px-6 py-3 rounded-lg hover:bg-gray-100 transition"
            >
              Cancel
            </Link>

            <button
              type="submit"
              :disabled="form.processing"
              class="px-6 py-3 bg-white text-indigo-600 font-bold rounded-lg shadow-md hover:bg-indigo-100 transition disabled:opacity-50"
            >
              {{ form.processing ? 'Updating...' : 'Update Project' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </AppLayout>
</template>
