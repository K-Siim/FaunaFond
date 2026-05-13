<script setup>
import { ref, computed, watch } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { Input } from '@/Components/ui/input'
import { Label } from '@/Components/ui/label'
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/Components/ui/select'

const props = defineProps({
    petId: { type: Number, required: true },
    petName: { type: String, default: '' },
})

const emit = defineEmits(['close'])

const treatmentType = ref('vaktsiin')
const today = new Date()

const YEARS = Array.from({ length: 21 }, (_, i) => today.getFullYear() - 10 + i)
const MONTHS = ['Jaanuar', 'Veebruar', 'Märts', 'Aprill', 'Mai', 'Juuni', 'Juuli', 'August', 'September', 'Oktoober', 'November', 'Detsember']
const DAYS_SHORT = ['E', 'T', 'K', 'N', 'R', 'L', 'P']

const date1Month = ref(today.getMonth())
const date1Year = ref(today.getFullYear())
const date2Month = ref(today.getMonth())
const date2Year = ref(today.getFullYear())

const date1 = ref(null)
const date2 = ref(null)

const showDate1Calendar = ref(true)
const showDate2Calendar = ref(true)

const vaccineForm = useForm({
    name: '',
    administered_date: '',
    expiry_date: '',
    batch_number: '',
})

const medicationForm = useForm({
    name: '',
    dose_amount: '',
    dose_unit: 'ml',
    frequency_amount: '',
    frequency_unit: 'päevas',
})

watch(treatmentType, () => {
    vaccineForm.reset()
    medicationForm.reset()
    date1.value = null
    date2.value = null
})

const currentForm = computed(() =>
    treatmentType.value === 'vaktsiin' ? vaccineForm : medicationForm
)

function formatDate(date) {
    const y = date.getFullYear()
    const m = String(date.getMonth() + 1).padStart(2, '0')
    const d = String(date.getDate()).padStart(2, '0')
    return `${y}-${m}-${d}`
}

const date1DisplayLabel = computed(() => {
    if (!date1.value) return null
    return date1.value.toLocaleDateString('et-EE', { weekday: 'short', month: 'short', day: 'numeric' })
})

const date2DisplayLabel = computed(() => {
    if (!date2.value) return null
    return date2.value.toLocaleDateString('et-EE', { weekday: 'short', month: 'short', day: 'numeric' })
})

const date1CalendarDays = computed(() => {
    let firstDay = new Date(date1Year.value, date1Month.value, 1).getDay()
    firstDay = firstDay === 0 ? 6 : firstDay - 1
    const daysInMonth = new Date(date1Year.value, date1Month.value + 1, 0).getDate()
    const cells = []
    for (let i = 0; i < firstDay; i++) cells.push(null)
    for (let d = 1; d <= daysInMonth; d++) cells.push(d)
    return cells
})

const date2CalendarDays = computed(() => {
    let firstDay = new Date(date2Year.value, date2Month.value, 1).getDay()
    firstDay = firstDay === 0 ? 6 : firstDay - 1
    const daysInMonth = new Date(date2Year.value, date2Month.value + 1, 0).getDate()
    const cells = []
    for (let i = 0; i < firstDay; i++) cells.push(null)
    for (let d = 1; d <= daysInMonth; d++) cells.push(d)
    return cells
})

function selectDate1Day(day) {
    if (!day) return
    date1.value = new Date(date1Year.value, date1Month.value, day)
}

function selectDate2Day(day) {
    if (!day) return
    date2.value = new Date(date2Year.value, date2Month.value, day)
}

function isDate1Today(day) {
    if (!day) return false
    return day === today.getDate() && date1Month.value === today.getMonth() && date1Year.value === today.getFullYear()
}

function isDate2Today(day) {
    if (!day) return false
    return day === today.getDate() && date2Month.value === today.getMonth() && date2Year.value === today.getFullYear()
}

function isDate1Selected(day) {
    if (!day || !date1.value) return false
    return day === date1.value.getDate() && date1Month.value === date1.value.getMonth() && date1Year.value === date1.value.getFullYear()
}

function isDate2Selected(day) {
    if (!day || !date2.value) return false
    return day === date2.value.getDate() && date2Month.value === date2.value.getMonth() && date2Year.value === date2.value.getFullYear()
}

function submit() {
    if (treatmentType.value === 'vaktsiin') {
        if (date1.value) vaccineForm.administered_date = formatDate(date1.value)
        if (date2.value) vaccineForm.expiry_date = formatDate(date2.value)

        vaccineForm.post(route('vaccines.store', { pet: props.petId }), {
            preserveScroll: true,
            onSuccess: () => emit('close'),
        })
    } else {
        medicationForm.post(route('medications.store', { pet: props.petId }), {
            preserveScroll: true,
            onSuccess: () => emit('close'),
        })
    }
}
</script>

