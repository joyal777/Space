<script setup lang="ts">
import { computed } from 'vue';
import { router } from '@inertiajs/vue3';

const patternId = computed(
    () => `pattern-${Math.random().toString(36).substring(2, 9)}`,
);

const goToProjects = () => {
    router.visit('/projects/index');
};
</script>

<template>
    <button @click="goToProjects" class="relative w-full h-full group">
        <svg
            class="absolute inset-0 size-full stroke-neutral-900/20 dark:stroke-neutral-100/20 group-hover:stroke-neutral-900/30 dark:group-hover:stroke-neutral-100/30 transition-colors"
            fill="none"
        >
            <defs>
                <pattern
                    :id="patternId"
                    x="0"
                    y="0"
                    width="8"
                    height="8"
                    patternUnits="userSpaceOnUse"
                >
                    <path d="M-1 5L5 -1M3 9L8.5 3.5" stroke-width="0.5"></path>
                </pattern>
            </defs>

            <rect width="100%" height="100%" :fill="`url(#${patternId})`"></rect>

            <text
                x="50%"
                y="50%"
                text-anchor="middle"
                dominant-baseline="middle"
                class="fill-neutral-600 dark:fill-neutral-300 text-lg group-hover:fill-neutral-800 dark:group-hover:fill-neutral-100 transition-colors cursor-pointer"
            >
                View Project
            </text>
        </svg>
    </button>
</template>
