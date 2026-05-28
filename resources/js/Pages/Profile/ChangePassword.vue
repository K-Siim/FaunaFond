<script setup>
import { ref } from "vue";
import { Head, Link, router, useForm } from "@inertiajs/vue3";

const menuOpen = ref(false);

const toggleMenu = () => {
    menuOpen.value = !menuOpen.value;
};

const logout = () => {
    router.post(route("logout"));
};

const form = useForm({
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.put(route('profile.password.update'), {
        onSuccess: () => form.reset(),
    });
};
</script>

<template>
    <div>
        <Head title="Parooli muutmine" />

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
            <Link :href="route('profile.settings')" class="absolute top-6 right-6 z-50 p-2"
                style="color: #275342">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </Link>
            <transition name="slide">
                <div v-if="menuOpen" class="fixed inset-0 z-40" style="background-color: #e7f0e4">
                    <div class="flex flex-col pt-24 px-8 space-y-6">
                        <Link :href="route('dashboard')" class="text-xl py-3 hover:opacity-80" style="color: #275342">Avaleht</Link>
                        <Link href="/pets" class="text-xl py-3 hover:opacity-80" style="color: #275342">Lemmikud</Link>
                        <Link :href="route('profile.index')" class="text-xl py-3 hover:opacity-80" style="color: #275342">Profiil</Link>
                        <Link :href="route('profile.settings')" class="text-xl py-3 hover:opacity-80" style="color: #275342">Sätted</Link>
                        <button @click="logout" class="text-xl py-3 hover:opacity-80 text-left" style="color: #275342">Logi välja</button>
                    </div>
                </div>
            </transition>
            <div class="flex flex-col items-center justify-center min-h-screen px-6">
                <div class="w-full max-w-sm">

                    <div class="flex flex-col gap-3 mb-6">
                        <input
                            v-model="form.password"
                            type="password"
                            placeholder="Salasõna"
                            class="w-full rounded-2xl px-4 py-4 text-base outline-none"
                            style="background-color: #f5f9f4; color: #275342; border: none;"
                        />
                        <p v-if="form.errors.password" class="text-red-500 text-sm px-1">
                            {{ form.errors.password }}
                        </p>

                        <input
                            v-model="form.password_confirmation"
                            type="password"
                            placeholder="Korda salasõna"
                            class="w-full rounded-2xl px-4 py-4 text-base outline-none"
                            style="background-color: #f5f9f4; color: #275342; border: none;"
                        />
                        <p v-if="form.errors.password_confirmation" class="text-red-500 text-sm px-1">
                            {{ form.errors.password_confirmation }}
                        </p>
                    </div>

                    <button
                        @click="submit"
                        :disabled="form.processing"
                        class="w-full h-11 rounded-2xl py-4 font-bold tracking-widest text-sm"
                        style="background-color: #275342; color: #e7f0e4;"
                    >
                        SALVESTA SALASÕNA
                    </button>
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