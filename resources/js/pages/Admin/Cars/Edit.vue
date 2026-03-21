<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import FileUpload from '@/components/ViltFilePond/FileUpload.vue';
import AdminLayout from '@/layouts/AdminLayout.vue';
import { index, store, update } from '@/routes/admin/cars';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';

const props = defineProps<{
    car: any | null;
    imageFiles: Array<{ id: number; url: string }>;
    enums: {
        colors: Array<{ name: string; value: string; hex: string }>;
        fuelTypes: string[];
        statuses: Array<{ value: string; label: string; color: string }>;
    };
}>();

const isEdit = computed(() => !!props.car);

// Form state
const carColors = computed(() =>
    props.enums.colors.map((color) => ({
        ...color,
        value: color.value.toLowerCase(),
        name: color.name.charAt(0).toUpperCase() + color.name.slice(1),
    })),
);

const fuelTypes = computed(() =>
    props.enums.fuelTypes.map((fuel) => ({
        value: fuel.toLowerCase(),
        label: fuel.charAt(0).toUpperCase() + fuel.slice(1),
    })),
);

const statuses = computed(() =>
    props.enums.statuses.map((status) => ({
        value: status.value,
        label: status.label,
        color: status.color,
    })),
);

// Initialize form with default values
const form = useForm({
    make: props.car?.make ?? '',
    model: props.car?.model ?? '',
    year: props.car?.year ?? '',
    license_plate: props.car?.license_plate ?? '',
    color: (props.car?.color || 'white').toLowerCase(),
    price_per_day: props.car?.price_per_day ?? '',
    mileage: props.car?.mileage ?? '',
    transmission: props.car?.transmission ?? 'automatic',
    seats: props.car?.seats ?? '',
    fuel_type: (props.car?.fuel_type || 'gasoline').toLowerCase(),
    description: props.car?.description ?? '',
    status: props.car?.status ?? 'available',
    // FilePond fields
    image: [] as string[],
    image_temp_folders: [] as string[],
    image_removed_files: [] as number[],
});

// Single image upload handling
const fileUploadRef = ref<InstanceType<typeof FileUpload> | null>(null);
const tempFolders = ref<string[]>([]);
const removedFileIds = ref<number[]>([]);

// Sync temp folders with form for edit
watch(
    tempFolders,
    (value) => {
        form.image_temp_folders = [...value];
    },
    { deep: true },
);

function handleFileRemoved(data: { type: string; fileId?: number }) {
    if (data.type === 'existing' && data.fileId) {
        removedFileIds.value.push(data.fileId);
        form.image_removed_files = [...removedFileIds.value];
    }
}

function submit() {
    if (isEdit.value) {
        form.put(update(props.car.id).url);
    } else {
        // for create, pass image temp folders via `image`
        form.image = [...tempFolders.value];
        form.post(store().url, {
            onSuccess: () => {
                form.reset();
                tempFolders.value = [];
                fileUploadRef.value?.resetFiles();
            },
        });
    }
}
</script>

