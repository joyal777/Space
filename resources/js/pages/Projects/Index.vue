<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { type BreadcrumbItem } from '@/types';
import { create, edit, show } from '@/routes/projects';

interface Props {
    projects: any[];
    statusOptions: Record<string, string>;
}
// Updated Date formatting function that handles both date strings and text
const formatDateWithHyphen = (dateString: string | null) => {
    // If null, empty, or falsy, return empty string (don't show anything)
    if (!dateString) return '';

    // If it's already in a readable text format (like "2025-12-18"), try to parse it
    // If it contains non-date text or is invalid, return the original text
    const date = new Date(dateString);

    // Check if the date is valid
    if (isNaN(date.getTime())) {
        // If not a valid date, check if it's a simple date string like "2025-12-18"
        const simpleDateMatch = dateString.match(/^(\d{4})-(\d{1,2})-(\d{1,2})$/);
        if (simpleDateMatch) {
            const [, year, month, day] = simpleDateMatch;
            return `${parseInt(day)}-${parseInt(month)}-${year}`;
        }

        // If it's some other text format, return it as-is
        return dateString;
    }

    // If it's a valid date object, format it as day-month-year
    const month = date.getMonth() + 1;
    const day = date.getDate();
    const year = date.getFullYear();
    return `${day}-${month}-${year}`;
};

const props = defineProps<Props>();

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

        <div v-if="$page.props.flash?.success" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ $page.props.flash.success }}
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
            <div v-for="project in props.projects" :key="project.id" 
                class="bg-white rounded-lg shadow-lg overflow-hidden transition-all duration-300 
                        hover:shadow-xl hover:bg-blue-200 hover:scale-105 hover:-translate-y-1">
                <!-- Wrap entire card content with Link -->
                <Link :href="show(project.id).url" class="block">
                    <!-- Project Image -->
                    <img
                        :src="`/frontend/images/projects/${project.project_image}`"
                        :alt="project.project_name"
                        class="w-full h-40 object-cover transition-transform duration-300 hover:scale-105"
                    />

                    <div class="p-4">
                        <h2 class="font-bold text-lg text-gray-800 truncate group-hover:text-blue-700">{{ project.project_name }}</h2>
                        <p class="text-sm text-gray-600 truncate group-hover:text-blue-600">{{ project.project_title }}</p>

                        <span
                            class="inline-block mt-2 px-2 py-1 text-xs font-semibold rounded-full transition-colors duration-300"
                            :class="{
                                'bg-gray-100 text-gray-800 group-hover:bg-blue-200 group-hover:text-blue-800': project.project_status === 'pending',
                                'bg-blue-100 text-blue-800 group-hover:bg-blue-200 group-hover:text-blue-900': project.project_status === 'in_progress',
                                'bg-green-100 text-green-800 group-hover:bg-blue-200 group-hover:text-blue-800': project.project_status === 'completed',
                                'bg-yellow-100 text-yellow-800 group-hover:bg-blue-200 group-hover:text-blue-800': project.project_status === 'on_hold',
                                'bg-red-100 text-red-800 group-hover:bg-blue-200 group-hover:text-blue-800': project.project_status === 'cancelled',
                            }"
                        >
                            {{ props.statusOptions[project.project_status] }}
                        </span>

                        <p class="text-sm text-gray-500 mt-2 group-hover:text-blue-600">Start: {{ formatDateWithHyphen(project.start_date) }}</p>
                        <p class="text-sm text-gray-500 group-hover:text-blue-600">End: {{ formatDateWithHyphen(project.end_date) }}</p>
                    </div>
                </Link>
            </div>
        </div>
    </div>
</AppLayout>
</template>

