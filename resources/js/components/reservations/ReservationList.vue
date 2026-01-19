<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { useI18n } from 'vue-i18n';

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

const props = defineProps<{
  reservations: Reservation[];
}>();

const { t } = useI18n();
const { formatReservationDate } = useReservationFormatting();

const pageSize = 10;
const totalPages = computed(() =>
  Math.max(1, Math.ceil(props.reservations.length / pageSize)),
);
const currentPage = ref(1);
const safeCurrentPage = computed({
  get: () => Math.min(currentPage.value, totalPages.value),
  set: (value) => {
    currentPage.value = value;
  },
});
const pagedReservations = computed(() => {
  const start = (safeCurrentPage.value - 1) * pageSize;
  return props.reservations.slice(start, start + pageSize);
});

const deleteReservation = (reservationId: number) => {
  const confirmMessage = t('reservations.ui.list.confirm_cancel');

  if (!window.confirm(confirmMessage)) {
    return;
  }
  router.delete(destroy(reservationId), { preserveScroll: true });
};

const goPrev = () => {
  if (safeCurrentPage.value > 1) {
    safeCurrentPage.value -= 1;
  }
};

const goNext = () => {
  if (safeCurrentPage.value < totalPages.value) {
    safeCurrentPage.value += 1;
  }
};

const pageLabel = computed(() => t(
  'reservations.ui.list.page',
  { current: safeCurrentPage.value, total: totalPages.value },
));

const guestsLabel = (count: number) => t(
  'reservations.ui.list.guests',
  { count },
);
</script>

<template>
  <Card>
    <CardHeader>
      <CardTitle>
        {{ t('reservations.ui.list.title') }}
      </CardTitle>
      <CardDescription>
        {{ t('reservations.ui.list.description') }}
      </CardDescription>
    </CardHeader>
    <CardContent class="space-y-4">
      <div v-if="pagedReservations.length" class="grid gap-3">
        <Card
          v-for="reservation in pagedReservations"
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
              {{ t('reservations.ui.list.delete') }}
            </Button>
          </CardContent>
        </Card>

        <div
          v-if="totalPages > 1"
          class="flex items-center justify-between text-sm text-muted-foreground"
        >
          <Button
            type="button"
            variant="outline"
            size="sm"
            :disabled="safeCurrentPage === 1"
            @click="goPrev"
          >
            {{ t('reservations.ui.list.prev') }}
          </Button>
          <span>{{ pageLabel }}</span>
          <Button
            type="button"
            variant="outline"
            size="sm"
            :disabled="safeCurrentPage === totalPages"
            @click="goNext"
          >
            {{ t('reservations.ui.list.next') }}
          </Button>
        </div>
      </div>

      <p v-else class="text-sm text-muted-foreground">
        {{ t('reservations.ui.list.empty') }}
      </p>
    </CardContent>
  </Card>
</template>
