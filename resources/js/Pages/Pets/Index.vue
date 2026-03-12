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
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        Minu lemmikud
      </h2>
    </template>

    <div class="p-6">
      <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold">Lemmikute nimekiri</h3>
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
                    <p><strong>Liik:</strong> {{ pet.species }}</p>
                    <p><strong>Tõug:</strong> {{ pet.breed }}</p>
                    <p><strong>Sugu:</strong> {{ pet.gender }}</p>
                    <p><strong>Sünnikuupäev:</strong> {{ pet.dob }}</p>
                    <p><strong>Kaal:</strong> {{ pet.weight }}</p>
                    <p><strong>Kiip:</strong> {{ pet.chip }}</p>
                    <p><strong>Pilt:</strong> {{ pet.image }}</p>
                </div>

              </div>
            </Link>

          </div>
        </div>


      <p v-else>Lemmikloomi ei leitud</p>
    </div>
  </AuthenticatedLayout>
</template>
