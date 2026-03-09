<script setup>
import { ref, computed } from 'vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    petId: { type: Number, required: true },
    petName: { type: String, default: '' },
});

const emit = defineEmits(['close']);

const today = new Date();
const adminMonth = ref(today.getMonth());
const adminYear = ref(today.getFullYear());
const expiryMonth = ref(today.getMonth());
const expiryYear = ref(today.getFullYear());
const administeredDate = ref(null);
const expiryDate = ref(null);
const showAdminCalendar = ref(true);
const showExpiryCalendar = ref(true);

const MONTHS = ['January','February','March','April','May','June','July','August','September','October','November','December'];
const DAYS_SHORT = ['S','M','T','W','T','F','S'];

const adminMonthLabel = computed(() => `${MONTHS[adminMonth.value]} ${adminYear.value}`);
const adminCalendarDays = computed(() => {
    const firstDay = new Date(adminYear.value, adminMonth.value, 1).getDay();
    const daysInMonth = new Date(adminYear.value, adminMonth.value + 1, 0).getDate();
    const cells = [];
    for (let i = 0; i < firstDay; i++) cells.push(null);
    for (let d = 1; d <= daysInMonth; d++) cells.push(d);
    return cells;
});

const administeredLabel = computed(() => {
    if (!administeredDate.value) return null;
    return administeredDate.value.toLocaleDateString('en-US', { weekday: 'short', month: 'short', day: 'numeric' });
});

function prevAdminMonth() {
    if (adminMonth.value === 0) { adminMonth.value = 11; adminYear.value--; }
    else adminMonth.value--;
}

function nextAdminMonth() {
    if (adminMonth.value === 11) { adminMonth.value = 0; adminYear.value++; }
    else adminMonth.value++;
}

function selectAdminDay(day) {
    if (!day) return;
    administeredDate.value = new Date(adminYear.value, adminMonth.value, day);
}

function isAdminToday(day) {
    if (!day) return false;
    return day === today.getDate() && adminMonth.value === today.getMonth() && adminYear.value === today.getFullYear();
}

function isAdminSelected(day) {
    if (!day || !administeredDate.value) return false;
    return day === administeredDate.value.getDate() && adminMonth.value === administeredDate.value.getMonth() && adminYear.value === administeredDate.value.getFullYear();
}

const expiryMonthLabel = computed(() => `${MONTHS[expiryMonth.value]} ${expiryYear.value}`);
const expiryCalendarDays = computed(() => {
    const firstDay = new Date(expiryYear.value, expiryMonth.value, 1).getDay();
    const daysInMonth = new Date(expiryYear.value, expiryMonth.value + 1, 0).getDate();
    const cells = [];
    for (let i = 0; i < firstDay; i++) cells.push(null);
    for (let d = 1; d <= daysInMonth; d++) cells.push(d);
    return cells;
});

const expiryLabel = computed(() => {
    if (!expiryDate.value) return null;
    return expiryDate.value.toLocaleDateString('en-US', { weekday: 'short', month: 'short', day: 'numeric' });
});

function prevExpiryMonth() {
    if (expiryMonth.value === 0) { expiryMonth.value = 11; expiryYear.value--; }
    else expiryMonth.value--;
}

function nextExpiryMonth() {
    if (expiryMonth.value === 11) { expiryMonth.value = 0; expiryYear.value++; }
    else expiryMonth.value++;
}

function selectExpiryDay(day) {
    if (!day) return;
    expiryDate.value = new Date(expiryYear.value, expiryMonth.value, day);
}

function isExpiryToday(day) {
    if (!day) return false;
    return day === today.getDate() && expiryMonth.value === today.getMonth() && expiryYear.value === today.getFullYear();
}

function isExpirySelected(day) {
    if (!day || !expiryDate.value) return false;
    return day === expiryDate.value.getDate() && expiryMonth.value === expiryDate.value.getMonth() && expiryYear.value === expiryDate.value.getFullYear();
}

const form = useForm({
    name: '',
    administered_date: '',
    expiry_date: '',
    batch_number: '',
});

