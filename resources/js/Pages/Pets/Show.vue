<script setup>
import { ref, onMounted, onUnmounted } from "vue";
import { Head, useForm } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import VetVisitLogModal from "@/Components/VetVisitLogModal.vue";
import MedicalRecordModal from "@/Components/MedicalRecordModal.vue";
import PetShowMobile from "./Layout/PetShowMobile.vue";
import PetShowDesktop from "./Layout/PetShowDesktop.vue";

const props = defineProps({
    pet: {
        type: Object,
        required: true,
    },
    vaccineExpiryReminders: {
        type: Array,
        default: () => [],
    },
    medicationRepeatReminders: {
        type: Array,
        default: () => [],
    },
});

const isDesktop = ref(false);

function checkWidth() {
    isDesktop.value = window.innerWidth >= 1024;
}

onMounted(() => {
    checkWidth();
    window.addEventListener("resize", checkWidth);
});

onUnmounted(() => {
    window.removeEventListener("resize", checkWidth);
});

const showVetModal     = ref(false);
const showMedicalModal = ref(false);
const showDeleteModal  = ref(false);

const pendingFiles = ref({});

const deleteForm    = useForm({});
const uploadForm    = useForm({ files: [] });
const deletePetForm = useForm({});

function formatDate(dateStr) {
    if (!dateStr) return "";
    const d = new Date(dateStr);
    return d.toLocaleDateString("et-EE", {
        day: "2-digit",
        month: "2-digit",
        year: "numeric",
    });
}

function uploadFiles(event, visit) {
    pendingFiles.value[visit.id] = Array.from(event.target.files || []);
}

function removePendingFile(visitId, index) {
    pendingFiles.value[visitId]?.splice(index, 1);
}

function submitFiles(visit) {
    uploadForm.files = pendingFiles.value[visit.id] || [];
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

function deleteVisit(visitId) {
    if (!confirm("Kustuta see arstivisiit?")) return;
    deleteForm.delete(
        route("vet-visits.destroy", {
            pet: props.pet.id,
            vetVisit: visitId,
        }),
        { preserveScroll: true },
    );
}

function deleteVaccine(vaccineId) {
    if (!confirm("Kustuta see vaktsiin?")) return;
    deleteForm.delete(
        route("vaccines.destroy", {
            pet: props.pet.id,
            vaccine: vaccineId,
        }),
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

function deleteFile(fileId) {
    if (!confirm("Kustuta see fail?")) return;
    deleteForm.delete(route("vet-visit-files.destroy", fileId), {
        preserveScroll: true,
    });
}

function deleteReminder(reminderId) {
    if (!confirm("Kustuta see meeldetuletus?")) return;
    deleteForm.delete(route("reminders.destroy", reminderId), {
        preserveScroll: true,
    });
}

function confirmDelete() {
    showDeleteModal.value = true;
}

function deletePet() {
    deletePetForm.delete(route("pets.destroy", props.pet.id));
}
</script>

<template>
    <Head :title="pet.name" />

    <AuthenticatedLayout>
        <PetShowDesktop
            v-if="isDesktop"
            :pet="pet"
            :pending-files="pendingFiles"
            :format-date="formatDate"
            :vaccine-expiry-reminders="vaccineExpiryReminders"
            :medication-repeat-reminders="medicationRepeatReminders"
            @open-vet-modal="showVetModal = true"
            @open-medical-modal="showMedicalModal = true"
            @confirm-delete="confirmDelete"
            @delete-visit="deleteVisit"
            @delete-vaccine="deleteVaccine"
            @delete-medication="deleteMedication"
            @delete-file="deleteFile"
            @upload-files="uploadFiles"
            @remove-pending-file="removePendingFile"
            @submit-files="submitFiles"
            @delete-reminder="deleteReminder"
        />

        <PetShowMobile
            v-else
            :pet="pet"
            :pending-files="pendingFiles"
            :format-date="formatDate"
            :vaccine-expiry-reminders="vaccineExpiryReminders"
            :medication-repeat-reminders="medicationRepeatReminders"
            @open-vet-modal="showVetModal = true"
            @open-medical-modal="showMedicalModal = true"
            @confirm-delete="confirmDelete"
            @delete-visit="deleteVisit"
            @delete-vaccine="deleteVaccine"
            @delete-medication="deleteMedication"
            @delete-file="deleteFile"
            @upload-files="uploadFiles"
            @remove-pending-file="removePendingFile"
            @submit-files="submitFiles"
            @delete-reminder="deleteReminder"
        />

        <!-- Delete pet modal -->
        <div
            v-if="showDeleteModal"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/40"
        >
            <div class="w-80 rounded-2xl bg-white p-6 shadow-xl">
                <div class="flex flex-col gap-4">
                    <h2 class="text-center text-lg font-semibold text-[#275342]">
                        Kustuta lemmik
                    </h2>
                    <p class="text-center text-sm text-[#275342]">
                        Kas oled kindel, et soovid
                        <strong>{{ pet.name }}</strong> kustutada? Seda tegevust
                        ei saa tagasi võtta.
                    </p>
                    <div class="mt-2 flex gap-3">
                        <button
                            class="flex-1 rounded-xl border border-gray-300 py-2 text-sm text-[#275342] transition hover:bg-gray-50"
                            @click="showDeleteModal = false"
                        >
                            Ei
                        </button>
                        <button
                            class="flex-1 rounded-xl bg-red-500 py-2 text-sm text-white transition hover:bg-red-600"
                            :disabled="deletePetForm.processing"
                            @click="deletePet"
                        >
                            Jah, kustuta
                        </button>
                    </div>
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
    </AuthenticatedLayout>
</template>