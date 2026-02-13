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
        Add Pet
      </h2>
    </template>

    <div class="p-6">
      <form @submit.prevent="form.post(route('pets.store'))">
        <div class="grid grid-cols-1 gap-4">
          <input v-model="form.name" placeholder="Name" class="border p-2 rounded" />
          <input v-model="form.chip" placeholder="Chip" class="border p-2 rounded" />
          <input v-model="form.species" placeholder="Species" class="border p-2 rounded" />
          <input v-model="form.breed" placeholder="Breed" class="border p-2 rounded" />
          <input v-model="form.gender" placeholder="Gender" class="border p-2 rounded" />
          <input v-model="form.dob" type="date" class="border p-2 rounded" />
          <input v-model="form.weight" placeholder="Weight" class="border p-2 rounded" />
          <input v-model="form.image" placeholder="Image URL" class="border p-2 rounded" />

          <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">
            Save Pet
          </button>
        </div>
      </form>
    </div>
  </AuthenticatedLayout>
</template>
