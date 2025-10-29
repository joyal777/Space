<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3'; // Added useForm
import { type BreadcrumbItem } from '@/types';
import { create, edit, index, destroy as destroyProjectRoute } from '@/routes/projects';
import { create as createTask, show as showTask, edit as editTask, destroy as destroyTaskRoute } from '@/routes/tasks';
import { computed, ref } from 'vue';
import TeamMembers from '@/components/TeamMembers.vue';

interface Props {
    project: {
        id: number;
        project_code: string;
        project_name: string;
        project_title: string;
        project_description: string;
        project_status: string;
        project_update: string;
        project_image: string;
        start_date: string;
        end_date: string;
        status_color: string;
        tasks: any[];
        users: any[];
    };
    statusOptions: Record<string, string>;
    priorityOptions: Record<number, string>;
}

const props = defineProps<Props>();

// Modal state
const showDeleteModal = ref(false);
const itemToDelete = ref<{ type: 'project' | 'task'; id: number; name: string } | null>(null);

// ADDED: Invite modal state
const showInviteModal = ref(false);

// ADDED: Invite form
const inviteForm = useForm({
    email: ''
});

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
    { title: 'Projects', href: index().url },
    { title: props.project.project_name, href: '#' },
];

// Open delete confirmation modal
const confirmDelete = (type: 'project' | 'task', id: number, name: string) => {
    itemToDelete.value = { type, id, name };
    showDeleteModal.value = true;
};

// Close delete confirmation modal
const closeDeleteModal = () => {
    showDeleteModal.value = false;
    itemToDelete.value = null;
};

// ADDED: Open invite modal
const openInviteModal = () => {
    showInviteModal.value = true;
};

// ADDED: Close invite modal
const closeInviteModal = () => {
    showInviteModal.value = false;
    inviteForm.reset();
};

// ADDED: Submit invite form
const submitInvite = () => {
    inviteForm.post(`/projects/${props.project.id}/invite`, {
        preserveScroll: true,
        onSuccess: () => {
            closeInviteModal();
        }
    });
};

// Execute delete after confirmation
const executeDelete = () => {
    if (!itemToDelete.value) return;

    const { type, id } = itemToDelete.value;

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
                // The page will automatically refresh due to Inertia's visit
            },
            onError: (errors) => {
                console.error('Error deleting task:', errors);
            },
            // Force a fresh visit to get updated data
            preserveScroll: false,
            preserveState: false
        });
    }

    closeDeleteModal();
};

// Delete project function (now opens modal)
const deleteProject = () => {
    confirmDelete('project', props.project.id, props.project.project_name);
};

// Delete task function (now opens modal)
const deleteTask = (task: any) => {
    confirmDelete('task', task.id, task.task_name);
};
// Get team members (accepted invitations)
const teamMembers = computed(() => {
    return props.project.users?.filter(user => user.pivot.status === 'accepted') || [];
});

// Get pending invitations
const pendingInvitations = computed(() => {
    return props.project.users?.filter(user => user.pivot.status === 'pending') || [];
});
// Calculate project progress based on tasks
const projectProgress = computed(() => {
    const totalTasks = props.project.tasks?.length || 0;
    if (totalTasks === 0) return 0;

    const completedTasks = props.project.tasks?.filter(task => task.task_status === 'completed').length || 0;
    return Math.round((completedTasks / totalTasks) * 100);
});

// ADDED: Accept invitation function
const acceptInvitation = (userId: number) => {
    router.post(`/projects/${props.project.id}/accept-invitation`, {
        user_id: userId
    }, {
        preserveScroll: true,
        onSuccess: () => {
            console.log('Invitation accepted successfully');
        }
    });
};

