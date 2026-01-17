<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import logo from '../../../images/Logo.jpg';

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <GuestLayout>
        <Head title="Unustasid parooli" />

        <div class="w-full flex items-center justify-center px-4" style="background-color: #E7F0E4;">
            <div class="w-full max-w-[375px] flex flex-col items-center">
                <div class="mb-8">
                    <img 
                        :src="logo" 
                        alt="Logo" 
                        class="w-[130px] h-[130px] border border-[#275342] rounded-3xl object-cover"
                    />
                </div>

                <div v-if="status" class="mb-4 text-sm font-medium text-center px-4" style="color: #275342;">
                    {{ status }}
                </div>

                <div class="w-full max-w-[300px]">
                    <form @submit.prevent="submit" class="flex flex-col gap-[10px]">
                        <div>
                            <input
                                id="email"
                                type="email"
                                v-model="form.email"
                                required
                                autofocus
                                autocomplete="username"
                                placeholder="E-mail"
                                class="w-full h-[44px] rounded-[16px] border px-4 py-3 placeholder:text-[#275342]/60"
                                style="background-color: #FFFDF5; color: #275342; border-width: 1px; border-color: #275342;"
                            />
                            <InputError class="mt-1 text-xs" :message="form.errors.email" />
                        </div>

                        <button
                            type="submit"
                            :disabled="form.processing"
                            :class="{ 'opacity-25': form.processing }"
                            class="w-full h-[44px] rounded-[16px] mt-4 transition-opacity"
                            style="background-color: #275342; color: #FFFDF3; font-weight: 800;"
                        >
                            SAADA UUS PAROOL MEILILE
                        </button>

                        <div class="text-center mt-4">
                            <Link
                                :href="route('login')"
                                class="text-xl hover:underline"
                                style="color: #0E2C20;"
                            >
                                Salasõna meeles? Logi sisse
                            </Link>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </GuestLayout>
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

@media (max-width: 375px) {
    .max-w-\[300px\] {
        max-width: calc(100vw - 2rem);
    }
}

input {
    font-size: 16px;
}
</style>