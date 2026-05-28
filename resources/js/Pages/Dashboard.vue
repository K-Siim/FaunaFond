<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import PetContent from "@/Components/PetInfo/PetContent.vue";
import RemindersSection from "@/Components/RemindersSection.vue";
import PwaInstallButton from "@/Components/PwaInstallButton.vue";
import { ref } from "vue";
import { Link, Head, router } from "@inertiajs/vue3";
import { CircleAlert, AlarmClock, Bell, Sun, Moon, X } from '@lucide/vue';

const props = defineProps({
    reminders:                 Array,
    vaccineExpiryReminders:    Array,
    pets:                      Array,
    medicationRepeatReminders: { type: Array, default: () => [] },
});

const showReminderForm   = ref(false);
const expandedReminderId = ref(null);
const dismissedExpiries  = ref(new Set());

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
function formatDate(date) {
    if (!date) return '';
    const d = new Date(date);
    if (isNaN(d)) return date;
    return d.toLocaleDateString('et-EE', { day: '2-digit', month: '2-digit', year: 'numeric' });
}
function deleteReminder(id) {
    router.delete(route('reminders.destroy', id), { preserveScroll: true });
}

const vaccineReminders  = () => (props.reminders ?? []).filter(r => r.type === "vaccine");
const vetReminders      = () => (props.reminders ?? []).filter(r => r.type === "vet_visit");
const medicineReminders = () => (props.reminders ?? []).filter(r => r.type === "medicine");
const visibleExpiries   = () => (props.vaccineExpiryReminders ?? []).filter(r => !dismissedExpiries.value.has(r.id));

const hasAnyReminders = () =>
    visibleExpiries().length +
    (props.reminders ?? []).length +
    (props.medicationRepeatReminders ?? []).length > 0;
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout :pets="pets">
        <template #header>
            <h2 class="text-2xl font-semibold leading-tight text-[#275342]">
                Tere, {{ $page.props.auth.user.name }}!
            </h2>
        </template>

        <div class="flex flex-col gap-8 p-5">

            <div class="w-full">
                <div class="flex flex-row justify-between items-center w-full pt-2 pb-2">
                    <h5 class="text-[#275342] text-2xl font-semibold">
                        Lemmikute nimekiri
                    </h5>
                    <Link :href="route('pets.index')">
                        <button class="text-[#275342] text-[20px] h-full">
                            Vaata kõiki
                        </button>
                    </Link>
                </div>
                <div class="w-full flex justify-end mb-4">
                    <Link
                        href="/pets/create"
                        class="text-[#275342] text-lg font-bold pl-2 pr-2 border border-[#275342] rounded-full hover:bg-[#275342] hover:text-white transition"
                    >
                        +
                    </Link>
                </div>
                <div v-if="pets && pets.length">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div v-for="pet in pets" :key="pet.id">
                            <Link :href="route('pets.show', pet.id)" class="w-full">
                                <PetContent :pet="pet" />
                            </Link>
                        </div>
                    </div>
                </div>
                <p v-else>Lemmikloomi ei leitud</p>
            </div>

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

                        <!-- Vaccine expiry alerts -->
                        <div
                            v-for="r in visibleExpiries()"
                            :key="'exp-' + r.id"
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

                        <!-- Vaccine reminders -->
                        <div
                            v-for="r in vaccineReminders()"
                            :key="'vac-' + r.id"
                            class="bg-[#FFF6CA] text-[#275342] border border-yellow-100 rounded-2xl overflow-hidden shadow-sm"
                        >
                            <button
                                class="w-full flex items-center justify-between px-3 py-3 hover:bg-[#FFEEA1] transition"
                                @click="toggleReminder(r.id)"
                            >
                                <div class="flex items-center gap-3 min-w-0">
                                    <AlarmClock class="flex-shrink-0" />
                                    <div class="flex flex-col items-start text-left">
                                        <span class="text-md font-semibold">{{ r.pet?.name }}</span>
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
                                    <button @click.stop="deleteReminder(r.id)" class="hover:text-red-500 transition text-xl leading-none">×</button>
                                </div>
                            </button>
                            <div v-if="expandedReminderId === r.id && r.notes" class="px-4 pb-4 border-t border-yellow-100">
                                <p class="text-sm mt-3 leading-relaxed whitespace-pre-wrap">{{ r.notes }}</p>
                            </div>
                        </div>

                        <!-- Vet visit reminders -->
                        <div
                            v-for="r in vetReminders()"
                            :key="'vet-' + r.id"
                            class="bg-[#D5EEFF] text-[#275342] border border-blue-100 rounded-2xl overflow-hidden shadow-sm"
                        >
                            <button
                                class="w-full flex items-center justify-between px-3 py-3 hover:bg-[#B4E1FF] transition"
                                @click="toggleReminder(r.id)"
                            >
                                <div class="flex items-center gap-3 min-w-0">
                                    <Bell class="flex-shrink-0" />
                                    <div class="flex flex-col items-start text-left">
                                        <span class="text-md font-semibold">{{ r.pet?.name }}</span>
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
                                    <button @click.stop="deleteReminder(r.id)" class="hover:text-red-500 transition text-xl leading-none">×</button>
                                </div>
                            </button>
                            <div v-if="expandedReminderId === r.id && r.notes" class="px-4 pb-4 border-t border-blue-100">
                                <p class="text-sm mt-3 leading-relaxed whitespace-pre-wrap">{{ r.notes }}</p>
                            </div>
                        </div>

                        <!-- Medicine reminders -->
                        <div
                            v-for="r in medicineReminders()"
                            :key="'med-' + r.id"
                            class="bg-[#DAF2D0] text-[#275342] border border-green-100 rounded-2xl overflow-hidden shadow-sm"
                        >
                            <button
                                class="w-full flex items-center justify-between px-3 py-3 hover:bg-[#C4EDB4] transition"
                                @click="toggleReminder(r.id)"
                            >
                                <div class="flex items-center gap-3 min-w-0">
                                    <component :is="isNight(r.reminder_time) ? Moon : Sun" class="flex-shrink-0" />
                                    <div class="flex flex-col items-start text-left">
                                        <span class="text-md font-semibold">{{ r.pet?.name }}</span>
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
                                    <button @click.stop="deleteReminder(r.id)" class="hover:text-red-500 transition text-xl leading-none">×</button>
                                </div>
                            </button>
                            <div v-if="expandedReminderId === r.id && r.notes" class="px-4 pb-4 border-t border-green-100">
                                <p class="text-sm mt-3 leading-relaxed whitespace-pre-wrap">{{ r.notes }}</p>
                            </div>
                        </div>

                        <!-- Medication repeat reminders (no delete, same as before) -->
                        <div
                            v-for="r in medicationRepeatReminders"
                            :key="'rep-' + r.id"
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

        </div>
    </AuthenticatedLayout>

    <RemindersSection
        :show-form="showReminderForm"
        :pets="pets"
        @close-form="showReminderForm = false"
    />

    <PwaInstallButton class="fixed bottom-6 right-6 z-50" />
</template>