<script setup lang="ts">
import { ref } from 'vue';
import { router, useForm, usePage } from '@inertiajs/vue3';

interface Props {
    project: {
        id: number;
        users: any[];
    };
}

const props = defineProps<Props>();

// Modal state
const showInviteModal = ref(false);

// Invite form
const inviteForm = useForm({
    email: ''
});

// Get authenticated user
const page = usePage();
const authUser = page.props.auth.user;

// Get team members (accepted invitations)
const teamMembers = props.project.users?.filter(user => user.pivot.status === 'accepted') || [];

// Get pending invitations
const pendingInvitations = props.project.users?.filter(user => user.pivot.status === 'pending') || [];

// Check if current user is the owner of the project
const isOwner = teamMembers.some(user =>
    user.id === authUser.id && user.pivot.role === 'owner'
);

// Open invite modal
const openInviteModal = () => {
    showInviteModal.value = true;
};

// Close invite modal
const closeInviteModal = () => {
    showInviteModal.value = false;
    inviteForm.reset();
};

// Submit invite form
const submitInvite = () => {
    inviteForm.post(`/projects/${props.project.id}/invite`, {
        preserveScroll: true,
        onSuccess: () => {
            closeInviteModal();
        }
    });
};

// Accept invitation function
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

// Reject invitation function
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

// Remove member function
const removeMember = (userId: number, userName: string) => {
    if (confirm(`Are you sure you want to remove ${userName} from this project?`)) {
        router.delete(`/projects/${props.project.id}/users/${userId}`, {
            preserveScroll: true,
            onSuccess: () => {
                console.log('Member removed successfully');
            }
        });
    }
};

// Delete confirmation modal state
const showDeleteModal = ref(false);
const memberToDelete = ref<{ id: number; name: string } | null>(null);

// Open delete confirmation modal
const openDeleteModal = (userId: number, userName: string) => {
    memberToDelete.value = { id: userId, name: userName };
    showDeleteModal.value = true;
};

// Close delete confirmation modal
const closeDeleteModal = () => {
    showDeleteModal.value = false;
    memberToDelete.value = null;
};

// Confirm and remove member
const confirmRemoveMember = () => {
    if (memberToDelete.value) {
        router.delete(`/projects/${props.project.id}/users/${memberToDelete.value.id}`, {
            preserveScroll: true,
            onSuccess: () => {
                console.log('Member removed successfully');
                closeDeleteModal();
            }
        });
    }
};
</script>

<template>
    <div class="bg-white rounded-xl shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Team Members</h2>
            <button
                v-if="isOwner"
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
                <div class="flex items-center space-x-3">
                    <span
                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium"
                        :class="{
                            'bg-purple-100 text-purple-800': user.pivot.role === 'owner',
                            'bg-green-100 text-green-800': user.pivot.role === 'member'
                        }"
                    >
                        {{ user.pivot.role === 'owner' ? 'Owner' : 'Member' }}
                    </span>
                    <!-- Remove Button (only for members, not owners, and only visible to project owner) -->
                    <button
                        v-if="user.pivot.role !== 'owner' && isOwner"
                        @click="openDeleteModal(user.id, user.name)"
                        class="text-red-600 hover:text-red-800 text-sm font-medium transition"
                        title="Remove member"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Pending Invitations -->
        <div v-if="pendingInvitations.length > 0 && isOwner" class="mt-6">
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
                        <!-- Accept Button -->
                        <button
                            @click="acceptInvitation(user.id)"
                            class="px-3 py-1 bg-green-600 text-white text-xs font-medium rounded-md hover:bg-green-700 transition"
                        >
                            Accept
                        </button>
                        <!-- Reject Button -->
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
                    v-if="isOwner"
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

    <!-- Invite User Modal -->
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

    <!-- Delete Confirmation Modal -->
    <div v-if="showDeleteModal" class="fixed inset-0 bg-[#00000075] overflow-y-auto h-full w-full z-50 flex items-center justify-center">
        <div class="relative p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <!-- Warning Icon -->
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                    <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>

                <h3 class="text-lg leading-6 font-medium text-gray-900 mt-2">
                    Remove Team Member
                </h3>

                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">
                        Are you sure you want to remove <strong>{{ memberToDelete?.name }}</strong> from this project?
                    </p>

                    <div class="flex justify-center space-x-3 mt-4">
                        <button
                            type="button"
                            @click="closeDeleteModal"
                            class="px-4 py-2 bg-gray-300 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 transition"
                        >
                            Cancel
                        </button>
                        <button
                            type="button"
                            @click="confirmRemoveMember"
                            class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 transition"
                        >
                            Remove Member
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
