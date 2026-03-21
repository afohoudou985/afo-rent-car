<script setup lang="ts">
import { Button } from '@/components/ui/button';
import ClientLayout from '@/layouts/ClientLayout.vue';
import { index, print } from '@/routes/client/reservations';
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps<{
    reservation: any;
    statusMeta: Array<{ value: string; label: string; color: string }>;
    paymentStatusMeta: Array<{ value: string; label: string }>;
    currency: { symbol: string; code: string };
}>();

const statusMap = computed(() => {
    const map: Record<string, { label: string; color: string }> = {};
    for (const s of props.statusMeta || [])
        map[s.value] = { label: s.label, color: s.color };
    return map;
});

function getStatusStyle(status: string) {
    const meta = statusMap.value[status];
    if (!meta)
        return {
            bg: 'rgba(107,114,128,0.1)',
            text: '#6B7280',
            dot: '#6B7280',
            label: status,
        };
    const hex = meta.color.replace('#', '');
    const r = parseInt(hex.slice(0, 2), 16);
    const g = parseInt(hex.slice(2, 4), 16);
    const b = parseInt(hex.slice(4, 6), 16);
    return {
        bg: `rgba(${r}, ${g}, ${b}, 0.1)`,
        text: meta.color,
        dot: meta.color,
        label: meta.label,
    };
}

function fmtDate(d?: string) {
    return d ? new Date(d).toLocaleDateString() : '—';
}
function fmtMoney(n?: number | string) {
    const v = Number(n ?? 0);
    return `${props.currency.symbol}${v.toFixed(2)}`;
}
</script>

