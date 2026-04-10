<script setup>
import { ref, onMounted } from 'vue';

defineProps({
  message: String,
  type: String
});

const isVisible = ref(false);

const hideMessage = () => {
  isVisible.value = false;
}

onMounted(() => {
  isVisible.value = true;

  setTimeout(() => {
    isVisible.value = false;
  }, 4000);
});
</script>

<template>
  <div v-if="isVisible" :class="[
    'px-4 py-3 rounded-xl text-white shadow flex justify-between items-center',
    {
      'bg-green-500': type === 'success',
      'bg-red-500': type === 'error',
      'bg-yellow-400': type === 'warning'
    }
  ]">
    <span>{{ message }}</span>
    <button @click="hideMessage">&times;</button>
  </div>
</template>