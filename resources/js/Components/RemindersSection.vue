<script setup>
import { ref } from "vue";
import { useForm, router } from "@inertiajs/vue3";

/**
 * Props
 *  - reminders              : array of user-created Reminder models
 *                             each reminder must have a `pet` relation loaded
 *                             so reminder.pet.name is available
 *  - vaccineExpiryReminders : array of auto-generated expiry objects built
 *                             in PetController@show (or a dashboard controller)
 *  - petId                  : pass when used on a single-pet page (PetShowMobile).
 *                             hides the pet selector in the add form.
 *  - pets                   : pass when used on a multi-pet page (Dashboard).
 *                             shows a pet dropdown in the add form.
 *  - formatDate             : optional date formatter fn — falls back to et-EE locale
 */
const props = defineProps({
    reminders:              { type: Array,    default: () => [] },
    vaccineExpiryReminders: { type: Array,    default: () => [] },
    petId:                  { type: Number,   default: null },
    pets:                   { type: Array,    default: () => [] },
    formatDate:             { type: Function, default: null },
});

// ── date helper ────────────────────────────────────────────────────────────
function fmt(dateStr) {
    if (!dateStr) return "";
    if (props.formatDate) return props.formatDate(dateStr);
    return new Date(dateStr).toLocaleDateString("et-EE", {
        day: "2-digit", month: "2-digit", year: "numeric",
    });
}

// ── accordion state ────────────────────────────────────────────────────────
const expandedId       = ref(null);
const showReminderForm = ref(false);

function toggleReminder(id) {
    expandedId.value = expandedId.value === id ? null : id;
}

// ── form ───────────────────────────────────────────────────────────────────
const reminderForm = useForm({
    pet_id:        props.petId ?? "",
    type:          "",
    name:          "",
    notes:         "",
    reminder_date: "",
    reminder_time: "",
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
            showReminderForm.value = false;
        },
    });
}

function deleteReminder(id) {
    if (!confirm("Kustuta see meeldetuletus?")) return;
    router.delete(route("reminders.destroy", id), { preserveScroll: true });
}

// ── filter helpers ─────────────────────────────────────────────────────────
const vaccineReminders  = () => props.reminders.filter(r => r.type === "vaccine");
const vetReminders      = () => props.reminders.filter(r => r.type === "vet_visit");
const medicineReminders = () => props.reminders.filter(r => r.type === "medicine");
const hasAnyReminders   = () => props.vaccineExpiryReminders.length + props.reminders.length > 0;

function isNight(time) {
    if (!time) return false;
    const h = parseInt(time.split(":")[0]);
    return h >= 18 || h < 6;
}
</script>

