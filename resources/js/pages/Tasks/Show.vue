<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { type BreadcrumbItem } from '@/types';
import { index, edit } from '@/routes/tasks';
import { show } from '@/routes/projects';
import { computed } from 'vue';

interface Props {
    task: any;
    projects: any[];
    statusOptions: Record<string, string>;
    priorityOptions: Record<number, string>;
}
const currentProject = computed(() => {
    return props.projects.find(project => project.id === props.task.project_id);
});

const props = defineProps<Props>();

// Date formatting function
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

// Breadcrumb items
const breadcrumbItems: BreadcrumbItem[] = [
    { title: 'Tasks', href: index().url },
    { title: props.task.task_name, href: '#' },
];

// Delete task function
const deleteTask = () => {
    if (confirm('Are you sure you want to delete this task?')) {
        router.delete(route('tasks.destroy', props.task.id));
    }
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head :title="task.task_name" />

        <div class="p-6 space-y-6">
            <!-- Success Message -->
            <div v-if="$page.props.flash?.success" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ $page.props.flash.success }}
            </div>

            <!-- Header Section -->
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ task.task_name }}</h1>
                    <p class="text-lg text-gray-600 mt-1">{{ task.task_code }}</p>
                </div>
                <div class="flex space-x-3">
                    <Link
                        :href="edit(task.id).url"
                        class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition flex items-center"
                    >
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit Task
                    </Link>
                    <button
                        @click="deleteTask"
                        class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition flex items-center"
                    >
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Delete
                    </button>

                    <Link
                        v-if="currentProject"
                        :href="show(currentProject.id).url"
                        class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition flex items-center"
                    >
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to {{ currentProject.project_name }}
                    </Link>

                </div>
            </div>

            <!-- Task Details -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Information -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Description Card -->
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">Task Description</h2>
                        <p class="text-gray-700 leading-relaxed">{{ task.task_description || 'No description provided' }}</p>
                    </div>

                    <!-- Notes Card -->
                    <div v-if="task.notes" class="bg-white rounded-xl shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">Notes</h2>
                        <p class="text-gray-700 leading-relaxed">{{ task.notes }}</p>
                    </div>
                </div>

                <!-- Sidebar Information -->
                <div class="space-y-6">
                    <!-- Task Details Card -->
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">Task Details</h2>

                        <div class="space-y-4">
                            <!-- Project -->
                            <div>
                                <p class="text-sm font-medium text-gray-500">Project</p>
                                <p class="text-lg font-semibold text-gray-800">{{ task.project?.project_name }}</p>
                                <p class="text-sm text-gray-600">{{ task.project?.project_code }}</p>
                            </div>

                            <!-- Status -->
                            <div>
                                <p class="text-sm font-medium text-gray-500">Status</p>
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
                                    :class="{
                                        'bg-gray-100 text-gray-800': task.task_status === 'pending',
                                        'bg-blue-100 text-blue-800': task.task_status === 'in_progress',
                                        'bg-green-100 text-green-800': task.task_status === 'completed',
                                        'bg-yellow-100 text-yellow-800': task.task_status === 'on_hold',
                                        'bg-red-100 text-red-800': task.task_status === 'cancelled',
                                    }"
                                >
                                    {{ statusOptions[task.task_status] }}
                                </span>
                            </div>

                            <!-- Priority -->
                            <div>
                                <p class="text-sm font-medium text-gray-500">Priority</p>
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
                                    :class="{
                                        'bg-red-100 text-red-800': task.priority === 1,
                                        'bg-orange-100 text-orange-800': task.priority === 2,
                                        'bg-yellow-100 text-yellow-800': task.priority === 3,
                                        'bg-blue-100 text-blue-800': task.priority === 4,
                                        'bg-gray-100 text-gray-800': task.priority === 5,
                                    }"
                                >
                                    {{ priorityOptions[task.priority] }}
                                </span>
                            </div>

                            <!-- Dates -->
                            <div class="space-y-2">
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Start Date</p>
                                    <p class="text-lg text-gray-800">{{ formatDateWithHyphen(task.start_date) || 'Not set' }}</p>
                                </div>

                                <div>
                                    <p class="text-sm font-medium text-gray-500">End Date</p>
                                    <p class="text-lg text-gray-800">{{ formatDateWithHyphen(task.end_date) || 'Not set' }}</p>
                                </div>

                                <div v-if="task.completed_at">
                                    <p class="text-sm font-medium text-gray-500">Completed At</p>
                                    <p class="text-lg text-gray-800">{{ formatDateWithHyphen(task.completed_at) }}</p>
                                </div>
                            </div>

                            <!-- Hours -->
                            <div class="grid grid-cols-2 gap-4 pt-2 border-t border-gray-200">
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Estimated Hours</p>
                                    <p class="text-lg font-semibold text-gray-800">{{ task.estimated_hours || 0 }}h</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Actual Hours</p>
                                    <p class="text-lg font-semibold text-gray-800">{{ task.actual_hours || 0 }}h</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">Quick Actions</h2>
                        <div class="space-y-3">
                            <Link
                                :href="edit(task.id).url"
                                class="w-full flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 transition"
                            >
                                Edit Task
                            </Link>
                            <Link
                                :href="index().url"
                                class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition"
                            >
                                Back to Tasks
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
