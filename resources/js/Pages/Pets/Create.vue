<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import { Button } from '@/Components/ui/button';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue,} from '@/Components/ui/select';

const form = useForm({
  name: '',
  chip: '',
  species: '',
  breed: '',
  gender: '',
  dob: '',
  weight: '',
  image: null,
});

const preview = ref(null);

function capitalize(value) {
  if (!value) return '';
  return value.charAt(0).toUpperCase() + value.slice(1).toLowerCase();
}

function handleImage(e) {
  const file = e.target.files[0];
  if (!file) return;

  form.image = file;
  preview.value = URL.createObjectURL(file);
}

function submit() {
  form.post(route('pets.store'), {
    forceFormData: true,
  });
}
</script>

<template>
  <Head title="Lisa lemmik" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold text-gray-800">
        Lisa uus lemmik
      </h2>
    </template>

    <div class="flex justify-center p-6">
      <div class="w-full max-w-md">
        <div class="p-6 space-y-6 bg-transparent">

          <form @submit.prevent="submit" class="space-y-6">

            <div class="space-y-2">
              <Input
                v-model="form.name"
                @input="form.name = capitalize(form.name)"
                placeholder="Nimi"
              />
              <p v-if="form.errors.name" class="text-sm text-red-500">
                {{ form.errors.name }}
              </p>
            </div>

            <div class="space-y-2">
              <Input
                v-model="form.chip"
                type="number"
                placeholder="Kiip"
              />
              <p v-if="form.errors.chip" class="text-sm text-red-500">
                {{ form.errors.chip }}
              </p>
            </div>

            <div class="space-y-2">
              <Input
                v-model="form.species"
                @input="form.species = capitalize(form.species)"
                placeholder="Liik"
              />
              <p v-if="form.errors.species" class="text-sm text-red-500">
                {{ form.errors.species }}
              </p>
            </div>

            <div class="space-y-2">
              <Input
                v-model="form.breed"
                @input="form.breed = capitalize(form.breed)"
                placeholder="Tõug"
              />
            </div>

            <div class="space-y-2">
              <Select v-model="form.gender">
                <SelectTrigger>
                  <SelectValue placeholder="Vali sugu" />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem value="isane">Isane</SelectItem>
                  <SelectItem value="emane">Emane</SelectItem>
                </SelectContent>
              </Select>
              <p v-if="form.errors.gender" class="text-sm text-red-500">
                {{ form.errors.gender }}
              </p>
            </div>

            <div class="space-y-2">
              <Input
                v-model="form.dob"
                type="date"
              />
              <p v-if="form.errors.dob" class="text-sm text-red-500">
                {{ form.errors.dob }}
              </p>
            </div>

            <div class="space-y-2">
              <Input
                v-model="form.weight"
                type="number"
                step="0.1"
                placeholder="Kaal (kg)"
              />
            </div>

            <div class="space-y-2">
              <Input
                type="file"
                accept="image/*"
                @change="handleImage"
              />

              <div v-if="preview">
                <img
                  :src="preview"
                  class="w-32 h-32 object-cover rounded-xl border mt-2"
                />
              </div>

              <p v-if="form.errors.image" class="text-sm text-red-500">
                {{ form.errors.image }}
              </p>
            </div>

            <Button
              type="submit"
              class="w-full"
              :disabled="form.processing"
            >
              Salvesta lemmik
            </Button>

          </form>

        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
