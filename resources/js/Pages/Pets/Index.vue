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
      <h2 class="text-2xl font-semibold leading-tight text-[#275342]">
        Minu lemmikud
      </h2>
          <div class="flex justify-center items-center h-full w-full flex-col p-5 gap-5">
      <div class="flex flex-row justify-between items- w-full pt-2 pb-2">
        <h5 class="text-[#275342] text-xl font-semibold">
          Lemmikute nimekiri
        </h5>        
        <Link href="/pets/create" class="text-green-600 font-medium pl-2 pr-2 border border-green-600 rounded-full hover:bg-green-600 hover:text-white transition">
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
                <div class="flex flex-col w-2/3">
                  <h4 class="text-lg font-bold pb-2">{{ pet.name }}</h4>
                  <div class="flex flex-col h-full justify-center">
                    <p> {{ pet.species }}</p>
                    <p class="text-md">
                      {{ pet.formatted_dob }} ({{ pet.age }})
                    </p>
                    <p> {{ pet.weight }} kg</p>                         
                  </div>
                </div>

              </div>
            </Link>
          </div>
        </div>
      </div>
      <p v-else>Lemmikloomi ei leitud</p>
    </div>
    </template>
  </AuthenticatedLayout>
</template>
