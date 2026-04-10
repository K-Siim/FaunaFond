<script setup>
import { ref, computed, watch } from 'vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
  petId: { type: Number, required: true },
  petName: { type: String, default: '' },
  vaccineNames: { type: Array, default: () => [] },
  medicationNames: { type: Array, default: () => [] },
  clinicNames: { type: Array, default: () => [] },
});

const emit = defineEmits(['close']);

const reminderType = ref('vaktsiin');
const selectedName = ref('');
const manualName = ref('');
const useManualName = ref(true);

const today = new Date();
const dateMonth = ref(today.getMonth());
const dateYear = ref(today.getFullYear());
const selectedDate = ref(null);
const showDateCalendar = ref(true);

const timeOfDay = ref('09:00');
const multipleTimes = ref([]);
const showAddTimeForm = ref(false);
const newTime = ref('09:00');

const MONTHS = ['January','February','March','April','May','June','July','August','September','October','November','December'];
const DAYS_SHORT = ['S','M','T','W','T','F','S'];

const dateMonthLabel = computed(() => `${MONTHS[dateMonth.value]} ${dateYear.value}`);

const calendarDays = computed(() => {
  const firstDay = new Date(dateYear.value, dateMonth.value, 1).getDay();
  const daysInMonth = new Date(dateYear.value, dateMonth.value + 1, 0).getDate();
  const cells = [];
  for (let i = 0; i < firstDay; i++) cells.push(null);
  for (let d = 1; d <= daysInMonth; d++) cells.push(d);
  return cells;
});

const dateDisplayLabel = computed(() => {
  if (!selectedDate.value) return null;
  return selectedDate.value.toLocaleDateString('en-US', { 
    weekday: 'short', 
    month: 'short', 
    day: 'numeric' 
  });
});

const nameOptions = computed(() => {
  if (reminderType.value === 'vaktsiin') return props.vaccineNames;
  if (reminderType.value === 'ravim') return props.medicationNames;
  if (reminderType.value === 'arstivisiit') return props.clinicNames;
  return [];
});

const nameLabel = computed(() => {
  if (reminderType.value === 'vaktsiin') return 'Vaktsiini nimi';
  if (reminderType.value === 'ravim') return 'Ravimi nimi';
  return 'Kliiniku nimi';
});

const typeLabel = computed(() => {
  if (reminderType.value === 'vaktsiin') return 'Vaktsiin';
  if (reminderType.value === 'ravim') return 'Ravim';
  return 'Arstivisiit';
});

function prevMonth() {
  if (dateMonth.value === 0) {
    dateMonth.value = 11;
    dateYear.value--;
  } else dateMonth.value--;
}

function nextMonth() {
  if (dateMonth.value === 11) {
    dateMonth.value = 0;
    dateYear.value++;
  } else dateMonth.value++;
}

function selectDay(day) {
  if (!day) return;
  selectedDate.value = new Date(dateYear.value, dateMonth.value, day);
}

function isToday(day) {
  if (!day) return false;
  return day === today.getDate() && dateMonth.value === today.getMonth() && dateYear.value === today.getFullYear();
}

function isSelected(day) {
  if (!day || !selectedDate.value) return false;
  return day === selectedDate.value.getDate() && dateMonth.value === selectedDate.value.getMonth() && dateYear.value === selectedDate.value.getFullYear();
}

function addTime() {
  if (newTime.value && !multipleTimes.value.includes(newTime.value)) {
    multipleTimes.value.push(newTime.value);
    multipleTimes.value.sort();
    newTime.value = '09:00';
    showAddTimeForm.value = false;
  }
}

function removeTime(time) {
  multipleTimes.value = multipleTimes.value.filter(t => t !== time);
}

function formatDate(d) {
  const y = d.getFullYear();
  const m = String(d.getMonth() + 1).padStart(2, '0');
  const day = String(d.getDate()).padStart(2, '0');
  return `${y}-${m}-${day}`;
}

watch(reminderType, () => {
  selectedName.value = '';
  manualName.value = '';
  useManualName.value = false;
  multipleTimes.value = [];
  showAddTimeForm.value = false;
});

