<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { type BreadcrumbItem } from '@/types';
import { create, edit, index, destroy as destroyProjectRoute } from '@/routes/projects';
import { create as createTask, show as showTask, edit as editTask, destroy as destroyTaskRoute } from '@/routes/tasks';
import { show } from '@/routes/projects';
import { computed, ref } from 'vue';

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
const breadcrumbItems = computed((): BreadcrumbItem[] => {
    if (currentProject.value) {
        return [
            { title: currentProject.value.project_name, href: show(currentProject.value.id).url },
            { title: props.task.task_name, href: '#' },
        ];
    } else {
        return [
            { title: 'Tasks', href: index().url },
            { title: props.task.task_name, href: '#' },
        ];
    }
});

// Delete task function
// Modal state - ADD THIS
const showDeleteModal = ref(false);
const itemToDelete = ref<{ type: string; id: number; name: string } | null>(null);

// Open delete confirmation modal - ADD THIS
const confirmDelete = (type: string, id: number, name: string) => {
    itemToDelete.value = { type, id, name };
    showDeleteModal.value = true;
};

// Close delete confirmation modal - ADD THIS
const closeDeleteModal = () => {
    showDeleteModal.value = false;
    itemToDelete.value = null;
};

// Execute delete after confirmation - MODIFY THIS BASED ON YOUR ROUTES
const executeDelete = () => {
    if (!itemToDelete.value) return;

    const { type, id } = itemToDelete.value;

    // MODIFY THESE ROUTES BASED ON YOUR PAGE
    if (type === 'project') {
        router.delete(destroyProjectRoute(id).url, {
            onSuccess: () => {
                console.log('Project deleted successfully');
            },
            onError: (errors) => {
                console.error('Error deleting project:', errors);
            }
        });
    } else if (type === 'task') {
        router.delete(destroyTaskRoute(id).url, {
            onSuccess: () => {
                console.log('Task deleted successfully');
            },
            onError: (errors) => {
                console.error('Error deleting task:', errors);
            },
            preserveScroll: false,
            preserveState: false
        });
    }
    // ADD MORE TYPES AS NEEDED

    closeDeleteModal();
};
const deleteTask = (task: any) => {
    confirmDelete('task', task.id, task.task_name);
};
// Delete item function - MODIFY THIS FOR YOUR BUTTONS

</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head :title="task.task_name" />

        <div class="p-6 space-y-6">
            <!-- Success Message -->
            <div v-if="$page.props.flash?.success" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ $page.props.flash.success }}
            </div>

            <div v-if="$page.props.flash?.error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                {{ $page.props.flash.error }}
            </div>

            <div v-if="showDeleteModal" class="fixed inset-0 bg-[#00000075] overflow-y-auto h-full w-full z-50 flex items-center justify-center"><div class="relative p-5 border w-96 shadow-lg rounded-md bg-white">
                    <div class="mt-3 text-center">
                        <!-- Warning Icon -->
                        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>

                        <h3 class="text-lg leading-6 font-medium text-gray-900 mt-2">
                            Delete {{ itemToDelete?.type === 'project' ? 'Project' : 'Task' }}
                        </h3>

                        <div class="mt-2 px-7 py-3">
                            <p class="text-sm text-gray-500">
                                Are you sure you want to delete
                                <span class="font-semibold">"{{ itemToDelete?.name }}"</span>?
                                {{ itemToDelete?.type === 'project' ? 'This will also delete all associated tasks.' : 'This action cannot be undone.' }}
                            </p>
                        </div>

                        <div class="flex justify-center space-x-3 mt-4">
                            <button
                                @click="closeDeleteModal"
                                class="px-4 py-2 bg-gray-300 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 transition"
                            >
                                Cancel
                            </button>
                            <button
                                @click="executeDelete"
                                class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 transition"
                            >
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
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
                        @click="deleteTask(task)"
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
