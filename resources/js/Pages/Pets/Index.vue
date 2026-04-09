<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';

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

    <div class="p-6 flex flex-col p-6 justify-center items-center w-full">
      <div class="flex justify-between items-center mb-4 w-full">
        <h3 class="text-lg font-semibold">Lemmikute nimekiri</h3>
        <Link href="/pets/create" class="text-green-600 font-medium pl-2 pr-2 border border-green-600 rounded-lg hover:bg-green-600 hover:text-white transition">
          +
        </Link>
      </div>
      <div v-if="pets.length" class="flex flex-col justify-center items-center  w-full max-w-md mx-auto">
        <div
          v-for="(pet, index) in pets"
          :key="pet.id"
          class="w-full max-w-lg"
        >
          <h3
            v-if="index === 0 || pet.species !== pets[index - 1].species"
            class="text-xl font-bold text-[#275342] mt-4 mb-2"
          >
            {{ pet.species }}
          </h3>

          <div class="bg-[#FFFDF5] p-6 rounded-2xl shadow mb-3">
            <Link :href="route('pets.show', pet.id)">
              <div class="flex flex-row justify-start gap-2">
                <div>
                  <img
                    :src="pet.photo_url?.medium || '/images/default-pet.png'"
                    :alt="pet.name"
                    class="w-32 h-32 object-cover rounded-xl border"
                  />
                </div>
                <div class="flex flex-col">
                  <h4 class="text-lg font-bold pb-2">{{ pet.name }}</h4>
                  <p> {{ pet.species }}</p>
                  <p class="text-md">
                    {{ pet.formatted_dob }} ({{ pet.age }})
                  </p>
                  <p> {{ pet.weight }} kg</p>                  
                </div>

              </div>
            </Link>
          </div>
        </div>
      </div>
      <p v-else>Lemmikloomi ei leitud</p>
    </div>
  </AuthenticatedLayout>
</template>
