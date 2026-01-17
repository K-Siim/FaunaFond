<script setup>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputError from "@/Components/InputError.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import logo from "../../../images/Logo.jpg";

const form = useForm({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
});

const submit = () => {
    form.post(route("register"), {
        onFinish: () => form.reset("password", "password_confirmation"),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Register" />

        <div
            class="w-full flex items-center justify-center px-4"
            style="background-color: #e7f0e4"
        >
            <div class="w-full max-w-[375px] flex flex-col items-center">
                <div class="mb-8">
                    <img
                        :src="logo"
                        alt="Logo"
                        class="w-[130px] h-[130px] border border-[#275342] rounded-3xl object-cover"
                    />
                </div>

                <div class="w-full max-w-[300px]">
                    <form
                        @submit.prevent="submit"
                        class="flex flex-col gap-[10px]"
                    >
                        <div>
                            <input
                                id="name"
                                type="text"
                                v-model="form.name"
                                required
                                autofocus
                                autocomplete="name"
                                placeholder="Nimi"
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
                                :message="form.errors.name"
                            />
                        </div>

                        <div>
                            <input
                                id="email"
                                type="email"
                                v-model="form.email"
                                required
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

                        <div>
                            <input
                                id="password"
                                type="password"
                                v-model="form.password"
                                required
                                autocomplete="new-password"
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

                        <div>
                            <input
                                id="password_confirmation"
                                type="password"
                                v-model="form.password_confirmation"
                                required
                                autocomplete="new-password"
                                placeholder="Korda salasõna"
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
                                :message="form.errors.password_confirmation"
                            />
                        </div>

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
                            LOO KASUTAJA
                        </button>

                        <div class="text-center mt-2">
                            <Link
                                :href="route('login')"
                                class="text-base hover:underline"
                                style="color: #0e2c20"
                            >
                                Kasutaja olemas? Logi sisse
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
