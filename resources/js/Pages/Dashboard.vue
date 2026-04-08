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
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Tere, {{ $page.props.auth.user.name }}!
            </h2>
            <div class="flex justify-center items-center h-full flex-col p-5 gap-5">
                <div class="flex justify-center items-center pt-3 pb-3 gap-5 flex-col w-full">
                    <div class="flex flex-row justify-between items- w-full pt-2 pb-2">
                        <h5 class="text-[#275342] text-[20px]">
                            Lemmikute nimekiri
                        </h5>
                        <Link :href="route('pets.index')">
                            <button class="text-[#275342] text-[16px] h-full">
                                Vaata kõiki
                            </button>
                        </Link>
                    </div>
                    
                    <div class=" rounded-2xl w-full max-w-md mx-auto mt-20">
                        <div v-if="pets && pets.length">
                            <div v-for="pet in pets" :key="pet.id">
                                <Link :href="route('pets.show', pet.id)" class="w-full">
                                    <PetContent :pet="pet" class="mb-4" />
                                </Link>
                            </div>
                        </div>
                        <p v-else>Lemmikloomi ei leitud</p>
                    </div>
                                        
                    <Link :href="route('pets.create')">
                        <button class="text-[#275342] text-[16px] h-full">
                            Lisa loom
                        </button>
                    </Link>
                </div>
            </div>
        </template>
    </AuthenticatedLayout>

    <!-- PWA install nupp - fixed paremasse alumisse nurka -->
    <PwaInstallButton class="fixed bottom-6 right-6 z-50" />
</template>