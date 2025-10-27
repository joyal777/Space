<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import { projects, index, update } from '@/routes/projects'
import { BreadcrumbItem } from '@/types'
import { ref, reactive } from 'vue'

const props = defineProps({
  project: Object,
  statusOptions: Object
})

// Refs for file input and preview
const fileInput = ref<HTMLInputElement>()
const imagePreview = ref<string>('')

// Use Inertia form helper with image
const form = useForm({
  project_name: props.project.project_name,
  project_title: props.project.project_title,
  project_description: props.project.project_description,
  project_status: props.project.project_status,
  project_update: props.project.project_update,
  start_date: props.project.start_date,
  end_date: props.project.end_date,
  project_image: null as File | null,
  _method: 'put'
})

// Handle file selection
const handleFileChange = (event: Event) => {
  const target = event.target as HTMLInputElement
  if (target.files && target.files[0]) {
    const file = target.files[0]
    form.project_image = file

    // Create preview URL
    if (imagePreview.value) {
      URL.revokeObjectURL(imagePreview.value) // Clean up previous URL
    }
    imagePreview.value = URL.createObjectURL(file)
    console.log('File selected and preview created:', file.name)
  }
}

// Trigger file input click
const triggerFileInput = () => {
  fileInput.value?.click()
}

// Remove selected image
const removeImage = () => {
  form.project_image = null
  if (imagePreview.value) {
    URL.revokeObjectURL(imagePreview.value) // Clean up URL
    imagePreview.value = ''
  }
  if (fileInput.value) {
    fileInput.value.value = ''
  }
}

// Submit form
const submit = () => {
  console.log('Submitting form with image:', form.project_image ? form.project_image.name : 'No image')

  form.post(update(props.project.id).url, {
    preserveScroll: true,
    forceFormData: true,
    onSuccess: () => {
      // Clean up preview URL on success
      if (imagePreview.value) {
        URL.revokeObjectURL(imagePreview.value)
      }
    },
    onError: (errors) => {
      console.error('Update errors:', errors)
    }
  })
}

// Clean up URL when component unmounts
import { onUnmounted } from 'vue'
onUnmounted(() => {
  if (imagePreview.value) {
    URL.revokeObjectURL(imagePreview.value)
  }
})

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
      class="bg-grey-600 text-black px-4 py-2 rounded-lg"
    >
      Go to Projects
    </Link>

    <div class="max-w-3xl mx-auto my-10">
      <div class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 p-8 rounded-xl shadow-lg text-white">
        <h1 class="text-3xl font-bold mb-6 text-center">Edit Project</h1>

        <form @submit.prevent="submit" class="space-y-5" enctype="multipart/form-data">
          <!-- Project Image Upload -->
          <div>
            <label class="block mb-1 font-semibold">Project Banner Image</label>

            <!-- Current Image Preview -->
            <div v-if="project.project_image && !form.project_image" class="mb-4">
              <p class="text-sm mb-2">Current Image:</p>
              <img
                :src="`/frontend/images/projects/${project.project_image}`"
                :alt="project.project_name"
                class="w-full h-48 object-cover rounded-lg border-2 border-white"
              />
            </div>

            <!-- New Image Preview -->
            <div v-if="form.project_image && imagePreview" class="mb-4">
              <p class="text-sm mb-2">New Image Preview:</p>
              <img
                :src="imagePreview"
                :alt="'New image for ' + project.project_name"
                class="w-full h-48 object-cover rounded-lg border-2 border-white"
              />
              <button
                type="button"
                @click="removeImage"
                class="mt-2 bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600 transition"
              >
                Remove New Image
              </button>
            </div>

            <!-- File Input -->
            <input
              ref="fileInput"
              type="file"
              @change="handleFileChange"
              accept="image/*"
              class="hidden"
            />

            <button
              type="button"
              @click="triggerFileInput"
              class="w-full px-4 py-2 rounded-lg border-2 border-dashed border-white bg-transparent hover:bg-white hover:border-2 hover:border-dashed hover:border-black hover:text-black transition flex items-center justify-center"
            >
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
              </svg>
              {{ form.project_image ? 'Change Image' : 'Upload New Image' }}
            </button>

            <p class="text-sm text-white text-opacity-80 mt-1">
              {{ form.project_image ? form.project_image.name : 'Click to upload a new banner image' }}
            </p>
            <div v-if="form.errors.project_image" class="text-red-200 text-sm mt-1">{{ form.errors.project_image }}</div>
          </div>

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
