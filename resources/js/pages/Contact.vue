<script setup lang="ts">
import HomeLayout from '@/layouts/HomeLayout.vue';
import { about, fleet } from '@/routes';
import { guestContact } from '@/routes/contact';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const form = useForm({
    name: '',
    email: '',
    subject: '',
    message: '',
});

const showNotification = ref(false);
const notificationMessage = ref('');

const sendTicket = () => {
    form.post(guestContact().url, {
        onSuccess() {
            form.reset();
            showNotification.value = true;
            notificationMessage.value = 'Message sent successfully!';
            setTimeout(() => {
                showNotification.value = false;
            }, 2000);
        },
        onError() {
            showNotification.value = true;
            notificationMessage.value =
                'Failed to send message! Please try again.';
            setTimeout(() => {
                showNotification.value = false;
            }, 2000);
        },
    });
};
</script>
<template>
    <HomeLayout>
        <div class="min-h-screen bg-white py-16">
            <!-- notification -->
            <div>
                <p
                    class="fixed top-24 right-4 rounded-xl bg-slate-700 p-3 text-white"
                    v-if="showNotification"
                >
                    {{ notificationMessage }}
                </p>
            </div>

            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <!-- Header Section -->
                <div class="mb-16 text-center">
                    <h1 class="mb-4 text-4xl font-bold text-gray-900">
                        Contactez-nous
                    </h1>
                    <p class="mx-auto max-w-2xl text-xl text-gray-600">
                        Vous avez des questions sur nos services de location de
                        voitures ? Nous sommes là pour vous aider. Envoyez-nous
                        un message et nous vous répondrons dans les plus brefs
                        délais.
                    </p>
                </div>

                <div class="grid gap-12 lg:grid-cols-3">
                    <!-- Formulaire de Contact -->
                    <div class="lg:col-span-2">
                        <div
                            class="rounded-lg border border-gray-200 bg-white p-8 shadow-sm"
                        >
                            <h2 class="mb-6 text-2xl font-bold text-gray-900">
                                Envoyez-nous un message
                            </h2>

                            <form
                                class="space-y-6"
                                @submit.prevent="sendTicket"
                            >
                                <!-- Champ Nom -->
                                <div>
                                    <label
                                        for="name"
                                        class="mb-2 block text-sm font-semibold text-gray-700"
                                    >
                                        Nom complet
                                    </label>
                                    <input
                                        type="text"
                                        id="name"
                                        name="name"
                                        class="w-full rounded-lg border border-gray-300 px-4 py-3 transition-colors focus:border-orange-500 focus:ring-2 focus:ring-orange-500"
                                        placeholder="Entrez votre nom complet"
                                        v-model="form.name"
                                    />
                                    <span
                                        class="text-red-500"
                                        v-if="form.errors.name"
                                        >{{ form.errors.name }}</span
                                    >
                                </div>

                                <!-- Champ Email -->
                                <div>
                                    <label
                                        for="email"
                                        class="mb-2 block text-sm font-semibold text-gray-700"
                                    >
                                        Adresse email
                                    </label>
                                    <input
                                        type="email"
                                        id="email"
                                        name="email"
                                        class="w-full rounded-lg border border-gray-300 px-4 py-3 transition-colors focus:border-orange-500 focus:ring-2 focus:ring-orange-500"
                                        placeholder="Entrez votre adresse email"
                                        v-model="form.email"
                                    />
                                    <span
                                        class="text-red-500"
                                        v-if="form.errors.email"
                                        >{{ form.errors.email }}</span
                                    >
                                </div>

                                <!-- Champ Sujet -->
                                <div>
                                    <label
                                        for="subject"
                                        class="mb-2 block text-sm font-semibold text-gray-700"
                                    >
                                        Sujet
                                    </label>
                                    <input
                                        type="text"
                                        id="subject"
                                        name="subject"
                                        class="w-full rounded-lg border border-gray-300 px-4 py-3 transition-colors focus:border-orange-500 focus:ring-2 focus:ring-orange-500"
                                        placeholder="À propos de quoi s'agit-il ?"
                                        v-model="form.subject"
                                    />
                                    <span
                                        class="text-red-500"
                                        v-if="form.errors.subject"
                                        >{{ form.errors.subject }}</span
                                    >
                                </div>

                                <!-- Champ Message -->
                                <div>
                                    <label
                                        for="message"
                                        class="mb-2 block text-sm font-semibold text-gray-700"
                                    >
                                        Message
                                    </label>
                                    <textarea
                                        id="message"
                                        name="message"
                                        rows="6"
                                        class="resize-vertical w-full rounded-lg border border-gray-300 px-4 py-3 transition-colors focus:border-orange-500 focus:ring-2 focus:ring-orange-500"
                                        placeholder="Dites-nous comment nous pouvons vous aider..."
                                        v-model="form.message"
                                    ></textarea>
                                    <span
                                        class="text-red-500"
                                        v-if="form.errors.message"
                                        >{{ form.errors.message }}</span
                                    >
                                </div>

                                <!-- Bouton Envoyer -->
                                <div>
                                    <button
                                        type="submit"
                                        class="w-full cursor-pointer rounded-lg bg-orange-500 px-6 py-3 font-semibold text-white transition-colors duration-200 hover:bg-orange-600"
                                    >
                                        Envoyer le message
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Barre latérale d'informations de contact -->
                    <div class="lg:col-span-1">
                        <div
                            class="rounded-lg border border-gray-200 bg-gray-50 p-8"
                        >
                            <h3 class="mb-6 text-xl font-bold text-gray-900">
                                Contactez-nous
                            </h3>

                            <div class="space-y-6">
                                <!-- Téléphone -->
                                <div>
                                    <h4
                                        class="mb-2 font-semibold text-gray-900"
                                    >
                                        Téléphone
                                    </h4>
                                    <p class="text-gray-600">
                                        +1 (555) 123-4567
                                    </p>
                                </div>

                                <!-- Email -->
                                <div>
                                    <h4
                                        class="mb-2 font-semibold text-gray-900"
                                    >
                                        Email
                                    </h4>
                                    <p class="text-gray-600">
                                        info@Aforentcar.com
                                    </p>
                                </div>

                                <!-- Adresse -->
                                <div>
                                    <h4
                                        class="mb-2 font-semibold text-gray-900"
                                    >
                                        Adresse
                                    </h4>
                                    <p class="text-gray-600">
                                        123 Rue Principale<br />
                                        Quartier Centre-Ville<br />
                                        Ville, État 12345
                                    </p>
                                </div>

                                <!-- Horaires d'ouverture -->
                                <div>
                                    <h4
                                        class="mb-2 font-semibold text-gray-900"
                                    >
                                        Horaires d'ouverture
                                    </h4>
                                    <div class="space-y-1 text-gray-600">
                                        <p>Lundi - Vendredi : 8h00 - 20h00</p>
                                        <p>Samedi : 9h00 - 18h00</p>
                                        <p>Dimanche : 10h00 - 16h00</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Liens rapides -->
                        <div
                            class="mt-8 rounded-lg border border-gray-200 bg-white p-6"
                        >
                            <h3 class="mb-4 text-lg font-bold text-gray-900">
                                Liens rapides
                            </h3>
                            <div class="space-y-3">
                                <a
                                    :href="fleet.url()"
                                    class="block font-medium text-orange-500 transition-colors hover:text-orange-600"
                                >
                                    Parcourez notre flotte
                                </a>
                                <a
                                    :href="about.url()"
                                    class="block font-medium text-orange-500 transition-colors hover:text-orange-600"
                                >
                                    À propos de nous
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </HomeLayout>
</template>
