<script setup>
import logo from '../images/Logo.jpg'
</script>

<template>
  <div
    class="min-h-screen flex items-center justify-center bg-[#E7F0E4]"
  >
    <div class="text-center px-6">
      <img
        :src="logo"
        alt="FaunaFond logo"
        class="mx-auto mb-8 w-40 h-auto"
      />
      <h1
        class="text-[#000000] text-2xl font-medium mb-8"
      >
        Lae FaunaFond enda telefoni siit!
      </h1>

      <button
        class="border-2 border-[rgba(255,253,243,1)]
               bg-[rgba(39,83,66,1)]
               text-[rgba(255,253,243,1)]
               px-8 py-4 rounded-xl
               text-base font-semibold
               hover:opacity-90
               active:scale-95
               transition"
      >
        Laadi rakendus alla
      </button>
    </div>
  </div>
</template>

