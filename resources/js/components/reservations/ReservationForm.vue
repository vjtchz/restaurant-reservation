<script setup lang="ts">
import { router, usePage } from '@inertiajs/vue3';
import { wTrans } from 'laravel-vue-i18n';
import { computed, reactive, ref, watch } from 'vue';

import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { useReservationFormatting } from '@/composables/useReservationFormatting';
import { store } from '@/routes/reservations';

const props = defineProps<{
  maxGuests?: number;
}>();
const emit = defineEmits<{
  (event: 'created'): void;
}>();

const page = usePage();
const {
  addMinutesToTime,
  formatDateForInput,
} = useReservationFormatting();
const errors = computed(() => page.props.errors ?? {});
const slotMinutes = 30;
const minDurationMinutes = 60;
const timeSlots = Array.from({ length: 48 }, (_, index) => {
  const totalMinutes = index * slotMinutes;
  const hours = Math.floor(totalMinutes / 60)
    .toString()
    .padStart(2, '0');
  const minutes = (totalMinutes % 60).toString().padStart(2, '0');

  return `${hours}:${minutes}`;
});

const getDefaultForm = () => ({
  date: formatDateForInput(new Date()),
  time_from: '18:00',
  time_to: '19:00',
  guests: 2,
});

const form = reactive(getDefaultForm());

const maxGuests = computed(() => props.maxGuests ?? 10);

const minTimeTo = computed(() => {
  if (!form.time_from) {
    return '';
  }

  return addMinutesToTime(form.time_from, minDurationMinutes);
});

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

watch(() => form.time_from, (value) => {
  if (!value) {
    return;
  }

  const nextTimeTo = addMinutesToTime(value, minDurationMinutes);
  if (!form.time_to || form.time_to < nextTimeTo) {
    form.time_to = nextTimeTo;
  }
});

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
</script>

<template>
  <form class="grid gap-4" @submit.prevent="createReservation">
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
        step="900"
        :min="minTimeTo"
        list="reservation-time-options"
        required
      />
      <p class="text-xs text-muted-foreground">
        {{ $t('reservations.ui.form.min_duration', { minutes: minDurationMinutes }) }}
      </p>
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
