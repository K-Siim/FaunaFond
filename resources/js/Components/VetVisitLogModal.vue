<script setup>
import { ref, computed } from 'vue'
import { useForm } from '@inertiajs/vue3'

import { X, Calendar, ChevronLeft, ChevronRight, ChevronDown, } from 'lucide-vue-next'
import { Button } from '@/Components/ui/button'
import { Input } from '@/Components/ui/input'
import { Textarea } from '@/Components/ui/textarea'
import { Card, CardContent, } from '@/Components/ui/card'
import { Separator } from '@/Components/ui/separator'
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue, } from '@/Components/ui/select'
import { Label } from '@/Components/ui/label'

const props = defineProps({
  petId: {
    type: Number,
    required: true,
  },
  petName: {
    type: String,
    default: '',
  },
})

const emit = defineEmits(['close'])

const today = new Date()

const currentMonth = ref(today.getMonth())
const currentYear = ref(today.getFullYear())

const selectedDate = ref(null)
const showCalendar = ref(true)

const MONTHS = ['Jaanuar', 'Veebruar', 'Märts', 'Aprill', 'Mai', 'Juuni', 'Juuli', 'August', 'September', 'Oktoober', 'November', 'Detsember']
const DAYS_SHORT = ['E', 'T', 'K', 'N', 'R', 'L', 'P']
const YEARS = Array.from(
  { length: 20 },
  (_, i) => new Date().getFullYear() - 10 + i
)

const monthLabel = computed(
  () => `${MONTHS[currentMonth.value]} ${currentYear.value}`
)

const calendarDays = computed(() => {
  const firstDay = new Date(
    currentYear.value,
    currentMonth.value,
    1
  ).getDay()

  const daysInMonth = new Date(
    currentYear.value,
    currentMonth.value + 1,
    0
  ).getDate()

  const cells = []

  for (let i = 0; i < firstDay; i++) {
    cells.push(null)
  }

  for (let d = 1; d <= daysInMonth; d++) {
    cells.push(d)
  }

  return cells
})

const selectedLabel = computed(() => {
    if (!selectedDate.value) return null
    return selectedDate.value.toLocaleDateString('et-EE', { weekday: 'short', month: 'short', day: 'numeric' })
})

function prevMonth() {
  if (currentMonth.value === 0) {
    currentMonth.value = 11
    currentYear.value--
  } else {
    currentMonth.value--
  }
}

function nextMonth() {
  if (currentMonth.value === 11) {
    currentMonth.value = 0
    currentYear.value++
  } else {
    currentMonth.value++
  }
}

function selectDay(day) {
  if (!day) return

  selectedDate.value = new Date(
    currentYear.value,
    currentMonth.value,
    day
  )
}

function isToday(day) {
  if (!day) return false

  return (
    day === today.getDate() &&
    currentMonth.value === today.getMonth() &&
    currentYear.value === today.getFullYear()
  )
}

function isSelected(day) {
  if (!day || !selectedDate.value) return false

  return (
    day === selectedDate.value.getDate() &&
    currentMonth.value === selectedDate.value.getMonth() &&
    currentYear.value === selectedDate.value.getFullYear()
  )
}

function clearDate() {
  selectedDate.value = null
}

const form = useForm({
  clinic_name: '',
  visit_date: '',
  log: '',
})

