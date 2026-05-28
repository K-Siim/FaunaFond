<script setup>
import { ref, computed, watch } from "vue";
import { useForm } from "@inertiajs/vue3";
import { X } from '@lucide/vue'
import { Button } from '@/Components/ui/button'
import { Input } from '@/Components/ui/input'
import { Textarea } from '@/Components/ui/textarea'
import { Label } from '@/Components/ui/label'
import { Card, CardContent } from '@/Components/ui/card'
import { Separator } from '@/Components/ui/separator'
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/Components/ui/select'
import { TimePicker } from '@/Components/ui/time-picker'

const props = defineProps({
    petId:    { type: Number,  default: null },
    pets:     { type: Array,   default: () => [] },
    showForm: { type: Boolean, default: false },
});

const emit = defineEmits(['close-form']);

const selectedDate = ref(null);
const calendarOpen = ref(false);

const today        = new Date();
const currentMonth = ref(today.getMonth());
const currentYear  = ref(today.getFullYear());

const MONTHS     = ['Jaanuar','Veebruar','Märts','Aprill','Mai','Juuni','Juuli','August','September','Oktoober','November','Detsember'];
const DAYS_SHORT = ['E','T','K','N','R','L','P'];
const YEARS      = Array.from({ length: 20 }, (_, i) => today.getFullYear() - 5 + i);

const calendarDays = computed(() => {
    const firstDay    = new Date(currentYear.value, currentMonth.value, 1).getDay();
    const daysInMonth = new Date(currentYear.value, currentMonth.value + 1, 0).getDate();
    const cells       = [];
    const offset      = firstDay === 0 ? 6 : firstDay - 1;
    for (let i = 0; i < offset; i++) cells.push(null);
    for (let d = 1; d <= daysInMonth; d++) cells.push(d);
    return cells;
});

const selectedLabel = computed(() => {
    if (!selectedDate.value) return null;
    return selectedDate.value.toLocaleDateString('et-EE', {
        day: '2-digit', month: '2-digit', year: 'numeric',
    });
});

function selectDay(day) {
    if (!day) return;
    selectedDate.value = new Date(currentYear.value, currentMonth.value, day);
}
function clearDate() {
    selectedDate.value = null;
}
function isToday(day) {
    if (!day) return false;
    return (
        day === today.getDate() &&
        currentMonth.value === today.getMonth() &&
        currentYear.value === today.getFullYear()
    );
}
function isSelected(day) {
    if (!day || !selectedDate.value) return false;
    return (
        day === selectedDate.value.getDate() &&
        currentMonth.value === selectedDate.value.getMonth() &&
        currentYear.value === selectedDate.value.getFullYear()
    );
}

const reminderForm = useForm({
    pet_id:        props.petId ?? "",
    type:          "",
    name:          "",
    notes:         "",
    reminder_date: "",
    reminder_time: "",
});

const reminderMode = ref('onetime');

watch(() => reminderForm.type, () => {
    reminderMode.value = 'onetime';
});

watch(selectedDate, (val) => {
    if (val) {
        const y = val.getFullYear();
        const m = String(val.getMonth() + 1).padStart(2, '0');
        const d = String(val.getDate()).padStart(2, '0');
        reminderForm.reminder_date = `${y}-${m}-${d}`;
    } else {
        reminderForm.reminder_date = "";
    }
});

const namePlaceholders = {
    vaccine:   "Vaktsiini nimi *",
    medicine:  "Ravimi nimi *",
    vet_visit: "Kliiniku / veterinaari nimi *",
};

function submitReminder() {
    reminderForm.post(route("reminders.store"), {
        preserveScroll: true,
        onSuccess: () => {
            reminderForm.reset("type", "name", "notes", "reminder_date", "reminder_time");
            if (!props.petId) reminderForm.pet_id = "";
            selectedDate.value = null;
            calendarOpen.value = false;
            emit('close-form');
        },
    });
}
</script>

