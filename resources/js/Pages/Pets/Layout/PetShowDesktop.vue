<script setup>
import { computed, ref } from "vue";
import { Link } from "@inertiajs/vue3";
import RemindersSection from "@/Components/RemindersSection.vue";
import { CircleAlert, AlarmClock, Bell, Sun, Moon, X } from '@lucide/vue';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from "@/Components/ui/card";
import { Badge } from "@/Components/ui/badge";
import Separator from "@/Components/ui/separator/Separator.vue";
import {
    Accordion,
    AccordionContent,
    AccordionItem,
    AccordionTrigger,
} from "@/Components/ui/accordion";

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

const showReminderForm  = ref(false);
const expandedReminderId = ref(null);
const dismissedExpiries  = ref(new Set());

const stats = computed(() => ({
    vaccines:    props.pet.vaccines?.length || 0,
    medications: props.pet.medications?.length || 0,
    visits:      props.pet.vet_visits?.length || 0,
    reminders:
        (props.pet.reminders?.length || 0) +
        (props.vaccineExpiryReminders?.length || 0) +
        (props.medicationRepeatReminders?.length || 0),
}));

const vaccineReminders  = () => (props.pet.reminders ?? []).filter(r => r.type === "vaccine");
const vetReminders      = () => (props.pet.reminders ?? []).filter(r => r.type === "vet_visit");
const medicineReminders = () => (props.pet.reminders ?? []).filter(r => r.type === "medicine");
const visibleExpiries   = () => props.vaccineExpiryReminders.filter(r => !dismissedExpiries.value.has(r.id));

const hasAnyReminders = () =>
    visibleExpiries().length +
    (props.pet.reminders ?? []).length +
    props.medicationRepeatReminders.length > 0;

function toggleReminder(id) {
    expandedReminderId.value = expandedReminderId.value === id ? null : id;
}
function dismissExpiry(id) {
    dismissedExpiries.value = new Set([...dismissedExpiries.value, id]);
}
function isNight(time) {
    if (!time) return false;
    const h = parseInt(time.split(":")[0]);
    return h >= 18 || h < 6;
}
</script>

<template>
    <div class="min-h-screen bg-[#E7F0E4] text-[#275342] pt-36 pb-20">
        <div class="mx-auto max-w-7xl px-6">

            <div class="mb-8 flex items-start justify-between gap-6">
                <div class="flex items-center gap-5">
                    <img
                        :src="pet.photo_url?.medium || '/images/default-pet.png'"
                        alt="Lemmiku pilt"
                        class="h-36 w-36 rounded-2xl border border-[#275342]/30 object-cover"
                    />
                    <div class="space-y-3">
                        <h1 class="text-3xl font-semibold tracking-tight text-[#275342]">
                            {{ pet.name }}
                        </h1>
                        <div class="flex flex-wrap gap-2 text-sm text-[#275342]/70">
                            <span>{{ pet.species || "Liik puudub" }}</span>
                            <span>•</span>
                            <span>{{ pet.breed || "Tõug puudub" }}</span>
                            <span>•</span>
                            <span>{{ pet.gender || "Sugu puudub" }}</span>
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

            <div class="mb-8 grid grid-cols-4 gap-4">
                <Card class="bg-[#FFFDF5] rounded-2xl border-[#275342]/40 shadow-none">
                    <CardHeader class="pb-2">
                        <CardDescription class="text-[#275342]/60">Vaktsiinid</CardDescription>
                        <CardTitle class="text-3xl text-[#275342]">{{ stats.vaccines }}</CardTitle>
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
                        <CardTitle class="text-3xl text-[#275342]">{{ stats.reminders }}</CardTitle>
                    </CardHeader>
                </Card>
            </div>

            <div class="grid grid-cols-12 gap-6">

                <div class="col-span-8">
                    <Card class="bg-[#FFFDF5] rounded-2xl border-[#275342]/40 shadow-none">
                        <CardHeader class="flex flex-row items-center justify-between space-y-0">
                            <div>
                                <CardTitle class="text-[#275342]">Arstivisiitide logi</CardTitle>
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

                    <Card class="bg-[#FFFDF5] rounded-2xl border-[#275342]/40 shadow-none">
                        <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-4">
                            <CardTitle class="text-[#275342]">Meeldetuletused</CardTitle>
                            <button
                                @click="showReminderForm = true"
                                class="text-[#275342] text-lg font-bold pl-2 pr-2 border border-[#275342] rounded-full hover:bg-[#275342] hover:text-white transition"
                            >+</button>
                        </CardHeader>
                        <CardContent class="pt-0">
                            <p v-if="!hasAnyReminders()" class="text-sm text-[#275342]/60 text-center py-4">
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
                                                <span class="text-sm font-semibold">{{ r.pet_name }}</span>
                                                <span class="text-sm mt-0.5">
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
                                                <span class="text-sm font-semibold">{{ r.pet?.name ?? pet.name }}</span>
                                                <span class="text-sm mt-0.5">
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
                                                <span class="text-sm font-semibold">{{ r.pet?.name ?? pet.name }}</span>
                                                <span class="text-sm mt-0.5">
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
                                                <span class="text-sm font-semibold">{{ r.pet?.name ?? pet.name }}</span>
                                                <span class="text-sm mt-0.5">
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
                                                <span class="text-sm font-semibold">{{ r.pet_name }}</span>
                                                <span class="text-sm mt-0.5">
                                                    {{ r.name }} — kellaaeg
                                                    <strong>{{ r.reminder_time }}</strong>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </CardContent>
                    </Card>

                </div>
            </div>
        </div>
    </div>

    <RemindersSection
        :pet-id="pet.id"
        :show-form="showReminderForm"
        @close-form="showReminderForm = false"
    />
</template>