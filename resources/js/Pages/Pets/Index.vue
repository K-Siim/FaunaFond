<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
  pets: Array,
});
</script>

<template>
  <Head title="My Pets" />

  <AuthenticatedLayout>
    <div class="p-6">
      <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold">Kõik lemmikud</h3>
        <Link href="/pets/create" class="text-green-600 font-medium pl-2 pr-2 border border-green-600 rounded-lg hover:bg-green-600 hover:text-white transition">
          +
        </Link>
      </div>

        <div v-if="pets.length">
        <div
            v-for="pet in pets"
            :key="pet.id"
            class="bg-white p-4 rounded shadow mb-3"
        >
          <Link :href="route('pets.show', pet.id)" class="">
            <div class="flex items-center justify-between">

              <div>
                  <h4 class="text-lg font-bold">{{ pet.name }}</h4>
                  <p><strong>Species:</strong> {{ pet.species }}</p>
                  <p><strong>Breed:</strong> {{ pet.breed }}</p>
                  <p><strong>Gender:</strong> {{ pet.gender }}</p>
                  <p><strong>DOB:</strong> {{ pet.dob }}</p>
                  <p><strong>Weight:</strong> {{ pet.weight }}</p>
                  <p><strong>Chip:</strong> {{ pet.chip }}</p>
                  <p><strong>Image URL:</strong> {{ pet.image }}</p>
              </div>

            </div>
          </Link>

        </div>
        </div>


      <p v-else>Lemmikuid ei leitud</p>
    </div>
  </AuthenticatedLayout>
</template>
