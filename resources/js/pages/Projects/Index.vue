<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { type BreadcrumbItem } from '@/types';
import { create, edit, show } from '@/routes/projects'; // 👈 import route helpers

interface Props {
    projects: any[];
    statusOptions: Record<string, string>;
}

defineProps<Props>();

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Projects',
        href: create().url, // or route('projects.index')
    },
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

            <div class="bg-white shadow rounded-lg overflow-hidden">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Code</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Start Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="project in projects" :key="project.id">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ project.project_code }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ project.project_name }}</td>
                            <td class="px-6 py-4">
                                <span
                                    class="px-2 inline-flex text-xs font-semibold rounded-full"
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
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ project.start_date }}</td>
                            <td class="px-6 py-4 text-sm font-medium">
                                <Link :href="show(project.id).url" class="text-blue-600 hover:text-blue-900 mr-3">View</Link>
                                <Link :href="edit(project.id).url" class="text-green-600 hover:text-green-900 mr-3">Edit</Link>
                                <button @click="deleteProject(project)" class="text-red-600 hover:text-red-900">Delete</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
