<script setup lang="ts">
import type { HTMLAttributes } from "vue";
import { usePage } from "@inertiajs/vue3";

defineOptions({ inheritAttrs: false });

interface Props {
  className?: HTMLAttributes["class"];
}

defineProps<Props>();

const { props } = usePage();
const settings = props.settings;
</script>

<template>
  <img
    v-if="settings?.logo"
    :src="`/images/gsettings/${settings.logo}`"
    alt="Logo"
    class="size-8 rounded"
  />
</template>
