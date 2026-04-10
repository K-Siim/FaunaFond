<script setup>
import { ref } from "vue";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    pet: { type: Object, required: true },
    pendingFiles: { type: Object, required: true },
    formatDate: { type: Function, required: true },
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
]);

const expandedVisitId = ref(null);
const expandedVaccineId = ref(null);
const expandedMedicationId = ref(null);

function toggleVisit(id) {
    expandedVisitId.value = expandedVisitId.value === id ? null : id;
}
function toggleVaccine(id) {
    expandedVaccineId.value = expandedVaccineId.value === id ? null : id;
}
function toggleMedication(id) {
    expandedMedicationId.value = expandedMedicationId.value === id ? null : id;
}
</script>

<template>
    <div class="flex flex-col gap-6 p-4 pt-20 pb-12">

        <!-- Info -->
        <section class="bg-[#FFFDF5] p-6 rounded-2xl w-full">
            <div class="flex flex-col gap-4 text-[#275342]">
                <div class="flex flex-row gap-4 items-start">
                    <img
                        :src="pet.photo_url?.medium || '/images/default-pet.png'"
                        alt="Lemmiku pilt"
                        class="w-32 h-32 object-cover rounded-xl border border-[#275342]/20 flex-shrink-0"
                    />
                    <div class="flex flex-col flex-1 gap-1">
                        <div class="flex justify-end gap-2 mb-1">
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
                        <p class="text-2xl font-bold text-[#275342]">{{ pet.name }}</p>
                        <p class="text-sm text-[#275342]/70">{{ pet.species }} · {{ pet.breed }}</p>
                        <p class="text-sm text-[#275342]/70">{{ pet.gender }}</p>
                    </div>
                </div>

                <div class="border-t border-[#275342]/10 pt-4 flex flex-col gap-2">
                    <p class="flex flex-row justify-between text-sm">
                        <strong>Kiip</strong> <span>{{ pet.chip || '—' }}</span>
                    </p>
                    <p class="flex flex-row justify-between text-sm">
                        <strong>Sünnikuupäev</strong> <span>{{ pet.formatted_dob }} ({{ pet.age }})</span>
                    </p>
                    <p class="flex flex-row justify-between text-sm">
                        <strong>Kaal</strong> <span>{{ pet.weight }} kg</span>
                    </p>
                    <div v-if="pet.description" class="pt-2">
                        <p class="text-sm font-semibold mb-1">Kirjeldus</p>
                        <p class="text-sm text-[#275342]/70">{{ pet.description }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Meditsiiniline info -->
        <section class="bg-[#FFFDF5] p-6 rounded-2xl w-full">
            <div class="flex flex-col gap-4">
                <div class="flex flex-row justify-between items-center">
                    <h3 class="text-lg font-semibold text-[#275342]">Meditsiiniline info</h3>
                    <button
                        @click="$emit('open-medical-modal')"
                        class="text-[#275342] text-lg font-bold px-2 border border-[#275342] rounded-full hover:bg-[#275342] hover:text-white transition"
                    >+</button>
                </div>

                <!-- Vaktsiinid -->
                <div class="flex flex-col gap-2">
                    <h4 class="text-sm font-semibold text-[#275342]">Vaktsiinid</h4>
                    <p v-if="!pet.vaccines || pet.vaccines.length === 0" class="text-sm text-[#275342]/60 text-center py-2">
                        Vaktsiine pole veel lisatud.
                    </p>
                    <div
                        v-for="vaccine in pet.vaccines"
                        :key="vaccine.id"
                        class="bg-white border border-[#275342]/20 rounded-2xl overflow-hidden"
                    >
                        <button
                            class="w-full flex items-center justify-between px-4 py-3 hover:bg-gray-50 transition"
                            @click="toggleVaccine(vaccine.id)"
                        >
                            <div class="flex flex-col items-start text-left">
                                <span class="text-sm font-semibold text-[#275342]">{{ vaccine.name }}</span>
                                <span class="text-xs text-[#275342]/60 mt-0.5">{{ formatDate(vaccine.administered_date) }}</span>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#275342] transition-transform duration-200" :class="expandedVaccineId === vaccine.id ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div v-if="expandedVaccineId === vaccine.id" class="px-4 pb-4 border-t border-[#275342]/10">
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

                <!-- Ravimid -->
                <div class="flex flex-col gap-2 mt-2">
                    <h4 class="text-sm font-semibold text-[#275342]">Ravimid</h4>
                    <p v-if="!pet.medications || pet.medications.length === 0" class="text-sm text-[#275342]/60 text-center py-2">
                        Ravimeid pole veel lisatud.
                    </p>
                    <div
                        v-for="medication in pet.medications"
                        :key="medication.id"
                        class="bg-white border border-[#275342]/20 rounded-2xl overflow-hidden"
                    >
                        <button
                            class="w-full flex items-center justify-between px-4 py-3 hover:bg-gray-50 transition"
                            @click="toggleMedication(medication.id)"
                        >
                            <div class="flex flex-col items-start text-left">
                                <span class="text-sm font-semibold text-[#275342]">{{ medication.name }}</span>
                                <span class="text-xs text-[#275342]/60 mt-0.5">{{ medication.dose_amount }}{{ medication.dose_unit }} · {{ medication.frequency_per_day }}x päevas</span>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#275342] transition-transform duration-200" :class="expandedMedicationId === medication.id ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div v-if="expandedMedicationId === medication.id" class="px-4 pb-4 border-t border-[#275342]/10">
                            <div class="mt-3 space-y-1 text-sm text-[#275342]">
                                <p><strong>Algas:</strong> {{ formatDate(medication.start_date) }}</p>
                                <p v-if="medication.end_date"><strong>Lõpeb:</strong> {{ formatDate(medication.end_date) }}</p>
                            </div>
                            <div class="flex justify-end mt-3">
                                <button @click="$emit('delete-medication', medication.id)" class="text-xs text-red-400 hover:text-red-600 transition">Kustuta</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Meeldetuletused -->
        <section class="bg-[#FFFDF5] p-6 rounded-2xl w-full">
            <div class="flex flex-row justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-[#275342]">Meeldetuletused</h3>
                <button class="text-[#275342] text-lg font-bold px-2 border border-[#275342] rounded-full hover:bg-[#275342] hover:text-white transition">+</button>
            </div>
            <p class="text-sm text-[#275342]/60 text-center py-2">Meeldetuletusi pole veel lisatud.</p>
        </section>

        <!-- Arstivisiidid -->
        <section class="bg-[#FFFDF5] p-6 rounded-2xl w-full">
            <div class="flex flex-row justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-[#275342]">Arstivisiitide logi</h3>
                <button
                    @click="$emit('open-vet-modal')"
                    class="text-[#275342] text-lg font-bold px-2 border border-[#275342] rounded-full hover:bg-[#275342] hover:text-white transition"
                >+</button>
            </div>

            <div class="flex flex-col gap-3">
                <p v-if="!pet.vet_visits || pet.vet_visits.length === 0" class="text-sm text-[#275342]/60 text-center py-2">
                    Arstivisiite pole veel lisatud.
                </p>
                <div
                    v-for="visit in pet.vet_visits"
                    :key="visit.id"
                    class="bg-white border border-[#275342]/20 rounded-2xl overflow-hidden"
                >
                    <button
                        class="w-full flex items-center justify-between px-4 py-3 hover:bg-gray-50 transition"
                        @click="toggleVisit(visit.id)"
                    >
                        <div class="flex flex-col items-start text-left">
                            <span class="text-sm font-semibold text-[#275342]">{{ visit.clinic_name }}</span>
                            <span class="text-xs text-[#275342]/60 mt-0.5">{{ formatDate(visit.visit_date) }}</span>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#275342] transition-transform duration-200" :class="expandedVisitId === visit.id ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div v-if="expandedVisitId === visit.id" class="px-4 pb-4 border-t border-[#275342]/10">
                        <p class="text-sm text-[#275342]/80 whitespace-pre-wrap mt-3 leading-relaxed">
                            {{ visit.log || "Logi puudub." }}
                        </p>
                        <div v-for="file in visit.files" :key="file.id" class="flex items-center gap-2 bg-[#E7F0E4]/40 rounded-lg px-3 py-2 mt-2">
                            <a :href="route('vet-visit-files.download', file.id)" class="text-xs text-[#275342] truncate hover:underline flex-1">
                                📄 {{ file.original_name }}
                            </a>
                            <button @click="$emit('delete-file', file.id)" class="text-[#275342] hover:text-red-500 transition ml-auto">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        <div class="mt-3">
                            <label class="flex items-center justify-center gap-2 border-2 border-dashed border-[#275342]/30 rounded-xl py-3 cursor-pointer hover:border-[#275342] transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#275342]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                </svg>
                                <span class="text-xs text-[#275342]">Lisa PDF</span>
                                <input type="file" multiple accept=".pdf" class="hidden" @change="$emit('upload-files', $event, visit)" />
                            </label>
                            <div v-if="pendingFiles[visit.id]?.length" class="mt-2 flex flex-col gap-1">
                                <div
                                    v-for="(file, index) in pendingFiles[visit.id]"
                                    :key="index"
                                    class="flex items-center justify-between bg-[#E7F0E4] rounded-lg px-3 py-1.5"
                                >
                                    <span class="text-xs text-[#275342] truncate max-w-[180px]">📄 {{ file.name }}</span>
                                    <button @click="$emit('remove-pending-file', visit.id, index)" class="text-[#275342] hover:text-red-500 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                                <button
                                    @click="$emit('submit-files', visit)"
                                    class="mt-1 w-full text-xs bg-[#275342] text-white py-2 rounded-lg hover:bg-[#1e3f31] transition"
                                >
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
        </section>
    </div>
</template>