<script setup>
import { ref, computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { X, Calendar } from '@lucide/vue';
import { Button } from '@/Components/ui/button';

const props = defineProps({
    petId: {
        type: Number,
        required: true,
    },
    petName: {
        type: String,
        default: '',
    },
});

const emit = defineEmits(['close']);

const today = new Date();
const currentMonth = ref(today.getMonth());
const currentYear  = ref(today.getFullYear());
const selectedDate = ref(null);
const showCalendar = ref(true);

const MONTHS = [
    'January','February','March','April','May','June',
    'July','August','September','October','November','December',
];
const DAYS_SHORT = ['S','M','T','W','T','F','S'];

const monthLabel = computed(() => `${MONTHS[currentMonth.value]} ${currentYear.value}`);

const calendarDays = computed(() => {
    const firstDay = new Date(currentYear.value, currentMonth.value, 1).getDay();
    const daysInMonth = new Date(currentYear.value, currentMonth.value + 1, 0).getDate();
    const cells = [];
    for (let i = 0; i < firstDay; i++) cells.push(null);
    for (let d = 1; d <= daysInMonth; d++) cells.push(d);
    return cells;
});

const selectedLabel = computed(() => {
    if (!selectedDate.value) return null;
    return selectedDate.value.toLocaleDateString('en-US', {
        weekday: 'short', month: 'short', day: 'numeric',
    });
});

function prevMonth() {
    if (currentMonth.value === 0) { currentMonth.value = 11; currentYear.value--; }
    else currentMonth.value--;
}

function nextMonth() {
    if (currentMonth.value === 11) { currentMonth.value = 0; currentYear.value++; }
    else currentMonth.value++;
}

function selectDay(day) {
    if (!day) return;
    selectedDate.value = new Date(currentYear.value, currentMonth.value, day);
}

function isToday(day) {
    if (!day) return false;
    return day === today.getDate()
        && currentMonth.value === today.getMonth()
        && currentYear.value  === today.getFullYear();
}

function isSelected(day) {
    if (!day || !selectedDate.value) return false;
    return day === selectedDate.value.getDate()
        && currentMonth.value === selectedDate.value.getMonth()
        && currentYear.value  === selectedDate.value.getFullYear();
}

function clearDate() { selectedDate.value = null; }

const form = useForm({
    clinic_name: '',
    visit_date:  '',
    log:         '',
});

function submit() {
    if (selectedDate.value) {
        const y = selectedDate.value.getFullYear();
        const m = String(selectedDate.value.getMonth() + 1).padStart(2, '0');
        const d = String(selectedDate.value.getDate()).padStart(2, '0');
        form.visit_date = `${y}-${m}-${d}`;
    }

    form.post(route('vet-visits.store', { pet: props.petId }), {
        preserveScroll: true,
        onSuccess: () => emit('close'),
    });
}
</script>

<template>
  <Teleport to="body">
    <div
      class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm px-4 overflow-auto"
      @click.self="emit('close')"
    >
      <div
        class="relative w-full max-w-sm bg-[#F0F4EF] rounded-2xl shadow-2xl max-h-[90vh] overflow-y-auto scrollbar-hide"
      >
                <Button
                    variant="ghost"
                    @click="emit('close')"
                    class="absolute bg-transparent top-2 right-0 text-gray-400 hover:text-gray-700 transition z-10"
                >
                  <X />
                </Button>

        <div class="p-5 pt-12 flex flex-col gap-4">

          <div class="flex items-center justify-between bg-white rounded-xl px-4 py-3 border border-gray-200">
            <span class="text-sm text-gray-700 font-medium">{{ petName || 'Lemmiku nimi' }}</span>
            <button @click="emit('close')" class="text-gray-400 hover:text-gray-600 transition">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M6 18L18 6M6 6l12 12"/>
              </svg>
            </button>
          </div>
          <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-4 pt-3 pb-1">
              <p class="text-xs text-gray-500">Lisa kuupäev</p>
            </div>
            <div class="flex items-center justify-between px-4 pb-3">
              <span class="text-2xl font-semibold text-gray-800 h-12">
                {{ selectedLabel ?? 'Vali kuupäev' }}
              </span>
              <button
                @click="showCalendar = !showCalendar"
                class="text-gray-500 hover:text-[#2D5A3D] transition"
              >
              <Calendar class="w-6 h-6"/>
              </button>
            </div>
            <div v-if="showCalendar" class="border-t border-gray-100 px-4 py-3">
              <div class="flex items-center justify-between mb-3">
                <button
                  @click="showMonthPicker = !showMonthPicker"
                  class="text-sm font-medium text-gray-700 flex items-center gap-1 hover:text-[#2D5A3D] transition"
                >
                  {{ monthLabel }}
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                  </svg>
                </button>
                <div class="flex items-center gap-3">
                  <button @click="prevMonth" class="text-gray-400 hover:text-[#2D5A3D] transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                      viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                  </button>
                  <button @click="nextMonth" class="text-gray-400 hover:text-[#2D5A3D] transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                      viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                  </button>
                </div>
              </div>
              <div class="grid grid-cols-7 mb-1">
                <span
                  v-for="d in DAYS_SHORT" :key="d"
                  class="text-center text-xs text-gray-400 font-medium py-1"
                >{{ d }}</span>
              </div>
              <div class="grid grid-cols-7 gap-y-1">
                <button
                  v-for="(day, idx) in calendarDays"
                  :key="idx"
                  @click="selectDay(day)"
                  :disabled="!day"
                  :class="[
                    'h-8 w-8 mx-auto text-sm rounded-full flex items-center justify-center transition',
                    !day && 'invisible',
                    isSelected(day) && 'bg-[#2D5A3D] text-white font-semibold',
                    isToday(day) && !isSelected(day) && 'border border-[#2D5A3D] text-[#2D5A3D] font-semibold',
                    !isSelected(day) && !isToday(day) && day && 'text-gray-700 hover:bg-gray-100',
                  ]"
                >{{ day }}</button>
              </div>
              <div class="flex justify-between mt-3 pt-3 border-t border-gray-100">
                <button @click="clearDate"
                  class="text-sm text-gray-500 hover:text-gray-800 transition">
                  Clear
                </button>
                <div class="flex gap-4">
                  <button @click="showCalendar = false"
                    class="text-sm text-gray-500 hover:text-gray-800 transition">
                    Tühista
                  </button>
                  <button @click="showCalendar = false"
                    class="text-sm font-semibold text-[#2D5A3D] hover:opacity-80 transition">
                    OK
                  </button>
                </div>
              </div>
            </div>
          </div>
          <input
            v-model="form.clinic_name"
            type="text"
            placeholder="Kliiniku nimi"
            class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#2D5A3D]/40 transition"
          />
          <p v-if="form.errors.clinic_name" class="text-red-500 text-xs -mt-2">
            {{ form.errors.clinic_name }}
          </p>
          <textarea
            v-model="form.log"
            rows="4"
            placeholder="Arstivisiidi logi"
            class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#2D5A3D]/40 transition resize-y max-h-40"
          />
          <p v-if="form.errors.log" class="text-red-500 text-xs -mt-2">
            {{ form.errors.log }}
          </p>
          <Button
            variant="save"
            @click="submit"
            :disabled="form.processing"
            class="w-full h-12 bg-[#2D5A3D] text-[#FFFDF3] font-semibold tracking-widest text-sm py-4 rounded-2xl hover:bg-[#234830] active:scale-[0.98] transition disabled:opacity-60 shadow-sm"
            >
            {{ form.processing ? 'Salvestamine...' : 'LISA SISSEKANNE' }}
          </Button>

        </div>
      </div>
    </div>
  </Teleport>
</template>
