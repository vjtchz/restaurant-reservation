<script setup lang="ts">
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { wTrans } from 'laravel-vue-i18n';
import { computed } from 'vue';

import { Button } from '@/components/ui/button';
import WelcomeLayout from '@/layouts/WelcomeLayout.vue';

type ReservationIntent = {
    date: string;
    time_from: string;
    time_to: string;
    guests: number;
};

const props = defineProps<{
    intent: ReservationIntent;
    maxGuests?: number;
}>();

const page = usePage();
const errors = computed(() => page.props.errors ?? {});

const summary = computed(() => {
    const key =
        props.intent.guests === 1
            ? 'reservations.ui.form.summary_single'
            : 'reservations.ui.form.summary_plural';

    return wTrans(key, {
        date: props.intent.date,
        from: props.intent.time_from,
        to: props.intent.time_to,
        guests: props.intent.guests,
    }).value;
});

const confirmReservation = () => {
    router.post('/reservations/confirm', {}, { preserveScroll: true });
};
</script>

<template>
    <Head :title="$t('reservations.public.confirm.page_title')" />

    <WelcomeLayout>
        <main class="mx-auto w-full max-w-3xl space-y-6 px-6 py-14 lg:py-24">
            <div>
                <h1 class="welcome-title text-3xl">
                    {{ $t('reservations.public.confirm.title') }}
                </h1>
                <p class="mt-2 text-sm text-[color:var(--welcome-ink-muted)]">
                    {{ $t('reservations.public.confirm.description') }}
                </p>
            </div>

            <div class="welcome-card">
                <div class="grid gap-4">
                    <div class="rounded-lg border border-dashed border-[color:var(--welcome-border)] px-4 py-3 text-sm text-[color:var(--welcome-ink-muted)]">
                        {{ summary }}
                    </div>

                    <p v-if="errors.time_from" class="text-sm text-red-600">
                        {{ errors.time_from }}
                    </p>

                    <div class="flex flex-wrap gap-3">
                        <Button type="button" class="welcome-button" @click="confirmReservation">
                            {{ $t('reservations.public.confirm.submit') }}
                        </Button>
                        <Link href="/reserve" class="welcome-link text-sm">
                            {{ $t('reservations.public.confirm.edit') }}
                        </Link>
                    </div>
                </div>
            </div>
        </main>
    </WelcomeLayout>
</template>
