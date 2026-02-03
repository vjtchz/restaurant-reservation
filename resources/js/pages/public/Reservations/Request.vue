<script setup lang="ts">
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { wTrans } from 'laravel-vue-i18n';
import { computed } from 'vue';

import ReservationForm from '@/components/reservations/ReservationForm.vue';
import WelcomeLayout from '@/layouts/WelcomeLayout.vue';
import { login, register } from '@/routes';

type ReservationIntent = {
    date: string;
    time_from: string;
    time_to: string;
    guests: number;
};

const props = withDefaults(
    defineProps<{
        availableTables?: number;
        openingHours?: {
            from: string;
            to: string;
        };
        maxGuests?: number;
        minDuration?: number;
        intent?: ReservationIntent | null;
    }>(),
    {
        availableTables: 6,
        openingHours: () => ({
            from: '11:00',
            to: '22:00',
        }),
        maxGuests: 10,
        minDuration: 60,
        intent: null,
    },
);

const page = usePage();
const isAuthenticated = computed(() => Boolean(page.props.auth?.user));
const hasIntent = computed(
    () => Boolean(props.intent) && !isAuthenticated.value,
);
const isFull = computed(() => props.availableTables === 0);

const intentSummary = computed(() => {
    if (!props.intent) {
        return wTrans('reservations.ui.form.summary_empty').value;
    }

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

const createIntent = (payload: ReservationIntent) => {
    if (isAuthenticated.value) {
        return;
    }

    router.post('/reservations/intent', payload, {
        preserveScroll: true,
    });
};

const clearIntent = () => {
    router.post('/reservations/intent/clear', {}, { preserveScroll: true });
};
</script>

<template>
    <Head :title="$t('reservations.public.page_title')" />

    <WelcomeLayout>
        <main
            class="relative mx-auto flex w-full max-w-6xl flex-col gap-10 px-6 py-14 lg:flex-row lg:items-start lg:py-24"
        >
            <section class="space-y-6 lg:w-6/12">
                <div class="welcome-pill">
                    <span class="welcome-dot"></span>
                    {{ $t('reservations.public.hero.pill') }}
                </div>

                <div class="space-y-4">
                    <p class="welcome-kicker tracking-[0.28em]">
                        {{ $t('reservations.public.hero.kicker') }}
                    </p>
                    <h1
                        class="text-4xl leading-tight font-[var(--welcome-font-serif)] text-[color:var(--welcome-ink-strong)] sm:text-5xl"
                    >
                        {{ $t('reservations.public.hero.title') }}
                    </h1>
                    <p
                        class="max-w-xl text-lg text-[color:var(--welcome-ink-muted)]"
                    >
                        {{ $t('reservations.public.hero.description_prefix') }}
                        <span
                            class="font-semibold text-[color:var(--welcome-ink-strong)]"
                        >
                            {{ props.availableTables }}
                        </span>
                        {{ $t('reservations.public.hero.description_suffix') }}
                    </p>
                </div>

                <div
                    class="rounded-2xl border border-[color:var(--welcome-border-warm)] bg-white/80 px-4 py-3 text-sm text-[color:var(--welcome-ink-warm)]"
                >
                    {{
                        $t('reservations.public.hero.notice', {
                            from: props.openingHours.from,
                            to: props.openingHours.to,
                        })
                    }}
                </div>
            </section>

            <section class="lg:w-6/12">
                <div class="welcome-card relative">
                    <div class="space-y-6">
                        <div>
                            <p class="welcome-kicker">
                                {{
                                    hasIntent
                                        ? $t('reservations.public.auth.kicker')
                                        : $t('reservations.public.form.kicker')
                                }}
                            </p>
                            <h2 class="welcome-title">
                                {{
                                    hasIntent
                                        ? $t('reservations.public.auth.title')
                                        : $t('reservations.public.form.title')
                                }}
                            </h2>
                            <p
                                class="mt-2 text-sm text-[color:var(--welcome-ink-muted)]"
                            >
                                {{
                                    hasIntent
                                        ? $t(
                                              'reservations.public.auth.description',
                                          )
                                        : $t(
                                              'reservations.public.form.description',
                                          )
                                }}
                            </p>
                        </div>

                        <div
                            v-if="isFull"
                            class="rounded-2xl border border-[color:var(--welcome-border-warm)] bg-white/80 px-4 py-3 text-sm text-[color:var(--welcome-ink-warm)]"
                        >
                            {{ $t('welcome.full_notice') }}
                        </div>

                        <ReservationForm
                            v-if="!hasIntent"
                            :max-guests="props.maxGuests"
                            :min-duration="props.minDuration"
                            :opening-hours="props.openingHours"
                            :submit-mode="isAuthenticated ? 'create' : 'intent'"
                            :submit-label="
                                $t('reservations.public.form.submit')
                            "
                            @intent="createIntent"
                        />

                        <div v-else class="grid gap-4">
                            <div
                                class="rounded-lg border border-dashed border-border px-4 py-3 text-sm text-muted-foreground"
                            >
                                {{ intentSummary }}
                            </div>

                            <div class="grid gap-3 sm:grid-cols-2">
                                <Link
                                    :href="login()"
                                    class="welcome-link welcome-link-lg justify-center text-center"
                                >
                                    {{ $t('reservations.public.auth.login') }}
                                </Link>
                                <Link
                                    :href="register()"
                                    class="welcome-button justify-center text-center"
                                >
                                    {{
                                        $t('reservations.public.auth.register')
                                    }}
                                </Link>
                            </div>

                            <button
                                type="button"
                                class="welcome-link text-sm"
                                @click="clearIntent"
                            >
                                {{ $t('reservations.public.auth.back') }}
                            </button>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </WelcomeLayout>
</template>
