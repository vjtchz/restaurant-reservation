<script setup lang="ts">
import { router, usePage } from '@inertiajs/vue3';
import { wTrans } from 'laravel-vue-i18n';
import { computed, reactive, ref, watch } from 'vue';

import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { useReservationFormatting } from '@/composables/useReservationFormatting';
import { useReservationTime } from '@/composables/useReservationTime';
import { store } from '@/routes/reservations';

const props = defineProps<{
  maxGuests?: number;
  minDuration?: number;
  openingHours?: {
    from: string;
    to: string;
  };
}>();
const emit = defineEmits<{
  (event: 'created'): void;
}>();

const page = usePage();
const { formatDateForInput } = useReservationFormatting();
const { addMinutes, buildTimeSlots, toMinutes, toTime } = useReservationTime();
const errors = computed(() => page.props.errors ?? {});
const openingHours = computed(() => props.openingHours ?? { from: '11:00', to: '22:00' });
const openingFrom = computed(() => openingHours.value.from);
const openingTo = computed(() => openingHours.value.to);


const getDefaultForm = () => {
  const now = new Date();
  const today = formatDateForInput(now);
  const openingStart = toMinutes(openingFrom.value);
  const openingEnd = toMinutes(openingTo.value);

  let date = today;
  let startTime = openingFrom.value;

  if (openingStart !== null && openingEnd !== null) {
    const nowMinutes = now.getHours() * 60 + now.getMinutes();
    const isAfterClose = nowMinutes >= openingEnd;

    if (isAfterClose) {
      const tomorrow = new Date(now);
      tomorrow.setDate(now.getDate() + 1);
      date = formatDateForInput(tomorrow);
    } else if (nowMinutes > openingStart) {
      startTime = toTime(nowMinutes);
    }
  }

  return {
    date,
    time_from: startTime,
    time_to: addMinutes(startTime, 60, openingTo.value),
    guests: 2,
  };
};

const form = reactive(getDefaultForm());

const maxGuests = computed(() => props.maxGuests ?? 10);
const minDuration = computed(() => props.minDuration ?? 60);

const timeSlots = computed(() => buildTimeSlots(
  openingFrom.value,
  openingTo.value,
  minDuration,
));


const summary = computed(() => {
  if (!form.date || !form.time_from || !form.time_to) {
    return wTrans('reservations.ui.form.summary_empty').value;
  }

  const key = form.guests === 1
    ? 'reservations.ui.form.summary_single'
    : 'reservations.ui.form.summary_plural';

  return wTrans(key, {
    date: form.date,
    from: form.time_from,
    to: form.time_to,
    guests: form.guests,
  }).value;
});

const availability = reactive({
  status: 'idle' as 'idle' | 'checking' | 'available' | 'full' | 'error',
  remaining: null as number | null,
});
const availabilityRequest = ref(0);
const availabilityTimer = ref<number | null>(null);

const availabilityMessage = computed(() => {
  if (availability.status === 'checking') {
    return wTrans('reservations.ui.form.availability.checking').value;
  }

  if (availability.status === 'available') {
    const key = availability.remaining === 1
      ? 'reservations.ui.form.availability.available_single'
      : 'reservations.ui.form.availability.available';

    return wTrans(key, { count: availability.remaining ?? 0 }).value;
  }

  if (availability.status === 'full') {
    return wTrans('reservations.ui.form.availability.full').value;
  }

  if (availability.status === 'error') {
    return wTrans('reservations.ui.form.availability.error').value;
  }

  return '';
});

const resetForm = () => {
  Object.assign(form, getDefaultForm());
};

const createReservation = () => {
  router.post(store(), { ...form }, {
    preserveScroll: true,
    onSuccess: () => {
      resetForm();
      emit('created');
    },
  });
};

watch(
  () => [form.date, form.time_from, form.time_to],
  () => {
    if (!form.date || !form.time_from || !form.time_to || form.time_to <= form.time_from) {
      availability.status = 'idle';
      availability.remaining = null;
      return;
    }

    if (availabilityTimer.value !== null) {
      window.clearTimeout(availabilityTimer.value);
    }

    availability.status = 'checking';
    const requestId = ++availabilityRequest.value;

    availabilityTimer.value = window.setTimeout(async () => {
      try {
        const params = new URLSearchParams({
          date: form.date,
          time_from: form.time_from,
          time_to: form.time_to,
        });

        const response = await fetch(`/api/reservations/availability?${params.toString()}`, {
          headers: { Accept: 'application/json' },
        });

        if (requestId !== availabilityRequest.value) {
          return;
        }

        if (!response.ok) {
          throw new Error('Availability check failed');
        }

        const data = await response.json() as { available: boolean; remaining: number };
        availability.remaining = data.remaining;
        availability.status = data.available ? 'available' : 'full';
      } catch {
        if (requestId !== availabilityRequest.value) {
          return;
        }

        availability.status = 'error';
        availability.remaining = null;
      }
    }, 300);
  },
  { immediate: true },
);

watch(
  () => form.time_from,
  (value) => {
    if (!value) {
      return;
    }

    form.time_to = addMinutes(value, minDuration.value, openingTo.value);
  },
);
</script>

<template>
  <form class="grid gap-4" novalidate @submit.prevent="createReservation">
    <div class="grid gap-2">
      <Label for="reservation-date">
        {{ $t('reservations.ui.form.labels.date') }}
      </Label>
      <Input
        id="reservation-date"
        v-model="form.date"
        type="date"
        :min="formatDateForInput(new Date())"
        required
      />
      <InputError :message="errors.date" />
    </div>

    <div class="grid gap-2">
      <Label for="reservation-time-from">
        {{ $t('reservations.ui.form.labels.start_time') }}
      </Label>
      <Input
        id="reservation-time-from"
        v-model="form.time_from"
        type="time"
        :min="openingFrom"
        :max="openingTo"
        step="900"
        list="reservation-time-options"
        required
      />
      <InputError :message="errors.time_from" />
    </div>

    <div class="grid gap-2">
      <Label for="reservation-time-to">
        {{ $t('reservations.ui.form.labels.end_time') }}
      </Label>
      <Input
        id="reservation-time-to"
        v-model="form.time_to"
        type="time"
        :min="form.time_from || openingFrom"
        :max="openingTo"
        step="900"
        list="reservation-time-options"
        required
      />
      <InputError :message="errors.time_to" />
    </div>

    <div class="grid gap-2">
      <Label for="reservation-guests">
        {{ $t('reservations.ui.form.labels.guests') }}
      </Label>
      <Input
        id="reservation-guests"
        v-model.number="form.guests"
        type="number"
        min="1"
        :max="maxGuests"
        required
      />
      <p class="text-xs text-muted-foreground">
        {{ $t('reservations.ui.form.guests_hint', { max: maxGuests }) }}
      </p>
      <InputError :message="errors.guests" />
    </div>

    <datalist id="reservation-time-options">
      <option v-for="slot in timeSlots" :key="slot" :value="slot" />
    </datalist>

    <div v-if="availabilityMessage" class="text-sm text-muted-foreground">
      {{ availabilityMessage }}
    </div>

    <div class="rounded-lg border border-dashed border-border px-4 py-3 text-sm text-muted-foreground">
      {{ summary }}
    </div>

    <div class="flex justify-end">
      <Button type="submit">
        {{ $t('reservations.ui.form.submit') }}
      </Button>
    </div>
  </form>
</template>
