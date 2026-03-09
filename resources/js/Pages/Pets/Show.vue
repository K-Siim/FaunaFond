<script setup>
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import VetVisitLogModal from '@/Components/VetVisitLogModal.vue';

const props = defineProps({
    pet: Object,
});

const showVetModal = ref(false);

const expandedVisitId = ref(null);

function toggleVisit(id) {
    expandedVisitId.value = expandedVisitId.value === id ? null : id;
}

const deleteForm = useForm({});

function deleteVisit(visitId) {
    if (!confirm('Kustuta see arstivisiit?')) return;
    deleteForm.delete(route('vet-visits.destroy', { pet: props.pet.id, vetVisit: visitId }), {
        preserveScroll: true,
    });
}

function formatDate(dateStr) {
    if (!dateStr) return '';
    const d = new Date(dateStr);
    return d.toLocaleDateString('et-EE', { day: '2-digit', month: '2-digit', year: 'numeric' });
}
</script>

<template>
    <Head :title="pet.name" />

    <AuthenticatedLayout>
        <div class="flex flex-col gap-10">

            <section class="bg-[#FFFDF5]">
                <div class="flex flex-col gap-6">
                    <div class="flex flex-row justify-between">
                        <h3>Meditsiiniline info</h3>
                        <Link
                            href="/pets/create"
                            class="text-green-600 font-medium flex justify-center items-center pl-2 pr-2 border border-green-600 rounded-full hover:bg-green-600 hover:text-white transition w-5 h-5"
                        >+</Link>
                    </div>

                    <div class="flex flex-col gap-4">
                        <div class="flex flex-col gap-2">
                            <h4>Vaktsiinid</h4>
                            <div></div>
                        </div>
                        <div class="flex flex-col gap-2">
                            <h4>Ravimid</h4>
                            <div></div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="bg-[#FFFDF5]">
                <div class="flex flex-col gap-6">
                    <div class="flex flex-row justify-between">
                        <h3>Meeldetuletused</h3>
                        <Link
                            href="/pets/create"
                            class="text-green-600 font-medium flex justify-center items-center pl-2 pr-2 border border-green-600 rounded-full hover:bg-green-600 hover:text-white transition w-5 h-5"
                        >+</Link>
                    </div>
                    <div class="flex flex-col gap-4">
                    </div>
                </div>
            </section>

            <section class="bg-[#FFFDF5]">
                <div class="flex flex-col gap-6">

                    <div class="flex flex-row justify-between items-center">
                        <h3 class="text-base font-semibold text-gray-800">Arstivisiitide logi</h3>
                        <button
                            @click="showVetModal = true"
                            class="text-green-700 font-medium flex justify-center items-center border border-green-700 rounded-full hover:bg-green-700 hover:text-white transition w-5 h-5 leading-none"
                            aria-label="Lisa arstivisiit"
                        >+</button>
                    </div>

                    <div class="flex flex-col gap-3">
                        <p
                            v-if="!pet.vetVisits || pet.vetVisits.length === 0"
                            class="text-sm text-gray-400 text-center py-4"
                        >
                            Arstivisiite pole veel lisatud.
                        </p>

                        <div
                            v-for="visit in pet.vetVisits"
                            :key="visit.id"
                            class="bg-white border border-gray-100 rounded-2xl overflow-hidden shadow-sm"
                        >
                            <button
                                class="w-full flex items-center justify-between px-4 py-3 hover:bg-gray-50 transition"
                                @click="toggleVisit(visit.id)"
                            >
                                <div class="flex flex-col items-start text-left">
                                    <span class="text-sm font-semibold text-gray-800">{{ visit.clinic_name }}</span>
                                    <span class="text-xs text-gray-400 mt-0.5">{{ formatDate(visit.visit_date) }}</span>
                                </div>

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="w-5 h-5 text-gray-400 transition-transform duration-200"
                                    :class="expandedVisitId === visit.id ? 'rotate-180' : ''"
                                    fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            <div
                                v-if="expandedVisitId === visit.id"
                                class="px-4 pb-4 border-t border-gray-100"
                            >
                                <p class="text-sm text-gray-600 whitespace-pre-wrap mt-3 leading-relaxed">
                                    {{ visit.log || 'Logi puudub.' }}
                                </p>

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

    <VetVisitLogModal
        v-if="showVetModal"
        :pet-id="pet.id"
        :pet-name="pet.name"
        @close="showVetModal = false"
    />
</template>