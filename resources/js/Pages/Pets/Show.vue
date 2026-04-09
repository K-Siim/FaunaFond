<script setup>
import { ref } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import VetVisitLogModal from "@/Components/VetVisitLogModal.vue";
import MedicalRecordModal from "@/Components/MedicalRecordModal.vue";

const props = defineProps({
    pet: Object,
});

const showVetModal = ref(false);
const showMedicalModal = ref(false);
const showDeleteModal = ref(false);

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

const deleteForm = useForm({});

function deleteVisit(visitId) {
    if (!confirm("Kustuta see arstivisiit?")) return;
    deleteForm.delete(
        route("vet-visits.destroy", { pet: props.pet.id, vetVisit: visitId }),
        { preserveScroll: true },
    );
}

function deleteVaccine(vaccineId) {
    if (!confirm("Kustuta see vaktsiin?")) return;
    deleteForm.delete(
        route("vaccines.destroy", { pet: props.pet.id, vaccine: vaccineId }),
        { preserveScroll: true },
    );
}

function deleteMedication(medicationId) {
    if (!confirm("Kustuta see ravim?")) return;
    deleteForm.delete(
        route("medications.destroy", {
            pet: props.pet.id,
            medication: medicationId,
        }),
        { preserveScroll: true },
    );
}

function formatDate(dateStr) {
    if (!dateStr) return "";
    const d = new Date(dateStr);
    return d.toLocaleDateString("et-EE", {
        day: "2-digit",
        month: "2-digit",
        year: "numeric",
    });
}

const pendingFiles = ref({});

function uploadFiles(e, visit) {
    pendingFiles.value[visit.id] = Array.from(e.target.files);
}

function removePendingFile(visitId, index) {
    pendingFiles.value[visitId].splice(index, 1);
}

const uploadForm = useForm({ files: [] });

function submitFiles(visit) {
    uploadForm.files = pendingFiles.value[visit.id];
    uploadForm.post(
        route("vet-visits.upload-files", {
            pet: props.pet.id,
            vetVisit: visit.id,
        }),
        {
            forceFormData: true,
            preserveScroll: true,
            onSuccess: () => {
                pendingFiles.value[visit.id] = [];
            },
        },
    );
}

const deletePetForm = useForm({});

function confirmDelete() {
    showDeleteModal.value = true;
}

function deletePet() {
    deletePetForm.delete(route("pets.destroy", props.pet.id));
}

function deleteFile(fileId) {
    if (!confirm("Kustuta see fail?")) return;
    deleteForm.delete(route("vet-visit-files.destroy", fileId), {
        preserveScroll: true,
    });
}
</script>

