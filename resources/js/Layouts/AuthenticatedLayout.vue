<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';

const menuOpen = ref(false);

const toggleMenu = () => {
    menuOpen.value = !menuOpen.value;
};

const logout = () => {
    router.post(route('logout'));
};

const props = defineProps({
  pets: Array,
});
</script>

<template>
    <div>
        <div class="min-h-screen bg-[#E7F0E4]">

            <button
                @click="toggleMenu"
                class="absolute top-6 left-6 z-50 p-2"
                style="color: #275342"
                aria-label="Menüü"
            >
                <svg v-if="!menuOpen" xmlns="http://www.w3.org/2000/svg" class="h-8 w-8"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-8 w-8"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <transition name="slide">
                <div v-if="menuOpen" class="fixed inset-0 z-40" style="background-color: #e7f0e4">
                    <div class="flex flex-col pt-24 px-8 space-y-6">
                        <Link :href="route('dashboard')"
                            class="text-xl py-3 hover:opacity-80" style="color: #275342">
                            Avaleht
                        </Link>
                        <Link href="/pets"
                            class="text-xl py-3 hover:opacity-80" style="color: #275342">
                            Lemmikud
                        </Link>
                        <Link :href="route('profile.index')"
                            class="text-xl py-3 hover:opacity-80" style="color: #275342">
                            Profiil
                        </Link>
                        <button @click="logout"
                            class="text-xl py-3 hover:opacity-80 text-left" style="color: #275342">
                            Logi välja
                        </button>
                    </div>
                </div>
            </transition>
             <!-- Profiili ikoon -->
            <Link :href="route('profile.index')" class="absolute top-6 right-6 z-50 p-2"
                style="color: #275342">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </Link>

            <header class="bg-[#E7F0E4]" v-if="$slots.header">
                <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8 pt-20">
                    <slot name="header" />
                </div>
            </header>

            <main>
                <slot />
            </main>
        </div>
    </div>
</template>

<style scoped>
.slide-enter-active,
.slide-leave-active {
    transition: transform 0.3s ease;
}
.slide-enter-from {
    transform: translateX(-100%);
}
.slide-leave-to {
    transform: translateX(-100%);
}
</style>