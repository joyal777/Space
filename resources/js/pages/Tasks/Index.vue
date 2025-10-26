<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { type BreadcrumbItem } from '@/types';
import { create, edit, show } from '@/routes/tasks';

interface Props {
    tasks: any[];
    statusOptions: Record<string, string>;
    priorityOptions: Record<number, string>;
}

const props = defineProps<Props>();

// Date formatting function (same as projects)
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
    { title: 'Tasks', href: create().url },
];

// Delete task function
const deleteTask = (task: any) => {
    if (confirm('Are you sure you want to delete this task?')) {
        router.delete(route('tasks.destroy', task.id));
    }
};

// Get priority color
const getPriorityColor = (priority: number) => {
    return match(priority) {
        1: 'red',
        2: 'orange',
        3: 'yellow',
        4: 'blue',
        5: 'gray',
        default: 'gray',
    };
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head title="Tasks" />

        <div class="p-6 space-y-6">
            <!-- Header -->
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold">Tasks</h1>
                <Link
                    :href="create().url"
                    class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition flex items-center"
                >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Create Task
                </Link>
            </div>

            <!-- Success Message -->
            <div v-if="$page.props.flash?.success" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ $page.props.flash.success }}
            </div>

            <!-- Tasks Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div v-for="task in props.tasks" :key="task.id" class="bg-white rounded-lg shadow-lg overflow-hidden border-l-4" :class="{
                    'border-red-500': task.priority === 1,
                    'border-orange-500': task.priority === 2,
                    'border-yellow-500': task.priority === 3,
                    'border-blue-500': task.priority === 4,
                    'border-gray-500': task.priority === 5,
                }">
                    <div class="p-4">
                        <!-- Task Header -->
                        <div class="flex justify-between items-start mb-2">
                            <h2 class="font-bold text-lg text-gray-800 truncate">{{ task.task_name }}</h2>
                            <span class="text-xs text-gray-500">{{ task.task_code }}</span>
                        </div>

                        <!-- Project Info -->
                        <div class="mb-3">
                            <p class="text-sm text-gray-600 flex items-center">
                                <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                {{ task.project?.project_name }}
                            </p>
                        </div>

                        <!-- Task Description -->
                        <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ task.task_description }}</p>

                        <!-- Status and Priority -->
                        <div class="flex justify-between items-center mb-3">
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
                                {{ props.statusOptions[task.task_status] }}
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
                                {{ props.priorityOptions[task.priority] }}
                            </span>
                        </div>

                        <!-- Dates -->
                        <div class="space-y-1 text-xs text-gray-500">
                            <p class="flex justify-between">
                                <span>Start:</span>
                                <span>{{ formatDateWithHyphen(task.start_date) }}</span>
                            </p>
                            <p class="flex justify-between">
                                <span>End:</span>
                                <span>{{ formatDateWithHyphen(task.end_date) }}</span>
                            </p>
                        </div>

                        <!-- Hours -->
                        <div v-if="task.estimated_hours || task.actual_hours" class="mt-3 pt-3 border-t border-gray-100">
                            <div class="flex justify-between text-xs text-gray-500">
                                <span>Estimated: {{ task.estimated_hours }}h</span>
                                <span>Actual: {{ task.actual_hours }}h</span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-between mt-4 pt-3 border-t border-gray-100">
                            <Link :href="show(task.id).url" class="text-blue-600 hover:text-blue-900 text-xs font-semibold flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                View
                            </Link>
                            <Link :href="edit(task.id).url" class="text-green-600 hover:text-green-900 text-xs font-semibold flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Edit
                            </Link>
                            <button @click="deleteTask(task)" class="text-red-600 hover:text-red-900 text-xs font-semibold flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <div v-if="tasks.meta && tasks.meta.last_page > 1" class="flex justify-center mt-6">
                <div class="flex space-x-2">
                    <Link
                        v-for="(link, index) in tasks.meta.links"
                        :key="index"
                        :href="link.url"
                        class="px-3 py-2 rounded-lg border border-gray-300 text-sm"
                        :class="{
                            'bg-blue-600 text-white border-blue-600': link.active,
                            'text-gray-700 hover:bg-gray-50': !link.active && link.url,
                            'text-gray-400 cursor-not-allowed': !link.url,
                        }"
                        v-html="link.label"
                    />
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
