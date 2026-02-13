<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import PetContent from '@/Components/PetInfo/PetContent.vue';
import { Link } from '@inertiajs/vue3';

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
      <div class="flex justify-center items-center h-full flex-col p-5 gap-5">
                    <div class="flex justify-center items-center pt-3 pb-3 gap-5 flex-col w-full">
                        <div class="flex flex-row justify-between items- w-full pt-2 pb-2">
                            <h5 class="text-[#275342] text-[20px]">Lemmikute nimekiri</h5>

                            <Link :href="route('pets.index')">
                                <button class="text-[#275342] text-[16px] h-full">
                                    Vaata kõiki
                                </button>
                            </Link>
                        </div>
                        <div class="p-5">
                            <div v-if="pets && pets.length">
                                <PetContent
                                v-for="pet in pets"
                                :key="pet.id"
                                :pet="pet"
                                class="mb-4"
                                />
                            </div>
                            <p v-else>No pets found</p>
                        </div>


                        <Link :href="route('pets.create')">
                        <button class="text-[#275342] text-[16px] h-full">
                            Add Pet
                        </button>
                        </Link>

                    </div>
                </div>
    </template>
  </AuthenticatedLayout>
</template>
