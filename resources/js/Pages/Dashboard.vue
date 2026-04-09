<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import PetContent from "@/Components/PetInfo/PetContent.vue";
import PwaInstallButton from "@/Components/PwaInstallButton.vue";
import { Link, Head } from "@inertiajs/vue3";

const props = defineProps({
    pets: Array,
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
                    <div class="flex flex-row justify-between items- w-full pt-2 pb-2">
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
                        <Link href="/pets/create" class="text-[#275342] text-lg font-bold pl-2 pr-2 border border-[#275342] rounded-full hover:bg-[#275342] hover:text-white transition">
                        +
                        </Link>                     
                    </div>

                    
                    <div class=" rounded-2xl w-full max-w-md mx-auto">
                        <div v-if="pets && pets.length">
                            <div v-for="pet in pets" :key="pet.id">
                                <Link :href="route('pets.show', pet.id)" class="w-full">
                                    <PetContent :pet="pet" class="mb-4" />
                                </Link>
                            </div>
                        </div>
                        <p v-else>Lemmikloomi ei leitud</p>
                    </div>
                </div>
            </div>
        </template>
    </AuthenticatedLayout>

    <PwaInstallButton class="fixed bottom-6 right-6 z-50" />
</template>