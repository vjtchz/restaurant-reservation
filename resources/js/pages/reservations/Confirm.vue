<script setup lang="ts">
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { wTrans } from 'laravel-vue-i18n';
import { computed } from 'vue';

import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';

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

    <AppLayout :breadcrumbs="[{ title: $t('reservations.public.confirm.breadcrumb'), href: '/reservations/confirm' }]">
        <div class="mx-auto w-full max-w-3xl space-y-6">
            <div>
                <h1 class="text-2xl font-semibold text-foreground">
                    {{ $t('reservations.public.confirm.title') }}
                </h1>
                <p class="text-sm text-muted-foreground">
                    {{ $t('reservations.public.confirm.description') }}
                </p>
            </div>

            <div class="rounded-2xl border border-border bg-card p-6 shadow-sm">
                <div class="grid gap-4">
                    <div class="rounded-lg border border-dashed border-border px-4 py-3 text-sm text-muted-foreground">
                        {{ summary }}
                    </div>

                    <p v-if="errors.time_from" class="text-sm text-destructive">
                        {{ errors.time_from }}
                    </p>

                    <div class="flex flex-wrap gap-3">
                        <Button type="button" @click="confirmReservation">
                            {{ $t('reservations.public.confirm.submit') }}
                        </Button>
                        <Link href="/reserve" class="text-sm text-muted-foreground hover:text-foreground">
                            {{ $t('reservations.public.confirm.edit') }}
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