<template>
    <Head :title="`Réservation ${reservation?.reservation_number || ''}`" />
    <ClientLayout>
        <main class="flex-1 space-y-6 p-8">
            <div class="flex items-center justify-between gap-4">
                <h1 class="text-2xl font-semibold">
                    Réservation {{ reservation?.reservation_number }}
                </h1>
                <div class="flex gap-2">
                    <Link :href="index().url">
                        <Button variant="outline">Retour</Button>
                    </Link>
                    <a
                        :href="print(reservation.id).url"
                        target="_blank"
                        rel="noopener"
                    >
                        <Button variant="secondary">Imprimer</Button>
                    </a>
                </div>
            </div>

            <!-- Bandeau d'en-tête -->
            <div
                class="flex items-center justify-between rounded-md border p-4"
            >
                <div class="space-y-1">
                    <div class="text-sm text-muted-foreground">Statut</div>
                    <div>
                        <span
                            class="inline-flex items-center gap-2 rounded-full px-2.5 py-1 text-xs font-medium"
                            :style="{
                                backgroundColor: getStatusStyle(
                                    reservation.status,
                                ).bg,
                                color: getStatusStyle(reservation.status).text,
                            }"
                        >
                            <span
                                class="size-2 rounded-full"
                                :style="{
                                    backgroundColor: getStatusStyle(
                                        reservation.status,
                                    ).dot,
                                }"
                            />
                            {{ getStatusStyle(reservation.status).label }}
                        </span>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-sm text-muted-foreground">Total</div>
                    <div class="text-xl font-semibold">
                        {{ fmtMoney(reservation.total_amount) }}
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- Client -->
                <div class="rounded-md border">
                    <div class="border-b px-4 py-3 font-medium">Client</div>
                    <div class="space-y-1 p-4">
                        <div class="text-sm">Nom</div>
                        <div class="font-medium">
                            {{ reservation.user?.name || '—' }}
                        </div>
                        <div class="mt-3 text-sm">Email</div>
                        <div class="font-medium">
                            {{ reservation.user?.email || '—' }}
                        </div>
                    </div>
                </div>

                <!-- Voiture -->
                <div class="rounded-md border">
                    <div class="border-b px-4 py-3 font-medium">Voiture</div>
                    <div class="space-y-1 p-4">
                        <div class="text-sm">Véhicule</div>
                        <div class="font-medium">
                            {{
                                reservation.car
                                    ? `${reservation.car.year} ${reservation.car.make} ${reservation.car.model}`
                                    : '—'
                            }}
                        </div>
                        <div class="mt-3 text-sm">Plaque</div>
                        <div class="font-medium">
                            {{ reservation.car?.license_plate || '—' }}
                        </div>
                    </div>
                </div>

                <!-- Détails de la réservation -->
                <div class="rounded-md border md:col-span-2">
                    <div class="border-b px-4 py-3 font-medium">
                        Détails de la réservation
                    </div>
                    <div class="grid grid-cols-1 gap-4 p-4 md:grid-cols-3">
                        <div>
                            <div class="text-sm text-muted-foreground">
                                Date de début
                            </div>
                            <div class="font-medium">
                                {{ fmtDate(reservation.start_date) }}
                                {{ reservation.pickup_time }}
                            </div>
                        </div>
                        <div>
                            <div class="text-sm text-muted-foreground">
                                Date de fin
                            </div>
                            <div class="font-medium">
                                {{ fmtDate(reservation.end_date) }}
                                {{ reservation.return_time }}
                            </div>
                        </div>
                        <div>
                            <div class="text-sm text-muted-foreground">
                                Durée
                            </div>
                            <div class="font-medium">
                                {{ reservation.total_days }} jours
                            </div>
                        </div>
                        <div>
                            <div class="text-sm text-muted-foreground">
                                Lieu de prise en charge
                            </div>
                            <div class="font-medium">
                                {{ reservation.pickup_location || '—' }}
                            </div>
                        </div>
                        <div>
                            <div class="text-sm text-muted-foreground">
                                Lieu de retour
                            </div>
                            <div class="font-medium">
                                {{ reservation.return_location || '—' }}
                            </div>
                        </div>
                        <div v-if="reservation.status === 'cancelled'">
                            <div class="text-sm text-muted-foreground">
                                Annulé le
                            </div>
                            <div class="font-medium">
                                {{
                                    reservation.cancelled_at
                                        ? new Date(
                                              reservation.cancelled_at,
                                          ).toLocaleString()
                                        : '—'
                                }}
                            </div>
                            <div class="mt-2 text-sm text-muted-foreground">
                                Raison
                            </div>
                            <div class="font-medium">
                                {{ reservation.cancellation_reason || '—' }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Montants -->
                <div class="rounded-md border">
                    <div class="border-b px-4 py-3 font-medium">Montants</div>
                    <div class="space-y-2 p-4">
                        <div class="flex items-center justify-between">
                            <div class="text-sm">Tarif journalier</div>
                            <div class="font-medium">
                                {{ fmtMoney(reservation.daily_rate) }}
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="text-sm">Sous-total</div>
                            <div class="font-medium">
                                {{ fmtMoney(reservation.subtotal) }}
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="text-sm">Taxe</div>
                            <div class="font-medium">
                                {{ fmtMoney(reservation.tax_amount) }}
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="text-sm">Remise</div>
                            <div class="font-medium">
                                -{{ fmtMoney(reservation.discount_amount) }}
                            </div>
                        </div>
                        <div
                            class="flex items-center justify-between border-t pt-2"
                        >
                            <div class="text-sm">Total</div>
                            <div class="text-lg font-semibold">
                                {{ fmtMoney(reservation.total_amount) }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Paiements -->
                <div class="rounded-md border md:col-span-2">
                    <div class="border-b px-4 py-3 font-medium">Paiements</div>
                    <div class="overflow-x-auto p-4">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-4 py-2 text-left text-xs font-medium tracking-wider text-gray-500 uppercase"
                                    >
                                        #
                                    </th>
                                    <th
                                        class="px-4 py-2 text-left text-xs font-medium tracking-wider text-gray-500 uppercase"
                                    >
                                        Montant
                                    </th>
                                    <th
                                        class="px-4 py-2 text-left text-xs font-medium tracking-wider text-gray-500 uppercase"
                                    >
                                        Méthode
                                    </th>
                                    <th
                                        class="px-4 py-2 text-left text-xs font-medium tracking-wider text-gray-500 uppercase"
                                    >
                                        Statut
                                    </th>
                                    <th
                                        class="px-4 py-2 text-left text-xs font-medium tracking-wider text-gray-500 uppercase"
                                    >
                                        Traité
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                <tr
                                    v-for="p in reservation.payments || []"
                                    :key="p.id"
                                >
                                    <td class="px-4 py-2">
                                        {{ p.payment_number }}
                                    </td>
                                    <td class="px-4 py-2">
                                        {{ fmtMoney(p.amount) }}
                                    </td>
                                    <td class="px-4 py-2">
                                        {{ p.payment_method }}
                                    </td>
                                    <td class="px-4 py-2">{{ p.status }}</td>
                                    <td class="px-4 py-2">
                                        {{
                                            p.processed_at
                                                ? new Date(
                                                      p.processed_at,
                                                  ).toLocaleString()
                                                : '—'
                                        }}
                                    </td>
                                </tr>
                                <tr
                                    v-if="
                                        !reservation.payments ||
                                        reservation.payments.length === 0
                                    "
                                >
                                    <td
                                        colspan="5"
                                        class="px-4 py-4 text-center text-gray-500"
                                    >
                                        Aucun paiement enregistré.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </ClientLayout>
</template>
