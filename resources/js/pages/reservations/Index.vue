<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';
import { ChevronDown } from 'lucide-vue-next';
import { computed, ref } from 'vue';

import ReservationForm from '@/components/reservations/ReservationForm.vue';
import ReservationList from '@/components/reservations/ReservationList.vue';
import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from '@/components/ui/card';
import {
  Collapsible,
  CollapsibleContent,
  CollapsibleTrigger,
} from '@/components/ui/collapsible';
import AppLayout from '@/layouts/AppLayout.vue';
import { index } from '@/routes/reservations';
import { type BreadcrumbItem } from '@/types';

const page = usePage();
const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'reservations.ui.page_title',
    href: index().url,
  },
];

const reservations = computed(() => page.props.reservations ?? {
  data: [],
  links: [],
  current_page: 1,
  last_page: 1,
  prev_page_url: null,
  next_page_url: null,
});
const maxGuests = computed(() => page.props.maxGuests ?? 10);
const hasReservations = computed(() => reservations.value.data.length > 0);
const manualFormOpen = ref<boolean | null>(null);
const formOpen = computed({
  get: () => manualFormOpen.value ?? !hasReservations.value,
  set: (value) => {
    manualFormOpen.value = value;
  },
});

const handleReservationCreated = () => {
  manualFormOpen.value = false;
};
</script>

<template>
  <AppLayout :breadcrumbs="breadcrumbs">
    <Head :title="$t('reservations.ui.page_title')" />

    <div class="flex h-full flex-1 flex-col gap-6 overflow-x-auto rounded-xl p-4">
      <div class="flex flex-col gap-6">
        <Card>
          <Collapsible v-model:open="formOpen">
            <CardHeader class="gap-2">
              <CollapsibleTrigger class="flex w-full items-center justify-between text-left">
                <div>
                  <CardTitle>
                    {{ $t('reservations.ui.form.title') }}
                  </CardTitle>
                  <CardDescription>
                    {{ $t('reservations.ui.form.description') }}
                  </CardDescription>
                </div>
                <ChevronDown
                  class="size-4 transition-transform"
                  :class="formOpen ? 'rotate-180' : ''"
                />
              </CollapsibleTrigger>
            </CardHeader>
            <CollapsibleContent>
              <CardContent>
                <ReservationForm
                  :max-guests="maxGuests"
                  @created="handleReservationCreated"
                />
              </CardContent>
            </CollapsibleContent>
          </Collapsible>
        </Card>

        <ReservationList :reservations="reservations" />
      </div>
    </div>
  </AppLayout>
</template>
