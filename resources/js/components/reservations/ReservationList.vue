<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3';
import { wTrans } from 'laravel-vue-i18n';
import { computed } from 'vue';

import { Button } from '@/components/ui/button';
import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from '@/components/ui/card';
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
const totalPages = computed(() => Math.max(1, props.reservations.last_page ?? 1));
const currentPage = computed(() => Math.min(
  props.reservations.current_page ?? 1,
  totalPages.value,
));
const prevLink = computed(() => props.reservations.prev_page_url ?? null);
const nextLink = computed(() => props.reservations.next_page_url ?? null);

const deleteReservation = (reservationId: number) => {
  const confirmMessage = wTrans('reservations.ui.list.confirm_cancel').value;

  if (!window.confirm(confirmMessage)) {
    return;
  }
  router.delete(destroy(reservationId), { preserveScroll: true });
};

const pageLabel = computed(() => wTrans(
  'reservations.ui.list.page',
  { current: currentPage.value, total: totalPages.value },
).value);

const guestsLabel = (count: number) => wTrans(
  'reservations.ui.list.guests',
  { count },
).value;
</script>

<template>
    <Card>
        <CardHeader>
            <CardTitle>
                {{ $t('reservations.ui.list.title') }}
            </CardTitle>
            <CardDescription>
                {{ $t('reservations.ui.list.description') }}
            </CardDescription>
        </CardHeader>
    <CardContent class="space-y-4">
      <div v-if="reservationItems.length" class="grid gap-3">
        <Card
          v-for="reservation in reservationItems"
          :key="reservation.id"
          class="border border-border/60"
        >
          <CardContent class="flex items-start justify-between gap-4 p-4">
            <div class="space-y-1">
              <p class="text-sm font-semibold">{{ formatReservationDate(reservation.date) }}</p>
              <p class="text-sm text-muted-foreground">
                {{ reservation.time_range }}
              </p>
              <p class="text-sm text-muted-foreground">
                {{ guestsLabel(reservation.guests) }}
              </p>
            </div>
            <Button
              variant="destructive"
              size="sm"
              type="button"
              @click="deleteReservation(reservation.id)"
            >
              {{ $t('reservations.ui.list.delete') }}
            </Button>
          </CardContent>
        </Card>

        <div
          v-if="totalPages > 1"
          class="flex items-center justify-between text-sm text-muted-foreground"
        >
          <Button
            as-child
            variant="outline"
            size="sm"
            :disabled="!prevLink"
          >
            <Link
              :href="prevLink ?? ''"
              preserve-scroll
              preserve-state
              :class="!prevLink ? 'pointer-events-none opacity-50' : ''"
            >
              {{ $t('reservations.ui.list.prev') }}
            </Link>
          </Button>
          <span>{{ pageLabel }}</span>
          <Button
            as-child
            variant="outline"
            size="sm"
            :disabled="!nextLink"
          >
            <Link
              :href="nextLink ?? ''"
              preserve-scroll
              preserve-state
              :class="!nextLink ? 'pointer-events-none opacity-50' : ''"
            >
              {{ $t('reservations.ui.list.next') }}
            </Link>
          </Button>
        </div>
      </div>

      <p v-else class="text-sm text-muted-foreground">
        {{ $t('reservations.ui.list.empty') }}
      </p>
    </CardContent>
  </Card>
</template>