<template>
    <section class="bg-[#FFFDF5] p-6 rounded-2xl w-full">
        <div class="flex flex-col gap-6">

            <!-- Header -->
            <div class="flex flex-row justify-between items-center">
                <h3 class="text-lg font-semibold text-[#275342]">Meeldetuletused</h3>
                <button
                    @click="showReminderForm = !showReminderForm"
                    class="text-[#275342] text-lg font-bold pl-2 pr-2 border border-[#275342] rounded-full hover:bg-[#275342] hover:text-white transition"
                >{{ showReminderForm ? '×' : '+' }}</button>
            </div>

            <!-- Add form -->
            <div v-if="showReminderForm" class="flex flex-col gap-3 bg-white border border-gray-100 rounded-2xl p-4 shadow-sm">

                <!-- Pet selector — only shown on multi-pet pages (e.g. dashboard) -->
                <div v-if="!petId">
                    <select
                        v-model="reminderForm.pet_id"
                        class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm text-[#275342] focus:outline-none focus:ring-2 focus:ring-[#275342]/30"
                    >
                        <option value="" disabled>Vali lemmik *</option>
                        <option v-for="p in pets" :key="p.id" :value="p.id">{{ p.name }}</option>
                    </select>
                    <p v-if="reminderForm.errors.pet_id" class="text-red-500 text-xs mt-1">{{ reminderForm.errors.pet_id }}</p>
                </div>

                <!-- Type -->
                <div>
                    <select
                        v-model="reminderForm.type"
                        class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm text-[#275342] focus:outline-none focus:ring-2 focus:ring-[#275342]/30"
                    >
                        <option value="" disabled>Meeldetuletuse tüüp *</option>
                        <option value="vaccine">💉 Vaktsiin</option>
                        <option value="medicine">💊 Ravim</option>
                        <option value="vet_visit">🏥 Arstivisiit</option>
                    </select>
                    <p v-if="reminderForm.errors.type" class="text-red-500 text-xs mt-1">{{ reminderForm.errors.type }}</p>
                </div>

                <!-- Name -->
                <div>
                    <input
                        v-model="reminderForm.name"
                        type="text"
                        :placeholder="namePlaceholders[reminderForm.type] ?? 'Nimi *'"
                        class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm text-[#275342] placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#275342]/30"
                    />
                    <p v-if="reminderForm.errors.name" class="text-red-500 text-xs mt-1">{{ reminderForm.errors.name }}</p>
                </div>

                <!-- Notes -->
                <textarea
                    v-model="reminderForm.notes"
                    rows="2"
                    placeholder="Märkused (valikuline)"
                    class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm text-[#275342] placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#275342]/30 resize-none"
                />

                <!-- Date + Time -->
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Kuupäev *</label>
                        <input
                            v-model="reminderForm.reminder_date"
                            type="date"
                            class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm text-[#275342] focus:outline-none focus:ring-2 focus:ring-[#275342]/30"
                        />
                        <p v-if="reminderForm.errors.reminder_date" class="text-red-500 text-xs mt-1">{{ reminderForm.errors.reminder_date }}</p>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Kellaaeg</label>
                        <input
                            v-model="reminderForm.reminder_time"
                            type="time"
                            class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm text-[#275342] focus:outline-none focus:ring-2 focus:ring-[#275342]/30"
                        />
                    </div>
                </div>

                <button
                    @click="submitReminder"
                    :disabled="reminderForm.processing"
                    class="w-full bg-[#275342] text-white text-sm font-semibold py-2.5 rounded-xl hover:bg-[#1e4234] transition disabled:opacity-60"
                >
                    Salvesta meeldetuletus
                </button>
            </div>

            <!-- Empty state -->
            <p v-if="!hasAnyReminders()" class="text-md text-[#275342] text-center py-4">
                Meeldetuletusi pole veel lisatud.
            </p>

            <div v-else class="flex flex-col gap-3">

                <!-- 1. AUTO vaccine expiry → RED ─────────────────────── -->
                <div
                    v-for="r in vaccineExpiryReminders"
                    :key="r.id"
                    class="flex items-center gap-3 px-4 py-3.5 rounded-2xl bg-red-50 border border-red-100"
                >
                    <div class="w-8 h-8 rounded-xl bg-red-100 flex items-center justify-center flex-shrink-0 text-sm">⚠️</div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-red-800">{{ r.pet_name }}</p>
                        <p class="text-sm text-red-700 leading-snug">
                            "{{ r.name }}" {{ r.is_expired ? 'aegus' : 'aegub' }}
                            <strong>"{{ r.end_date }}"</strong>
                        </p>
                    </div>
                </div>

                <!-- 2. Vaccine reminders → YELLOW ────────────────────── -->
                <div
                    v-for="r in vaccineReminders()"
                    :key="r.id"
                    class="bg-yellow-50 border border-yellow-100 rounded-2xl overflow-hidden"
                >
                    <button
                        class="w-full flex items-center justify-between px-4 py-3 hover:bg-yellow-100/50 transition"
                        @click="toggleReminder(r.id)"
                    >
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-8 h-8 rounded-xl bg-yellow-100 flex items-center justify-center flex-shrink-0 text-sm">🔔</div>
                            <div class="flex flex-col items-start text-left min-w-0">
                                <span class="text-sm font-semibold text-yellow-800">{{ r.pet?.name }}</span>
                                <span class="text-xs text-yellow-700 leading-snug">
                                    "{{ r.name }}" — vaktsineerimine
                                    <strong>"{{ fmt(r.reminder_date) }}"</strong>
                                    <span v-if="r.reminder_time"> kell <strong>"{{ r.reminder_time }}"</strong></span>
                                </span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 flex-shrink-0 ml-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-yellow-400 transition-transform duration-200" :class="expandedId === r.id ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                            <button @click.stop="deleteReminder(r.id)" class="text-yellow-300 hover:text-red-500 transition text-lg leading-none">×</button>
                        </div>
                    </button>
                    <div v-if="expandedId === r.id && r.notes" class="px-4 pb-4 border-t border-yellow-100">
                        <p class="text-sm text-yellow-700 bg-yellow-100/50 rounded-xl px-3 py-2 mt-3 leading-relaxed whitespace-pre-wrap">{{ r.notes }}</p>
                    </div>
                </div>

                <!-- 3. Vet visit reminders → BLUE ─────────────────────── -->
                <div
                    v-for="r in vetReminders()"
                    :key="r.id"
                    class="bg-blue-50 border border-blue-100 rounded-2xl overflow-hidden"
                >
                    <button
                        class="w-full flex items-center justify-between px-4 py-3 hover:bg-blue-100/50 transition"
                        @click="toggleReminder(r.id)"
                    >
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-8 h-8 rounded-xl bg-blue-100 flex items-center justify-center flex-shrink-0 text-sm">🕐</div>
                            <div class="flex flex-col items-start text-left min-w-0">
                                <span class="text-sm font-semibold text-blue-800">{{ r.pet?.name }}</span>
                                <span class="text-xs text-blue-700 leading-snug">
                                    "{{ r.name }}" — arstivisiit
                                    <strong>"{{ fmt(r.reminder_date) }}"</strong>
                                    <span v-if="r.reminder_time"> kell <strong>"{{ r.reminder_time }}"</strong></span>
                                </span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 flex-shrink-0 ml-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-400 transition-transform duration-200" :class="expandedId === r.id ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                            <button @click.stop="deleteReminder(r.id)" class="text-blue-300 hover:text-red-500 transition text-lg leading-none">×</button>
                        </div>
                    </button>
                    <div v-if="expandedId === r.id && r.notes" class="px-4 pb-4 border-t border-blue-100">
                        <p class="text-sm text-blue-700 bg-blue-100/50 rounded-xl px-3 py-2 mt-3 leading-relaxed whitespace-pre-wrap">{{ r.notes }}</p>
                    </div>
                </div>

                <!-- 4. Medicine reminders → GREEN (sun / moon) ──────── -->
                <div
                    v-for="r in medicineReminders()"
                    :key="r.id"
                    class="flex items-center gap-3 px-4 py-3.5 rounded-2xl bg-green-50 border border-green-100"
                >
                    <div class="w-8 h-8 rounded-xl bg-green-100 flex items-center justify-center flex-shrink-0 text-sm">
                        {{ isNight(r.reminder_time) ? '🌙' : '☀️' }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-green-800">{{ r.pet?.name }}</p>
                        <p class="text-sm text-green-700 leading-snug">
                            "{{ r.name }}" — kellaaeg
                            <strong>"{{ r.reminder_time || fmt(r.reminder_date) }}"</strong>
                        </p>
                    </div>
                    <button @click="deleteReminder(r.id)" class="text-green-300 hover:text-red-500 transition flex-shrink-0 text-lg leading-none">×</button>
                </div>

            </div>
        </div>
    </section>
</template>