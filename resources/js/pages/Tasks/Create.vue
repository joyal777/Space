<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { type BreadcrumbItem } from '@/types';
import { index, store } from '@/routes/tasks'; // Import store from your routes file

interface Props {
    projects: any[];
    statusOptions: Record<string, string>;
    priorityOptions: Record<number, string>;
}

const props = defineProps<Props>();

// Breadcrumb items
const breadcrumbItems: BreadcrumbItem[] = [
    { title: 'Tasks', href: index().url },
    { title: 'Create Task', href: '#' },
];

const goBack = () => {
    if (window.history.length > 1) {
        window.history.back();
    } else {
        // Fallback if no history
        window.location.href = '/projects';
    }
};

// Form handling
const form = useForm({
    project_id: '',
    task_name: '',
    task_description: '',
    task_status: 'pending',
    priority: 3,
    start_date: '',
    end_date: '',
    estimated_hours: '',
    actual_hours: '',
    assigned_to: [],
    notes: '',
});

const submit = () => {
    // Use the store route from your routes file
    form.post(store().url);
};
</script>

<template>
  <AppLayout :breadcrumbs="breadcrumbItems">
    <Head title="Create Task" />

    <div class="p-6 max-w-4xl mx-auto">
      <!-- Header -->
      <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Create New Task</h1>
        <p class="text-gray-600 mt-1">Add a new task to your project</p>
      </div>

      <!-- Success Message -->
      <div
        v-if="$page.props.flash?.success"
        class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6"
      >
        {{ $page.props.flash.success }}
      </div>

      <!-- Error Messages -->
      <div
        v-if="Object.keys(form.errors).length > 0"
        class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6"
      >
        <ul>
          <li v-for="error in form.errors" :key="error" class="text-sm">
            {{ error }}
          </li>
        </ul>
      </div>

      <!-- Form -->
      <form @submit.prevent="submit" class="bg-white rounded-lg shadow-md p-6 space-y-6">
        <!-- Project Selection -->
        <div>
          <label for="project_id" class="block text-sm font-medium text-gray-700 mb-2">
            Project *
          </label>
          <select
            id="project_id"
            v-model="form.project_id"
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
            :class="{ 'border-red-500': form.errors.project_id }"
            required
          >
            <option value="">Select a Project</option>
            <option
              v-for="project in props.projects"
              :key="project.id"
              :value="project.id"
            >
              {{ project.project_name }} ({{ project.project_code }})
            </option>
          </select>
          <p v-if="form.errors.project_id" class="mt-1 text-sm text-red-600">
            {{ form.errors.project_id }}
          </p>
        </div>

        <!-- Task Name -->
        <div>
          <label for="task_name" class="block text-sm font-medium text-gray-700 mb-2">
            Task Name *
          </label>
          <input
            type="text"
            id="task_name"
            v-model="form.task_name"
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
            :class="{ 'border-red-500': form.errors.task_name }"
            placeholder="Enter task name"
            required
          />
          <p v-if="form.errors.task_name" class="mt-1 text-sm text-red-600">
            {{ form.errors.task_name }}
          </p>
        </div>

        <!-- Task Description -->
        <div>
          <label
            for="task_description"
            class="block text-sm font-medium text-gray-700 mb-2"
          >
            Task Description
          </label>
          <textarea
            id="task_description"
            v-model="form.task_description"
            rows="3"
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
            :class="{ 'border-red-500': form.errors.task_description }"
            placeholder="Enter task description"
          ></textarea>
          <p v-if="form.errors.task_description" class="mt-1 text-sm text-red-600">
            {{ form.errors.task_description }}
          </p>
        </div>

        <!-- Status and Priority -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label for="task_status" class="block text-sm font-medium text-gray-700 mb-2">
              Status *
            </label>
            <select
              id="task_status"
              v-model="form.task_status"
              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
              required
            >
              <option
                v-for="(label, value) in props.statusOptions"
                :key="value"
                :value="value"
              >
                {{ label }}
              </option>
            </select>
          </div>

          <div>
            <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">
              Priority *
            </label>
            <select
              id="priority"
              v-model="form.priority"
              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
              required
            >
              <option
                v-for="(label, value) in props.priorityOptions"
                :key="value"
                :value="value"
              >
                {{ label }}
              </option>
            </select>
          </div>
        </div>

        <!-- Dates -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">
              Start Date
            </label>
            <input
              type="date"
              id="start_date"
              v-model="form.start_date"
              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
            />
          </div>

          <div>
            <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">
              End Date
            </label>
            <input
              type="date"
              id="end_date"
              v-model="form.end_date"
              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
            />
          </div>
        </div>

        <!-- Hours -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label
              for="estimated_hours"
              class="block text-sm font-medium text-gray-700 mb-2"
            >
              Estimated Hours
            </label>
            <input
              type="number"
              id="estimated_hours"
              v-model="form.estimated_hours"
              min="0"
              step="0.5"
              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
              placeholder="0"
            />
          </div>

          <div>
            <label
              for="actual_hours"
              class="block text-sm font-medium text-gray-700 mb-2"
            >
              Actual Hours
            </label>
            <input
              type="number"
              id="actual_hours"
              v-model="form.actual_hours"
              min="0"
              step="0.5"
              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
              placeholder="0"
            />
          </div>
        </div>

        <!-- Notes -->
        <div>
          <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
            Notes
          </label>
          <textarea
            id="notes"
            v-model="form.notes"
            rows="3"
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
            placeholder="Additional notes..."
          ></textarea>
        </div>

        <!-- Form Actions -->
        <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
          <button
            @click="goBack"
            class="bg-white text-gray-800 font-semibold px-6 py-3 rounded-lg hover:bg-gray-100 transition"
          >
            Cancel
          </button>
          <button
            type="submit"
            :disabled="form.processing"
            class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50"
          >
            <span v-if="form.processing">Creating...</span>
            <span v-else>Create Task</span>
          </button>
        </div>
      </form>
    </div>
  </AppLayout>
</template>
