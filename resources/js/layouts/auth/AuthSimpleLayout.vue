<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

import AppLogoIcon from '@/components/AppLogoIcon.vue';
import LocaleSwitcher from '@/components/LocaleSwitcher.vue';
import BrandLayout from '@/layouts/BrandLayout.vue';
import { home, login, register } from '@/routes';
import { index as reservationsIndex } from '@/routes/reservations';

defineProps<{
    title?: string;
    description?: string;
}>();

const page = usePage();
const isAuthenticated = computed(() => Boolean(page.props.auth?.user));
</script>

<template>
    <BrandLayout centered :show-locale="false">
        <template #header>
            <header class="relative z-10 mx-auto flex w-full max-w-6xl items-center justify-between px-6 pt-8">
                <div class="welcome-kicker">{{ $t('welcome.brand') }}</div>
                <div class="flex items-center gap-3">
                    <LocaleSwitcher />
                    <Link
                        v-if="!isAuthenticated"
                        :href="login()"
                        class="welcome-link"
                    >
                        {{ $t('welcome.nav.login') }}
                    </Link>
                    <Link
                        v-if="!isAuthenticated"
                        :href="register()"
                        class="welcome-button px-4 py-2 text-sm shadow-none hover:translate-y-0"
                    >
                        {{ $t('welcome.nav.register') }}
                    </Link>
                    <Link
                        v-else
                        :href="reservationsIndex()"
                        class="welcome-link"
                    >
                        {{ $t('welcome.nav.reservations') }}
                    </Link>
                </div>
            </header>
        </template>

        <div class="w-full max-w-sm">
            <div class="welcome-card flex flex-col gap-8">
                <div class="flex flex-col items-center gap-4">
                    <Link
                        :href="home()"
                        class="flex flex-col items-center gap-2 font-medium"
                    >
                        <div
                            class="mb-1 flex h-9 w-9 items-center justify-center rounded-md"
                        >
                            <AppLogoIcon
                                class="size-9 fill-current text-[color:var(--welcome-ink)]"
                            />
                        </div>
                        <span class="sr-only">{{ title }}</span>
                    </Link>
                    <div class="space-y-2 text-center">
                        <p class="welcome-kicker tracking-[0.28em]">{{ $t('welcome.brand') }}</p>
                        <h1 class="welcome-title text-2xl">{{ title }}</h1>
                        <p class="text-center text-sm text-[color:var(--welcome-ink-muted)]">
                            {{ description }}
                        </p>
                    </div>
                </div>
                <slot />
            </div>
        </div>
    </BrandLayout>
</template>
