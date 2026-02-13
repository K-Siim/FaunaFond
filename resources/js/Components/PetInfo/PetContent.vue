<template>
  <div class="bg-[#FFFDF5] w-full h-full p-5 rounded-lg flex flex-row items-center">
    <div class="w-1/3 flex justify-center">
      <img
        v-if="pet?.image"
        :src="pet.image"
        alt="Pet Image"
        class="w-28 h-28 rounded-2xl object-cover"
      />
      <div
        v-else
        class="w-24 h-24 rounded-2xl bg-gray-200 flex items-center justify-center"
      >
        No Image
      </div>
    </div>

    <div class="w-2/3 pl-6 flex flex-col gap-2 text-[#275342]">
      <h2 class="text-xl font-semibold">{{ pet?.name }}</h2>
      <p class="text-md">{{ pet?.species }}</p>
      <p class="text-md">
        {{ formatDate(pet?.dob) }} ({{ calculateAge(pet?.dob) }})
      </p>
      <p class="text-md">{{ pet?.weight }} kg</p>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    pet: {
      type: Object,
      required: true,
    },
  },
  methods: {
    formatDate(date) {
      if (!date) return ''
      const d = new Date(date)
      return `${String(d.getDate()).padStart(2, '0')}.${String(
        d.getMonth() + 1
      ).padStart(2, '0')}.${d.getFullYear()}`
    },
    calculateAge(date) {
      if (!date) return ''
      const dob = new Date(date)
      const now = new Date()
      let age = now.getFullYear() - dob.getFullYear()
      if (
        now.getMonth() < dob.getMonth() ||
        (now.getMonth() === dob.getMonth() && now.getDate() < dob.getDate())
      ) {
        age--
      }
      return age
    },
  },
}
</script>
