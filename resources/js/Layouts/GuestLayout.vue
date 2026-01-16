<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
  <div
    class="flex min-h-screen flex-col items-center justify-center bg-[#E7F0E4]"
  >
    <div class="w-full overflow-hidden bg-[#E7F0E4] px-6 py-4">
      <slot />
    </div>
  </div>
</template>

