<script setup>
import { useForm, usePage, router } from '@inertiajs/vue3';
import { Head } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';

const user = usePage().props.auth.user;

const form = useForm({
    name: user.name,
    email: user.email,
    phone: user.phone || '',
});

const submit = () => {
    form.patch(route('profile.update'), {
        preserveScroll: true,
        onSuccess: () => {
            router.visit(route('profile.index'));
        },
    });
};

const goBack = () => {
    router.visit(route('profile.index'));
};
</script>

<template>
    <div>
        <Head title="Muuda profiili" />

        <div class="min-h-screen w-full relative" style="background-color: #E7F0E4;">
            <button 
                @click="goBack"
                class="absolute top-6 right-6 z-50 p-2"
                style="color: #275342;"
                aria-label="Sulge"
            >
                <svg 
                    xmlns="http://www.w3.org/2000/svg" 
                    class="h-8 w-8" 
                    fill="none" 
                    viewBox="0 0 24 24" 
                    stroke="currentColor"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <div class="flex flex-col items-center justify-center min-h-screen px-4 pb-8 pt-20">
                <div class="w-full max-w-[300px] flex flex-col items-center">
                    <div class="mb-8">
                        <div class="w-32 h-32 rounded-full flex items-center justify-center" style="background-color: #C8D5C8;">
                            <svg 
                                xmlns="http://www.w3.org/2000/svg" 
                                class="h-20 w-20" 
                                fill="none" 
                                viewBox="0 0 24 24" 
                                stroke="#275342"
                                stroke-width="2"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                    </div>

                    <form @submit.prevent="submit" class="w-full flex flex-col gap-[10px]">
                        <div>
                            <input
                                id="name"
                                type="text"
                                v-model="form.name"
                                required
                                placeholder="Täisnimi"
                                class="w-full h-[44px] rounded-[16px] border px-4 py-3 placeholder:text-[#0E2C20]"
                                style="background-color: #FFFDF5; color: #275342; border-width: 1px; border-color: #275342;"
                            />
                            <InputError class="mt-1 text-xs" :message="form.errors.name" />
                        </div>

                        <div>
                            <input
                                id="email"
                                type="email"
                                v-model="form.email"
                                required
                                placeholder="E-mail"
                                class="w-full h-[44px] rounded-[16px] border px-4 py-3 placeholder:text-[#0E2C20]"
                                style="background-color: #FFFDF5; color: #275342; border-width: 1px; border-color: #275342;"
                            />
                            <InputError class="mt-1 text-xs" :message="form.errors.email" />
                        </div>

                        <div>
                            <input
                                id="phone"
                                type="tel"
                                v-model="form.phone"
                                placeholder="Telefon"
                                class="w-full h-[44px] rounded-[16px] border px-4 py-3 placeholder:text-[#0E2C20]"
                                style="background-color: #FFFDF5; color: #275342; border-width: 1px; border-color: #275342;"
                            />
                            <InputError class="mt-1 text-xs" :message="form.errors.phone" />
                        </div>

                        <button
                            type="submit"
                            :disabled="form.processing"
                            :class="{ 'opacity-25': form.processing }"
                            class="w-full h-[44px] rounded-[16px] mt-4 transition-opacity"
                            style="background-color: #275342; color: #FFFDF3; font-weight: 800;"
                        >
                            SALVESTA MUUTUSED
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
input:focus {
    outline: none;
    border-color: #275342;
    box-shadow: 0 0 0 2px rgba(39, 83, 66, 0.1);
}

button:hover:not(:disabled) {
    opacity: 0.9;
}

button:disabled {
    cursor: not-allowed;
}

input {
    font-size: 16px;
}
</style>