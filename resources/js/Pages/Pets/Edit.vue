<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Input } from '@/Components/ui/input';
import {Textarea} from '@/Components/ui/textarea';
import { Label } from '@/Components/ui/label';
import { Button } from '@/Components/ui/button';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue,} from '@/Components/ui/select';

const props = defineProps({
    pet: Object
});

const form = useForm({
    name: props.pet.name || '',
    chip: props.pet.chip || '',
    species: props.pet.species || '',
    breed: props.pet.breed || '',
    gender: props.pet.gender || '',
    weight: props.pet.weight || '',
    dob: props.pet.dob || '',
    description: props.pet.description || '',
    image: null,
});

const preview = ref(props.pet.photo_url?.thumb || null);

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
  form.transform((data) => ({
    ...data,
    _method: 'PUT',
  })).post(`/pets/${props.pet.id}`, {
    forceFormData: true,
    onSuccess: () => {
      window.location.href = `/pets/${props.pet.id}`;
    },
  });
}
</script>

<template>
  <Head title="Uuenda oma lemmikut" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold text-gray-800">
        Uuenda oma lemmikut
      </h2>
    </template>

    <div class="flex justify-center p-6">
      <div class="w-full max-w-md">
        <div class="space-y-6 bg-transparent">
          <form @submit.prevent="submit" class="space-y-6">
            <div class="space-y-2">
              <Label class="font-medium text-lg" style="color: #275342">Foto</Label>
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
            <div class="space-y-2">
              <Label class="font-medium text-lg" style="color: #275342">Nimi</Label>
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
              <Label class="font-medium text-lg" style="color: #275342">Kiip</Label>
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
              <Label class="font-medium text-lg" style="color: #275342">Liik</Label>
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
              <Label class="font-medium text-lg" style="color: #275342">Tõug</Label>
              <Input
                v-model="form.breed"
                @input="form.breed = capitalize(form.breed)"
                placeholder="Tõug"
              />
            </div>
            <div class="space-y-2">
              <Label class="font-medium text-lg" style="color: #275342">Sugu</Label>
              <Select v-model="form.gender">
                <SelectTrigger class="h-12 rounded-xl border border-input bg-[#FFFDF3] px-3 py-1 text-sm shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50">
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
              <Label class="font-medium text-lg" style="color: #275342">Sünniaeg</Label>
              <Input
                v-model="form.dob"
                type="date"
              />
              <p v-if="form.errors.dob" class="text-sm text-red-500">
                {{ form.errors.dob }}
              </p>
            </div>
            <div class="space-y-2">
              <Label class="font-medium text-lg" style="color: #275342">Kaal (kg)</Label>
              <Input
                v-model="form.weight"
                type="number"
                step="0.1"
                placeholder="Kaal (kg)"
              />
            </div>
            <div class="space-y-2">
              <Label class="font-medium text-lg" style="color: #275342">Kirjeldus</Label>
              <Textarea
                v-model="form.description"
                placeholder="Kirjeldus"
                class="h-24 rounded-xl border border-input bg-[#FFFDF3] px-3 py-1 text-sm shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50">
              </Textarea>
            </div>

            <Button
              type="submit"
              variant="save"
              :disabled="form.processing"
              class="w-full h-11 mt-4 "
            >
              UUENDA LEMMIKUT
            </Button>

          </form>

        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>