<script setup>
import { computed } from "vue";
import { Link } from "@inertiajs/vue3";
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from "@/Components/ui/card";
import { Badge } from "@/Components/ui/badge";
import { Separator } from "@/Components/ui/separator";
import {
    Accordion,
    AccordionContent,
    AccordionItem,
    AccordionTrigger,
} from "@/Components/ui/accordion";

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

const stats = computed(() => ({
    vaccines: props.pet.vaccines?.length || 0,
    medications: props.pet.medications?.length || 0,
    visits: props.pet.vet_visits?.length || 0,
}));
</script>

<template>
    <div class="min-h-screen bg-[#E7F0E4] text-[#275342] pt-36 pb-20">
        <div class="mx-auto max-w-7xl px-6">

            <!-- Header -->
            <div class="mb-8 flex items-start justify-between gap-6">
                <div class="flex items-center gap-5">
                    <img
                        :src="pet.photo_url?.medium || '/images/default-pet.png'"
                        alt="Lemmiku pilt"
                        class="h-36 w-36 rounded-2xl border border-[#275342]/30 object-cover"
                    />
                    <div class="space-y-3">
                        <div class="flex items-center gap-3">
                            <h1 class="text-3xl font-semibold tracking-tight text-[#275342] ">
                                {{ pet.name }}
                            </h1>
                            <Badge class="border border-[#275342] bg-transparent text-[#275342] rounded-full px-4 py-1.5 ">
                                {{ pet.species || "Liik puudub" }}
                            </Badge>
                            <Badge class="border border-[#275342] bg-transparent text-[#275342] rounded-full px-4 py-1.5 ">
                                {{ pet.gender || "Sugu puudub" }}
                            </Badge>
                        </div>
                        <div class="flex flex-wrap gap-2 text-sm text-[#275342]/70">
                            <span>{{ pet.breed || "Tõug puudub" }}</span>
                            <span>•</span>
                            <span>{{ pet.formatted_dob }} ({{ pet.age }})</span>
                            <span>•</span>
                            <span>{{ pet.weight }} kg</span>
                            <span v-if="pet.chip">•</span>
                            <span v-if="pet.chip">Kiip: {{ pet.chip }}</span>
                        </div>
                        <p class="max-w-3xl text-sm text-[#275342]/70">
                            {{ pet.description || "Kirjeldus puudub" }}
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-2 mt-2">
                    <Link
                        :href="`/pets/${pet.id}/edit`"
                        class="rounded-full border border-[#275342] px-4 py-1.5 text-sm text-[#275342] hover:bg-[#275342] hover:text-white transition"
                    >
                        Muuda
                    </Link>
                    <button
                        @click="$emit('confirm-delete')"
                        class="rounded-full border border-red-400 px-4 py-1.5 text-sm text-red-400 hover:bg-red-400 hover:text-white transition"
                    >
                        Kustuta
                    </button>
                </div>
            </div>

            <!-- Stats -->
            <div class="mb-8 grid grid-cols-4 gap-4">
                <Card class="bg-[#FFFDF5] rounded-2xl border-[#275342]/40 shadow-none ">
                    <CardHeader class="pb-2 ">
                        <CardDescription class="text-[#275342]/60 ">Vaktsiinid</CardDescription>
                        <CardTitle class="text-3xl text-[#275342] ">{{ stats.vaccines }}</CardTitle>
                    </CardHeader>
                </Card>
                <Card class="bg-[#FFFDF5] rounded-2xl border-[#275342]/40 shadow-none">
                    <CardHeader class="pb-2">
                        <CardDescription class="text-[#275342]/60">Ravimid</CardDescription>
                        <CardTitle class="text-3xl text-[#275342]">{{ stats.medications }}</CardTitle>
                    </CardHeader>
                </Card>
                <Card class="bg-[#FFFDF5] rounded-2xl border-[#275342]/40 shadow-none">
                    <CardHeader class="pb-2">
                        <CardDescription class="text-[#275342]/60">Arstivisiidid</CardDescription>
                        <CardTitle class="text-3xl text-[#275342]">{{ stats.visits }}</CardTitle>
                    </CardHeader>
                </Card>
                <Card class="bg-[#FFFDF5] rounded-2xl border-[#275342]/40 shadow-none">
                    <CardHeader class="pb-2">
                        <CardDescription class="text-[#275342]/60">Meeldetuletused</CardDescription>
                        <CardTitle class="text-3xl text-[#275342]">0</CardTitle>
                    </CardHeader>
                </Card>
            </div>

            <!-- Main grid -->
            <div class="grid grid-cols-12 gap-6">

                <!-- Left: visiidid -->
                <div class="col-span-8 ">
                    <Card class="bg-[#FFFDF5] rounded-2xl border-[#275342]/40 shadow-none">
                        <CardHeader class="flex flex-row items-center justify-between space-y-0">
                            <div>
                                <CardTitle class="text-[#275342] ">Arstivisiitide logi</CardTitle>
                                <CardDescription class="text-[#275342]/60">Visiidid, logid ja seotud PDF-failid</CardDescription>
                            </div>
                            <button
                                @click="$emit('open-vet-modal')"
                                class="rounded-full border border-[#275342] px-4 py-1.5 text-sm text-[#275342] hover:bg-[#275342] hover:text-white transition"
                            >
                                Lisa visiit
                            </button>
                        </CardHeader>
                        <CardContent>
                            <div
                                v-if="!pet.vet_visits || pet.vet_visits.length === 0"
                                class="rounded-xl border border-dashed border-[#275342]/30 p-10 text-center text-sm text-[#275342]/60"
                            >
                                Arstivisiite pole veel lisatud.
                            </div>

                            <Accordion v-else type="single" collapsible class="space-y-3">
                                <AccordionItem
                                    v-for="visit in pet.vet_visits"
                                    :key="visit.id"
                                    :value="String(visit.id)"
                                    class="rounded-xl border border-[#275342]/20 px-4 bg-white"
                                >
                                    <AccordionTrigger class="hover:no-underline text-[#275342]">
                                        <div class="flex flex-col items-start text-left">
                                            <span class="font-medium text-[#275342]">{{ visit.clinic_name }}</span>
                                            <span class="text-xs text-[#275342]/60">{{ formatDate(visit.visit_date) }}</span>
                                        </div>
                                    </AccordionTrigger>
                                    <AccordionContent>
                                        <div class="space-y-5">
                                            <p class="whitespace-pre-wrap text-sm leading-relaxed text-[#275342]/80">
                                                {{ visit.log || "Logi puudub." }}
                                            </p>

                                            <Separator class="bg-[#275342]/10" />

                                            <div class="space-y-3">
                                                <p class="text-sm font-medium text-[#275342]">Failid</p>
                                                <div v-if="visit.files?.length" class="space-y-2">
                                                    <div
                                                        v-for="file in visit.files"
                                                        :key="file.id"
                                                        class="flex items-center justify-between rounded-lg border border-[#275342]/20 bg-[#E7F0E4]/40 px-3 py-2"
                                                    >
                                                        <a
                                                            :href="route('vet-visit-files.download', file.id)"
                                                            class="truncate text-sm text-[#275342] hover:underline"
                                                        >
                                                            {{ file.original_name }}
                                                        </a>
                                                        <button
                                                            @click="$emit('delete-file', file.id)"
                                                            class="text-xs text-red-400 hover:text-red-600 transition ml-4"
                                                        >
                                                            Kustuta
                                                        </button>
                                                    </div>
                                                </div>
                                                <p v-else class="text-sm text-[#275342]/60">Faile pole lisatud.</p>
                                            </div>

                                            <div class="rounded-xl border border-dashed border-[#275342]/30 p-4">
                                                <div class="flex items-center justify-between">
                                                    <label class="cursor-pointer">
                                                        <span class="inline-flex rounded-full border border-[#275342] px-3 py-1.5 text-sm text-[#275342] hover:bg-[#275342] hover:text-white transition">
                                                            Lisa PDF
                                                        </span>
                                                        <input
                                                            type="file"
                                                            multiple
                                                            accept=".pdf"
                                                            class="hidden"
                                                            @change="$emit('upload-files', $event, visit)"
                                                        />
                                                    </label>
                                                    <button
                                                        v-if="pendingFiles[visit.id]?.length"
                                                        @click="$emit('submit-files', visit)"
                                                        class="rounded-full bg-[#275342] px-3 py-1.5 text-sm text-white hover:bg-[#1e3f31] transition"
                                                    >
                                                        Lae üles
                                                    </button>
                                                </div>
                                                <div v-if="pendingFiles[visit.id]?.length" class="mt-3 space-y-2">
                                                    <div
                                                        v-for="(file, index) in pendingFiles[visit.id]"
                                                        :key="index"
                                                        class="flex items-center justify-between rounded-lg bg-[#E7F0E4] px-3 py-2"
                                                    >
                                                        <span class="truncate text-xs text-[#275342]">{{ file.name }}</span>
                                                        <button
                                                            @click="$emit('remove-pending-file', visit.id, index)"
                                                            class="text-xs text-red-400 hover:text-red-600 transition ml-4"
                                                        >
                                                            Eemalda
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="flex justify-end">
                                                <button
                                                    @click="$emit('delete-visit', visit.id)"
                                                    class="text-xs text-red-400 hover:text-red-600 transition"
                                                >
                                                    Kustuta visiit
                                                </button>
                                            </div>
                                        </div>
                                    </AccordionContent>
                                </AccordionItem>
                            </Accordion>
                        </CardContent>
                    </Card>
                </div>

                <!-- Right -->
                <div class="col-span-4 space-y-6">
                    <Card class="bg-[#FFFDF5] rounded-2xl border-[#275342]/40 shadow-none">
                        <CardHeader class="flex flex-row items-center justify-between space-y-0">
                            <div>
                                <CardTitle class="text-[#275342]">Meditsiiniline info</CardTitle>
                                <CardDescription class="text-[#275342]/60">Vaktsiinid ja ravimid</CardDescription>
                            </div>
                            <button
                                @click="$emit('open-medical-modal')"
                                class="rounded-full border border-[#275342]/40 px-4 py-1.5 text-sm text-[#275342] hover:bg-[#275342] hover:text-white transition"
                            >
                                Lisa
                            </button>
                        </CardHeader>
                        <CardContent class="space-y-6">
                            <!-- Vaktsiinid -->
                            <div>
                                <div class="flex items-center justify-between mb-3">
                                    <h3 class="text-sm font-medium text-[#275342]">Vaktsiinid</h3>
                                    <Badge class="border border-[#275342]/30 bg-transparent text-[#275342] rounded-full text-xs">
                                        {{ stats.vaccines }}
                                    </Badge>
                                </div>
                                <p v-if="!pet.vaccines || pet.vaccines.length === 0" class="text-sm text-[#275342]/60">
                                    Vaktsiine pole veel lisatud.
                                </p>
                                <div v-else class="space-y-2">
                                    <div
                                        v-for="vaccine in pet.vaccines"
                                        :key="vaccine.id"
                                        class="rounded-xl border border-[#275342]/20 bg-white p-3"
                                    >
                                        <div class="flex items-start justify-between gap-3">
                                            <div>
                                                <p class="font-medium text-[#275342]">{{ vaccine.name }}</p>
                                                <p class="text-xs text-[#275342]/60">{{ formatDate(vaccine.administered_date) }}</p>
                                                <p v-if="vaccine.expiry_date" class="mt-1 text-xs text-[#275342]/60">
                                                    Aegub: {{ formatDate(vaccine.expiry_date) }}
                                                </p>
                                            </div>
                                            <button
                                                @click="$emit('delete-vaccine', vaccine.id)"
                                                class="text-xs text-red-400 hover:text-red-600 transition"
                                            >
                                                Kustuta
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <Separator class="bg-[#275342]/10" />

                            <!-- Ravimid -->
                            <div>
                                <div class="flex items-center justify-between mb-3">
                                    <h3 class="text-sm font-medium text-[#275342]">Ravimid</h3>
                                    <Badge class="border border-[#275342]/30 bg-transparent text-[#275342] rounded-full text-xs">
                                        {{ stats.medications }}
                                    </Badge>
                                </div>
                                <p v-if="!pet.medications || pet.medications.length === 0" class="text-sm text-[#275342]/60">
                                    Ravimeid pole veel lisatud.
                                </p>
                                <div v-else class="space-y-2">
                                    <div
                                        v-for="medication in pet.medications"
                                        :key="medication.id"
                                        class="rounded-xl border border-[#275342]/20 bg-white p-3"
                                    >
                                        <div class="flex items-start justify-between gap-3">
                                            <div>
                                                <p class="font-medium text-[#275342]">{{ medication.name }}</p>
                                                <p class="text-xs text-[#275342]/60">
                                                    {{ medication.dose_amount }}{{ medication.dose_unit }} · {{ medication.frequency_per_day }}x päevas
                                                </p>
                                                <p class="mt-1 text-xs text-[#275342]/60">
                                                    {{ formatDate(medication.start_date) }}
                                                    <span v-if="medication.end_date"> – {{ formatDate(medication.end_date) }}</span>
                                                </p>
                                            </div>
                                            <button
                                                @click="$emit('delete-medication', medication.id)"
                                                class="text-xs text-red-400 hover:text-red-600 transition"
                                            >
                                                Kustuta
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Meeldetuletused -->
                    <Card class="bg-[#FFFDF5] rounded-2xl border-0 shadow-none">
                        <CardHeader>
                            <CardTitle class="text-[#275342]">Meeldetuletused</CardTitle>
                            <CardDescription class="text-[#275342]/60">Pole veel lisatud</CardDescription>
                        </CardHeader>
                        <CardContent>
                            <div class="rounded-xl border border-dashed border-[#275342]/30 p-6 text-center text-sm text-[#275342]/60">
                                Meeldetuletusi pole veel lisatud.
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </div>
    </div>
</template>