watch(nameOptions, (options) => {
  if (options.length && !selectedName.value && !useManualName.value) {
    selectedName.value = options[0];
  }
});

const reminderForm = useForm({
  type: 'vaktsiin',
  name: '',
  date: '',
  time: '09:00',
  times: [],
});

function submit() {
  reminderForm.type = reminderType.value;
  
  if (useManualName.value) {
    reminderForm.name = manualName.value;
  } else {
    reminderForm.name = selectedName.value;
  }

  if (selectedDate.value) {
    reminderForm.date = formatDate(selectedDate.value);
  }

  if (reminderType.value === 'ravim' && multipleTimes.value.length > 0) {
    reminderForm.times = multipleTimes.value;
    reminderForm.time = null;
  } else {
    reminderForm.time = timeOfDay.value;
    reminderForm.times = [];
  }

  reminderForm.post(route('reminders.store', { pet: props.petId }), {
    preserveScroll: true,
    onSuccess: () => emit('close'),
  });
}

const isFormValid = computed(() => {
  const hasDate = selectedDate.value;
  const hasName = (useManualName.value && manualName.value) || (!useManualName.value && selectedName.value);
  const hasTime = reminderType.value !== 'ravim' || multipleTimes.value.length > 0;
  
  return hasDate && hasName && hasTime;
});
</script>

