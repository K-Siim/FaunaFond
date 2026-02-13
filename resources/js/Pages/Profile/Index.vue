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
</script>

<template>
    <div>
        <Head title="Profiil" />

        <div
            class="min-h-screen w-full relative"
            style="background-color: #e7f0e4"
        >
            <button
                @click="toggleMenu"
                class="absolute top-6 left-6 z-50 p-2"
                style="color: #275342"
                aria-label="Menüü"
            >
                <svg
                    v-if="!menuOpen"
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-8 w-8"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16"
                    />
                </svg>
                <svg
                    v-else
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-8 w-8"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M6 18L18 6M6 6l12 12"
                    />
                </svg>
            </button>

            <transition name="slide">
                <div
                    v-if="menuOpen"
                    class="fixed inset-0 z-40"
                    style="background-color: #e7f0e4"
                >
                    <div class="flex flex-col pt-24 px-8 space-y-6">
                        <Link
                            :href="route('dashboard')"
                            class="text-xl py-3 hover:opacity-80"
                            style="color: #275342"
                        >
                            Avaleht
                        </Link>
                        <Link
                            href="/lemmikud"
                            class="text-xl py-3 hover:opacity-80"
                            style="color: #275342"
                        >
                            Lemmikud
                        </Link>
                        <Link
                            :href="route('profile.index')"
                            class="text-xl py-3 hover:opacity-80 font-bold"
                            style="color: #275342"
                        >
                            Profiil
                        </Link>
                        <Link
                            href="/profile/settings"
                            class="text-xl py-3 hover:opacity-80"
                            style="color: #275342"
                        >
                            Sätted
                        </Link>
                    </div>
                </div>
            </transition>

            <div
                class="flex flex-col items-center justify-center min-h-screen px-4 pb-8 pt-20"
            >
                <div class="w-full max-w-[300px] flex flex-col items-center">
                    <div
                        class="mb-6"
                        style="display: flex; justify-content: center"
                    >
                        <div
                            style="
                                width: 128px;
                                height: 128px;
                                border-radius: 50%;
                                background-color: #c8d5c8;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                            "
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                style="width: 80px; height: 80px"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="#275342"
                                stroke-width="2"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                />
                            </svg>
                        </div>
                    </div>

                    <h1 class="text-2xl font-bold mb-8" style="color: #0e2c20">
                        {{ user.name }}
                    </h1>

                    <div
                        class="w-full border-b py-4 mb-0"
                        style="border-color: #275342"
                    >
                        <div class="flex justify-between items-center mb-3">
                            <span class="font-bold" style="color: #275342"
                                >E-mail:</span
                            >
                            <span style="color: #275342">{{ user.email }}</span>
                        </div>

                        <div class="flex justify-between items-center mt-3">
                            <span class="font-bold" style="color: #275342"
                                >Telefon:</span
                            >
                            <span style="color: #275342">{{
                                user.phone || "+372 xxx xxxx"
                            }}</span>
                        </div>
                    </div>

                    <div class="w-full">
                        <Link
                            :href="route('profile.edit')"
                            class="w-full py-4 flex items-center gap-3 transition-opacity hover:opacity-80 border-b"
                            style="color: #275342; border-color: #275342"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-6 w-6"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"
                                />
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                />
                            </svg>
                            <span class="font-medium">Muuda andmeid</span>
                        </Link>

                        <Link
                            href="/profile/settings"
                            class="w-full py-4 flex items-center gap-3 transition-opacity hover:opacity-80 border-b"
                            style="color: #275342; border-color: #275342"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-6 w-6"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"
                                />
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                />
                            </svg>
                            <span class="font-medium">Sätted</span>
                        </Link>

                        <button
                            @click="logout"
                            class="w-full py-4 flex items-center gap-3 transition-opacity hover:opacity-80 border-b"
                            style="color: #275342; border-color: #275342"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-6 w-6"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
                                />
                            </svg>
                            <span class="font-medium">Logi välja</span>
                        </button>
                    </div>
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
