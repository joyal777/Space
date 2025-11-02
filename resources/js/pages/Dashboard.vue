<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { create } from '@/routes/projects'; // Import create project route
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import PlaceholderPattern from '../components/PlaceholderPattern.vue';
import { Rocket, Plus, Users, Calendar, Target } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
];
</script>

<template>
  <Head title="Dashboard" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-6 p-6">
      <!-- Welcome Section -->
      <div
        class="bg-gradient-to-r from-blue-600 to-purple-700 rounded-2xl p-8 text-white"
      >
        <div class="flex flex-col md:flex-row items-center justify-between">
          <div class="flex-1">
            <div class="flex items-center space-x-3 mb-4">
              <Rocket class="h-8 w-8" />
              <h1 class="text-3xl font-bold">Welcome to Space Project Management</h1>
            </div>
            <p class="text-blue-100 text-lg max-w-2xl mb-6">
              Streamline your projects, collaborate with your team, and reach new heights
              in productivity. Everything you need to manage your projects across the
              universe.
            </p>
            <div class="flex flex-wrap gap-4">
              <Link
                :href="create().url"
                class="inline-flex items-center space-x-2 bg-white text-blue-600 px-6 py-3 rounded-lg font-semibold hover:bg-blue-50 transition-all duration-300 shadow-lg hover:shadow-xl"
              >
                <Plus class="h-5 w-5" />
                <span>Create New Project</span>
              </Link>
              <Link
                :href="dashboard().url"
                class="inline-flex items-center space-x-2 border border-blue-300 text-blue-100 px-6 py-3 rounded-lg font-semibold hover:bg-blue-500 transition-all duration-300"
              >
                <Users class="h-5 w-5" />
                <span>Invite Team</span>
              </Link>
            </div>
          </div>
          <div class="hidden md:block flex-shrink-0">
            <div
              class="w-48 h-48 bg-white/10 rounded-full flex items-center justify-center backdrop-blur-sm"
            >
              <Rocket class="h-20 w-20 text-white" />
            </div>
          </div>
        </div>
      </div>

      <!-- Quick Stats -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl border border-sidebar-border/70 p-6 shadow-sm">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600">Total Projects</p>
              <p class="text-2xl font-bold text-gray-900 mt-1">12</p>
            </div>
            <div class="p-3 bg-blue-100 rounded-lg">
              <Target class="h-6 w-6 text-blue-600" />
            </div>
          </div>
          <p class="text-xs text-gray-500 mt-2">+2 from last month</p>
        </div>

        <div class="bg-white rounded-xl border border-sidebar-border/70 p-6 shadow-sm">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600">Active Tasks</p>
              <p class="text-2xl font-bold text-gray-900 mt-1">47</p>
            </div>
            <div class="p-3 bg-green-100 rounded-lg">
              <Calendar class="h-6 w-6 text-green-600" />
            </div>
          </div>
          <p class="text-xs text-gray-500 mt-2">5 due this week</p>
        </div>

        <div class="bg-white rounded-xl border border-sidebar-border/70 p-6 shadow-sm">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600">Team Members</p>
              <p class="text-2xl font-bold text-gray-900 mt-1">8</p>
            </div>
            <div class="p-3 bg-purple-100 rounded-lg">
              <Users class="h-6 w-6 text-purple-600" />
            </div>
          </div>
          <p class="text-xs text-gray-500 mt-2">All active</p>
        </div>

        <div class="bg-white rounded-xl border border-sidebar-border/70 p-6 shadow-sm">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600">Completion Rate</p>
              <p class="text-2xl font-bold text-gray-900 mt-1">78%</p>
            </div>
            <div class="p-3 bg-orange-100 rounded-lg">
              <Rocket class="h-6 w-6 text-orange-600" />
            </div>
          </div>
          <p class="text-xs text-gray-500 mt-2">+5% this month</p>
        </div>
      </div>

      <!-- Recent Projects & Activity -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Projects -->
        <div class="bg-white rounded-xl border border-sidebar-border/70 p-6 shadow-sm">
          <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-semibold text-gray-900">Recent Projects</h2>
            <Link
              :href="create().url"
              class="inline-flex items-center space-x-2 text-blue-600 hover:text-blue-700 font-medium"
            >
              <Plus class="h-4 w-4" />
              <span>New Project</span>
            </Link>
          </div>
          <div class="space-y-4">
            <div
              v-for="i in 3"
              :key="i"
              class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:border-blue-300 transition"
            >
              <div class="flex items-center space-x-3">
                <div
                  class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center"
                >
                  <Target class="h-5 w-5 text-blue-600" />
                </div>
                <div>
                  <h3 class="font-medium text-gray-900">Project Apollo {{ i }}</h3>
                  <p class="text-sm text-gray-500">Last updated 2 days ago</p>
                </div>
              </div>
              <div class="text-right">
                <span
                  class="inline-block px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full"
                >
                  Active
                </span>
              </div>
            </div>
          </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white rounded-xl border border-sidebar-border/70 p-6 shadow-sm">
          <h2 class="text-xl font-semibold text-gray-900 mb-6">Recent Activity</h2>
          <div class="space-y-4">
            <div v-for="i in 3" :key="i" class="flex items-start space-x-3">
              <div
                class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center flex-shrink-0"
              >
                <Users class="h-4 w-4 text-purple-600" />
              </div>
              <div class="flex-1">
                <p class="text-sm text-gray-900">
                  <span class="font-medium">Sarah Johnson</span>
                  {{ i === 1 ? "created" : i === 2 ? "updated" : "completed" }} a task in
                  <span class="font-medium">Project Apollo {{ i }}</span>
                </p>
                <p class="text-xs text-gray-500 mt-1">
                  About {{ i }} hour{{ i > 1 ? "s" : "" }} ago
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Placeholder sections (keeping your existing structure) -->

      <div
        class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 md:min-h-min dark:border-sidebar-border"
      >
        <PlaceholderPattern />
      </div>
    </div>
  </AppLayout>
</template>
