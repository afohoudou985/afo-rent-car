<script setup lang="ts">
import { about, contact, fleet, home, login, register } from '@/routes';
import { index as adminCarsIndex } from '@/routes/admin/cars/index';
import { index as clientReservationsIndex } from '@/routes/client/reservations/index';
import { Link, usePage } from '@inertiajs/vue3';

const $page = usePage();

const role = $page.props.auth.user?.role;

const dashboardLink =
    role === 'admin' ? adminCarsIndex() : clientReservationsIndex();
</script>

<template>
    <div>
        <header
            class="sticky top-0 z-50 border-b border-gray-100 bg-white/95 shadow-sm backdrop-blur-md"
        >
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <nav class="flex h-16 items-center justify-between">
                    <!-- Logo -->
                    <div class="flex flex-col items-center space-x-2">
                        <img src="/logo/logo.png" alt="logo" class="h-6" />
                        <p class="font-bold">
                            AFO<span class="text-orange-500">RENT</span>CAR
                        </p>
                    </div>

                    <!-- Navigation -->
                    <div class="hidden items-center space-x-8 md:flex">
                        <Link
                            :href="home()"
                            :class="{
                                'text-orange-500': $page.url === home().url,
                                'text-gray-700': $page.url !== home().url,
                            }"
                            class="font-medium transition-colors hover:text-orange-500"
                        >
                            Accueil
                        </Link>
                        <Link
                            :href="fleet()"
                            :class="{
                                'text-orange-500':
                                    $page.url.startsWith('/fleet'),
                                'text-gray-700':
                                    !$page.url.startsWith('/fleet'),
                            }"
                            class="font-medium transition-colors hover:text-orange-500"
                        >
                            Flotte
                        </Link>
                        <Link
                            :href="about()"
                            :class="{
                                'text-orange-500': $page.url === '/about',
                                'text-gray-700': $page.url !== '/about',
                            }"
                            class="font-medium transition-colors hover:text-orange-500"
                        >
                            À propos
                        </Link>
                        <Link
                            :href="contact()"
                            :class="{
                                'text-orange-500': $page.url === '/contact',
                                'text-gray-700': $page.url !== '/contact',
                            }"
                            class="font-medium transition-colors hover:text-orange-500"
                        >
                            Contact
                        </Link>
                    </div>

                    <!-- Boutons d'authentification -->
                    <div class="flex items-center space-x-3">
                        <Link
                            v-if="$page.props.auth.user"
                            :href="dashboardLink"
                            class="inline-flex items-center rounded-xl bg-gray-50 px-6 py-2.5 text-sm font-semibold text-gray-700 transition-all duration-200 hover:bg-gray-100 hover:shadow-md"
                        >
                            <svg
                                class="mr-2 h-4 w-4"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"
                                ></path>
                            </svg>
                            Tableau de bord
                        </Link>
                        <template v-else>
                            <Link
                                :href="login()"
                                class="inline-flex items-center px-6 py-2.5 text-sm font-semibold text-gray-700 transition-colors duration-200 hover:text-orange-600"
                            >
                                Se connecter
                            </Link>
                            <Link
                                :href="register()"
                                class="inline-flex items-center rounded-xl bg-gradient-to-r from-orange-500 to-orange-600 px-6 py-2.5 text-sm font-semibold text-white shadow-lg transition-all duration-200 hover:scale-105 hover:from-orange-600 hover:to-orange-700 hover:shadow-xl"
                            >
                                S'inscrire
                            </Link>
                        </template>
                    </div>
                </nav>
            </div>
        </header>

        <slot />

        <!-- Pied de page -->
        <footer class="bg-gray-900 py-16 text-white">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid gap-12 md:grid-cols-4">
                    <div class="space-y-6">
                        <div class="flex items-center space-x-2">
                            <div
                                class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-orange-500 to-orange-600"
                            >
                                <svg
                                    class="h-6 w-6 text-white"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z"
                                    ></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold">
                                    AFO<span class="text-orange-500">RENT</span>
                                </h3>
                                <p class="text-xs font-medium text-gray-400">
                                    VOITURES PREMIUM
                                </p>
                            </div>
                        </div>
                        <p class="leading-relaxed text-gray-400">
                            Service de location de voitures premium proposant
                            des véhicules de luxe et fiables pour tous vos
                            besoins de transport avec un service client
                            exceptionnel.
                        </p>
                    </div>

                    <div class="space-y-6">
                        <h4 class="text-lg font-semibold">Services</h4>
                        <ul class="space-y-3 text-gray-400">
                            <li>
                                <a
                                    href="#"
                                    class="transition-colors hover:text-orange-500"
                                    >Location de voitures de luxe</a
                                >
                            </li>
                            <li>
                                <a
                                    href="#"
                                    class="transition-colors hover:text-orange-500"
                                    >Location longue durée</a
                                >
                            </li>
                            <li>
                                <a
                                    href="#"
                                    class="transition-colors hover:text-orange-500"
                                    >Solutions entreprises</a
                                >
                            </li>
                            <li>
                                <a
                                    href="#"
                                    class="transition-colors hover:text-orange-500"
                                    >Transferts aéroport</a
                                >
                            </li>
                        </ul>
                    </div>

                    <div class="space-y-6">
                        <h4 class="text-lg font-semibold">Support</h4>
                        <ul class="space-y-3 text-gray-400">
                            <li>
                                <a
                                    :href="contact.url()"
                                    class="transition-colors hover:text-orange-500"
                                    >Nous contacter</a
                                >
                            </li>
                            <li>
                                <a
                                    href="#"
                                    class="transition-colors hover:text-orange-500"
                                    >Centre d’aide</a
                                >
                            </li>
                            <li>
                                <a
                                    href="#"
                                    class="transition-colors hover:text-orange-500"
                                    >Conditions générales</a
                                >
                            </li>
                            <li>
                                <a
                                    href="#"
                                    class="transition-colors hover:text-orange-500"
                                    >Politique de confidentialité</a
                                >
                            </li>
                        </ul>
                    </div>

                    <div class="space-y-6">
                        <h4 class="text-lg font-semibold">
                            Informations de contact
                        </h4>
                        <div class="space-y-3 text-gray-400">
                            <div class="flex items-center space-x-3">
                                <svg
                                    class="h-5 w-5 text-orange-500"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"
                                    ></path>
                                </svg>
                                <span>+228 90764027</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <svg
                                    class="h-5 w-5 text-orange-500"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                                    ></path>
                                </svg>
                                <span>afohoudou985@gmail.com</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <svg
                                    class="h-5 w-5 text-orange-500"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                                    ></path>
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                                    ></path>
                                </svg>
                                <span>Lomé, Togo</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-2 border-t border-gray-800 pt-8">
                    <p class="text-center text-gray-400">
                        &copy; 2025 AfoRent. Tous droits réservés.
                    </p>
                </div>
            </div>
        </footer>
    </div>
</template>