<template>
    <Head :title="isEdit ? 'Modifier une voiture' : 'Créer une voiture'" />
    <AdminLayout>
        <!-- Contenu principal -->
        <main class="flex-1 space-y-6 p-8">
            <div class="flex items-center justify-between gap-4">
                <h1 class="text-2xl font-semibold">
                    {{ isEdit ? 'Modifier une voiture' : 'Créer une voiture' }}
                </h1>
                <Link :href="index()">
                    <Button variant="outline">Retour</Button>
                </Link>
            </div>

            <form class="space-y-6" @submit.prevent="submit">
                <div class="space-y-6">
                    <!-- Section Image et Statut -->
                    <div class="flex flex-col gap-6 md:flex-row md:gap-8">
                        <!-- Image -->
                        <div class="w-full md:w-1/2">
                            <Label>Image</Label>
                            <div class="mt-2">
                                <FileUpload
                                    ref="fileUploadRef"
                                    v-model="tempFolders"
                                    :initial-files="imageFiles || []"
                                    :allow-multiple="false"
                                    :max-files="1"
                                    collection="image"
                                    theme="light"
                                    width="100%"
                                    @file-removed="handleFileRemoved"
                                />
                            </div>
                        </div>

                        <!-- Statut, Prix et Couleur -->
                        <div class="w-full space-y-4 py-0 md:w-1/2 md:py-6">
                            <!-- Statut -->
                            <div>
                                <Label for="status">Statut</Label>
                                <select
                                    id="status"
                                    v-model="form.status"
                                    class="mt-1 block w-full rounded-md border border-gray-300 py-2 pr-10 pl-3 text-base focus:border-blue-500 focus:ring-blue-500 focus:outline-none sm:text-sm"
                                >
                                    <option
                                        v-for="status in statuses"
                                        :key="status.value"
                                        :value="status.value"
                                        :class="`text-${status.color}-700 bg-${status.color}-100`"
                                    >
                                        {{ status.label }}
                                    </option>
                                </select>
                                <InputError
                                    :message="form.errors.status"
                                    class="mt-1"
                                />
                            </div>

                            <!-- Prix par jour -->
                            <div>
                                <Label for="price_per_day">Prix par jour</Label>
                                <Input
                                    id="price_per_day"
                                    v-model="form.price_per_day"
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    placeholder="ex : 50.00"
                                />
                                <InputError
                                    :message="form.errors.price_per_day"
                                    class="mt-1"
                                />
                            </div>

                            <!-- Couleur -->
                            <div>
                                <Label class="mb-2 block">Couleur</Label>
                                <div
                                    class="grid grid-cols-3 gap-2 sm:grid-cols-5"
                                >
                                    <div
                                        v-for="color in carColors"
                                        :key="color.value"
                                        class="flex items-center"
                                    >
                                        <input
                                            type="radio"
                                            :id="'color-' + color.value"
                                            v-model="form.color"
                                            :value="color.value"
                                            class="peer sr-only"
                                        />
                                        <label
                                            :for="'color-' + color.value"
                                            class="flex w-full cursor-pointer items-center justify-between rounded-md border p-2 text-sm font-medium peer-checked:border-blue-500 peer-checked:ring-1 peer-checked:ring-blue-500 hover:bg-gray-50 dark:hover:bg-gray-800"
                                            :title="color.name"
                                        >
                                            <span>{{ color.name }}</span>
                                            <span
                                                class="inline-block h-4 w-4 rounded-full border border-gray-300"
                                                :style="{
                                                    backgroundColor: color.hex,
                                                }"
                                            ></span>
                                        </label>
                                    </div>
                                </div>
                                <InputError
                                    :message="form.errors.color"
                                    class="mt-1"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Champs du formulaire -->
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <!-- Marque -->
                        <div>
                            <Label for="make">Marque</Label>
                            <Input
                                id="make"
                                v-model="form.make"
                                placeholder="ex : Toyota, Honda, Ford, BMW..."
                            />
                            <InputError
                                :message="form.errors.make"
                                class="mt-1"
                            />
                        </div>

                        <!-- Modèle -->
                        <div>
                            <Label for="model">Modèle</Label>
                            <Input
                                id="model"
                                v-model="form.model"
                                placeholder="ex : Camry, Civic, F-150, X5..."
                            />
                            <InputError
                                :message="form.errors.model"
                                class="mt-1"
                            />
                        </div>

                        <!-- Année -->
                        <div>
                            <Label for="year">Année</Label>
                            <Input
                                id="year"
                                v-model="form.year"
                                type="number"
                                :min="1900"
                                :max="new Date().getFullYear() + 1"
                                placeholder="ex : 2023"
                            />
                            <InputError
                                :message="form.errors.year"
                                class="mt-1"
                            />
                        </div>

                        <!-- Plaque d'immatriculation -->
                        <div>
                            <Label for="license_plate"
                                >Plaque d'immatriculation</Label
                            >
                            <Input
                                id="license_plate"
                                v-model="form.license_plate"
                                placeholder="ex : ABC-1234 ou 123-ABC-45"
                            />
                            <InputError
                                :message="form.errors.license_plate"
                                class="mt-1"
                            />
                        </div>

                        <!-- Kilométrage -->
                        <div>
                            <Label for="mileage">Kilométrage (km)</Label>
                            <Input
                                id="mileage"
                                v-model="form.mileage"
                                type="number"
                                min="0"
                                step="1000"
                                placeholder="ex : 15000"
                            />
                            <InputError
                                :message="form.errors.mileage"
                                class="mt-1"
                            />
                        </div>

                        <!-- Transmission -->
                        <div>
                            <Label for="transmission">Transmission</Label>
                            <select
                                id="transmission"
                                v-model="form.transmission"
                                class="w-full rounded-md border border-input bg-transparent px-3 py-2 dark:bg-input/30"
                            >
                                <option value="automatic">Automatique</option>
                                <option value="manual">Manuelle</option>
                            </select>
                            <InputError
                                :message="form.errors.transmission"
                                class="mt-1"
                            />
                        </div>

                        <!-- Places -->
                        <div>
                            <Label for="seats">Nombre de places</Label>
                            <Input
                                id="seats"
                                v-model="form.seats"
                                type="number"
                                min="1"
                                max="20"
                                placeholder="ex : 5"
                            />
                            <InputError
                                :message="form.errors.seats"
                                class="mt-1"
                            />
                        </div>

                        <!-- Type de carburant -->
                        <div>
                            <Label for="fuel_type">Type de carburant</Label>
                            <select
                                id="fuel_type"
                                v-model="form.fuel_type"
                                class="w-full rounded-md border border-input bg-transparent px-3 py-2 dark:bg-input/30"
                            >
                                <option
                                    v-for="fuel in fuelTypes"
                                    :key="fuel.value"
                                    :value="fuel.value"
                                >
                                    {{ fuel.label }}
                                </option>
                            </select>
                            <InputError
                                :message="form.errors.fuel_type"
                                class="mt-1"
                            />
                        </div>

                        <!-- Description -->
                        <div class="md:col-span-2">
                            <Label for="description">Description</Label>
                            <textarea
                                id="description"
                                v-model="form.description"
                                rows="4"
                                class="w-full rounded-md border border-input bg-transparent px-3 py-2 dark:bg-input/30"
                                placeholder="Entrez une description détaillée de la voiture (caractéristiques, état, remarques...)"
                            ></textarea>
                            <InputError
                                :message="form.errors.description"
                                class="mt-1"
                            />
                        </div>
                    </div>
                </div>

                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    <Button type="submit" :disabled="form.processing">
                        {{
                            form.processing
                                ? isEdit
                                    ? 'Enregistrement...'
                                    : 'Création...'
                                : isEdit
                                  ? 'Enregistrer les modifications'
                                  : 'Créer la voiture'
                        }}
                    </Button>
                    <Link :href="index()">
                        <Button type="button" variant="outline">Annuler</Button>
                    </Link>
                </div>
            </form>
        </main>
    </AdminLayout>
</template>
