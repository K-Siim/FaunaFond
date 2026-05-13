<script setup>
import { computed } from 'vue'
import { Input } from '@/Components/ui/input'
import { Label } from '@/Components/ui/label'

const props = defineProps({
  modelValue: { type: String, default: '12:00' }
})
const emit = defineEmits(['update:modelValue'])

const hours = computed({
  get: () => props.modelValue.split(':')[0] || '00',
  set: (val) => updateTime(val, minutes.value)
})

const minutes = computed({
  get: () => props.modelValue.split(':')[1] || '00',
  set: (val) => updateTime(hours.value, val)
})

function updateTime(h, m) {
  emit('update:modelValue', `${h}:${m}`)
}

function padField(field) {
  if (field === 'hours') {
    let h = parseInt(hours.value) || 0
    if (h > 23) h = 23
    if (h < 0) h = 0
    updateTime(String(h).padStart(2, '0'), minutes.value)
  } else {
    let m = parseInt(minutes.value) || 0
    if (m > 59) m = 59
    if (m < 0) m = 0
    updateTime(hours.value, String(m).padStart(2, '0'))
  }
}
</script>
<template>
  <div class="flex items-center gap-2">
    <div class="flex flex-col items-center gap-1">
      <Input
        id="hours"
        type="number"
        min="0"
        max="23"
        placeholder="00"
        class="w-14 text-center font-mono bg-white"
        v-model="hours"
        @blur="padField('hours')"
      />
    </div>
    <div class="text-xl font-semibold mt-5 text-muted-foreground">:</div>
    <div class="flex flex-col items-center gap-1">
      <Input
        id="minutes"
        type="number"
        min="0"
        max="59"
        placeholder="00"
        class="w-14 text-center font-mono bg-white"
        v-model="minutes"
        @blur="padField('minutes')"
      />
    </div>
  </div>
</template>