function submit() {
  if (selectedDate.value) {
    const y = selectedDate.value.getFullYear()
    const m = String(
      selectedDate.value.getMonth() + 1
    ).padStart(2, '0')

    const d = String(
      selectedDate.value.getDate()
    ).padStart(2, '0')

    form.visit_date = `${y}-${m}-${d}`
  }

  form.post(route('vet-visits.store', { pet: props.petId }), {
    preserveScroll: true,
    onSuccess: () => emit('close'),
  })
}
</script>
<template>
  <Teleport to="body">
    <div
      class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm px-4 overflow-auto"
      @click.self="emit('close')"
    >
      <Card
        class="relative w-full max-w-sm bg-[#F0F4EF] rounded-2xl shadow-2xl max-h-[90vh] overflow-y-auto border-none"
      >
        <Button
          variant="ghost"
          size="icon"
          @click="emit('close')"
          class="absolute top-2 right-2 z-10 text-gray-400 hover:text-gray-700"
        >
          <X class="w-5 h-5" />
        </Button>
        <CardContent class="p-5 pt-12 flex flex-col gap-4">
          <div
            class="flex items-center justify-between bg-white rounded-xl px-4 py-3 border border-gray-200"
          >
            <span class="text-sm text-gray-700 font-medium">
              {{ petName || 'Lemmiku nimi' }}
            </span>
          </div>
          <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
              <div class="px-4 pt-4 pb-2">
                  <Label>Lisa kuupäev</Label>
              </div>
              <div class="flex items-center justify-between px-4 pb-4">
                  <span class="text-lg font-semibold text-gray-800">
                      {{ selectedLabel ?? 'Vali kuupäev' }}
                  </span>
                  <Button
                      variant="calendar"
                      @click="showCalendar = !showCalendar"
                      class="rounded-xl p-0 m-0 border border-gray-200 flex items-center justify-center transition"
                  >
                      <Calendar class="w-5 h-5 p-0 m-0" />
                  </Button>
              </div>
              <div
                  v-if="showCalendar"
                  class="border-t border-gray-100 px-4 py-4"
              >
                  <div class="flex items-center gap-2 mb-4">
                      <Select v-model="currentMonth">
                          <SelectTrigger
                              class="flex-1 h-11 rounded-xl border-gray-200 bg-white"
                          >
                              <SelectValue />
                          </SelectTrigger>

                          <SelectContent class="rounded-xl border-gray-200">
                              <SelectItem
                                  v-for="(month, idx) in MONTHS"
                                  :key="month"
                                  :value="idx"
                              >
                                  {{ month }}
                              </SelectItem>
                          </SelectContent>
                      </Select>

                      <Select v-model="currentYear">
                          <SelectTrigger
                              class="w-28 h-11 rounded-xl border-gray-200 bg-white"
                          >
                              <SelectValue />
                          </SelectTrigger>

                          <SelectContent
                              class="rounded-xl border-gray-200 max-h-64"
                          >
                              <SelectItem
                                  v-for="year in YEARS"
                                  :key="year"
                                  :value="year"
                              >
                                  {{ year }}
                              </SelectItem>
                          </SelectContent>
                      </Select>
                  </div>

                  <div class="grid grid-cols-7 mb-2">
                      <span
                          v-for="d in DAYS_SHORT"
                          :key="d"
                          class="text-center text-xs text-gray-400 font-medium py-1"
                      >
                          {{ d }}
                      </span>
                  </div>

                  <div class="grid grid-cols-7 gap-y-2">
                      <Button
                          variant="calendar"
                          v-for="(day, idx) in calendarDays"
                          :key="idx"
                          @click="selectDay(day)"
                          :disabled="!day"
                          :class="[
                              'h-9 w-9 m-0 p-0 mx-auto text-sm rounded-xl flex items-center justify-center transition',
                              !day && 'invisible',
                              isSelected(day) &&
                                  'bg-[#2D5A3D] text-white font-semibold shadow-sm',
                              isToday(day) &&
                                  !isSelected(day) &&
                                  'border border-[#2D5A3D] text-[#2D5A3D] font-semibold',
                              !isSelected(day) &&
                                  !isToday(day) &&
                                  day &&
                                  'text-gray-700 hover:bg-gray-100',
                          ]"
                      >
                          {{ day }}
                      </Button>
                  </div>

                  <div class="flex justify-between mt-4 pt-4 border-t border-gray-100">
                      <Button
                          variant="ghost"
                          @click="clearDate"
                          class="text-sm text-gray-500 hover:text-gray-800 px-0"
                      >
                          Tühjenda
                      </Button>

                      <div class="flex gap-2">
                          <Button
                              variant="ghost"
                              @click="showCalendar = false"
                              class="text-sm font-semibold text-[#2D5A3D]"
                          >
                              OK
                          </Button>
                      </div>
                  </div>
              </div>
          </div>
          <Input
            v-model="form.clinic_name"
            type="text"
            placeholder="Kliiniku nimi"
            class="h-12 rounded-xl border-gray-200 bg-white text-sm focus-visible:ring-[#2D5A3D]/40"
          />
          <p
            v-if="form.errors.clinic_name"
            class="text-red-500 text-xs -mt-2"
          >
            {{ form.errors.clinic_name }}
          </p>
          <Textarea
            v-model="form.log"
            rows="4"
            placeholder="Arstivisiidi logi"
            class="rounded-xl border-gray-200 bg-white text-sm resize-y max-h-40 focus-visible:ring-[#2D5A3D]/40"
          />
          <p
            v-if="form.errors.log"
            class="text-red-500 text-xs -mt-2"
          >
            {{ form.errors.log }}
          </p>
          <Button
            variant="default"
            @click="submit"
            :disabled="form.processing"
            class="w-full h-12 bg-[#2D5A3D] text-[#FFFDF3] font-semibold tracking-widest text-sm rounded-2xl hover:bg-[#234830] active:scale-[0.98] transition disabled:opacity-60 shadow-sm"
          >
            {{
              form.processing
                ? 'Salvestamine...'
                : 'LISA SISSEKANNE'
            }}
          </Button>
        </CardContent>
      </Card>
    </div>
  </Teleport>
</template>