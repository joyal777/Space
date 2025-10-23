<script setup lang="ts">
import { computed } from 'vue';

const patternId = computed(
    () => `pattern-${Math.random().toString(36).substring(2, 9)}`,
);
</script>

<template>
    <svg
        class="absolute inset-0 size-full stroke-neutral-900/20 dark:stroke-neutral-100/20"
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

        <text
            x="50%"
            y="50%"
            text-anchor="middle"
            dominant-baseline="middle"
            class="fill-neutral-600 dark:fill-neutral-300 text-lg"
            >
            Hello
            </text>


    </svg>
</template>
