<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import PetContent from "@/Components/PetInfo/PetContent.vue";
import RemindersSection from "@/Components/RemindersSection.vue";
import PwaInstallButton from "@/Components/PwaInstallButton.vue";
import { Link, Head } from "@inertiajs/vue3";

const props = defineProps({
    reminders:              Array,
    vaccineExpiryReminders: Array,
    pets:                   Array,  
    medicationTodayReminders: { type: Array, default: () => [] },
});
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout :pets="pets">
        <template #header>
            <h2 class="text-2xl font-semibold leading-tight text-[#275342]">
                Tere, {{ $page.props.auth.user.name }}!
            </h2>
            <div class="flex justify-center items-center h-full flex-col p-5 gap-5">
                <div class="flex justify-center items-center pb-3 gap-5 flex-col w-full">
                    <div class="flex flex-row justify-between items-center w-full pt-2 pb-2">
                        <h5 class="text-[#275342] text-xl font-semibold">
                            Lemmikute nimekiri
                        </h5>
                        <Link :href="route('pets.index')">
                            <button class="text-[#275342] text-[20px] h-full">
                                Vaata kõiki
                            </button>
                        </Link>
                    </div>
                    <div class="w-full flex justify-end">
                        <Link
                            href="/pets/create"
                            class="text-[#275342] text-lg font-bold pl-2 pr-2 border border-[#275342] rounded-full hover:bg-[#275342] hover:text-white transition"
                        >
                            +
                        </Link>
                    </div>

                    <div class="w-full">
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
                <div class="w-full max-w-md flex justify-center items-center pt-5">
                    <RemindersSection
                        class="w-full max-w-md mx-auto"
                        :reminders="reminders ?? []"
                        :vaccine-expiry-reminders="vaccineExpiryReminders ?? []"
                        :medication-today-reminders="medicationTodayReminders ?? []"
                        :pets="pets"
                    />                    
                </div>

                </div>
            </div>
        </template>
    </AuthenticatedLayout>

    <PwaInstallButton class="fixed bottom-6 right-6 z-50" />
</template>