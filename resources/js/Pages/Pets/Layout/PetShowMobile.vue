<script setup>
import { ref } from "vue";
import { Link } from "@inertiajs/vue3";
import RemindersSection from "@/Components/RemindersSection.vue";
import { CircleAlert, AlarmClock, Bell, Sun, Moon, X } from '@lucide/vue';

const props = defineProps({
    pet:                       { type: Object,   required: true },
    pendingFiles:              { type: Object,   required: true },
    formatDate:                { type: Function, required: true },
    vaccineExpiryReminders:    { type: Array,    default: () => [] },
    medicationRepeatReminders: { type: Array,    default: () => [] },
});

defineEmits([
    "open-vet-modal",
    "open-medical-modal",
    "confirm-delete",
    "delete-visit",
    "delete-vaccine",
    "delete-medication",
    "delete-file",
    "upload-files",
    "remove-pending-file",
    "submit-files",
    "delete-reminder",
]);

const expandedVisitId      = ref(null);
const expandedVaccineId    = ref(null);
const expandedMedicationId = ref(null);
const expandedReminderId   = ref(null);
const showReminderForm     = ref(false);
const dismissedExpiries    = ref(new Set());

function toggleVisit(id) {
    expandedVisitId.value = expandedVisitId.value === id ? null : id;
}
function toggleVaccine(id) {
    expandedVaccineId.value = expandedVaccineId.value === id ? null : id;
}
function toggleMedication(id) {
    expandedMedicationId.value = expandedMedicationId.value === id ? null : id;
}
function toggleReminder(id) {
    expandedReminderId.value = expandedReminderId.value === id ? null : id;
}
function dismissExpiry(id) {
    dismissedExpiries.value = new Set([...dismissedExpiries.value, id]);
}

const vaccineReminders  = () => (props.pet.reminders ?? []).filter(r => r.type === "vaccine");
const vetReminders      = () => (props.pet.reminders ?? []).filter(r => r.type === "vet_visit");
const medicineReminders = () => (props.pet.reminders ?? []).filter(r => r.type === "medicine");
const visibleExpiries   = () => props.vaccineExpiryReminders.filter(r => !dismissedExpiries.value.has(r.id));

const hasAnyReminders = () =>
    visibleExpiries().length +
    (props.pet.reminders ?? []).length +
    props.medicationRepeatReminders.length > 0;

function isNight(time) {
    if (!time) return false;
    const h = parseInt(time.split(":")[0]);
    return h >= 18 || h < 6;
}
</script>