<template>
    <Teleport to="body">
        <div
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm px-4"
            @click.self="emit('close')"
        >
            <div class="relative w-full max-w-sm bg-[#F0F4EF] rounded-3xl shadow-2xl overflow-hidden max-h-[90vh] overflow-y-auto border border-white/30">

                <!-- Close button -->
                <button
                    @click="emit('close')"
                    class="absolute top-4 right-4 text-gray-400 hover:text-gray-700 transition z-10"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>

                <div class="p-5 pt-12 flex flex-col gap-4">

                    <!-- Pet name -->
                    <div class="flex items-center bg-white rounded-2xl px-4 py-3 border border-gray-200 shadow-sm">
                        <span class="text-sm text-gray-700 font-medium">
                            {{ petName || 'Lemmiku nimi' }}
                        </span>
                    </div>

                    <!-- Treatment type -->
                    <Select v-model="treatmentType">
                        <SelectTrigger class="w-full h-12 rounded-2xl border-gray-200 bg-white shadow-sm">
                            <SelectValue />
                        </SelectTrigger>
                        <SelectContent class="rounded-2xl border-gray-200">
                            <SelectItem value="vaktsiin">Vaktsiin</SelectItem>
                            <SelectItem value="ravim">Ravim</SelectItem>
                        </SelectContent>
                    </Select>

                    <!-- Shared: name field -->
                    <div class="flex flex-col gap-1.5">
                        <Label>{{ treatmentType === 'vaktsiin' ? 'Vaktsiini nimi' : 'Ravimi nimi' }}</Label>
                        <Input
                            v-model="currentForm.name"
                            type="text"
                            :placeholder="treatmentType === 'vaktsiin' ? 'Sisesta vaktsiini nimi' : 'Sisesta ravimi nimi'"
                            class="h-12 rounded-2xl border-gray-200 bg-white shadow-sm"
                        />
                        <p v-if="currentForm.errors.name" class="text-red-500 text-xs px-1">
                            {{ currentForm.errors.name }}
                        </p>
                    </div>

                    <!-- Vaccine fields -->
                    <template v-if="treatmentType === 'vaktsiin'">

                        <div class="flex flex-col gap-1.5">
                            <Label>
                                Partii number
                                <span class="text-gray-400 font-normal">(valikuline)</span>
                            </Label>
                            <Input
                                v-model="vaccineForm.batch_number"
                                type="text"
                                placeholder="Sisesta partii number"
                                class="h-12 rounded-2xl border-gray-200 bg-white shadow-sm"
                            />
                        </div>

                        <!-- Administered date calendar -->
                        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                            <div class="px-4 pt-4 pb-2">
                                <Label>Andmise kuupäev</Label>
                            </div>
                            <div class="flex items-center justify-between px-4 pb-4">
                                <span class="text-xl font-semibold text-gray-800">
                                    {{ date1DisplayLabel ?? 'Lisa kuupäev' }}
                                </span>
                                <button
                                    @click="showDate1Calendar = !showDate1Calendar"
                                    class="h-9 w-9 rounded-xl border border-gray-200 flex items-center justify-center hover:bg-gray-50 transition"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10m-13 9h16a2 2 0 002-2V7a2 2 0 00-2-2H4a2 2 0 00-2 2v11a2 2 0 002 2z"/>
                                    </svg>
                                </button>
                            </div>
                            <div v-if="showDate1Calendar" class="border-t border-gray-100 px-4 py-4">
                                <div class="flex items-center gap-2 mb-4">
                                    <Select v-model="date1Month">
                                        <SelectTrigger class="flex-1 h-11 rounded-xl border-gray-200 bg-white">
                                            <SelectValue />
                                        </SelectTrigger>
                                        <SelectContent class="rounded-xl border-gray-200">
                                            <SelectItem v-for="(month, idx) in MONTHS" :key="month" :value="idx">
                                                {{ month }}
                                            </SelectItem>
                                        </SelectContent>
                                    </Select>
                                    <Select v-model="date1Year">
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
                                    >
                                        {{ d }}
                                    </span>
                                </div>
                                <div class="grid grid-cols-7 gap-y-2">
                                    <button
                                        v-for="(day, idx) in date1CalendarDays"
                                        :key="idx"
                                        @click="selectDate1Day(day)"
                                        :disabled="!day"
                                        :class="[
                                            'h-9 w-9 mx-auto text-sm rounded-xl flex items-center justify-center transition',
                                            !day && 'invisible',
                                            isDate1Selected(day) && 'bg-[#2D5A3D] text-white font-semibold shadow-sm',
                                            isDate1Today(day) && !isDate1Selected(day) && 'border border-[#2D5A3D] text-[#2D5A3D] font-semibold',
                                            !isDate1Selected(day) && !isDate1Today(day) && day && 'text-gray-700 hover:bg-gray-100',
                                        ]"
                                    >
                                        {{ day }}
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Expiry date calendar -->
                        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                            <div class="px-4 pt-4 pb-2">
                                <Label>Aegumise kuupäev</Label>
                            </div>
                            <div class="flex items-center justify-between px-4 pb-4">
                                <span class="text-xl font-semibold text-gray-800">
                                    {{ date2DisplayLabel ?? 'Lisa kuupäev' }}
                                </span>
                                <button
                                    @click="showDate2Calendar = !showDate2Calendar"
                                    class="h-9 w-9 rounded-xl border border-gray-200 flex items-center justify-center hover:bg-gray-50 transition"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10m-13 9h16a2 2 0 002-2V7a2 2 0 00-2-2H4a2 2 0 00-2 2v11a2 2 0 002 2z"/>
                                    </svg>
                                </button>
                            </div>
                            <div v-if="showDate2Calendar" class="border-t border-gray-100 px-4 py-4">
                                <div class="flex items-center gap-2 mb-4">
                                    <Select v-model="date2Month">
                                        <SelectTrigger class="flex-1 h-11 rounded-xl border-gray-200 bg-white">
                                            <SelectValue />
                                        </SelectTrigger>
                                        <SelectContent class="rounded-xl border-gray-200">
                                            <SelectItem v-for="(month, idx) in MONTHS" :key="month" :value="idx">
                                                {{ month }}
                                            </SelectItem>
                                        </SelectContent>
                                    </Select>
                                    <Select v-model="date2Year">
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
                                    >
                                        {{ d }}
                                    </span>
                                </div>
                                <div class="grid grid-cols-7 gap-y-2">
                                    <button
                                        v-for="(day, idx) in date2CalendarDays"
                                        :key="idx"
                                        @click="selectDate2Day(day)"
                                        :disabled="!day"
                                        :class="[
                                            'h-9 w-9 mx-auto text-sm rounded-xl flex items-center justify-center transition',
                                            !day && 'invisible',
                                            isDate2Selected(day) && 'bg-[#2D5A3D] text-white font-semibold shadow-sm',
                                            isDate2Today(day) && !isDate2Selected(day) && 'border border-[#2D5A3D] text-[#2D5A3D] font-semibold',
                                            !isDate2Selected(day) && !isDate2Today(day) && day && 'text-gray-700 hover:bg-gray-100',
                                        ]"
                                    >
                                        {{ day }}
                                    </button>
                                </div>
                            </div>
                        </div>

                    </template>

                    <!-- Medication fields -->
                    <template v-if="treatmentType === 'ravim'">

                        <div class="flex flex-col gap-1.5">
                            <Label>Annus</Label>
                            <div class="flex gap-3">
                                <Input
                                    v-model="medicationForm.dose_amount"
                                    type="number"
                                    placeholder="Kogus"
                                    class="h-12 rounded-2xl border-gray-200 bg-white shadow-sm"
                                />
                                <Select v-model="medicationForm.dose_unit">
                                    <SelectTrigger class="w-32 h-12 rounded-2xl border-gray-200 bg-white shadow-sm">
                                        <SelectValue />
                                    </SelectTrigger>
                                    <SelectContent class="rounded-2xl border-gray-200">
                                        <SelectItem value="ml">ml</SelectItem>
                                        <SelectItem value="mg">mg</SelectItem>
                                        <SelectItem value="g">g</SelectItem>
                                        <SelectItem value="tablet">tablett</SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>
                        </div>

                        <div class="flex flex-col gap-1.5">
                            <Label>Sagedus</Label>
                            <div class="flex gap-3">
                                <Input
                                    v-model="medicationForm.frequency_amount"
                                    type="number"
                                    min="1"
                                    placeholder="Mitu korda"
                                    class="h-12 rounded-2xl border-gray-200 bg-white shadow-sm"
                                />
                                <Select v-model="medicationForm.frequency_unit">
                                    <SelectTrigger class="w-36 h-12 rounded-2xl border-gray-200 bg-white shadow-sm">
                                        <SelectValue />
                                    </SelectTrigger>
                                    <SelectContent class="rounded-2xl border-gray-200">
                                        <SelectItem value="päevas">päevas</SelectItem>
                                        <SelectItem value="nädalas">nädalas</SelectItem>
                                        <SelectItem value="kuus">kuus</SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>
                        </div>

                    </template>

                    <!-- Submit -->
                    <button
                        @click="submit"
                        :disabled="currentForm.processing"
                        class="w-full bg-[#2D5A3D] text-white font-semibold tracking-widest text-sm py-4 rounded-2xl hover:bg-[#234830] active:scale-[0.98] transition disabled:opacity-60 shadow-sm"
                    >
                        {{ currentForm.processing ? 'Salvestamine...' : 'SALVESTA' }}
                    </button>

                </div>
            </div>
        </div>
    </Teleport>
</template>