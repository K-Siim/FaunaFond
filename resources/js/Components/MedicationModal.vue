<script setup>
import { ref, computed } from 'vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    petId: { type: Number, required: true },
    petName: { type: String, default: '' },
});

const emit = defineEmits(['close']);

const today = new Date();
const startMonth = ref(today.getMonth());
const startYear = ref(today.getFullYear());
const endMonth = ref(today.getMonth());
const endYear = ref(today.getFullYear());
const startDate = ref(null);
const endDate = ref(null);
const showStartCalendar = ref(true);
const showEndCalendar = ref(true);

const MONTHS = ['January','February','March','April','May','June','July','August','September','October','November','December'];
const DAYS_SHORT = ['S','M','T','W','T','F','S'];

const startMonthLabel = computed(() => `${MONTHS[startMonth.value]} ${startYear.value}`);
const startCalendarDays = computed(() => {
    const firstDay = new Date(startYear.value, startMonth.value, 1).getDay();
    const daysInMonth = new Date(startYear.value, startMonth.value + 1, 0).getDate();
    const cells = [];
    for (let i = 0; i < firstDay; i++) cells.push(null);
    for (let d = 1; d <= daysInMonth; d++) cells.push(d);
    return cells;
});

const startLabel = computed(() => {
    if (!startDate.value) return null;
    return startDate.value.toLocaleDateString('en-US', { weekday: 'short', month: 'short', day: 'numeric' });
});

function prevStartMonth() {
    if (startMonth.value === 0) { startMonth.value = 11; startYear.value--; }
    else startMonth.value--;
}

function nextStartMonth() {
    if (startMonth.value === 11) { startMonth.value = 0; startYear.value++; }
    else startMonth.value++;
}

function selectStartDay(day) {
    if (!day) return;
    startDate.value = new Date(startYear.value, startMonth.value, day);
}

function isStartToday(day) {
    if (!day) return false;
    return day === today.getDate() && startMonth.value === today.getMonth() && startYear.value === today.getFullYear();
}

function isStartSelected(day) {
    if (!day || !startDate.value) return false;
    return day === startDate.value.getDate() && startMonth.value === startDate.value.getMonth() && startYear.value === startDate.value.getFullYear();
}

const endMonthLabel = computed(() => `${MONTHS[endMonth.value]} ${endYear.value}`);
const endCalendarDays = computed(() => {
    const firstDay = new Date(endYear.value, endMonth.value, 1).getDay();
    const daysInMonth = new Date(endYear.value, endMonth.value + 1, 0).getDate();
    const cells = [];
    for (let i = 0; i < firstDay; i++) cells.push(null);
    for (let d = 1; d <= daysInMonth; d++) cells.push(d);
    return cells;
});

const endLabel = computed(() => {
    if (!endDate.value) return null;
    return endDate.value.toLocaleDateString('en-US', { weekday: 'short', month: 'short', day: 'numeric' });
});

function prevEndMonth() {
    if (endMonth.value === 0) { endMonth.value = 11; endYear.value--; }
    else endMonth.value--;
}

function nextEndMonth() {
    if (endMonth.value === 11) { endMonth.value = 0; endYear.value++; }
    else endMonth.value++;
}

function selectEndDay(day) {
    if (!day) return;
    endDate.value = new Date(endYear.value, endMonth.value, day);
}

function isEndToday(day) {
    if (!day) return false;
    return day === today.getDate() && endMonth.value === today.getMonth() && endYear.value === today.getFullYear();
}

function isEndSelected(day) {
    if (!day || !endDate.value) return false;
    return day === endDate.value.getDate() && endMonth.value === endDate.value.getMonth() && endYear.value === endDate.value.getFullYear();
}

const form = useForm({
    name: '',
    dose_amount: '',
    dose_unit: 'ml',
    frequency_per_day: '',
    start_date: '',
    end_date: '',
});

