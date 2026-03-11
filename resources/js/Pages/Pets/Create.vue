<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { useForm } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';


const form = useForm({
  name: '',
  chip: '',
  species: '',
  breed: '',
  gender: '',
  dob: '',
  weight: '',
  image: '',
});
</script>

<template>
  <Head title="Add Pet" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        Lisa uus lemmik
      </h2>
    </template>

    <div class="p-6 justify-center items-center w-full">
      <form @submit.prevent="form.post(route('pets.store'))">
        <div class="grid grid-cols-1 gap-4 w-full max-w-md mx-auto">
          <input v-model="form.name" placeholder="Nimi" class="border p-2 rounded" />
          <input v-model="form.chip" placeholder="Kiip" class="border p-2 rounded" />
          <input v-model="form.species" placeholder="Liik" class="border p-2 rounded" />
          <input v-model="form.breed" placeholder="Tõug" class="border p-2 rounded" />
          <input v-model="form.gender" placeholder="Sugu" class="border p-2 rounded" />
          <input v-model="form.dob" type="date" class="border p-2 rounded" />
          <input v-model="form.weight" placeholder="Kaal" class="border p-2 rounded" />
          <input v-model="form.image" placeholder="Pilt" class="border p-2 rounded" />

          <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">
            Salvesta
          </button>
        </div>
      </form>
    </div>
  </AuthenticatedLayout>
</template>
