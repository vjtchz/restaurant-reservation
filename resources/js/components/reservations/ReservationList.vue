<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3';
import { wTrans } from 'laravel-vue-i18n';
import { computed } from 'vue';

import { useReservationFormatting } from '@/composables/useReservationFormatting';
import { destroy } from '@/routes/reservations';

type Reservation = {
    id: number;
    date: string;
    time_from: string;
    time_to: string;
    time_range: string;
    guests: number;
};

type PaginationLink = {
    url: string | null;
    label: string;
    active: boolean;
};

const props = defineProps<{
    reservations: {
        data: Reservation[];
        links: PaginationLink[];
        current_page: number;
        last_page: number;
        prev_page_url: string | null;
        next_page_url: string | null;
    };
}>();

const { formatReservationDate } = useReservationFormatting();

const reservationItems = computed(() => props.reservations.data ?? []);
const totalPages = computed(() =>
    Math.max(1, props.reservations.last_page ?? 1),
);
const currentPage = computed(() =>
    Math.min(props.reservations.current_page ?? 1, totalPages.value),
);
const prevLink = computed(() => props.reservations.prev_page_url ?? null);
const nextLink = computed(() => props.reservations.next_page_url ?? null);

const deleteReservation = (reservationId: number) => {
    const confirmMessage = wTrans('reservations.ui.list.confirm_cancel').value;

    if (!window.confirm(confirmMessage)) {
        return;
    }
    router.delete(destroy(reservationId), { preserveScroll: true });
};

const pageLabel = computed(
    () =>
        wTrans('reservations.ui.list.page', {
            current: currentPage.value,
            total: totalPages.value,
        }).value,
);

const guestsLabel = (count: number) =>
    wTrans('reservations.ui.list.guests', { count }).value;
</script>

<template>
    <div class="welcome-card">
        <div>
            <h2 class="welcome-title">
                {{ $t('reservations.ui.list.title') }}
            </h2>
            <p class="mt-2 text-sm text-[color:var(--welcome-ink-muted)]">
                {{ $t('reservations.ui.list.description') }}
            </p>
        </div>

        <div class="mt-6 space-y-4">
            <div v-if="reservationItems.length" class="grid gap-3">
                <div
                    v-for="reservation in reservationItems"
                    :key="reservation.id"
                    class="welcome-card-soft flex items-start justify-between gap-4"
                >
                    <div class="space-y-1">
                        <p
                            class="text-sm font-semibold text-[color:var(--welcome-ink-strong)]"
                        >
                            {{ formatReservationDate(reservation.date) }}
                        </p>
                        <p
                            class="text-sm text-[color:var(--welcome-ink-muted)]"
                        >
                            {{ reservation.time_range }}
                        </p>
                        <p
                            class="text-sm text-[color:var(--welcome-ink-muted)]"
                        >
                            {{ guestsLabel(reservation.guests) }}
                        </p>
                    </div>
                    <button
                        type="button"
                        class="welcome-link border-red-200 text-xs text-red-600 hover:border-red-300"
                        @click="deleteReservation(reservation.id)"
                    >
                        {{ $t('reservations.ui.list.delete') }}
                    </button>
                </div>

                <div
                    v-if="totalPages > 1"
                    class="flex items-center justify-between text-sm text-[color:var(--welcome-ink-muted)]"
                >
                    <Link
                        :href="prevLink ?? ''"
                        preserve-scroll
                        preserve-state
                        class="welcome-link text-xs"
                        :class="
                            !prevLink ? 'pointer-events-none opacity-50' : ''
                        "
                    >
                        {{ $t('reservations.ui.list.prev') }}
                    </Link>
                    <span>{{ pageLabel }}</span>
                    <Link
                        :href="nextLink ?? ''"
                        preserve-scroll
                        preserve-state
                        class="welcome-link text-xs"
                        :class="
                            !nextLink ? 'pointer-events-none opacity-50' : ''
                        "
                    >
                        {{ $t('reservations.ui.list.next') }}
                    </Link>
                </div>
            </div>

            <p v-else class="text-sm text-[color:var(--welcome-ink-muted)]">
                {{ $t('reservations.ui.list.empty') }}
            </p>
        </div>
    </div>
</template>
