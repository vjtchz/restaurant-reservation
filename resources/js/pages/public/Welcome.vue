<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { computed } from 'vue';

import WelcomeLayout from '@/layouts/WelcomeLayout.vue';

const props = withDefaults(
    defineProps<{
        availableTables?: number;
        openingHours?: {
            from: string;
            to: string;
        };
    }>(),
    {
        availableTables: 6,
        openingHours: () => ({
            from: '11:00',
            to: '22:00',
        }),
    },
);

const isFull = computed(() => props.availableTables === 0);
const goToReservation = () => {
    if (isFull.value) return;
    router.visit('/reserve');
};
</script>

<template>
    <Head :title="$t('welcome.page_title')">
        <link rel="preconnect" href="https://fonts.bunny.net" />
        <link
            href="https://fonts.bunny.net/css?family=fraunces:400,600,700|space-grotesk:400,500,600"
            rel="stylesheet"
        />
    </Head>

    <WelcomeLayout>
        <main
            class="relative mx-auto flex w-full max-w-6xl flex-col gap-12 px-6 py-14 lg:flex-row lg:items-center lg:py-24"
        >
            <section class="space-y-6 lg:w-7/12">
                <div class="brand-pill">
                    <span class="brand-dot"></span>
                    {{ $t('welcome.hero.pill') }}
                </div>

                <div class="space-y-4">
                    <p class="brand-kicker tracking-[0.28em]">
                        {{ $t('welcome.hero.kicker') }}
                    </p>
                    <h1
                        class="text-4xl leading-tight font-[var(--brand-font-serif)] text-[color:var(--brand-ink-strong)] sm:text-5xl lg:text-6xl"
                    >
                        {{ $t('welcome.hero.title') }}
                    </h1>
                    <p
                        class="max-w-xl text-lg text-[color:var(--brand-ink-muted)]"
                    >
                        {{ $t('welcome.hero.description_prefix') }}
                        <span
                            class="font-semibold text-[color:var(--brand-ink-strong)]"
                            >{{ props.availableTables }}</span
                        >.
                        {{ $t('welcome.hero.description_suffix') }}
                    </p>
                </div>

                <div class="flex flex-wrap gap-4">
                    <button
                        type="button"
                        class="brand-button group"
                        :disabled="isFull"
                        @click="goToReservation"
                    >
                        {{ $t('welcome.actions.create_reservation') }}
                        <span
                            class="text-xl transition group-hover:translate-x-0.5"
                            >→</span
                        >
                    </button>
                    <a href="#menu" class="brand-link brand-link-lg">
                        {{ $t('welcome.actions.view_menu') }}
                    </a>
                </div>

                <p
                    v-if="isFull"
                    class="rounded-2xl border border-[color:var(--brand-border-warm)] bg-white/80 px-4 py-3 text-sm text-[color:var(--brand-ink-warm)]"
                >
                    {{ $t('welcome.full_notice') }}
                </p>
            </section>

            <section class="lg:w-5/12">
                <div class="brand-card relative">
                    <div class="space-y-5">
                        <div>
                            <p class="brand-kicker">
                                {{ $t('welcome.availability.kicker') }}
                            </p>
                            <h2 class="brand-title">
                                {{ $t('welcome.availability.title') }}
                            </h2>
                        </div>

                        <div class="brand-card-soft">
                            <p
                                class="text-sm text-[color:var(--brand-ink-soft)]"
                            >
                                {{ $t('welcome.availability.count_label') }}
                            </p>
                            <p
                                class="text-3xl font-semibold text-[color:var(--brand-ink)]"
                            >
                                {{ props.availableTables }}
                            </p>
                            <p
                                class="mt-2 text-xs text-[color:var(--brand-ink-subtle)]"
                            >
                                {{ $t('welcome.availability.note') }}
                            </p>
                        </div>

                        <div
                            class="grid gap-4 text-sm text-[color:var(--brand-ink-muted)]"
                        >
                            <div class="flex items-start gap-3">
                                <span class="brand-dot mt-1"></span>
                                <div>
                                    <p
                                        class="font-semibold text-[color:var(--brand-ink-strong)]"
                                    >
                                        {{ $t('welcome.details.hours_label') }}
                                    </p>
                                    <p>
                                        {{
                                            $t('welcome.details.hours_value', {
                                                from: props.openingHours.from,
                                                to: props.openingHours.to,
                                            })
                                        }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <span class="brand-dot mt-1"></span>
                                <div>
                                    <p
                                        class="font-semibold text-[color:var(--brand-ink-strong)]"
                                    >
                                        {{
                                            $t('welcome.details.address_label')
                                        }}
                                    </p>
                                    <p>
                                        {{
                                            $t('welcome.details.address_value')
                                        }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <span class="brand-dot mt-1"></span>
                                <div>
                                    <p
                                        class="font-semibold text-[color:var(--brand-ink-strong)]"
                                    >
                                        {{ $t('welcome.details.fast_title') }}
                                    </p>
                                    <p>
                                        {{
                                            $t(
                                                'welcome.details.fast_description',
                                            )
                                        }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <section id="menu" class="relative mx-auto w-full max-w-6xl px-6 pb-16">
            <div class="brand-card-muted">
                <div
                    class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between"
                >
                    <div>
                        <p class="brand-kicker">
                            {{ $t('welcome.menu.kicker') }}
                        </p>
                        <h3 class="brand-title">
                            {{ $t('welcome.menu.title') }}
                        </h3>
                        <p
                            class="mt-2 max-w-2xl text-sm text-[color:var(--brand-ink-soft)]"
                        >
                            {{ $t('welcome.menu.description') }}
                        </p>
                    </div>
                    <div
                        class="flex items-center gap-3 text-sm text-[color:var(--brand-ink-warm)]"
                    >
                        <span
                            class="inline-flex items-center rounded-full bg-white px-3 py-1 shadow-sm"
                            >{{ $t('welcome.menu.badge_new') }}</span
                        >
                        <span
                            class="inline-flex items-center rounded-full bg-white px-3 py-1 shadow-sm"
                            >{{ $t('welcome.menu.badge_local') }}</span
                        >
                    </div>
                </div>
            </div>
        </section>
    </WelcomeLayout>
</template>
