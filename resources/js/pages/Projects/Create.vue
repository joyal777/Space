<script setup lang="ts">
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import projects, { index } from '@/routes/projects';
import { BreadcrumbItem } from '@/types';

const project_name = ref('');
const project_title = ref('');
const project_description = ref('');
const project_status = ref('pending');
const start_date = ref('');
const end_date = ref('');

const statusOptions = {
    pending: 'Pending',
    in_progress: 'In Progress',
    completed: 'Completed',
    on_hold: 'On Hold',
    cancelled: 'Cancelled',
};

const submitForm = () => {
    router.post('/projects', {
        project_name: project_name.value,
        project_title: project_title.value,
        project_description: project_description.value,
        project_status: project_status.value,
        start_date: start_date.value,
        end_date: end_date.value,
    });
};

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Projects',
        href: index().url, // or route('projects.index')
    },
];
</script>

<template>
  <AppLayout :breadcrumbs="breadcrumbItems">
    <Head title="Create Project" />
    <Link
                    :href="index().url"
                    class="bg-grey-600 text-black px-4 py-2 rounded-lg"
                >
                    Go back
                </Link>
    <div class="max-w-3xl mx-auto my-10">
      <!-- Card -->
      <div class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 p-8 rounded-xl shadow-lg text-white">
        <h1 class="text-3xl font-bold mb-6 text-center">Create New Project</h1>

        <div class="space-y-5">
          <!-- Project Name -->
          <div>
            <label class="block mb-1 font-semibold">Project Name *</label>
            <input v-model="project_name" type="text"
                   class="w-full px-4 py-2 rounded-lg border-2 border-white bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-300"
                   placeholder="Enter project name" />
          </div>

          <!-- Project Title -->
          <div>
            <label class="block mb-1 font-semibold">Project Title</label>
            <input v-model="project_title" type="text"
                   class="w-full px-4 py-2 rounded-lg border-2 border-white bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-purple-300"
                   placeholder="Optional title" />
          </div>

          <!-- Project Description -->
          <div>
            <label class="block mb-1 font-semibold">Description</label>
            <textarea v-model="project_description" rows="3"
                      class="w-full px-4 py-2 rounded-lg border-2 border-white bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-pink-300"
                      placeholder="Describe your project"></textarea>
          </div>

          <!-- Status -->
          <div>
            <label class="block mb-1 font-semibold">Status</label>
            <select v-model="project_status"
                    class="w-full px-4 py-2 rounded-lg border-2 border-white bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-300">
              <option v-for="(label, key) in statusOptions" :key="key" :value="key">{{ label }}</option>
            </select>
          </div>

          <!-- Start & End Dates -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block mb-1 font-semibold">Start Date</label>
              <input v-model="start_date" type="date"
                     class="w-full px-4 py-2 rounded-lg border-2 border-white bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-purple-300" />
            </div>
            <div>
              <label class="block mb-1 font-semibold">End Date</label>
              <input v-model="end_date" type="date"
                     class="w-full px-4 py-2 rounded-lg border-2 border-white bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-pink-300" />
            </div>
          </div>

          <!-- Submit Button -->
          <div class="text-center">
            <button @click="submitForm"
                    class="px-6 py-3 bg-white text-indigo-600 font-bold rounded-lg shadow-md hover:bg-indigo-100 transition">
              Create Project
            </button>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
