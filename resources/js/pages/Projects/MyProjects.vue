<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import { index } from '@/routes/projects';

interface Props {
    projects: any[];
    pendingInvitationsCount: number;
    statusOptions: Record<string, string>;
}

const props = defineProps<Props>();

// Calculate progress for each project
const projectsWithProgress = computed(() => {
    return props.projects.map(project => {
        const totalTasks = project.tasks_count || 0;
        const completedTasks = project.completed_tasks_count || 0;
        const progress = totalTasks > 0 ? Math.round((completedTasks / totalTasks) * 100) : 0;

        return {
            ...project,
            progress,
            team_members_count: project.users?.length || 0
        };
    });
});

// Format date function
const formatDate = (dateString: string | null) => {
    if (!dateString) return '';

    const date = new Date(dateString);
    if (isNaN(date.getTime())) return dateString;

    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
};

// Breadcrumb items
const breadcrumbItems = [
    { title: 'My Projects', href: '#' },
];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head title="My Projects" />

        <div class="p-6 space-y-6">
            <!-- Header Section -->
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">My Projects</h1>
                    <p class="text-gray-600 mt-2">
                        Projects you're currently working on
                        <span v-if="pendingInvitationsCount > 0" class="ml-2 bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-sm">
                            {{ pendingInvitationsCount }} pending invitation(s)
                        </span>
                    </p>
                </div>
                <Link
                    :href="index().url"
                    class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition flex items-center"
                >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                    All Projects
                </Link>
            </div>

            <!-- Projects Grid -->
            <div v-if="projectsWithProgress.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div
                    v-for="project in projectsWithProgress"
                    :key="project.id"
                    class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow"
                >
                    <!-- Project Image -->
                    <div class="h-48 overflow-hidden">
                        <img
                            :src="`/frontend/images/projects/${project.project_image}`"
                            :alt="project.project_name"
                            class="w-full h-full object-cover"
                        />
                    </div>

                    <!-- Project Content -->
                    <div class="p-6">
                        <!-- Project Header -->
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900 mb-1">
                                    {{ project.project_name }}
                                </h3>
                                <p class="text-gray-600 text-sm">{{ project.project_title }}</p>
                            </div>
                            <span
                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium"
                                :class="{
                                    'bg-gray-100 text-gray-800': project.project_status === 'pending',
                                    'bg-blue-100 text-blue-800': project.project_status === 'in_progress',
                                    'bg-green-100 text-green-800': project.project_status === 'completed',
                                    'bg-yellow-100 text-yellow-800': project.project_status === 'on_hold',
                                    'bg-red-100 text-red-800': project.project_status === 'cancelled',
                                }"
                            >
                                {{ statusOptions[project.project_status] }}
                            </span>
                        </div>

                        <!-- Project Description -->
                        <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                            {{ project.project_description }}
                        </p>

                        <!-- Progress Bar -->
                        <div class="mb-4">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm font-medium text-gray-700">Progress</span>
                                <span class="text-sm font-medium text-gray-700">{{ project.progress }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div
                                    class="bg-green-600 h-2 rounded-full transition-all duration-300"
                                    :style="{ width: `${project.progress}%` }"
                                ></div>
                            </div>
                        </div>

                        <!-- Project Stats -->
                        <div class="flex justify-between items-center text-sm text-gray-500 mb-4">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                {{ project.completed_tasks_count }}/{{ project.tasks_count }} tasks
                            </div>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                </svg>
                                {{ project.team_members_count }} members
                            </div>
                        </div>

                        <!-- Dates -->
                        <div class="flex justify-between text-xs text-gray-500 mb-4">
                            <div>
                                <span class="font-medium">Start:</span> {{ formatDate(project.start_date) }}
                            </div>
                            <div>
                                <span class="font-medium">End:</span> {{ formatDate(project.end_date) }}
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex space-x-2">
                            <Link
                                :href="route('projects.show', project.id)"
                                class="flex-1 bg-blue-600 text-white text-center py-2 rounded-lg hover:bg-blue-700 transition text-sm"
                            >
                                View Project
                            </Link>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-else class="text-center py-12">
                <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">No projects assigned</h3>
                <p class="mt-2 text-gray-500">You haven't been assigned to any projects yet.</p>
                <div class="mt-6">
                    <Link
                        :href="index().url"
                        class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700"
                    >
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Browse All Projects
                    </Link>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