// ADDED: Reject invitation function
const rejectInvitation = (userId: number) => {
    router.post(`/projects/${props.project.id}/reject-invitation`, {
        user_id: userId
    }, {
        preserveScroll: true,
        onSuccess: () => {
            console.log('Invitation rejected successfully');
        }
    });
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head :title="project.project_name" />

        <div class="p-6 space-y-6">
            <!-- Success Message -->
            <div v-if="$page.props.flash?.success" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ $page.props.flash.success }}
            </div>

            <!-- Error Message -->
            <div v-if="$page.props.flash?.error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                {{ $page.props.flash.error }}
            </div>

            <!-- Delete Confirmation Modal -->
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

            <!-- ADDED: Invite User Modal -->
            <div v-if="showInviteModal" class="fixed inset-0 bg-[#00000075] overflow-y-auto h-full w-full z-50 flex items-center justify-center">
                <div class="relative p-5 border w-96 shadow-lg rounded-md bg-white">
                    <div class="mt-3 text-center">
                        <!-- User Icon -->
                        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-blue-100">
                            <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>

                        <h3 class="text-lg leading-6 font-medium text-gray-900 mt-2">
                            Invite Team Member
                        </h3>

                        <div class="mt-2 px-7 py-3">
                            <form @submit.prevent="submitInvite">
                                <div class="mb-4">
                                    <label for="email" class="block text-sm font-medium text-gray-700 text-left mb-2">
                                        Email Address
                                    </label>
                                    <input
                                        type="email"
                                        id="email"
                                        v-model="inviteForm.email"
                                        required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="Enter user's email"
                                    />
                                    <div v-if="inviteForm.errors.email" class="text-red-500 text-sm mt-1 text-left">
                                        {{ inviteForm.errors.email }}
                                    </div>
                                </div>

                                <div class="flex justify-center space-x-3 mt-4">
                                    <button
                                        type="button"
                                        @click="closeInviteModal"
                                        class="px-4 py-2 bg-gray-300 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 transition"
                                    >
                                        Cancel
                                    </button>
                                    <button
                                        type="submit"
                                        :disabled="inviteForm.processing"
                                        class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition disabled:opacity-50"
                                    >
                                        {{ inviteForm.processing ? 'Inviting...' : 'Invite' }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Header Section -->
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ project.project_name }}</h1>
                    <p class="text-lg text-gray-600 mt-1">{{ project.project_title }}</p>
                </div>
                <div class="flex space-x-3">
                    <!-- ADDED: Invite Member Button -->
                    <button
                        @click="openInviteModal"
                        class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition flex items-center"
                    >
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Invite
                    </button>
                    <Link
                        :href="createTask().url"
                        class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition flex items-center"
                    >
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Task
                    </Link>
                    <Link
                        :href="edit(project.id).url"
                        class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition flex items-center"
                    >
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit
                    </Link>
                    <button
                        @click="deleteProject"
                        class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition flex items-center"
                    >
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Delete
                    </button>
                    <Link
                        :href="index().url"
                        class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition flex items-center"
                    >
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Projects
                    </Link>
                </div>
            </div>

            <!-- Rest of your existing template remains exactly the same -->
            <!-- Project Image -->
            <div class="rounded-xl overflow-hidden shadow-lg">
                <img
                    :src="`/frontend/images/projects/${project.project_image}`"
                    :alt="project.project_name"
                    class="w-full h-64 md:h-80 object-cover"
                />
            </div>

            <!-- Project Details -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Information -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Description Card -->
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">Project Description</h2>
                        <p class="text-gray-700 leading-relaxed">{{ project.project_description }}</p>
                    </div>

                    <!-- Latest Update Card -->
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">Latest Update</h2>
                        <p class="text-gray-700 leading-relaxed">{{ project.project_update || 'No updates available' }}</p>
                    </div>

                    <!-- Team Members Card -->
                    <TeamMembers :project="project" />

                    <!-- Tasks Section -->
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-xl font-semibold text-gray-800">Project Tasks</h2>
                            <Link
                                :href="createTask().url"
                                class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition flex items-center text-sm"
                            >
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Add Task
                            </Link>
                        </div>

                        <!-- Progress Bar -->
                        <div class="mb-6">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm font-medium text-gray-700">Project Progress</span>
                                <span class="text-sm font-medium text-gray-700">{{ projectProgress }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div
                                    class="bg-green-600 h-2 rounded-full transition-all duration-300"
                                    :style="{ width: `${projectProgress}%` }"
                                ></div>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">
                                {{ project.tasks?.filter(t => t.task_status === 'completed').length || 0 }} of {{ project.tasks?.length || 0 }} tasks completed
                            </p>
                        </div>

                        <!-- Tasks List -->
                        <div v-if="project.tasks && project.tasks.length > 0" class="space-y-4">
                            <div
                                v-for="task in project.tasks"
                                :key="task.id"
                                class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow"
                            >
                                <div class="flex justify-between items-start mb-2">
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-gray-800">{{ task.task_name }}</h3>
                                        <p class="text-sm text-gray-600 mt-1">{{ task.task_description }}</p>
                                    </div>
                                    <span class="text-xs text-gray-500 ml-2">{{ task.task_code }}</span>
                                </div>

                                <div class="flex justify-between items-center">
                                    <div class="flex space-x-2">
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold"
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
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold"
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

                                    <div class="flex space-x-2">
                                        <Link
                                            :href="showTask(task.id).url"
                                            class="text-blue-600 hover:text-blue-800 text-sm font-medium"
                                        >
                                            View
                                        </Link>
                                        <Link
                                            :href="editTask(task.id).url"
                                            class="text-green-600 hover:text-green-800 text-sm font-medium"
                                        >
                                            Edit
                                        </Link>
                                        <button
                                            @click="deleteTask(task)"
                                            class="text-red-600 hover:text-red-800 text-sm font-medium"
                                        >
                                            Delete
                                        </button>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4 mt-3 text-xs text-gray-500">
                                    <div>
                                        <span class="font-medium">Start:</span> {{ formatDateWithHyphen(task.start_date) }}
                                    </div>
                                    <div>
                                        <span class="font-medium">End:</span> {{ formatDateWithHyphen(task.end_date) }}
                                    </div>
                                </div>

                                <div v-if="task.estimated_hours || task.actual_hours" class="flex justify-between mt-2 text-xs text-gray-500">
                                    <span>Estimated: {{ task.estimated_hours || 0 }}h</span>
                                    <span>Actual: {{ task.actual_hours || 0 }}h</span>
                                </div>
                            </div>
                        </div>

                        <!-- Empty State -->
                        <div v-else class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No tasks</h3>
                            <p class="mt-1 text-sm text-gray-500">Get started by creating your first task.</p>
                            <div class="mt-6">
                                <Link
                                    :href="createTask().url"
                                    class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700"
                                >
                                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Add Task
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Information -->
                <div class="space-y-6">
                    <!-- Status & Details Card -->
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">Project Details</h2>

                        <div class="space-y-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Project Code</p>
                                <p class="text-lg font-semibold text-gray-800">{{ project.project_code }}</p>
                            </div>

                            <div>
                                <p class="text-sm font-medium text-gray-500">Status</p>
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
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

                            <div>
                                <p class="text-sm font-medium text-gray-500">Start Date</p>
                                <p class="text-lg text-gray-800">{{ formatDateWithHyphen(project.start_date) }}</p>
                            </div>

                            <div>
                                <p class="text-sm font-medium text-gray-500">End Date</p>
                                <p class="text-lg text-gray-800">{{ formatDateWithHyphen(project.end_date) }}</p>
                            </div>

                            <!-- Task Statistics -->
                            <div class="pt-4 border-t border-gray-200">
                                <p class="text-sm font-medium text-gray-500 mb-2">Task Statistics</p>
                                <div class="space-y-2">
                                    <div class="flex justify-between text-sm">
                                        <span>Total Tasks:</span>
                                        <span class="font-semibold">{{ project.tasks?.length || 0 }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span>Completed:</span>
                                        <span class="font-semibold text-green-600">{{ project.tasks?.filter(t => t.task_status === 'completed').length || 0 }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span>In Progress:</span>
                                        <span class="font-semibold text-blue-600">{{ project.tasks?.filter(t => t.task_status === 'in_progress').length || 0 }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span>Pending:</span>
                                        <span class="font-semibold text-gray-600">{{ project.tasks?.filter(t => t.task_status === 'pending').length || 0 }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">Quick Actions</h2>
                        <div class="space-y-3">
                            <Link
                                :href="createTask().url"
                                class="w-full flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition"
                            >
                                Add New Task
                            </Link>
                            <Link
                                :href="edit(project.id).url"
                                class="w-full flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 transition"
                            >
                                Edit Project
                            </Link>
                            <Link
                                :href="index().url"
                                class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition"
                            >
                                Back to Projects
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