<template>
  <Teleport to="body">
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm px-4" @click.self="emit('close')">
      <div class="relative w-full max-w-sm bg-[#F0F4EF] rounded-2xl shadow-2xl overflow-hidden max-h-[90vh] overflow-y-auto">
        <button @click="emit('close')" class="absolute top-3.5 right-4 text-gray-500 hover:text-gray-800 transition z-10">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>

        <div class="p-5 flex flex-col gap-4 pt-12">
          <!-- Pet Info -->
          <div class="flex items-center justify-between bg-white rounded-xl px-4 py-3 border border-gray-200">
            <span class="text-sm text-gray-700 font-medium">{{ petName || 'Lemmiku nimi' }}</span>
          </div>

          <!-- Reminder Type -->
          <div>
            <label class="text-xs text-gray-600 font-medium mb-1 block">Meeldetuletuse tüüp</label>
            <select v-model="reminderType" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#2D5A3D]/40">
              <option value="vaktsiin">Vaktsiin</option>
              <option value="ravim">Ravim</option>
              <option value="arstivisiit">Arstivisiit</option>
            </select>
          </div>

          <!-- Name Field -->
          <div>
            <div class="flex items-center justify-between mb-1">
              <label class="text-xs text-gray-600 font-medium">{{ nameLabel }}</label>
              <button v-if="nameOptions.length > 0 && useManualName" @click="useManualName = false" class="text-xs text-gray-500 hover:text-gray-700 transition">
                Vali nimekirjast
              </button>
              <button v-if="nameOptions.length > 0 && !useManualName" @click="useManualName = true" class="text-xs text-gray-500 hover:text-gray-700 transition">
                Sisesta uus
              </button>
            </div>

            <select v-if="nameOptions.length > 0 && !useManualName" v-model="selectedName" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#2D5A3D]/40">
              <option value="">Vali nimekirjast</option>
              <option v-for="name in nameOptions" :key="name" :value="name">{{ name }}</option>
            </select>

            <input v-else v-model="manualName" type="text" :placeholder="`Sisesta ${typeLabel.toLowerCase()} nimi`" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#2D5A3D]/40"/>
          </div>

          <!-- Date Picker -->
          <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-4 pt-3 pb-1"><p class="text-xs text-gray-500">Kuupäev</p></div>
            <div class="flex items-center justify-between px-4 pb-3">
              <span class="text-2xl font-semibold text-gray-800">{{ dateDisplayLabel ?? 'Lisa kuupäev' }}</span>
              <button @click="showDateCalendar = !showDateCalendar" class="text-gray-500 hover:text-[#2D5A3D]">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536M9 13l6-6m-6 6l-3 3m3-3h.01"/>
                </svg>
              </button>
            </div>

            <div v-if="showDateCalendar" class="border-t border-gray-100 px-4 py-3">
              <div class="flex items-center justify-between mb-3">
                <span class="text-sm font-medium text-gray-700">{{ dateMonthLabel }}</span>
                <div class="flex gap-3">
                  <button @click="prevMonth" class="text-gray-400 hover:text-[#2D5A3D]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                  </button>
                  <button @click="nextMonth" class="text-gray-400 hover:text-[#2D5A3D]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                  </button>
                </div>
              </div>

              <div class="grid grid-cols-7 mb-1">
                <span v-for="d in DAYS_SHORT" :key="d" class="text-center text-xs text-gray-400 font-medium py-1">{{ d }}</span>
              </div>
              <div class="grid grid-cols-7 gap-y-1">
                <button v-for="(day, idx) in calendarDays" :key="idx" @click="selectDay(day)" :disabled="!day" :class="['h-8 w-8 mx-auto text-sm rounded-full flex items-center justify-center transition', !day && 'invisible', isSelected(day) && 'bg-[#2D5A3D] text-white font-semibold', isToday(day) && !isSelected(day) && 'border border-[#2D5A3D] text-[#2D5A3D] font-semibold', !isSelected(day) && !isToday(day) && day && 'text-gray-700 hover:bg-gray-100']">{{ day }}</button>
              </div>

              <div class="flex justify-between mt-3 pt-3 border-t border-gray-100">
                <button @click="selectedDate = null" class="text-sm text-gray-500 hover:text-gray-800">Tühista</button>
                <button @click="showDateCalendar = false" class="text-sm font-semibold text-[#2D5A3D]">OK</button>
              </div>
            </div>
          </div>

          <!-- Time Picker - Single for Non-Medications -->
          <div v-if="reminderType !== 'ravim'" class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-4 pt-3 pb-1"><p class="text-xs text-gray-500">Kellaaeg</p></div>
            <div class="px-4 py-3">
              <input v-model="timeOfDay" type="time" class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#2D5A3D]/40"/>
            </div>
          </div>

          <!-- Multiple Times for Medications -->
          <div v-else class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-4 pt-3 pb-1"><p class="text-xs text-gray-500">Manustamise kellaajad</p></div>
            
            <!-- List of added times -->
            <div v-if="multipleTimes.length > 0" class="px-4 py-3 space-y-2 border-b border-gray-100">
              <div v-for="time in multipleTimes" :key="time" class="flex items-center justify-between bg-gray-50 rounded-lg px-3 py-2">
                <span class="text-sm font-medium text-gray-700">{{ time }}</span>
                <button @click="removeTime(time)" class="text-gray-400 hover:text-red-500 transition">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                  </svg>
                </button>
              </div>
            </div>

            <!-- Add time form -->
            <div class="px-4 py-3">
              <button v-if="!showAddTimeForm" @click="showAddTimeForm = true" class="w-full text-sm font-medium text-[#2D5A3D] hover:bg-gray-50 py-2 px-3 rounded-lg transition">
                + Lisa kellaaeg
              </button>

              <div v-else class="space-y-2">
                <input v-model="newTime" type="time" class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#2D5A3D]/40"/>
                <div class="flex gap-2">
                  <button @click="addTime" class="flex-1 bg-[#2D5A3D] text-white text-sm font-medium py-2 rounded-lg hover:bg-[#234830] transition">Lisa</button>
                  <button @click="showAddTimeForm = false" class="flex-1 bg-gray-100 text-gray-700 text-sm font-medium py-2 rounded-lg hover:bg-gray-200 transition">Tühista</button>
                </div>
              </div>
            </div>
          </div>

          <!-- Submit Button -->
          <button @click="submit" :disabled="!isFormValid || reminderForm.processing" class="w-full bg-[#2D5A3D] text-white font-semibold tracking-widest text-sm py-4 rounded-xl hover:bg-[#234830] active:scale-[0.98] transition disabled:opacity-60">
            {{ reminderForm.processing ? 'Salvestamine...' : 'SALVESTA MEELDETULETUS' }}
          </button>
        </div>
      </div>
    </div>
  </Teleport>
</template>