<template>
  <div>
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold">{{ project.project_name }}</h1>
      <div class="space-x-2">
        <Link :href="route('projects.edit', project.id)" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Edit</Link>
        <Link :href="route('projects.index')" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Back</Link>
      </div>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6">
      <div class="grid grid-cols-2 gap-4">
        <div>
          <strong>Project Code:</strong> {{ project.project_code }}
        </div>
        <div>
          <strong>Status:</strong>
          <span :class="`px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-${project.status_color}-100 text-${project.status_color}-800`">
            {{ project.project_status }}
          </span>
        </div>
        <div>
          <strong>Title:</strong> {{ project.project_title }}
        </div>
        <div>
          <strong>Start Date:</strong> {{ project.start_date }}
        </div>
        <div class="col-span-2">
          <strong>Description:</strong>
          <p class="mt-1">{{ project.project_description }}</p>
        </div>
        <div class="col-span-2">
          <strong>Latest Update:</strong>
          <p class="mt-1">{{ project.project_update }}</p>
        </div>
        <div class="col-span-2">
          <strong>End Date:</strong> {{ project.end_date }}
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
  project: Object
})
</script>
