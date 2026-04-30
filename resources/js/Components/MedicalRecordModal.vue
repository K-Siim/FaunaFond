<script setup>
import { ref, computed, watch } from 'vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    petId: { type: Number, required: true },
    petName: { type: String, default: '' },
});

const emit = defineEmits(['close']);

const treatmentType = ref('vaktsiin');

const today = new Date();
const date1Month = ref(today.getMonth());
const date1Year = ref(today.getFullYear());
const date2Month = ref(today.getMonth());
const date2Year = ref(today.getFullYear());
const date1 = ref(null);
const date2 = ref(null);
const showDate1Calendar = ref(true);
const showDate2Calendar = ref(true);

const MONTHS = ['January','February','March','April','May','June','July','August','September','October','November','December'];
const DAYS_SHORT = ['S','M','T','W','T','F','S'];

const date1Label = computed(() => treatmentType.value === 'vaktsiin' ? 'Andmise kuupäev' : 'Alustamise kuupäev');
const date2Label = computed(() => treatmentType.value === 'vaktsiin' ? 'Lõppemise kuupäev' : 'Lõpetamise kuupäev (valikuline)');
const nameLabel = computed(() => treatmentType.value === 'vaktsiin' ? 'Vaktsiini nimi' : 'Ravimi nimi');

const date1MonthLabel = computed(() => `${MONTHS[date1Month.value]} ${date1Year.value}`);
const date1CalendarDays = computed(() => {
    const firstDay = new Date(date1Year.value, date1Month.value, 1).getDay();
    const daysInMonth = new Date(date1Year.value, date1Month.value + 1, 0).getDate();
    const cells = [];
    for (let i = 0; i < firstDay; i++) cells.push(null);
    for (let d = 1; d <= daysInMonth; d++) cells.push(d);
    return cells;
});

const date1DisplayLabel = computed(() => {
    if (!date1.value) return null;
    return date1.value.toLocaleDateString('en-US', { weekday: 'short', month: 'short', day: 'numeric' });
});

function prevDate1Month() {
    if (date1Month.value === 0) { date1Month.value = 11; date1Year.value--; }
    else date1Month.value--;
}

function nextDate1Month() {
    if (date1Month.value === 11) { date1Month.value = 0; date1Year.value++; }
    else date1Month.value++;
}

function selectDate1Day(day) {
    if (!day) return;
    date1.value = new Date(date1Year.value, date1Month.value, day);
}

function isDate1Today(day) {
    if (!day) return false;
    return day === today.getDate() && date1Month.value === today.getMonth() && date1Year.value === today.getFullYear();
}

function isDate1Selected(day) {
    if (!day || !date1.value) return false;
    return day === date1.value.getDate() && date1Month.value === date1.value.getMonth() && date1Year.value === date1.value.getFullYear();
}

const date2MonthLabel = computed(() => `${MONTHS[date2Month.value]} ${date2Year.value}`);
const date2CalendarDays = computed(() => {
    const firstDay = new Date(date2Year.value, date2Month.value, 1).getDay();
    const daysInMonth = new Date(date2Year.value, date2Month.value + 1, 0).getDate();
    const cells = [];
    for (let i = 0; i < firstDay; i++) cells.push(null);
    for (let d = 1; d <= daysInMonth; d++) cells.push(d);
    return cells;
});

const date2DisplayLabel = computed(() => {
    if (!date2.value) return null;
    return date2.value.toLocaleDateString('en-US', { weekday: 'short', month: 'short', day: 'numeric' });
});

function prevDate2Month() {
    if (date2Month.value === 0) { date2Month.value = 11; date2Year.value--; }
    else date2Month.value--;
}

function nextDate2Month() {
    if (date2Month.value === 11) { date2Month.value = 0; date2Year.value++; }
    else date2Month.value++;
}

function selectDate2Day(day) {
    if (!day) return;
    date2.value = new Date(date2Year.value, date2Month.value, day);
}

function isDate2Today(day) {
    if (!day) return false;
    return day === today.getDate() && date2Month.value === today.getMonth() && date2Year.value === today.getFullYear();
}

function isDate2Selected(day) {
    if (!day || !date2.value) return false;
    return day === date2.value.getDate() && date2Month.value === date2.value.getMonth() && date2Year.value === date2.value.getFullYear();
}

const vaccineForm = useForm({
    name: '',
    administered_date: '',
    expiry_date: '',
    batch_number: '',
});

const medicationForm = useForm({
    name: '',
    dose_amount: '',
    dose_unit: 'ml',
    frequency_amount: '',
    frequency_unit: 'päevas',
    start_date: '',
    end_date: '',
    reminder_time: '',
});

