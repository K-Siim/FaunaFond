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
      <div class="flex justify-center items-center h-full w-full flex-col p-5 gap-5">
        <div class="flex flex-row justify-between items-center w-full pt-2 pb-2">
          <h5 class="text-[#275342] text-2xl font-semibold">
            Minu lemmikud
          </h5>
          <Link href="/pets/create" class="text-[#275342] text-lg font-bold pl-2 pr-2 border border-[#275342] rounded-full hover:bg-[#275342] hover:text-white transition">
            +
          </Link>
        </div>

        <div v-if="pets.length" class="w-full">
          <div
            v-for="(speciesGroup, species) in pets.reduce((acc, pet) => { (acc[pet.species] = acc[pet.species] || []).push(pet); return acc; }, {})"
            :key="species"
          >
            <h3 class="text-xl font-bold text-[#275342] mt-4 mb-2">
              {{ species }}
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
              <div
                v-for="pet in speciesGroup"
                :key="pet.id"
              >
                <Link :href="route('pets.show', pet.id)">
                  <div class="bg-[#FFFDF5] p-6 rounded-2xl shadow">
                    <div class="flex flex-row justify-start gap-2">
                      <div>
                        <img
                          :src="pet.photo_url?.medium || '/images/default-pet.png'"
                          :alt="pet.name"
                          class="w-24 h-24 object-cover rounded-xl border"
                        />
                      </div>
                      <div class="flex flex-col w-2/3">
                        <h4 class="text-lg font-bold pb-2">{{ pet.name }}</h4>
                        <div class="flex flex-col h-full justify-center">
                          <p>{{ pet.species }}</p>
                          <p class="text-md">{{ pet.formatted_dob }} ({{ pet.age }})</p>
                          <p>{{ pet.weight }} kg</p>
                        </div>
                      </div>
                    </div>
                  </div>
                </Link>
              </div>
            </div>
          </div>
        </div>
        <p v-else>Lemmikloomi ei leitud</p>
      </div>
    </template>
  </AuthenticatedLayout>
</template>