function submit() {
    if (administeredDate.value) {
        const y = administeredDate.value.getFullYear();
        const m = String(administeredDate.value.getMonth() + 1).padStart(2, '0');
        const d = String(administeredDate.value.getDate()).padStart(2, '0');
        form.administered_date = `${y}-${m}-${d}`;
    }
    if (expiryDate.value) {
        const y = expiryDate.value.getFullYear();
        const m = String(expiryDate.value.getMonth() + 1).padStart(2, '0');
        const d = String(expiryDate.value.getDate()).padStart(2, '0');
        form.expiry_date = `${y}-${m}-${d}`;
    }

    form.post(route('vaccines.store', { pet: props.petId }), {
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

                    <!-- Treatment type dropdown (static for now - always Vaktsiin) -->
                    <select class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#2D5A3D]/40">
                        <option>Vaktsiin/Ravim</option>
                    </select>

                    <!-- Vaccine name -->
                    <input v-model="form.name" type="text" placeholder="Vaktsiini nimi" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#2D5A3D]/40"/>
                    <p v-if="form.errors.name" class="text-red-500 text-xs -mt-2">{{ form.errors.name }}</p>

                    <!-- Administered date picker -->
                    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                        <div class="px-4 pt-3 pb-1"><p class="text-xs text-gray-500">Andmise kuupäev</p></div>
                        <div class="flex items-center justify-between px-4 pb-3">
                            <span class="text-2xl font-semibold text-gray-800">{{ administeredLabel ?? 'Lisa kuupäev' }}</span>
                            <button @click="showAdminCalendar = !showAdminCalendar" class="text-gray-500 hover:text-[#2D5A3D]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536M9 13l6-6m-6 6l-3 3m3-3h.01"/>
                                </svg>
                            </button>
                        </div>

                        <div v-if="showAdminCalendar" class="border-t border-gray-100 px-4 py-3">
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-sm font-medium text-gray-700">{{ adminMonthLabel }}</span>
                                <div class="flex gap-3">
                                    <button @click="prevAdminMonth" class="text-gray-400 hover:text-[#2D5A3D]">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                                    </button>
                                    <button @click="nextAdminMonth" class="text-gray-400 hover:text-[#2D5A3D]">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                    </button>
                                </div>
                            </div>
                            <div class="grid grid-cols-7 mb-1">
                                <span v-for="d in DAYS_SHORT" :key="d" class="text-center text-xs text-gray-400 font-medium py-1">{{ d }}</span>
                            </div>
                            <div class="grid grid-cols-7 gap-y-1">
                                <button v-for="(day, idx) in adminCalendarDays" :key="idx" @click="selectAdminDay(day)" :disabled="!day"
                                    :class="['h-8 w-8 mx-auto text-sm rounded-full flex items-center justify-center transition',
                                        !day && 'invisible',
                                        isAdminSelected(day) && 'bg-[#2D5A3D] text-white font-semibold',
                                        isAdminToday(day) && !isAdminSelected(day) && 'border border-[#2D5A3D] text-[#2D5A3D] font-semibold',
                                        !isAdminSelected(day) && !isAdminToday(day) && day && 'text-gray-700 hover:bg-gray-100']">{{ day }}</button>
                            </div>
                            <div class="flex justify-between mt-3 pt-3 border-t border-gray-100">
                                <button @click="administeredDate = null" class="text-sm text-gray-500 hover:text-gray-800">Clear</button>
                                <button @click="showAdminCalendar = false" class="text-sm font-semibold text-[#2D5A3D]">OK</button>
                            </div>
                        </div>
                    </div>

                    <!-- Expiry date picker -->
                    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                        <div class="px-4 pt-3 pb-1"><p class="text-xs text-gray-500">Lõppemise kuupäev</p></div>
                        <div class="flex items-center justify-between px-4 pb-3">
                            <span class="text-2xl font-semibold text-gray-800">{{ expiryLabel ?? 'Lisa kuupäev' }}</span>
                            <button @click="showExpiryCalendar = !showExpiryCalendar" class="text-gray-500 hover:text-[#2D5A3D]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536M9 13l6-6m-6 6l-3 3m3-3h.01"/>
                                </svg>
                            </button>
                        </div>

                        <div v-if="showExpiryCalendar" class="border-t border-gray-100 px-4 py-3">
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-sm font-medium text-gray-700">{{ expiryMonthLabel }}</span>
                                <div class="flex gap-3">
                                    <button @click="prevExpiryMonth" class="text-gray-400 hover:text-[#2D5A3D]">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                                    </button>
                                    <button @click="nextExpiryMonth" class="text-gray-400 hover:text-[#2D5A3D]">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                    </button>
                                </div>
                            </div>
                            <div class="grid grid-cols-7 mb-1">
                                <span v-for="d in DAYS_SHORT" :key="d" class="text-center text-xs text-gray-400 font-medium py-1">{{ d }}</span>
                            </div>
                            <div class="grid grid-cols-7 gap-y-1">
                                <button v-for="(day, idx) in expiryCalendarDays" :key="idx" @click="selectExpiryDay(day)" :disabled="!day"
                                    :class="['h-8 w-8 mx-auto text-sm rounded-full flex items-center justify-center transition',
                                        !day && 'invisible',
                                        isExpirySelected(day) && 'bg-[#2D5A3D] text-white font-semibold',
                                        isExpiryToday(day) && !isExpirySelected(day) && 'border border-[#2D5A3D] text-[#2D5A3D] font-semibold',
                                        !isExpirySelected(day) && !isExpiryToday(day) && day && 'text-gray-700 hover:bg-gray-100']">{{ day }}</button>
                            </div>
                            <div class="flex justify-between mt-3 pt-3 border-t border-gray-100">
                                <button @click="expiryDate = null" class="text-sm text-gray-500 hover:text-gray-800">Clear</button>
                                <button @click="showExpiryCalendar = false" class="text-sm font-semibold text-[#2D5A3D]">OK</button>
                            </div>
                        </div>
                    </div>

                    <!-- Batch number (optional) -->
                    <input v-model="form.batch_number" type="text" placeholder="Partii number (valikuline)" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#2D5A3D]/40"/>

                    <!-- Submit -->
                    <button @click="submit" :disabled="form.processing" class="w-full bg-[#2D5A3D] text-white font-semibold tracking-widest text-sm py-4 rounded-xl hover:bg-[#234830] active:scale-[0.98] transition disabled:opacity-60">
                        {{ form.processing ? 'Salvestamine...' : 'SALVESTA' }}
                    </button>

                </div>
            </div>
        </div>
    </Teleport>
</template>