function submit() {
    if (startDate.value) {
        const y = startDate.value.getFullYear();
        const m = String(startDate.value.getMonth() + 1).padStart(2, '0');
        const d = String(startDate.value.getDate()).padStart(2, '0');
        form.start_date = `${y}-${m}-${d}`;
    }
    if (endDate.value) {
        const y = endDate.value.getFullYear();
        const m = String(endDate.value.getMonth() + 1).padStart(2, '0');
        const d = String(endDate.value.getDate()).padStart(2, '0');
        form.end_date = `${y}-${m}-${d}`;
    }

    form.post(route('medications.store', { pet: props.petId }), {
        preserveScroll: true,
        onSuccess: () => emit('close'),
    });
}
</script>

<template>
    <Teleport to="body">
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm px-4" @click.self="emit('close')">
            <div class="relative w-full max-w-sm bg-[#F0F4EF] rounded-2xl shadow-2xl overflow-hidden max-h-[90vh] overflow-y-auto">

                <!-- Close button -->
                <button @click="emit('close')" class="absolute top-4 right-4 text-gray-500 hover:text-gray-800 transition z-10">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>

                <div class="p-5 flex flex-col gap-4">

                    <!-- Pet name -->
                    <div class="flex items-center justify-between bg-white rounded-xl px-4 py-3 border border-gray-200">
                        <span class="text-sm text-gray-700 font-medium">{{ petName || 'Lemmiku nimi' }}</span>
                    </div>

                    <!-- Treatment type dropdown (static for now - always Ravim) -->
                    <select class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#2D5A3D]/40">
                        <option>Vaktsiin/Ravim</option>
                    </select>

                    <!-- Medication name -->
                    <input v-model="form.name" type="text" placeholder="Ravimi nimi" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#2D5A3D]/40"/>
                    <p v-if="form.errors.name" class="text-red-500 text-xs -mt-2">{{ form.errors.name }}</p>

                    <!-- Dose amount + unit -->
                    <div class="flex gap-3">
                        <input v-model="form.dose_amount" type="number" step="0.01" placeholder="Annus" class="flex-1 bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#2D5A3D]/40"/>
                        <select v-model="form.dose_unit" class="w-28 bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#2D5A3D]/40">
                            <option value="ml">ml</option>
                            <option value="mg">mg</option>
                            <option value="g">g</option>
                            <option value="tablet">tablet</option>
                        </select>
                    </div>
                    <p v-if="form.errors.dose_amount" class="text-red-500 text-xs -mt-2">{{ form.errors.dose_amount }}</p>

                    <!-- Frequency per day -->
                    <input v-model="form.frequency_per_day" type="number" min="1" placeholder="Mitu korda päevas" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#2D5A3D]/40"/>
                    <p v-if="form.errors.frequency_per_day" class="text-red-500 text-xs -mt-2">{{ form.errors.frequency_per_day }}</p>

                    <!-- Start date picker -->
                    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                        <div class="px-4 pt-3 pb-1"><p class="text-xs text-gray-500">Alustamise kuupäev</p></div>
                        <div class="flex items-center justify-between px-4 pb-3">
                            <span class="text-2xl font-semibold text-gray-800">{{ startLabel ?? 'Lisa kuupäev' }}</span>
                            <button @click="showStartCalendar = !showStartCalendar" class="text-gray-500 hover:text-[#2D5A3D]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536M9 13l6-6m-6 6l-3 3m3-3h.01"/>
                                </svg>
                            </button>
                        </div>

                        <div v-if="showStartCalendar" class="border-t border-gray-100 px-4 py-3">
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-sm font-medium text-gray-700">{{ startMonthLabel }}</span>
                                <div class="flex gap-3">
                                    <button @click="prevStartMonth" class="text-gray-400 hover:text-[#2D5A3D]">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                                    </button>
                                    <button @click="nextStartMonth" class="text-gray-400 hover:text-[#2D5A3D]">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                    </button>
                                </div>
                            </div>
                            <div class="grid grid-cols-7 mb-1">
                                <span v-for="d in DAYS_SHORT" :key="d" class="text-center text-xs text-gray-400 font-medium py-1">{{ d }}</span>
                            </div>
                            <div class="grid grid-cols-7 gap-y-1">
                                <button v-for="(day, idx) in startCalendarDays" :key="idx" @click="selectStartDay(day)" :disabled="!day"
                                    :class="['h-8 w-8 mx-auto text-sm rounded-full flex items-center justify-center transition',
                                        !day && 'invisible',
                                        isStartSelected(day) && 'bg-[#2D5A3D] text-white font-semibold',
                                        isStartToday(day) && !isStartSelected(day) && 'border border-[#2D5A3D] text-[#2D5A3D] font-semibold',
                                        !isStartSelected(day) && !isStartToday(day) && day && 'text-gray-700 hover:bg-gray-100']">{{ day }}</button>
                            </div>
                            <div class="flex justify-between mt-3 pt-3 border-t border-gray-100">
                                <button @click="startDate = null" class="text-sm text-gray-500 hover:text-gray-800">Clear</button>
                                <button @click="showStartCalendar = false" class="text-sm font-semibold text-[#2D5A3D]">OK</button>
                            </div>
                        </div>
                    </div>

                    <!-- End date picker (optional) -->
                    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                        <div class="px-4 pt-3 pb-1"><p class="text-xs text-gray-500">Lõpetamise kuupäev (valikuline)</p></div>
                        <div class="flex items-center justify-between px-4 pb-3">
                            <span class="text-2xl font-semibold text-gray-800">{{ endLabel ?? 'Lisa kuupäev' }}</span>
                            <button @click="showEndCalendar = !showEndCalendar" class="text-gray-500 hover:text-[#2D5A3D]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536M9 13l6-6m-6 6l-3 3m3-3h.01"/>
                                </svg>
                            </button>
                        </div>

                        <div v-if="showEndCalendar" class="border-t border-gray-100 px-4 py-3">
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-sm font-medium text-gray-700">{{ endMonthLabel }}</span>
                                <div class="flex gap-3">
                                    <button @click="prevEndMonth" class="text-gray-400 hover:text-[#2D5A3D]">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                                    </button>
                                    <button @click="nextEndMonth" class="text-gray-400 hover:text-[#2D5A3D]">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                    </button>
                                </div>
                            </div>
                            <div class="grid grid-cols-7 mb-1">
                                <span v-for="d in DAYS_SHORT" :key="d" class="text-center text-xs text-gray-400 font-medium py-1">{{ d }}</span>
                            </div>
                            <div class="grid grid-cols-7 gap-y-1">
                                <button v-for="(day, idx) in endCalendarDays" :key="idx" @click="selectEndDay(day)" :disabled="!day"
                                    :class="['h-8 w-8 mx-auto text-sm rounded-full flex items-center justify-center transition',
                                        !day && 'invisible',
                                        isEndSelected(day) && 'bg-[#2D5A3D] text-white font-semibold',
                                        isEndToday(day) && !isEndSelected(day) && 'border border-[#2D5A3D] text-[#2D5A3D] font-semibold',
                                        !isEndSelected(day) && !isEndToday(day) && day && 'text-gray-700 hover:bg-gray-100']">{{ day }}</button>
                            </div>
                            <div class="flex justify-between mt-3 pt-3 border-t border-gray-100">
                                <button @click="endDate = null" class="text-sm text-gray-500 hover:text-gray-800">Clear</button>
                                <button @click="showEndCalendar = false" class="text-sm font-semibold text-[#2D5A3D]">OK</button>
                            </div>
                        </div>
                    </div>

                    <!-- Submit -->
                    <button @click="submit" :disabled="form.processing" class="w-full bg-[#2D5A3D] text-white font-semibold tracking-widest text-sm py-4 rounded-xl hover:bg-[#234830] active:scale-[0.98] transition disabled:opacity-60">
                        {{ form.processing ? 'Salvestamine...' : 'SALVESTA' }}
                    </button>

                </div>
            </div>
        </div>
    </Teleport>
</template>