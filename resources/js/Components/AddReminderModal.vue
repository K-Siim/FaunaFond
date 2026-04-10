<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
  petId: Number,
  show: Boolean
});

const emit = defineEmits(['close']);

const form = useForm({
  pet_id: props.petId,
  type: '',
  title: '',
  date: '',
  time: ''
});

const submit = () => {
  form.post(route('reminders.store', { pet: props.petId }), {
        preserveScroll: true,
            onSuccess: () => {
      emit('close');
      form.reset();
    }
  });
};
</script>

<template>
  <div v-if="show" class="fixed inset-0 bg-black/30 flex justify-center items-center">
    <div class="bg-[#DDE5DC] p-6 rounded-2xl w-full max-w-md">

      <button @click="$emit('close')" class="float-right text-xl">✕</button>

      <h2 class="text-lg font-semibold mb-4">Lisa meeldetuletus</h2>

      <select v-model="form.type" class="w-full mb-3 p-2 rounded">
        <option value="">Tüüp</option>
        <option value="vaktsiin">Vaktsiin</option>
        <option value="ravim">Ravim</option>
        <option value="visiit">Arstivisiit</option>
      </select>

      <input v-model="form.title" placeholder="Nimi" class="w-full mb-3 p-2 rounded" />

      <input type="date" v-model="form.date" class="w-full mb-3 p-2 rounded" />

      <input type="time" v-model="form.time" class="w-full mb-3 p-2 rounded" />

      <button
        @click="submit"
        class="w-full bg-[#275342] text-white py-2 rounded-xl mt-4"
      >
        Lisa meeldetuletus
      </button>

    </div>
  </div>
</template>