<template>
    <div class="flex flex-col gap-10 p-6 justify-center items-center w-full">
        <section class="bg-[#FFFDF5] p-6 rounded-2xl w-full max-w-md mx-auto mt-20">
            <div class="flex flex-col gap-6">
                <div class="flex flex-col gap-2 text-[#275342]">
                    <div class="flex flex-row justify-between items-center h-full gap-4">
                        <img
                            :src="pet.photo_url?.medium || '/images/default-pet.png'"
                            alt="Lemmiku pilt"
                            class="w-32 h-32 object-cover rounded-xl border"
                        />
                        <div class="flex flex-col justify-start h-60 w-2/3">
                            <div class="w-full h-1/3 flex justify-end items-start gap-2">
                                <Link
                                    :href="`/pets/${pet.id}/edit`"
                                    class="flex justify-center items-center border border-[#275342] text-[#275342] rounded-full hover:bg-[#275342] hover:text-white transition w-8 h-8"
                                    title="Muuda"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 3.487a2.25 2.25 0 113.182 3.182L7.5 19.213l-4 1 1-4 12.362-12.726z" />
                                    </svg>
                                </Link>
                                <button
                                    @click="$emit('confirm-delete')"
                                    class="flex justify-center items-center border border-red-400 text-red-400 rounded-full hover:bg-red-400 hover:text-white transition w-8 h-8"
                                    title="Kustuta"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m2 0a1 1 0 00-1-1h-4a1 1 0 00-1 1H5" />
                                    </svg>
                                </button>
                            </div>
                            <p class="text-2xl h-2/3 font-bold text-[#275342] w-full">{{ pet.name }}</p>
                        </div>
                    </div>
                    <p class="flex flex-row justify-between"><strong>Kiip:</strong> {{ pet.chip }}</p>
                    <p class="flex flex-row justify-between"><strong>Liik:</strong> {{ pet.species }}</p>
                    <p class="flex flex-row justify-between"><strong>Tõug:</strong> {{ pet.breed }}</p>
                    <p class="flex flex-row justify-between"><strong>Sugu:</strong> {{ pet.gender }}</p>
                    <p class="flex flex-row justify-between"><strong>Sünnikuupäev:</strong> {{ pet.formatted_dob }} ({{ pet.age }})</p>
                    <p class="flex flex-row justify-between"><strong>Kaal:</strong> {{ pet.weight }} kg</p>
                    <div class="flex flex-col justify-center items-center">
                        <p class="flex flex-row justify-between"><strong>Kirjeldus</strong></p>
                        <p class="text-md">{{ pet.description || "Kirjeldus puudub" }}</p>
                    </div>
                </div>
            </div>
        </section>
        <section class="bg-[#FFFDF5] p-6 rounded-2xl w-full max-w-md mx-auto">
            <div class="flex flex-col gap-6">
                <div class="flex flex-row justify-between items-center">
                    <h3 class="text-lg font-semibold text-[#275342]">Meditsiiniline info</h3>
                    <button
                        @click="$emit('open-medical-modal')"
                        class="text-[#275342] text-lg font-bold pl-2 pr-2 border border-[#275342] rounded-full hover:bg-[#275342] hover:text-white transition"
                    >+</button>
                </div>
                <div class="flex flex-col gap-3">
                    <h4 class="text-md font-medium text-[#275342]">Vaktsiinid</h4>
                    <p v-if="!pet.vaccines || pet.vaccines.length === 0" class="text-md text-[#275342] text-center py-4">
                        Vaktsiine pole veel lisatud.
                    </p>
                    <div v-for="vaccine in pet.vaccines" :key="vaccine.id" class="bg-white border border-gray-100 rounded-2xl overflow-hidden shadow-sm">
                        <button class="w-full flex items-center justify-between px-3 py-3 hover:bg-gray-50 transition" @click="toggleVaccine(vaccine.id)">
                            <div class="flex flex-col items-start text-left">
                                <span class="text-md font-semibold text-[#275342]">{{ vaccine.name }}</span>
                                <span class="text-md text-[#275342] mt-0.5">{{ formatDate(vaccine.administered_date) }}</span>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#275342] transition-transform duration-200" :class="expandedVaccineId === vaccine.id ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div v-if="expandedVaccineId === vaccine.id" class="px-4 pb-4 border-t border-gray-100">
                            <div class="mt-3 space-y-1 text-sm text-[#275342]">
                                <p v-if="vaccine.expiry_date"><strong>Aegub:</strong> {{ formatDate(vaccine.expiry_date) }}</p>
                                <p v-if="vaccine.batch_number"><strong>Partii:</strong> {{ vaccine.batch_number }}</p>
                            </div>
                            <div class="flex justify-end mt-3">
                                <button @click="$emit('delete-vaccine', vaccine.id)" class="text-xs text-red-400 hover:text-red-600 transition">Kustuta</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col gap-3 mt-4">
                    <h4 class="text-md font-medium text-[#275342]">Ravimid</h4>
                    <p v-if="!pet.medications || pet.medications.length === 0" class="text-md text-[#275342] text-center py-4">
                        Ravimeid pole veel lisatud.
                    </p>
                    <div v-for="medication in pet.medications" :key="medication.id" class="bg-white border border-gray-100 rounded-2xl overflow-hidden shadow-sm">
                        <button class="w-full flex items-center justify-between px-3 py-3 hover:bg-gray-50 transition" @click="toggleMedication(medication.id)">
                            <div class="flex flex-col items-start text-left">
                                <span class="text-md font-semibold text-[#275342]">{{ medication.name }}</span>
                                <span class="text-md text-[#275342] mt-0.5">
                                    {{ medication.dose_amount }}{{ medication.dose_unit }} ·
                                    {{ medication.frequency_amount }}x {{ medication.frequency_unit }}
                                </span>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#275342] transition-transform duration-200" :class="expandedMedicationId === medication.id ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div v-if="expandedMedicationId === medication.id" class="px-4 pb-4 border-t border-gray-100">
                            <div class="flex justify-end mt-3">
                                <button @click="$emit('delete-medication', medication.id)" class="text-xs text-red-400 hover:text-red-600 transition">Kustuta</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="bg-[#FFFDF5] p-6 rounded-2xl w-full max-w-md mx-auto">
            <div class="flex flex-col gap-6">
                <div class="flex flex-row justify-between items-center">
                    <h3 class="text-lg font-semibold text-[#275342]">Meeldetuletused</h3>
                    <button
                        @click="showReminderForm = true"
                        class="text-[#275342] text-lg font-bold pl-2 pr-2 border border-[#275342] rounded-full hover:bg-[#275342] hover:text-white transition"
                    >+</button>
                </div>
                <p v-if="!hasAnyReminders()" class="text-md text-[#275342] text-center py-4">
                    Meeldetuletusi pole veel lisatud.
                </p>
                <div v-else class="flex flex-col gap-3">
                    <div
                        v-for="r in visibleExpiries()"
                        :key="r.id"
                        class="bg-[#FFCBC7] hover:bg-[#FFA8B0] text-[#275342] border border-red-100 rounded-2xl overflow-hidden shadow-sm"
                    >
                        <div class="w-full flex items-center justify-between text-[#275342] px-3 py-3">
                            <div class="flex items-center gap-3 min-w-0">
                                <CircleAlert class="flex-shrink-0" />
                                <div class="flex flex-col items-start text-left">
                                    <span class="text-md font-semibold">{{ r.pet_name }}</span>
                                    <span class="text-md mt-0.5">
                                        {{ r.name }} {{ r.is_expired ? 'aegus' : 'aegub' }}
                                        <strong>{{ r.end_date }}</strong>
                                    </span>
                                </div>
                            </div>
                            <button
                                @click="dismissExpiry(r.id)"
                                class="flex-shrink-0 ml-2 h-8 w-8 flex items-center justify-center text-[#275342] hover:text-red-700 transition"
                            >
                                <X class="w-4 h-4" />
                            </button>
                        </div>
                    </div>
                    <div
                        v-for="r in vaccineReminders()"
                        :key="r.id"
                        class="bg-[#FFF6CA] text-[#275342] border border-yellow-100 rounded-2xl overflow-hidden shadow-sm"
                    >
                        <button
                            class="w-full flex items-center justify-between px-3 py-3 hover:bg-[#FFEEA1] transition"
                            @click="toggleReminder(r.id)"
                        >
                            <div class="flex items-center gap-3 min-w-0">
                                <AlarmClock class="flex-shrink-0" />
                                <div class="flex flex-col items-start text-left">
                                    <span class="text-md font-semibold">{{ r.pet?.name ?? pet.name }}</span>
                                    <span class="text-md mt-0.5">
                                        {{ r.name }} — vaktsineerimine
                                        <strong>{{ formatDate(r.reminder_date) }}</strong>
                                        <span v-if="r.reminder_time"> kell <strong>{{ r.reminder_time }}</strong></span>
                                    </span>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 flex-shrink-0 ml-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#275342] transition-transform duration-200" :class="expandedReminderId === r.id ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                                <button @click.stop="$emit('delete-reminder', r.id)" class="hover:text-red-500 transition text-xl leading-none">×</button>
                            </div>
                        </button>
                        <div v-if="expandedReminderId === r.id && r.notes" class="px-4 pb-4 border-t border-yellow-100">
                            <p class="text-sm mt-3 leading-relaxed whitespace-pre-wrap">{{ r.notes }}</p>
                        </div>
                    </div>
                    <div
                        v-for="r in vetReminders()"
                        :key="r.id"
                        class="bg-[#D5EEFF] text-[#275342] border border-blue-100 rounded-2xl overflow-hidden shadow-sm"
                    >
                        <button
                            class="w-full flex items-center justify-between px-3 py-3 hover:bg-[#B4E1FF] transition"
                            @click="toggleReminder(r.id)"
                        >
                            <div class="flex items-center gap-3 min-w-0">
                                <Bell class="flex-shrink-0" />
                                <div class="flex flex-col items-start text-left">
                                    <span class="text-md font-semibold">{{ r.pet?.name ?? pet.name }}</span>
                                    <span class="text-md mt-0.5">
                                        {{ r.name }} — arstivisiit
                                        <strong>{{ formatDate(r.reminder_date) }}</strong>
                                        <span v-if="r.reminder_time"> kell <strong>{{ r.reminder_time }}</strong></span>
                                    </span>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 flex-shrink-0 ml-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#275342] transition-transform duration-200" :class="expandedReminderId === r.id ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                                <button @click.stop="$emit('delete-reminder', r.id)" class="hover:text-red-500 transition text-xl leading-none">×</button>
                            </div>
                        </button>
                        <div v-if="expandedReminderId === r.id && r.notes" class="px-4 pb-4 border-t border-blue-100">
                            <p class="text-sm mt-3 leading-relaxed whitespace-pre-wrap">{{ r.notes }}</p>
                        </div>
                    </div>
                    <div
                        v-for="r in medicineReminders()"
                        :key="r.id"
                        class="bg-[#DAF2D0] text-[#275342] border border-green-100 rounded-2xl overflow-hidden shadow-sm"
                    >
                        <button
                            class="w-full flex items-center justify-between px-3 py-3 hover:bg-[#C4EDB4] transition"
                            @click="toggleReminder(r.id)"
                        >
                            <div class="flex items-center gap-3 min-w-0">
                                <component :is="isNight(r.reminder_time) ? Moon : Sun" class="flex-shrink-0" />
                                <div class="flex flex-col items-start text-left">
                                    <span class="text-md font-semibold">{{ r.pet?.name ?? pet.name }}</span>
                                    <span class="text-md mt-0.5">
                                        {{ r.name }} — kellaaeg
                                        <strong>{{ r.reminder_time || formatDate(r.reminder_date) }}</strong>
                                    </span>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 flex-shrink-0 ml-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#275342] transition-transform duration-200" :class="expandedReminderId === r.id ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                                <button @click.stop="$emit('delete-reminder', r.id)" class="hover:text-red-500 transition text-xl leading-none">×</button>
                            </div>
                        </button>
                        <div v-if="expandedReminderId === r.id && r.notes" class="px-4 pb-4 border-t border-green-100">
                            <p class="text-sm mt-3 leading-relaxed whitespace-pre-wrap">{{ r.notes }}</p>
                        </div>
                    </div>
                    <div
                        v-for="r in medicationRepeatReminders"
                        :key="r.id"
                        class="bg-[#DAF2D0] text-[#275342] border border-green-100 rounded-2xl overflow-hidden shadow-sm"
                    >
                        <div class="w-full flex items-center justify-between px-3 py-3">
                            <div class="flex items-center gap-3 min-w-0">
                                <component :is="isNight(r.reminder_time) ? Moon : Sun" class="flex-shrink-0" />
                                <div class="flex flex-col items-start text-left">
                                    <span class="text-md font-semibold">{{ r.pet_name }}</span>
                                    <span class="text-md mt-0.5">
                                        {{ r.name }} — kellaaeg
                                        <strong>{{ r.reminder_time }}</strong>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <RemindersSection
            :pet-id="pet.id"
            :show-form="showReminderForm"
            @close-form="showReminderForm = false"
        />
        <section class="bg-[#FFFDF5] p-6 rounded-2xl w-full max-w-md mx-auto">
            <div class="flex flex-col gap-6">
                <div class="flex flex-row justify-between items-center">
                    <h3 class="text-base text-lg font-semibold text-[#275342]">Arstivisiitide logi</h3>
                    <button
                        @click="$emit('open-vet-modal')"
                        class="text-[#275342] text-lg font-bold pl-2 pr-2 border border-[#275342] rounded-full hover:bg-[#275342] hover:text-white transition"
                    >+</button>
                </div>

                <div class="flex flex-col gap-3">
                    <p v-if="!pet.vet_visits || pet.vet_visits.length === 0" class="text-md text-[#275342] text-center py-4">
                        Arstivisiite pole veel lisatud.
                    </p>
                    <div v-for="visit in pet.vet_visits" :key="visit.id" class="bg-white border border-gray-100 rounded-2xl overflow-hidden shadow-sm">
                        <button class="w-full flex items-center justify-between px-4 py-3 hover:bg-gray-50 transition" @click="toggleVisit(visit.id)">
                            <div class="flex flex-col items-start text-left">
                                <span class="text-sm font-semibold text-[#275342]">{{ visit.clinic_name }}</span>
                                <span class="text-xs text-[#275342] mt-0.5">{{ formatDate(visit.visit_date) }}</span>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#275342] transition-transform duration-200" :class="expandedVisitId === visit.id ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div v-if="expandedVisitId === visit.id" class="px-4 pb-4 border-t border-gray-100">
                            <p class="text-sm text-[#275342] whitespace-pre-wrap mt-3 leading-relaxed">
                                {{ visit.log || "Logi puudub." }}
                            </p>
                            <div v-for="file in visit.files" :key="file.id" class="flex items-center gap-2 bg-gray-50 rounded-lg px-3 py-2">
                                <span class="text-red-400">📄</span>
                                <a :href="route('vet-visit-files.download', file.id)" class="text-xs text-[#275342] truncate hover:underline flex-1">
                                    {{ file.original_name }}
                                </a>
                                <button @click="$emit('delete-file', file.id)" class="text-[#275342] hover:text-red-500 transition ml-auto">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                            <div class="mt-3">
                                <label class="flex items-center justify-center gap-2 border-2 border-dashed border-gray-200 rounded-xl py-3 cursor-pointer hover:border-[#2D5A3D] transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#275342]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                    </svg>
                                    <span class="text-xs text-[#275342]">Lisa PDF</span>
                                    <input type="file" multiple accept=".pdf" class="hidden" @change="$emit('upload-files', $event, visit)" />
                                </label>
                                <div v-if="pendingFiles[visit.id]?.length" class="mt-2 flex flex-col gap-1">
                                    <div v-for="(file, index) in pendingFiles[visit.id]" :key="index" class="flex items-center justify-between bg-gray-50 rounded-lg px-3 py-1.5">
                                        <span class="text-xs text-[#275342] truncate max-w-[180px]">📄 {{ file.name }}</span>
                                        <button @click="$emit('remove-pending-file', visit.id, index)" class="text-[#275342] hover:text-red-500 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                    <button @click="$emit('submit-files', visit)" class="mt-1 w-full text-xs bg-[#275342] text-white py-2 rounded-lg hover:bg-[#1e3f31] transition">
                                        Lae üles
                                    </button>
                                </div>
                            </div>
                            <div class="flex justify-end mt-3">
                                <button @click="$emit('delete-visit', visit.id)" class="text-xs text-red-400 hover:text-red-600 transition">
                                    Kustuta
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>