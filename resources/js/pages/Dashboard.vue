<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard, projects } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import PlaceholderPattern from '../components/PlaceholderPattern.vue';

interface RecentProject {
    id: number;
    project_code: string;
    project_name: string;
    project_title: string;
    project_status: string;
    project_image: string | null;
    last_accessed: string | null;
    progress_percentage: number;
    status_color: string;
}

interface Props {
    recentProjects: RecentProject[];
    stats: {
        total_projects: number;
        active_projects: number;
        recent_count: number;
    };
}

defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
];

const goToProject = (projectId: number) => {
    router.visit(projects.show.url(projectId));
};

const goToProjects = () => {
    router.visit(projects.index.url());
};

const formatDate = (dateString: string | null) => {
    if (!dateString) return 'Never';

    const date = new Date(dateString);
    const now = new Date();
    const diffTime = Math.abs(now.getTime() - date.getTime());
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

    if (diffDays === 1) return 'Yesterday';
    if (diffDays < 7) return `${diffDays} days ago`;
    if (diffDays < 30) return `${Math.floor(diffDays / 7)} weeks ago`;

    return date.toLocaleDateString();
};
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 overflow-x-auto rounded-xl p-4">
            <!-- Stats Cards -->
            <div class="grid gap-4 md:grid-cols-3">
                <div class="rounded-lg bg-white p-6 shadow-sm border border-sidebar-border/70 dark:bg-gray-800 dark:border-sidebar-border">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Projects</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ stats.total_projects }}</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-lg bg-white p-6 shadow-sm border border-sidebar-border/70 dark:bg-gray-800 dark:border-sidebar-border">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Active Projects</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ stats.active_projects }}</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-lg bg-white p-6 shadow-sm border border-sidebar-border/70 dark:bg-gray-800 dark:border-sidebar-border">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-purple-500 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Recently Viewed</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ stats.recent_count }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recently Viewed Projects -->
            <div class="flex-1">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Recently Viewed Projects</h2>
                    <button
                        @click="goToProjects"
                        class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 font-medium"
                    >
                        View All Projects
                    </button>
                </div>

                <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3" v-if="recentProjects.length > 0">
                    <div
                        v-for="project in recentProjects"
                        :key="project.id"
                        @click="goToProject(project.id)"
                        class="bg-white rounded-lg border border-sidebar-border/70 shadow-sm hover:shadow-md transition-shadow cursor-pointer dark:bg-gray-800 dark:border-sidebar-border"
                    >
                        <div class="p-4">
                            <div class="flex items-start justify-between mb-3">
                                <div>
                                    <h3 class="font-semibold text-gray-900 dark:text-white truncate">
                                        {{ project.project_name }}
                                    </h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                        {{ project.project_title }}
                                    </p>
                                </div>
                                <span
                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium"
                                    :class="{
                                        'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300': project.project_status === 'pending',
                                        'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300': project.project_status === 'in_progress',
                                        'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300': project.project_status === 'completed',
                                        'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300': project.project_status === 'on_hold',
                                        'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300': project.project_status === 'cancelled',
                                    }"
                                >
                                    {{ project.project_status.replace('_', ' ').toUpperCase() }}
                                </span>
                            </div>

                            <!-- Progress Bar -->
                            <div class="mb-3">
                                <div class="flex justify-between text-sm text-gray-500 dark:text-gray-400 mb-1">
                                    <span>Progress</span>
                                    <span>{{ Math.round(project.progress_percentage) }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2 dark:bg-gray-700">
                                    <div
                                        class="h-2 rounded-full transition-all duration-300"
                                        :class="{
                                            'bg-gray-400': project.project_status === 'pending',
                                            'bg-blue-500': project.project_status === 'in_progress',
                                            'bg-green-500': project.project_status === 'completed',
                                            'bg-yellow-500': project.project_status === 'on_hold',
                                            'bg-red-500': project.project_status === 'cancelled',
                                        }"
                                        :style="{ width: `${project.progress_percentage}%` }"
                                    ></div>
                                </div>
                            </div>

                            <div class="flex justify-between items-center text-xs text-gray-500 dark:text-gray-400">
                                <span>Code: {{ project.project_code }}</span>
                                <span>Last viewed: {{ formatDate(project.last_accessed) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-else class="text-center py-12">
                    <div class="max-w-md mx-auto">
                        <PlaceholderPattern />
                        <p class="mt-4 text-gray-500 dark:text-gray-400">No recently viewed projects</p>
                        <button
                            @click="goToProjects"
                            class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                        >
                            Browse Projects
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
