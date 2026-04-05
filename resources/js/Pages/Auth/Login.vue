```vue
<script setup>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputError from "@/Components/InputError.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import logo from "../../../images/Logo.jpg";

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: "",
    password: "",
});

const submit = () => {
    form.post(route("login"), {
        onFinish: () => form.reset("password"),
    });
};

const loginWithGoogle = () => {
    window.location.href = "/auth/google";
};
</script>

<template>
    <GuestLayout>
        <Head title="Logi sisse" />

        <div
            class="w-full flex items-center justify-center px-4"
            style="background-color: #e7f0e4"
        >
            <div class="w-full max-w-[375px] flex flex-col items-center">
                <!-- Logo -->
                <div class="mb-8">
                    <img
                        :src="logo"
                        alt="Logo"
                        class="w-[130px] h-[130px] border border-[#275342] rounded-3xl object-cover"
                    />
                </div>

                <!-- Status message -->
                <div
                    v-if="status"
                    class="mb-4 text-sm font-medium text-center"
                    style="color: #275342"
                >
                    {{ status }}
                </div>

                <div class="w-full max-w-[300px]">
                    <form
                        @submit.prevent="submit"
                        class="flex flex-col gap-[10px]"
                    >
                        <!-- Email -->
                        <div>
                            <input
                                id="email"
                                type="email"
                                v-model="form.email"
                                required
                                autofocus
                                autocomplete="username"
                                placeholder="E-mail"
                                class="w-full h-[44px] rounded-[16px] border px-4 py-3 placeholder:text-[#275342]"
                                style="
                                    background-color: #fffdf5;
                                    color: #275342;
                                    border-width: 1px;
                                    border-color: #275342;
                                "
                            />
                            <InputError
                                class="mt-1 text-xs"
                                :message="form.errors.email"
                            />
                        </div>

                        <!-- Password -->
                        <div>
                            <input
                                id="password"
                                type="password"
                                v-model="form.password"
                                required
                                autocomplete="current-password"
                                placeholder="Salasõna"
                                class="w-full h-[44px] rounded-[16px] border px-4 py-3 placeholder:text-[#275342]"
                                style="
                                    background-color: #fffdf5;
                                    color: #275342;
                                    border-width: 1px;
                                    border-color: #275342;
                                "
                            />
                            <InputError
                                class="mt-1 text-xs"
                                :message="form.errors.password"
                            />
                        </div>

                        <!-- Forgot password -->
                        <div class="text-right mt-0">
                            <Link
                                v-if="canResetPassword"
                                :href="route('password.request')"
                                class="text-sm hover:underline"
                                style="color: #0e2c20"
                            >
                                Unustasid parooli?
                            </Link>
                        </div>

                        <!-- Login button -->
                        <button
                            type="submit"
                            :disabled="form.processing"
                            :class="{ 'opacity-25': form.processing }"
                            class="w-full h-[44px] rounded-[16px] mt-4 transition-opacity"
                            style="
                                background-color: #275342;
                                color: #fffdf3;
                                font-weight: 800;
                            "
                        >
                            LOGI SISSE
                        </button>

                        <!-- Google login -->
                        <button
                            type="button"
                            @click="loginWithGoogle"
                            class="w-full h-[44px] rounded-[16px] mt-2 transition-opacity flex items-center justify-center gap-2"
                            style="
                                background-color: #275342;
                                color: #fffdf3;
                                font-weight: 800;
                            "
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"
                                    fill="#4285F4"
                                />
                                <path
                                    d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"
                                    fill="#34A853"
                                />
                                <path
                                    d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z"
                                    fill="#FBBC05"
                                />
                                <path
                                    d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"
                                    fill="#EA4335"
                                />
                            </svg>
                            LOGI SISSE GOOGLE'IGA
                        </button>

                        <!-- Register -->
                        <div class="text-center mt-2">
                            <Link
                                :href="route('register')"
                                class="text-base hover:underline"
                                style="color: #0e2c20"
                            >
                                Loo kasutaja
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

a:hover {
    opacity: 0.9;
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