<template>
    <Teleport to="body">
        <div
            v-if="showForm"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm px-4"
            @click.self="emit('close-form')"
        >
            <Card class="relative w-full max-w-sm bg-[#F0F4EF] rounded-3xl shadow-2xl border-none max-h-[90vh] overflow-y-auto">
                <Button
                    variant="ghost"
                    size="icon"
                    @click="emit('close-form')"
                    class="absolute top-2 right-2 z-10 text-gray-400 hover:text-gray-700"
                >
                    <X class="w-5 h-5" />
                </Button>
                <CardContent class="p-5 pt-12 flex flex-col gap-4">
                    <div class="flex items-center bg-white rounded-2xl px-4 py-3 border border-gray-200 shadow-sm">
                        <span class="text-sm text-gray-700 font-medium">Lisa meeldetuletus</span>
                    </div>
                    <div v-if="!petId" class="flex flex-col gap-1.5">
                        <Label>Lemmik</Label>
                        <Select v-model="reminderForm.pet_id">
                            <SelectTrigger class="h-12 rounded-2xl border-gray-200 bg-white shadow-sm text-[#275342]">
                                <SelectValue placeholder="Vali lemmik *" />
                            </SelectTrigger>
                            <SelectContent class="rounded-xl border-gray-200">
                                <SelectItem v-for="p in pets" :key="p.id" :value="p.id">
                                    {{ p.name }}
                                </SelectItem>
                            </SelectContent>
                        </Select>
                        <p v-if="reminderForm.errors.pet_id" class="text-red-500 text-xs px-1">
                            {{ reminderForm.errors.pet_id }}
                        </p>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <Label>Meeldetuletuse tüüp</Label>
                        <Select v-model="reminderForm.type">
                            <SelectTrigger class="h-12 rounded-2xl border-gray-200 bg-white shadow-sm text-[#275342]">
                                <SelectValue placeholder="Vali tüüp *" />
                            </SelectTrigger>
                            <SelectContent class="rounded-xl border-gray-200">
                                <SelectItem value="vaccine">Vaktsiin</SelectItem>
                                <SelectItem value="medicine">Ravim</SelectItem>
                                <SelectItem value="vet_visit">Arstivisiit</SelectItem>
                            </SelectContent>
                        </Select>
                        <p v-if="reminderForm.errors.type" class="text-red-500 text-xs px-1">
                            {{ reminderForm.errors.type }}
                        </p>
                    </div>
                    <div v-if="reminderForm.type === 'medicine'" class="flex gap-3">
                        <Button
                            type="button"
                            @click="reminderMode = 'onetime'"
                            :class="[
                                'flex-1 rounded-2xl text-sm font-medium transition shadow-sm',
                                reminderMode === 'onetime'
                                    ? 'bg-[#2D5A3D] text-white border-[#2D5A3D] hover:bg-[#234830]'
                                    : 'bg-white text-[#275342] border border-gray-200'
                            ]"
                        >
                            Ühekordne
                        </Button>
                        <Button
                            type="button"
                            @click="reminderMode = 'recurring'"
                            :class="[
                                'flex-1 rounded-2xl text-sm font-medium transition shadow-sm',
                                reminderMode === 'recurring'
                                    ? 'bg-[#2D5A3D] text-white border-[#2D5A3D] hover:bg-[#234830]'
                                    : 'bg-white text-[#275342] border border-gray-200'
                            ]"
                        >
                            Korduv
                        </Button>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <Label>Nimi</Label>
                        <Input
                            v-model="reminderForm.name"
                            type="text"
                            :placeholder="namePlaceholders[reminderForm.type] ?? 'Nimi *'"
                            class="h-12 rounded-2xl border-gray-200 bg-white shadow-sm"
                        />
                        <p v-if="reminderForm.errors.name" class="text-red-500 text-xs px-1">
                            {{ reminderForm.errors.name }}
                        </p>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <Label>Märkused <span class="text-gray-500 font-normal">(valikuline)</span></Label>
                        <Textarea
                            v-model="reminderForm.notes"
                            :rows="3"
                            placeholder="Lisa märkused"
                            class="rounded-2xl border-gray-200 bg-white shadow-sm text-[#275342] placeholder-gray-400 resize-none"
                        />
                    </div>
                    <div v-if="reminderForm.type !== 'medicine' || reminderMode === 'onetime'" class="flex flex-col gap-1.5">
                        <Label>Kuupäev</Label>
                        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                            <div class="flex items-center justify-between pt-4 pb-2 px-4">
                                <span class="text-lg font-semibold text-gray-800">
                                    {{ selectedLabel ?? 'Vali kuupäev' }}
                                </span>
                                <Button
                                    variant="outline"
                                    size="icon"
                                    @click="calendarOpen = !calendarOpen"
                                    class="rounded-xl border-gray-200 h-9 w-9"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </Button>
                            </div>
                            <div v-if="calendarOpen" class="border-t border-gray-100 px-4 py-4">
                                <div class="flex items-center gap-2 mb-4">
                                    <Select v-model="currentMonth">
                                        <SelectTrigger class="flex-1 h-11 rounded-xl border-gray-200 bg-white">
                                            <SelectValue />
                                        </SelectTrigger>
                                        <SelectContent class="rounded-xl border-gray-200">
                                            <SelectItem v-for="(month, idx) in MONTHS" :key="month" :value="idx">
                                                {{ month }}
                                            </SelectItem>
                                        </SelectContent>
                                    </Select>
                                    <Select v-model="currentYear">
                                        <SelectTrigger class="w-28 h-11 rounded-xl border-gray-200 bg-white">
                                            <SelectValue />
                                        </SelectTrigger>
                                        <SelectContent class="rounded-xl border-gray-200 max-h-64">
                                            <SelectItem v-for="year in YEARS" :key="year" :value="year">
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
                                    >{{ d }}</span>
                                </div>
                                <div class="grid grid-cols-7 gap-y-2">
                                    <Button
                                        variant="ghost"
                                        v-for="(day, idx) in calendarDays"
                                        :key="idx"
                                        @click="selectDay(day)"
                                        :disabled="!day"
                                        :class="[
                                            'h-9 w-9 m-0 p-0 mx-auto text-sm rounded-xl flex items-center justify-center transition',
                                            !day && 'invisible',
                                            isSelected(day) && 'bg-[#2D5A3D] text-white font-semibold shadow-sm hover:bg-[#2D5A3D]',
                                            isToday(day) && !isSelected(day) && 'border border-[#2D5A3D] text-[#2D5A3D] font-semibold',
                                            !isSelected(day) && !isToday(day) && day && 'text-gray-700 hover:bg-gray-100',
                                        ]"
                                    >{{ day }}</Button>
                                </div>
                                <Separator class="mt-4" />
                                <div class="flex justify-between mt-4">
                                    <Button
                                        variant="ghost"
                                        @click="clearDate"
                                        class="text-sm text-gray-500 hover:text-gray-800 px-0"
                                    >Tühjenda</Button>
                                    <Button
                                        variant="ghost"
                                        @click="calendarOpen = false"
                                        class="text-sm font-semibold text-[#2D5A3D]"
                                    >OK</Button>
                                </div>
                            </div>
                            <p v-if="reminderForm.errors.reminder_date" class="text-red-500 text-xs px-4 pb-4">
                                {{ reminderForm.errors.reminder_date }}
                            </p>
                        </div>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <Label>
                            {{ reminderForm.type === 'medicine' && reminderMode === 'recurring' ? 'Korduva ravimi kellaaeg *' : 'Kellaaeg' }}
                        </Label>
                        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-2">
                            <TimePicker v-model="reminderForm.reminder_time" />
                        </div>
                        <p v-if="reminderForm.type === 'medicine' && reminderMode === 'recurring'" class="text-sm text-gray-600 px-1">
                            Meeldetuletus kuvatakse iga päev selle kellaajaga.
                        </p>
                    </div>
                    <Button
                        @click="submitReminder"
                        :disabled="reminderForm.processing"
                        class="w-full h-11 bg-[#2D5A3D] text-[#FFFDF3] font-semibold tracking-widest text-sm rounded-2xl hover:bg-[#234830] active:scale-[0.98] transition disabled:opacity-60 shadow-sm"
                    >
                        {{ reminderForm.processing ? 'Salvestamine...' : 'SALVESTA' }}
                    </Button>
                </CardContent>
            </Card>
        </div>
    </Teleport>
</template>