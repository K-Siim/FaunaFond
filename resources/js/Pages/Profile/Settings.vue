<script setup>
import { ref } from "vue";
import { Head, Link, usePage, router } from "@inertiajs/vue3";

const user = usePage().props.auth.user;
const menuOpen = ref(false);

const toggleMenu = () => {
    menuOpen.value = !menuOpen.value;
};

const logout = () => {
    router.post(route("logout"));
};

const reminders = ref({
    ravimid: true,
    vaktsiinid: true,
    arstivisiidid: true,
});
</script>

<template>
    <div>
        <Head title="Sätted" />
        <div class="min-h-screen w-full relative" style="background-color: #e7f0e4">
            <button @click="toggleMenu" class="absolute top-6 left-6 z-50 p-2"
                style="color: #275342" aria-label="Menüü">
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
            <Link :href="route('profile.index')" class="absolute top-6 right-6 z-50 p-2"
                style="color: #275342">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </Link>
            <transition name="slide">
                <div v-if="menuOpen" class="fixed inset-0 z-40" style="background-color: #e7f0e4">
                    <div class="flex flex-col pt-24 px-8 space-y-6">
                        <Link :href="route('dashboard')" class="text-xl py-3 hover:opacity-80" style="color: #275342">Avaleht</Link>
                        <Link href="/pets" class="text-xl py-3 hover:opacity-80" style="color: #275342">Lemmikud</Link>
                        <Link :href="route('profile.index')" class="text-xl py-3 hover:opacity-80" style="color: #275342">Profiil</Link>
                        <Link :href="route('profile.settings')" class="text-xl py-3 hover:opacity-80 font-bold" style="color: #275342">Sätted</Link>
                        <button @click="logout" class="text-xl py-3 hover:opacity-80 text-left" style="color: #275342">Logi välja</button>
                    </div>
                </div>
            </transition>
            <div class="flex flex-col min-h-screen px-6 pt-24 pb-8">
                <div class="mb-8">
                    <div class="flex items-center gap-3 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="color: #275342">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <h2 class="text-xl font-bold" style="color: #275342">Meeldetuletused</h2>
                    </div>

                    <div class="rounded-2xl bg-white overflow-hidden">
                        <label class="flex items-center justify-between px-4 py-4 border-b" style="border-color: #e7f0e4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm"
                                    style="background-color: #c8d5c8; color: #275342">R</div>
                                <span style="color: #275342">Ravimid</span>
                            </div>
                            <input type="checkbox" v-model="reminders.ravimid" class="w-5 h-5 accent-[#275342]" />
                        </label>

                        <label class="flex items-center justify-between px-4 py-4 border-b" style="border-color: #e7f0e4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm"
                                    style="background-color: #c8d5c8; color: #275342">V</div>
                                <span style="color: #275342">Vaktsiinid</span>
                            </div>
                            <input type="checkbox" v-model="reminders.vaktsiinid" class="w-5 h-5 accent-[#275342]" />
                        </label>

                        <label class="flex items-center justify-between px-4 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm"
                                    style="background-color: #c8d5c8; color: #275342">A</div>
                                <span style="color: #275342">Arstivisiidid</span>
                            </div>
                            <input type="checkbox" v-model="reminders.arstivisiidid" class="w-5 h-5 accent-[#275342]" />
                        </label>
                    </div>
                </div>
                <div style="border-bottom: 1px solid #275342"></div>
                <div class="mt-8">
                    <div class="flex items-center gap-3 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="color: #275342">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <h2 class="text-xl font-bold" style="color: #275342">Salasõna</h2>
                    </div>

                    <div class="rounded-2xl bg-white overflow-hidden">
                        <Link
                            :href="route('profile.password')"
                            class="flex items-center justify-center px-4 py-4 w-full"
                            style="color: #275342"
                        >
                            <span>Salasõna muutmine</span>
                        </Link>
                    </div>

                    <div class="mt-6" style="border-bottom: 1px solid #275342"></div>
                </div>
            </div>
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