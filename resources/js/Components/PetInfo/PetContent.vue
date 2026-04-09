<template>
  <Link :href="route('pets.show', pet.id)" class="block">
    <div class="bg-[#FFFDF5] w-full h-full p-5 rounded-lg flex flex-row items-center hover:shadow-md transition">
      
      <div class="w-1/3 h-fit flex justify-center">
        <img
          v-if="pet?.photo_url?.thumb"
          :src="pet.photo_url.thumb"
          alt="Pet Image"
          class="w-28 h-28 rounded-2xl object-cover"
        />
        <div
          v-else
          class="w-28 h-28 rounded-2xl bg-gray-200 flex items-center justify-center"
        >
          No Image
        </div>
      </div>
      
      <div class="w-2/3 pl-6 flex flex-col gap-2 text-[#275342]">
        <h2 class="text-xl font-semibold">{{ pet?.name }}</h2>
        <p class="text-lg">{{ pet?.species }}</p>
        <p class="text-lg">
          {{ pet.formatted_dob }} ({{ pet.age }})
        </p>
        <p class="text-lg">{{ pet?.weight }} kg</p>
      </div>

    </div>
  </Link>
</template>

<script>
import { Link } from '@inertiajs/vue3'
export default {
    components: { Link },
    props: {
        pet: {
            type: Object,
            required: true,
        },
    },
};
</script>