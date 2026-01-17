<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'

const props = defineProps({
  pets: Array,
})
</script>

<template>
  <Head title="Dashboard" />

  <AuthenticatedLayout :pets="pets">
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        Tere, Kasutaja!
      </h2>
    </template>
  </AuthenticatedLayout>
</template>