<template>
    <Head :title="pet.name" />

    <AuthenticatedLayout>
        <div
            class="flex flex-col gap-10 p-6 justify-center items-center w-full"
        >
            <section
                class="bg-[#FFFDF5] p-6 rounded-2xl w-full max-w-md mx-auto mt-20"
            >
                <div class="flex flex-col gap-6">
                    <div class="flex flex-col gap-2">
                        <div
                            class="flex flex-row justify-between items-start gap-4"
                        >
                            <img
                                :src="
                                    pet.photo_url?.medium ||
                                    '/images/default-pet.png'
                                "
                                alt="Lemmiku pilt"
                                class="w-32 h-32 object-cover rounded-xl border"
                            />
                            <div class="flex flex-col justify-start w-2/3">
                                <div class="w-full flex justify-end gap-2">
                                    <Link
                                        :href="`/pets/${pet.id}/edit`"
                                        class="flex justify-center items-center border border-[#275342] text-[#275342] rounded-full hover:bg-[#275342] hover:text-white transition w-8 h-8"
                                        title="Muuda"
                                    >
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="w-4 h-4"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            stroke-width="2"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M16.862 3.487a2.25 2.25 0 113.182 3.182L7.5 19.213l-4 1 1-4 12.362-12.726z"
                                            />
                                        </svg>
                                    </Link>

                                    <button
                                        @click="confirmDelete"
                                        class="flex justify-center items-center border border-red-400 text-red-400 rounded-full hover:bg-red-400 hover:text-white transition w-8 h-8"
                                        title="Kustuta"
                                    >
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="w-4 h-4"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            stroke-width="2"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m2 0a1 1 0 00-1-1h-4a1 1 0 00-1 1H5"
                                            />
                                        </svg>
                                    </button>
                                </div>
                                <p
                                    class="text-2xl font-bold text-gray-800 w-full"
                                >
                                    {{ pet.name }}
                                </p>
                            </div>
                        </div>
                        <p class="flex flex-row justify-between">
                            <strong>Kiip:</strong> {{ pet.chip }}
                        </p>
                        <p class="flex flex-row justify-between">
                            <strong>Liik:</strong> {{ pet.species }}
                        </p>
                        <p class="flex flex-row justify-between">
                            <strong>Tõug:</strong> {{ pet.breed }}
                        </p>
                        <p class="flex flex-row justify-between">
                            <strong>Sugu:</strong> {{ pet.gender }}
                        </p>
                        <p class="flex flex-row justify-between">
                            <strong>Sünnikuupäev:</strong>
                            {{ formatDate(pet?.dob) }}
                        </p>
                        <p class="flex flex-row justify-between">
                            <strong>Kaal:</strong> {{ pet.weight }}
                        </p>
                        <div class="flex flex-col justify-center items-center">
                            <p class="flex flex-row justify-between">
                                <strong>Kirjeldus</strong>
                            </p>
                            <p class="text-sm text-gray-600">
                                {{ pet.description || "Kirjeldus puudub" }}
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <section
                class="bg-[#FFFDF5] p-6 rounded-2xl w-full max-w-md mx-auto"
            >
                <div class="flex flex-col gap-6">
                    <div class="flex flex-row justify-between items-center">
                        <h3 class="text-base font-semibold text-[#275342]">
                            Meditsiiniline info
                        </h3>
                        <button
                            @click="showMedicalModal = true"
                            class="text-green-700 font-medium flex justify-center items-center border border-green-700 rounded-full hover:bg-green-700 hover:text-white transition w-5 h-5 leading-none"
                        >
                            +
                        </button>
                    </div>

                    <div class="flex flex-col gap-3">
                        <h4 class="text-sm font-medium text-gray-700">
                            Vaktsiinid
                        </h4>
                        <p
                            v-if="!pet.vaccines || pet.vaccines.length === 0"
                            class="text-sm text-gray-400 text-center py-4"
                        >
                            Vaktsiine pole veel lisatud.
                        </p>
                        <div
                            v-for="vaccine in pet.vaccines"
                            :key="vaccine.id"
                            class="bg-white border border-gray-100 rounded-2xl overflow-hidden shadow-sm"
                        >
                            <button
                                class="w-full flex items-center justify-between px-4 py-3 hover:bg-gray-50 transition"
                                @click="toggleVaccine(vaccine.id)"
                            >
                                <div
                                    class="flex flex-col items-start text-left"
                                >
                                    <span
                                        class="text-sm font-semibold text-gray-800"
                                        >{{ vaccine.name }}</span
                                    >
                                    <span
                                        class="text-xs text-gray-400 mt-0.5"
                                        >{{
                                            formatDate(
                                                vaccine.administered_date,
                                            )
                                        }}</span
                                    >
                                </div>
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="w-5 h-5 text-gray-400 transition-transform duration-200"
                                    :class="
                                        expandedVaccineId === vaccine.id
                                            ? 'rotate-180'
                                            : ''
                                    "
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M19 9l-7 7-7-7"
                                    />
                                </svg>
                            </button>
                            <div
                                v-if="expandedVaccineId === vaccine.id"
                                class="px-4 pb-4 border-t border-gray-100"
                            >
                                <div
                                    class="mt-3 space-y-1 text-sm text-gray-600"
                                >
                                    <p v-if="vaccine.expiry_date">
                                        <strong>Aegub:</strong>
                                        {{ formatDate(vaccine.expiry_date) }}
                                    </p>
                                    <p v-if="vaccine.batch_number">
                                        <strong>Partii:</strong>
                                        {{ vaccine.batch_number }}
                                    </p>
                                </div>
                                <div class="flex justify-end mt-3">
                                    <button
                                        @click="deleteVaccine(vaccine.id)"
                                        class="text-xs text-red-400 hover:text-red-600 transition"
                                    >
                                        Kustuta
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col gap-3 mt-4">
                        <h4 class="text-sm font-medium text-gray-700">
                            Ravimid
                        </h4>
                        <p
                            v-if="
                                !pet.medications || pet.medications.length === 0
                            "
                            class="text-sm text-gray-400 text-center py-4"
                        >
                            Ravimeid pole veel lisatud.
                        </p>
                        <div
                            v-for="medication in pet.medications"
                            :key="medication.id"
                            class="bg-white border border-gray-100 rounded-2xl overflow-hidden shadow-sm"
                        >
                            <button
                                class="w-full flex items-center justify-between px-4 py-3 hover:bg-gray-50 transition"
                                @click="toggleMedication(medication.id)"
                            >
                                <div
                                    class="flex flex-col items-start text-left"
                                >
                                    <span
                                        class="text-sm font-semibold text-gray-800"
                                        >{{ medication.name }}</span
                                    >
                                    <span class="text-xs text-gray-400 mt-0.5"
                                        >{{ medication.dose_amount
                                        }}{{ medication.dose_unit }} ·
                                        {{ medication.frequency_per_day }}x
                                        päevas</span
                                    >
                                </div>
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="w-5 h-5 text-gray-400 transition-transform duration-200"
                                    :class="
                                        expandedMedicationId === medication.id
                                            ? 'rotate-180'
                                            : ''
                                    "
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M19 9l-7 7-7-7"
                                    />
                                </svg>
                            </button>
                            <div
                                v-if="expandedMedicationId === medication.id"
                                class="px-4 pb-4 border-t border-gray-100"
                            >
                                <div
                                    class="mt-3 space-y-1 text-sm text-gray-600"
                                >
                                    <p>
                                        <strong>Algas:</strong>
                                        {{ formatDate(medication.start_date) }}
                                    </p>
                                    <p v-if="medication.end_date">
                                        <strong>Lõpeb:</strong>
                                        {{ formatDate(medication.end_date) }}
                                    </p>
                                </div>
                                <div class="flex justify-end mt-3">
                                    <button
                                        @click="deleteMedication(medication.id)"
                                        class="text-xs text-red-400 hover:text-red-600 transition"
                                    >
                                        Kustuta
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section
                class="bg-[#FFFDF5] p-6 rounded-2xl w-full max-w-md mx-auto"
            >
                <div class="flex flex-col gap-6">
                    <div class="flex flex-row justify-between items-center">
                        <h3 class="text-base font-semibold text-gray-800">
                            Meeldetuletused
                        </h3>
                        <Link
                            href="/pets/create"
                            class="text-green-700 font-medium flex justify-center items-center border border-green-700 rounded-full hover:bg-green-700 hover:text-white transition w-5 h-5 leading-none"
                            >+</Link
                        >
                    </div>
                    <div class="flex flex-col gap-4">
                        <p class="text-sm text-gray-400 text-center py-4">
                            Meeldetuletusi pole veel lisatud.
                        </p>
                    </div>
                </div>
            </section>

            <section
                class="bg-[#FFFDF5] p-6 rounded-2xl w-full max-w-md mx-auto"
            >
                <div class="flex flex-col gap-6">
                    <div class="flex flex-row justify-between items-center">
                        <h3 class="text-base font-semibold text-gray-800">
                            Arstivisiitide logi
                        </h3>
                        <button
                            @click="showVetModal = true"
                            class="text-green-700 font-medium flex justify-center items-center border border-green-700 rounded-full hover:bg-green-700 hover:text-white transition w-5 h-5 leading-none"
                        >
                            +
                        </button>
                    </div>

                    <div class="flex flex-col gap-3">
                        <p
                            v-if="
                                !pet.vet_visits || pet.vet_visits.length === 0
                            "
                            class="text-sm text-gray-400 text-center py-4"
                        >
                            Arstivisiite pole veel lisatud.
                        </p>
                        <div
                            v-for="visit in pet.vet_visits"
                            :key="visit.id"
                            class="bg-white border border-gray-100 rounded-2xl overflow-hidden shadow-sm"
                        >
                            <button
                                class="w-full flex items-center justify-between px-4 py-3 hover:bg-gray-50 transition"
                                @click="toggleVisit(visit.id)"
                            >
                                <div
                                    class="flex flex-col items-start text-left"
                                >
                                    <span
                                        class="text-sm font-semibold text-gray-800"
                                        >{{ visit.clinic_name }}</span
                                    >
                                    <span
                                        class="text-xs text-gray-400 mt-0.5"
                                        >{{
                                            formatDate(visit.visit_date)
                                        }}</span
                                    >
                                </div>
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="w-5 h-5 text-gray-400 transition-transform duration-200"
                                    :class="
                                        expandedVisitId === visit.id
                                            ? 'rotate-180'
                                            : ''
                                    "
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M19 9l-7 7-7-7"
                                    />
                                </svg>
                            </button>
                            <div
                                v-if="expandedVisitId === visit.id"
                                class="px-4 pb-4 border-t border-gray-100"
                            >
                                <p
                                    class="text-sm text-gray-600 whitespace-pre-wrap mt-3 leading-relaxed"
                                >
                                    {{ visit.log || "Logi puudub." }}
                                </p>
                                <div
                                    v-for="file in visit.files"
                                    :key="file.id"
                                    class="flex items-center gap-2 bg-gray-50 rounded-lg px-3 py-2"
                                >
                                    <span class="text-red-400">📄</span>
                                    <a
                                        :href="
                                            route(
                                                'vet-visit-files.download',
                                                file.id,
                                            )
                                        "
                                        class="text-xs text-gray-700 truncate hover:underline flex-1"
                                    >
                                        {{ file.original_name }}
                                    </a>
                                    <button
                                        @click="deleteFile(file.id)"
                                        class="text-gray-400 hover:text-red-500 transition ml-auto"
                                    >
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="w-3.5 h-3.5"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            stroke-width="2"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M6 18L18 6M6 6l12 12"
                                            />
                                        </svg>
                                    </button>
                                </div>
                                <div class="mt-3">
                                    <label
                                        class="flex items-center justify-center gap-2 border-2 border-dashed border-gray-200 rounded-xl py-3 cursor-pointer hover:border-[#2D5A3D] transition"
                                    >
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="w-4 h-4 text-gray-400"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            stroke-width="2"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"
                                            />
                                        </svg>
                                        <span class="text-xs text-gray-500"
                                            >Lisa PDF</span
                                        >
                                        <input
                                            type="file"
                                            multiple
                                            accept=".pdf"
                                            class="hidden"
                                            @change="
                                                (e) => uploadFiles(e, visit)
                                            "
                                        />
                                    </label>
                                    <div
                                        v-if="
                                            pendingFiles[visit.id] &&
                                            pendingFiles[visit.id].length
                                        "
                                        class="mt-2 flex flex-col gap-1"
                                    >
                                        <div
                                            v-for="(
                                                file, index
                                            ) in pendingFiles[visit.id]"
                                            :key="index"
                                            class="flex items-center justify-between bg-gray-50 rounded-lg px-3 py-1.5"
                                        >
                                            <span
                                                class="text-xs text-gray-600 truncate max-w-[180px]"
                                                >📄 {{ file.name }}</span
                                            >
                                            <button
                                                @click="
                                                    removePendingFile(
                                                        visit.id,
                                                        index,
                                                    )
                                                "
                                                class="text-gray-400 hover:text-red-500 transition"
                                            >
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    class="w-3.5 h-3.5"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke="currentColor"
                                                    stroke-width="2"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        d="M6 18L18 6M6 6l12 12"
                                                    />
                                                </svg>
                                            </button>
                                        </div>
                                        <button
                                            @click="submitFiles(visit)"
                                            class="mt-1 w-full text-xs bg-[#275342] text-white py-2 rounded-lg hover:bg-[#1e3f31] transition"
                                        >
                                            Lae üles
                                        </button>
                                    </div>
                                </div>
                                <div class="flex justify-end mt-3">
                                    <button
                                        @click="deleteVisit(visit.id)"
                                        class="text-xs text-red-400 hover:text-red-600 transition"
                                    >
                                        Kustuta
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </AuthenticatedLayout>

    <!-- Kustutamise modal -->
    <div
        v-if="showDeleteModal"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/40"
    >
        <div
            class="bg-white rounded-2xl p-6 w-80 shadow-xl flex flex-col gap-4"
        >
            <h2 class="text-lg font-semibold text-gray-800 text-center">
                Kustuta lemmik
            </h2>
            <p class="text-sm text-gray-500 text-center">
                Kas oled kindel, et soovid
                <strong>{{ pet.name }}</strong> kustutada? Seda tegevust ei saa
                tagasi võtta.
            </p>
            <div class="flex gap-3 mt-2">
                <button
                    @click="showDeleteModal = false"
                    class="flex-1 py-2 rounded-xl border border-gray-300 text-gray-600 text-sm hover:bg-gray-50 transition"
                >
                    Ei
                </button>
                <button
                    @click="deletePet"
                    class="flex-1 py-2 rounded-xl bg-red-500 text-white text-sm hover:bg-red-600 transition"
                    :disabled="deletePetForm.processing"
                >
                    Jah, kustuta
                </button>
            </div>
        </div>
    </div>

    <VetVisitLogModal
        v-if="showVetModal"
        :pet-id="pet.id"
        :pet-name="pet.name"
        @close="showVetModal = false"
    />
    <MedicalRecordModal
        v-if="showMedicalModal"
        :pet-id="pet.id"
        :pet-name="pet.name"
        @close="showMedicalModal = false"
    />
</template>
