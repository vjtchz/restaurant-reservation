<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';
import { ChevronDown } from 'lucide-vue-next';
import { computed, ref } from 'vue';

import ReservationForm from '@/components/reservations/ReservationForm.vue';
import ReservationList from '@/components/reservations/ReservationList.vue';
import {
    Collapsible,
    CollapsibleContent,
    CollapsibleTrigger,
} from '@/components/ui/collapsible';
import WelcomeLayout from '@/layouts/WelcomeLayout.vue';

const page = usePage();
const reservations = computed(
    () =>
        page.props.reservations ?? {
            data: [],
            links: [],
            current_page: 1,
            last_page: 1,
            prev_page_url: null,
            next_page_url: null,
        },
);
const maxGuests = computed(() => page.props.maxGuests ?? 10);
const minDuration = computed(() => page.props.minDuration ?? 60);
const openingHours = computed(
    () => page.props.openingHours ?? { from: '11:00', to: '22:00' },
);
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
    <WelcomeLayout>
        <Head :title="$t('reservations.ui.page_title')" />

        <main class="mx-auto flex w-full max-w-5xl flex-col gap-6 px-6 py-10">
            <div class="brand-card">
                <Collapsible v-model:open="formOpen">
                    <CollapsibleTrigger
                        class="flex w-full items-start justify-between gap-4 text-left"
                    >
                        <div>
                            <h2 class="brand-title">
                                {{ $t('reservations.ui.form.title') }}
                            </h2>
                            <p
                                class="mt-2 text-sm text-[color:var(--brand-ink-muted)]"
                            >
                                {{ $t('reservations.ui.form.description') }}
                            </p>
                        </div>
                        <ChevronDown
                            class="mt-2 size-4 text-[color:var(--brand-ink-muted)] transition-transform"
                            :class="formOpen ? 'rotate-180' : ''"
                        />
                    </CollapsibleTrigger>
                    <CollapsibleContent class="mt-6">
                        <ReservationForm
                            :max-guests="maxGuests"
                            :min-duration="minDuration"
                            :opening-hours="openingHours"
                            @created="handleReservationCreated"
                        />
                    </CollapsibleContent>
                </Collapsible>
            </div>

            <ReservationList :reservations="reservations" />
        </main>
    </WelcomeLayout>
</template>
