<script setup lang="ts">
import AppLogoIcon from '@/components/AppLogoIcon.vue'
import { usePage } from '@inertiajs/vue3'

const { props } = usePage()
const settings = props.settings
</script>

<template>
  <div
    class="flex aspect-square size-8 items-center justify-center rounded-md bg-sidebar-primary text-sidebar-primary-foreground"
  >
    <!-- Show image logo if available -->
    <img
    v-if="settings?.logo"
    :src="`/images/gsettings/${settings.logo}`"
    alt="Logo"
    class="size-8 rounded"
    />


    <!-- Otherwise show default SVG icon -->
    <AppLogoIcon
      v-else
      class="size-5 fill-current text-white dark:text-black"
    />
  </div>

  <div class="ml-1 grid flex-1 text-left text-sm">
    <span class="mb-0.5 truncate leading-tight font-semibold">
      {{ settings?.site_name ?? 'Laravel Starter Kit' }}
    </span>
  </div>
</template>