watch(treatmentType, () => {
    vaccineForm.reset();
    medicationForm.reset();
    date1.value = null;
    date2.value = null;
});

function formatDate(d) {
    const y = d.getFullYear();
    const m = String(d.getMonth() + 1).padStart(2, '0');
    const day = String(d.getDate()).padStart(2, '0');
    return `${y}-${m}-${day}`;
}

function submit() {
    if (treatmentType.value === 'vaktsiin') {
        if (date1.value) vaccineForm.administered_date = formatDate(date1.value);
        if (date2.value) vaccineForm.expiry_date = formatDate(date2.value);

        vaccineForm.post(route('vaccines.store', { pet: props.petId }), {
            preserveScroll: true,
            onSuccess: () => emit('close'),
        });
    } else {
        if (date1.value) medicationForm.start_date = formatDate(date1.value);
        if (date2.value) medicationForm.end_date = formatDate(date2.value);

        medicationForm.post(route('medications.store', { pet: props.petId }), {
            preserveScroll: true,
            onSuccess: () => emit('close'),
        });
    }
}

const currentForm = computed(() => treatmentType.value === 'vaktsiin' ? vaccineForm : medicationForm);
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

                    <div class="flex items-center justify-between bg-white rounded-xl px-4 py-3 border border-gray-200">
                        <span class="text-sm text-gray-700 font-medium">{{ petName || 'Lemmiku nimi' }}</span>
                    </div>

                    <select v-model="treatmentType" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#275342]/40">
                        <option value="vaktsiin">Vaktsiin</option>
                        <option value="ravim">Ravim</option>
                    </select>

                    <input v-model="currentForm.name" type="text" :placeholder="nameLabel" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#2D5A3D]/40"/>
                    <p v-if="currentForm.errors.name" class="text-red-500 text-xs -mt-2">{{ currentForm.errors.name }}</p>

                    <template v-if="treatmentType === 'ravim'">

                        <!-- Doos -->
                        <div class="flex gap-3 w-full">
                            <input
                                v-model="medicationForm.dose_amount"
                                type="number"
                                step="1"
                                placeholder="Annus"
                                class="flex w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#2D5A3D]/40"
                            />
                            <select
                                v-model="medicationForm.dose_unit"
                                class="w-1/2 bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#2D5A3D]/40"
                            >
                                <option value="ml">ml</option>
                                <option value="mg">mg</option>
                                <option value="g">g</option>
                                <option value="tablet">tablet</option>
                            </select>
                        </div>
                        <p v-if="medicationForm.errors.dose_amount" class="text-red-500 text-xs -mt-2">{{ medicationForm.errors.dose_amount }}</p>

                        <!-- Sagedus -->
                        <div class="flex gap-3 w-full">
                            <input
                                v-model="medicationForm.frequency_amount"
                                type="number"
                                min="1"
                                placeholder="Mitu korda"
                                class="flex w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#2D5A3D]/40"
                            />
                            <select
                                v-model="medicationForm.frequency_unit"
                                class="w-1/2 bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#2D5A3D]/40"
                            >
                                <option value="päevas">päevas</option>
                                <option value="nädalas">nädalas</option>
                                <option value="kuus">kuus</option>
                            </select>
                        </div>
                        <p v-if="medicationForm.errors.frequency_amount" class="text-red-500 text-xs -mt-2">{{ medicationForm.errors.frequency_amount }}</p>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">
                            Meeldetuletuse kellaaeg
                        </label>

                        <input
                            v-model="medicationForm.reminder_time"
                            type="time"
                            class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm text-[#275342] focus:outline-none focus:ring-2 focus:ring-[#275342]/30"
                        />
                    </div>
                    </template>

                    <!-- Date1 picker -->
                    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                        <div class="px-4 pt-3 pb-1"><p class="text-xs text-gray-500">{{ date1Label }}</p></div>
                        <div class="flex items-center justify-between px-4 pb-3">
                            <span class="text-2xl font-semibold text-gray-800">{{ date1DisplayLabel ?? 'Lisa kuupäev' }}</span>
                            <button @click="showDate1Calendar = !showDate1Calendar" class="text-gray-500 hover:text-[#2D5A3D]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536M9 13l6-6m-6 6l-3 3m3-3h.01"/>
                                </svg>
                            </button>
                        </div>

                        <div v-if="showDate1Calendar" class="border-t border-gray-100 px-4 py-3">
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-sm font-medium text-gray-700">{{ date1MonthLabel }}</span>
                                <div class="flex gap-3">
                                    <button @click="prevDate1Month" class="text-gray-400 hover:text-[#2D5A3D]">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                                    </button>
                                    <button @click="nextDate1Month" class="text-gray-400 hover:text-[#2D5A3D]">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                    </button>
                                </div>
                            </div>
                            <div class="grid grid-cols-7 mb-1">
                                <span v-for="d in DAYS_SHORT" :key="d" class="text-center text-xs text-gray-400 font-medium py-1">{{ d }}</span>
                            </div>
                            <div class="grid grid-cols-7 gap-y-1">
                                <button v-for="(day, idx) in date1CalendarDays" :key="idx" @click="selectDate1Day(day)" :disabled="!day"
                                    :class="['h-8 w-8 mx-auto text-sm rounded-full flex items-center justify-center transition',
                                        !day && 'invisible',
                                        isDate1Selected(day) && 'bg-[#2D5A3D] text-white font-semibold',
                                        isDate1Today(day) && !isDate1Selected(day) && 'border border-[#2D5A3D] text-[#2D5A3D] font-semibold',
                                        !isDate1Selected(day) && !isDate1Today(day) && day && 'text-gray-700 hover:bg-gray-100']">{{ day }}</button>
                            </div>
                            <div class="flex justify-between mt-3 pt-3 border-t border-gray-100">
                                <button @click="date1 = null" class="text-sm text-gray-500 hover:text-gray-800">Clear</button>
                                <button @click="showDate1Calendar = false" class="text-sm font-semibold text-[#2D5A3D]">OK</button>
                            </div>
                        </div>
                    </div>

                    <!-- Date2 picker -->
                    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                        <div class="px-4 pt-3 pb-1"><p class="text-xs text-gray-500">{{ date2Label }}</p></div>
                        <div class="flex items-center justify-between px-4 pb-3">
                            <span class="text-2xl font-semibold text-gray-800">{{ date2DisplayLabel ?? 'Lisa kuupäev' }}</span>
                            <button @click="showDate2Calendar = !showDate2Calendar" class="text-gray-500 hover:text-[#2D5A3D]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536M9 13l6-6m-6 6l-3 3m3-3h.01"/>
                                </svg>
                            </button>
                        </div>

                        <div v-if="showDate2Calendar" class="border-t border-gray-100 px-4 py-3">
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-sm font-medium text-gray-700">{{ date2MonthLabel }}</span>
                                <div class="flex gap-3">
                                    <button @click="prevDate2Month" class="text-gray-400 hover:text-[#2D5A3D]">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                                    </button>
                                    <button @click="nextDate2Month" class="text-gray-400 hover:text-[#2D5A3D]">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                    </button>
                                </div>
                            </div>
                            <div class="grid grid-cols-7 mb-1">
                                <span v-for="d in DAYS_SHORT" :key="d" class="text-center text-xs text-gray-400 font-medium py-1">{{ d }}</span>
                            </div>
                            <div class="grid grid-cols-7 gap-y-1">
                                <button v-for="(day, idx) in date2CalendarDays" :key="idx" @click="selectDate2Day(day)" :disabled="!day"
                                    :class="['h-8 w-8 mx-auto text-sm rounded-full flex items-center justify-center transition',
                                        !day && 'invisible',
                                        isDate2Selected(day) && 'bg-[#2D5A3D] text-white font-semibold',
                                        isDate2Today(day) && !isDate2Selected(day) && 'border border-[#2D5A3D] text-[#2D5A3D] font-semibold',
                                        !isDate2Selected(day) && !isDate2Today(day) && day && 'text-gray-700 hover:bg-gray-100']">{{ day }}</button>
                            </div>
                            <div class="flex justify-between mt-3 pt-3 border-t border-gray-100">
                                <button @click="date2 = null" class="text-sm text-gray-500 hover:text-gray-800">Clear</button>
                                <button @click="showDate2Calendar = false" class="text-sm font-semibold text-[#2D5A3D]">OK</button>
                            </div>
                        </div>
                    </div>

                    <!-- Partii number (ainult vaktsiin) -->
                    <input v-if="treatmentType === 'vaktsiin'" v-model="vaccineForm.batch_number" type="text" placeholder="Partii number (valikuline)" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#2D5A3D]/40"/>

                    <button @click="submit" :disabled="currentForm.processing" class="w-full bg-[#2D5A3D] text-white font-semibold tracking-widest text-sm py-4 rounded-xl hover:bg-[#234830] active:scale-[0.98] transition disabled:opacity-60">
                        {{ currentForm.processing ? 'Salvestamine...' : 'SALVESTA' }}
                    </button>

                </div>
            </div>
        </div>
    </Teleport>
</template>