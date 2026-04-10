<script setup>
import { computed } from "vue";
import { Link } from "@inertiajs/vue3";

import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from "@/Components/ui/card";
import { Button } from "@/components/ui/button";
import { Badge } from "@/Components/ui/badge";
import { Separator } from "@/components/ui/separator";
import {
    Accordion,
    AccordionContent,
    AccordionItem,
    AccordionTrigger,
} from "@/Components/ui/accordion";

const props = defineProps({
    pet: { type: Object, required: true },
    pendingFiles: { type: Object, required: true },
    formatDate: { type: Function, required: true },
});

defineEmits([
    "open-vet-modal",
    "open-medical-modal",
    "confirm-delete",
    "delete-visit",
    "delete-vaccine",
    "delete-medication",
    "delete-file",
    "upload-files",
    "remove-pending-file",
    "submit-files",
]);

const stats = computed(() => ({
    vaccines: props.pet.vaccines?.length || 0,
    medications: props.pet.medications?.length || 0,
    visits: props.pet.vet_visits?.length || 0,
}));
</script>

<template>
    <div class="min-h-screen bg-background text-foreground">
        <div class="mx-auto max-w-7xl px-6 py-8">
            <!-- Header -->
            <div class="mb-8 flex items-start justify-between gap-6">
                <div class="flex items-center gap-5">
                    <img
                        :src="
                            pet.photo_url?.medium || '/images/default-pet.png'
                        "
                        alt="Lemmiku pilt"
                        class="h-28 w-28 rounded-2xl border border-border object-cover"
                    />

                    <div class="space-y-3">
                        <div class="flex items-center gap-3">
                            <h1 class="text-3xl font-semibold tracking-tight">
                                {{ pet.name }}
                            </h1>

                            <Badge variant="secondary">
                                {{ pet.species || "Liik puudub" }}
                            </Badge>

                            <Badge variant="outline">
                                {{ pet.gender || "Sugu puudub" }}
                            </Badge>
                        </div>

                        <div
                            class="flex flex-wrap gap-2 text-sm text-muted-foreground"
                        >
                            <span>{{ pet.breed || "Tõug puudub" }}</span>
                            <span>•</span>
                            <span>{{ pet.formatted_dob }} ({{ pet.age }})</span>
                            <span>•</span>
                            <span>{{ pet.weight }} kg</span>
                            <span v-if="pet.chip">•</span>
                            <span v-if="pet.chip">Kiip: {{ pet.chip }}</span>
                        </div>

                        <p class="max-w-3xl text-sm text-muted-foreground">
                            {{ pet.description || "Kirjeldus puudub" }}
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <Button variant="outline" as-child>
                        <Link :href="`/pets/${pet.id}/edit`">Muuda</Link>
                    </Button>

                    <Button
                        variant="destructive"
                        @click="$emit('confirm-delete')"
                    >
                        Kustuta
                    </Button>
                </div>
            </div>

            <!-- Stats -->
            <div class="mb-8 grid grid-cols-4 gap-4">
                <Card class="rounded-2xl">
                    <CardHeader class="pb-2">
                        <CardDescription>Vaktsiinid</CardDescription>
                        <CardTitle class="text-3xl">{{
                            stats.vaccines
                        }}</CardTitle>
                    </CardHeader>
                </Card>

                <Card class="rounded-2xl">
                    <CardHeader class="pb-2">
                        <CardDescription>Ravimid</CardDescription>
                        <CardTitle class="text-3xl">{{
                            stats.medications
                        }}</CardTitle>
                    </CardHeader>
                </Card>

                <Card class="rounded-2xl">
                    <CardHeader class="pb-2">
                        <CardDescription>Arstivisiidid</CardDescription>
                        <CardTitle class="text-3xl">{{
                            stats.visits
                        }}</CardTitle>
                    </CardHeader>
                </Card>

                <Card class="rounded-2xl">
                    <CardHeader class="pb-2">
                        <CardDescription>Meeldetuletused</CardDescription>
                        <CardTitle class="text-3xl">0</CardTitle>
                    </CardHeader>
                </Card>
            </div>

            <!-- Main grid -->
            <div class="grid grid-cols-12 gap-6">
                <!-- Left -->
                <div class="col-span-8">
                    <Card class="rounded-2xl">
                        <CardHeader
                            class="flex flex-row items-center justify-between space-y-0"
                        >
                            <div>
                                <CardTitle>Arstivisiitide logi</CardTitle>
                                <CardDescription>
                                    Visiidid, logid ja seotud PDF-failid
                                </CardDescription>
                            </div>

                            <Button @click="$emit('open-vet-modal')">
                                Lisa visiit
                            </Button>
                        </CardHeader>

                        <CardContent>
                            <div
                                v-if="
                                    !pet.vet_visits ||
                                    pet.vet_visits.length === 0
                                "
                                class="rounded-xl border border-dashed border-border p-10 text-center text-sm text-muted-foreground"
                            >
                                Arstivisiite pole veel lisatud.
                            </div>

                            <Accordion
                                v-else
                                type="single"
                                collapsible
                                class="space-y-3"
                            >
                                <AccordionItem
                                    v-for="visit in pet.vet_visits"
                                    :key="visit.id"
                                    :value="String(visit.id)"
                                    class="rounded-xl border px-4"
                                >
                                    <AccordionTrigger
                                        class="hover:no-underline"
                                    >
                                        <div
                                            class="flex flex-col items-start text-left"
                                        >
                                            <span class="font-medium">
                                                {{ visit.clinic_name }}
                                            </span>
                                            <span
                                                class="text-xs text-muted-foreground"
                                            >
                                                {{
                                                    formatDate(visit.visit_date)
                                                }}
                                            </span>
                                        </div>
                                    </AccordionTrigger>

                                    <AccordionContent>
                                        <div class="space-y-5">
                                            <p
                                                class="whitespace-pre-wrap text-sm leading-relaxed text-muted-foreground"
                                            >
                                                {{
                                                    visit.log || "Logi puudub."
                                                }}
                                            </p>

                                            <Separator />

                                            <div class="space-y-3">
                                                <p class="text-sm font-medium">
                                                    Failid
                                                </p>

                                                <div
                                                    v-if="visit.files?.length"
                                                    class="space-y-2"
                                                >
                                                    <div
                                                        v-for="file in visit.files"
                                                        :key="file.id"
                                                        class="flex items-center justify-between rounded-lg border bg-muted/40 px-3 py-2"
                                                    >
                                                        <a
                                                            :href="
                                                                route(
                                                                    'vet-visit-files.download',
                                                                    file.id,
                                                                )
                                                            "
                                                            class="truncate text-sm hover:underline"
                                                        >
                                                            {{
                                                                file.original_name
                                                            }}
                                                        </a>

                                                        <Button
                                                            variant="ghost"
                                                            size="sm"
                                                            @click="
                                                                $emit(
                                                                    'delete-file',
                                                                    file.id,
                                                                )
                                                            "
                                                        >
                                                            Kustuta
                                                        </Button>
                                                    </div>
                                                </div>

                                                <p
                                                    v-else
                                                    class="text-sm text-muted-foreground"
                                                >
                                                    Faile pole lisatud.
                                                </p>
                                            </div>

                                            <div
                                                class="rounded-xl border border-dashed border-border p-4"
                                            >
                                                <div
                                                    class="flex items-center justify-between"
                                                >
                                                    <label
                                                        class="cursor-pointer"
                                                    >
                                                        <span
                                                            class="inline-flex rounded-md border px-3 py-2 text-sm hover:bg-accent"
                                                        >
                                                            Lisa PDF
                                                        </span>
                                                        <input
                                                            type="file"
                                                            multiple
                                                            accept=".pdf"
                                                            class="hidden"
                                                            @change="
                                                                $emit(
                                                                    'upload-files',
                                                                    $event,
                                                                    visit,
                                                                )
                                                            "
                                                        />
                                                    </label>

                                                    <Button
                                                        v-if="
                                                            pendingFiles[
                                                                visit.id
                                                            ]?.length
                                                        "
                                                        size="sm"
                                                        @click="
                                                            $emit(
                                                                'submit-files',
                                                                visit,
                                                            )
                                                        "
                                                    >
                                                        Lae üles
                                                    </Button>
                                                </div>

                                                <div
                                                    v-if="
                                                        pendingFiles[visit.id]
                                                            ?.length
                                                    "
                                                    class="mt-3 space-y-2"
                                                >
                                                    <div
                                                        v-for="(
                                                            file, index
                                                        ) in pendingFiles[
                                                            visit.id
                                                        ]"
                                                        :key="index"
                                                        class="flex items-center justify-between rounded-lg bg-muted px-3 py-2"
                                                    >
                                                        <span
                                                            class="truncate text-xs"
                                                        >
                                                            {{ file.name }}
                                                        </span>

                                                        <Button
                                                            variant="ghost"
                                                            size="sm"
                                                            @click="
                                                                $emit(
                                                                    'remove-pending-file',
                                                                    visit.id,
                                                                    index,
                                                                )
                                                            "
                                                        >
                                                            Eemalda
                                                        </Button>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="flex justify-end">
                                                <Button
                                                    variant="destructive"
                                                    size="sm"
                                                    @click="
                                                        $emit(
                                                            'delete-visit',
                                                            visit.id,
                                                        )
                                                    "
                                                >
                                                    Kustuta visiit
                                                </Button>
                                            </div>
                                        </div>
                                    </AccordionContent>
                                </AccordionItem>
                            </Accordion>
                        </CardContent>
                    </Card>
                </div>

                <!-- Right -->
                <div class="col-span-4 space-y-6">
                    <Card class="rounded-2xl">
                        <CardHeader
                            class="flex flex-row items-center justify-between space-y-0"
                        >
                            <div>
                                <CardTitle>Meditsiiniline info</CardTitle>
                                <CardDescription
                                    >Vaktsiinid ja ravimid</CardDescription
                                >
                            </div>

                            <Button
                                variant="outline"
                                @click="$emit('open-medical-modal')"
                            >
                                Lisa
                            </Button>
                        </CardHeader>

                        <CardContent class="space-y-6">
                            <div>
                                <div
                                    class="mb-3 flex items-center justify-between"
                                >
                                    <h3 class="text-sm font-medium">
                                        Vaktsiinid
                                    </h3>
                                    <Badge variant="secondary">{{
                                        stats.vaccines
                                    }}</Badge>
                                </div>

                                <div
                                    v-if="
                                        !pet.vaccines ||
                                        pet.vaccines.length === 0
                                    "
                                    class="text-sm text-muted-foreground"
                                >
                                    Vaktsiine pole veel lisatud.
                                </div>

                                <div v-else class="space-y-2">
                                    <div
                                        v-for="vaccine in pet.vaccines"
                                        :key="vaccine.id"
                                        class="rounded-xl border p-3"
                                    >
                                        <div
                                            class="flex items-start justify-between gap-3"
                                        >
                                            <div>
                                                <p class="font-medium">
                                                    {{ vaccine.name }}
                                                </p>
                                                <p
                                                    class="text-xs text-muted-foreground"
                                                >
                                                    {{
                                                        formatDate(
                                                            vaccine.administered_date,
                                                        )
                                                    }}
                                                </p>
                                                <p
                                                    v-if="vaccine.expiry_date"
                                                    class="mt-1 text-xs text-muted-foreground"
                                                >
                                                    Aegub:
                                                    {{
                                                        formatDate(
                                                            vaccine.expiry_date,
                                                        )
                                                    }}
                                                </p>
                                            </div>

                                            <Button
                                                variant="ghost"
                                                size="sm"
                                                @click="
                                                    $emit(
                                                        'delete-vaccine',
                                                        vaccine.id,
                                                    )
                                                "
                                            >
                                                Kustuta
                                            </Button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <Separator />

                            <div>
                                <div
                                    class="mb-3 flex items-center justify-between"
                                >
                                    <h3 class="text-sm font-medium">Ravimid</h3>
                                    <Badge variant="secondary">{{
                                        stats.medications
                                    }}</Badge>
                                </div>

                                <div
                                    v-if="
                                        !pet.medications ||
                                        pet.medications.length === 0
                                    "
                                    class="text-sm text-muted-foreground"
                                >
                                    Ravimeid pole veel lisatud.
                                </div>

                                <div v-else class="space-y-2">
                                    <div
                                        v-for="medication in pet.medications"
                                        :key="medication.id"
                                        class="rounded-xl border p-3"
                                    >
                                        <div
                                            class="flex items-start justify-between gap-3"
                                        >
                                            <div>
                                                <p class="font-medium">
                                                    {{ medication.name }}
                                                </p>
                                                <p
                                                    class="text-xs text-muted-foreground"
                                                >
                                                    {{ medication.dose_amount
                                                    }}{{ medication.dose_unit }}
                                                    ·
                                                    {{
                                                        medication.frequency_per_day
                                                    }}x päevas
                                                </p>
                                                <p
                                                    class="mt-1 text-xs text-muted-foreground"
                                                >
                                                    {{
                                                        formatDate(
                                                            medication.start_date,
                                                        )
                                                    }}
                                                    <span
                                                        v-if="
                                                            medication.end_date
                                                        "
                                                    >
                                                        –
                                                        {{
                                                            formatDate(
                                                                medication.end_date,
                                                            )
                                                        }}
                                                    </span>
                                                </p>
                                            </div>

                                            <Button
                                                variant="ghost"
                                                size="sm"
                                                @click="
                                                    $emit(
                                                        'delete-medication',
                                                        medication.id,
                                                    )
                                                "
                                            >
                                                Kustuta
                                            </Button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <Card class="rounded-2xl">
                        <CardHeader>
                            <CardTitle>Meeldetuletused</CardTitle>
                            <CardDescription>Pole veel lisatud</CardDescription>
                        </CardHeader>

                        <CardContent>
                            <div
                                class="rounded-xl border border-dashed border-border p-6 text-sm text-muted-foreground"
                            >
                                Meeldetuletusi pole veel lisatud.
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </div>
    </div>
</template>
