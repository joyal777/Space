<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { type BreadcrumbItem } from '@/types';
import { create, edit, show } from '@/routes/projects';

interface Props {
    projects: any[];
    statusOptions: Record<string, string>;
}

interface Project {
    id: number;
    project_name: string;
    project_title: string;
    project_image: string;
    project_status: string;
    start_date: string | null;
    end_date: string | null;
    users?: any[];
    owner_id?: number; // Assuming you have owner_id field
}

const props = defineProps<Props>();

// Updated Date formatting function
const formatDateWithHyphen = (dateString: string | null) => {
    if (!dateString) return '';

    const date = new Date(dateString);
    if (isNaN(date.getTime())) {
        const simpleDateMatch = dateString.match(/^(\d{4})-(\d{1,2})-(\d{1,2})$/);
        if (simpleDateMatch) {
            const [, year, month, day] = simpleDateMatch;
            return `${parseInt(day)}-${parseInt(month)}-${year}`;
        }
        return dateString;
    }

    const month = date.getMonth() + 1;
    const day = date.getDate();
    const year = date.getFullYear();
    return `${day}-${month}-${year}`;
};

// Function to get assignment status based on your logic
const getAssignmentStatus = (project: Project) => {
    if (!project.users || project.users.length === 0) {
        return { status: 'not_assigned', text: 'Not Assigned', class: 'bg-red-100 text-red-800' };
    }

    // If only one user (just owner or just one member)
    if (project.users.length === 1) {
        return { status: 'not_assigned', text: 'Not Assigned', class: 'bg-red-100 text-red-800' };
    }

    // If more than one user (owner + at least one member)
    return {
        status: 'assigned',
        text: 'Assigned',
        class: 'bg-green-100 text-green-800'
    };
};

// Alternative approach if you want to distinguish between owner and members
const getAssignmentStatusDetailed = (project: Project) => {
    if (!project.users || project.users.length === 0) {
        return { status: 'not_assigned', text: 'Not Assigned', class: 'bg-gray-100 text-gray-800' };
    }

    // Check if we have owner_id to distinguish owner from members
    if (project.owner_id) {
        const members = project.users.filter(user => user.id !== project.owner_id);
        if (members.length === 0) {
            return { status: 'not_assigned', text: 'Not Assigned', class: 'bg-gray-100 text-gray-800' };
        }
        return {
            status: 'assigned',
            text: `Assigned (${members.length})`,
            class: 'bg-green-100 text-green-800'
        };
    }

    // Fallback: if no owner_id, use simple count logic
    if (project.users.length === 1) {
        return { status: 'not_assigned', text: 'Not Assigned', class: 'bg-gray-100 text-gray-800' };
    }

    return {
        status: 'assigned',
        text: `Assigned (${project.users.length - 1})`,
        class: 'bg-green-100 text-green-800'
    };
};

const breadcrumbItems: BreadcrumbItem[] = [
    { title: 'Projects', href: create().url },
];

const deleteProject = (project: any) => {
    if (confirm('Are you sure you want to delete this project?')) {
        router.delete(route('projects.destroy', project.id));
    }
};
</script>

<template>
  <AppLayout :breadcrumbs="breadcrumbItems">
    <Head title="Projects" />

    <div class="p-6 space-y-6">
      <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold">Projects</h1>
        <Link
          :href="create().url"
          class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition"
        >
          Create Project
        </Link>
      </div>

      <div
        v-if="$page.props.flash?.success"
        class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded"
      >
        {{ $page.props.flash.success }}
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
        <div
          v-for="project in props.projects"
          :key="project.id"
          class="bg-white rounded-lg shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl hover:bg-blue-200 hover:scale-105 hover:-translate-y-1"
        >
          <!-- Wrap entire card content with Link -->
          <Link :href="show(project.id).url" class="block">
            <!-- Project Image -->
            <img
              :src="`/frontend/images/projects/${project.project_image}`"
              :alt="project.project_name"
              class="w-full h-40 object-cover transition-transform duration-300 hover:scale-105"
            />

            <div class="p-4">
              <h2
                class="font-bold text-lg text-gray-800 truncate group-hover:text-blue-700"
              >
                {{ project.project_name }}
              </h2>
              <p class="text-sm text-gray-600 truncate group-hover:text-blue-600">
                {{ project.project_title }}
              </p>

              <div class="flex flex-wrap gap-2 mt-2">
                <!-- Project Status Tag -->
                <span
                  class="inline-block px-2 py-1 text-xs font-semibold rounded-full transition-colors duration-300"
                  :class="{
                    'bg-gray-100 text-gray-800 group-hover:bg-blue-200 group-hover:text-blue-800':
                      project.project_status === 'pending',
                    'bg-blue-100 text-blue-800 group-hover:bg-blue-200 group-hover:text-blue-900':
                      project.project_status === 'in_progress',
                    'bg-green-100 text-green-800 group-hover:bg-blue-200 group-hover:text-blue-800':
                      project.project_status === 'completed',
                    'bg-yellow-100 text-yellow-800 group-hover:bg-blue-200 group-hover:text-blue-800':
                      project.project_status === 'on_hold',
                    'bg-red-100 text-red-800 group-hover:bg-blue-200 group-hover:text-blue-800':
                      project.project_status === 'cancelled',
                  }"
                >
                  {{ props.statusOptions[project.project_status] }}
                </span>

                <!-- Assignment Status Tag -->
                <span
                  class="inline-block px-2 py-1 text-xs font-semibold rounded-full transition-colors duration-300 group-hover:bg-blue-200"
                  :class="getAssignmentStatus(project).class"
                >
                  {{ getAssignmentStatus(project).text }}
                </span>
              </div>

              <p class="text-sm text-gray-500 mt-2 group-hover:text-blue-600">
                Start: {{ formatDateWithHyphen(project.start_date) }}
              </p>
              <p class="text-sm text-gray-500 group-hover:text-blue-600">
                End: {{ formatDateWithHyphen(project.end_date) }}
              </p>
            </div>
          </Link>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
