<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

import LocaleSwitcher from '@/components/LocaleSwitcher.vue';
import BrandLayout from '@/layouts/BrandLayout.vue';
import { login, register } from '@/routes';
import { index as reservationsIndex } from '@/routes/reservations';

const page = usePage();
const isAuthenticated = computed(() => Boolean(page.props.auth?.user));
</script>

<template>
    <BrandLayout :show-locale="false">
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

        <slot />
    </BrandLayout>
</template>
