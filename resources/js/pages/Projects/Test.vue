<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3'; // Added useForm
import { type BreadcrumbItem } from '@/types';
import { create, edit, index, destroy as destroyProjectRoute } from '@/routes/projects';
import { create as createTask, show as showTask, edit as editTask, destroy as destroyTaskRoute } from '@/routes/tasks';
import { computed, ref } from 'vue';

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
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-xl font-semibold text-gray-800">Team Members</h2>
                            <button
                                @click="openInviteModal"
                                class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition flex items-center text-sm"
                            >
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Invite Member
                            </button>
                        </div>

                        <!-- Team Members List -->
                        <div v-if="teamMembers.length > 0" class="space-y-3">
                            <div
                                v-for="user in teamMembers"
                                :key="user.id"
                                class="flex items-center justify-between p-3 border border-gray-200 rounded-lg"
                            >
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white text-sm font-semibold">
                                        {{ user.name.charAt(0).toUpperCase() }}
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">{{ user.name }}</p>
                                        <p class="text-sm text-gray-500">{{ user.email }}</p>
                                    </div>
                                </div>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Member
                                </span>
                            </div>
                        </div>

                        <!-- Pending Invitations -->
                        <div v-if="pendingInvitations.length > 0" class="mt-6">
                            <h3 class="text-lg font-medium text-gray-800 mb-3">Pending Invitations</h3>
                            <div class="space-y-3">
                                <div
                                    v-for="user in pendingInvitations"
                                    :key="user.id"
                                    class="flex items-center justify-between p-3 border border-gray-200 rounded-lg"
                                >
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center text-white text-sm font-semibold">
                                            {{ user.name.charAt(0).toUpperCase() }}
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900">{{ user.name }}</p>
                                            <p class="text-sm text-gray-500">{{ user.email }}</p>
                                        </div>
                                    </div>
                                    <div class="flex space-x-2">
                                        <!-- ADDED: Accept Button -->
                                        <button
                                            @click="acceptInvitation(user.id)"
                                            class="px-3 py-1 bg-green-600 text-white text-xs font-medium rounded-md hover:bg-green-700 transition"
                                        >
                                            Accept
                                        </button>
                                        <!-- ADDED: Reject Button -->
                                        <button
                                            @click="rejectInvitation(user.id)"
                                            class="px-3 py-1 bg-red-600 text-white text-xs font-medium rounded-md hover:bg-red-700 transition"
                                        >
                                            Reject
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Empty State -->
                        <div v-if="teamMembers.length === 0 && pendingInvitations.length === 0" class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No team members</h3>
                            <p class="mt-1 text-sm text-gray-500">Get started by inviting team members to collaborate.</p>
                            <div class="mt-6">
                                <button
                                    @click="openInviteModal"
                                    class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700"
                                >
                                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Invite Team Member
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Rest of your existing Tasks Section remains the same -->
                    <!-- ... existing tasks code ... -->

                </div>

                <!-- Sidebar Information (unchanged) -->
                <!-- ... existing sidebar code ... -->

            </div>
        </div>
    </AppLayout>
</template>
