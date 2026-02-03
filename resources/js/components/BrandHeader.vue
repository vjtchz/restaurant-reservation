<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

import LocaleSwitcher from '@/components/LocaleSwitcher.vue';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import UserMenuContent from '@/components/UserMenuContent.vue';
import { getInitials } from '@/composables/useInitials';
import { home, login, register } from '@/routes';
import { index as reservationsIndex } from '@/routes/reservations';

const page = usePage();
const isAuthenticated = computed(() => Boolean(page.props.auth?.user));
const user = computed(() => page.props.auth?.user ?? null);
</script>

<template>
    <header
        class="relative z-10 mx-auto flex w-full max-w-6xl items-center justify-between px-6 pt-8"
    >
        <Link :href="home()" class="brand-kicker">
            {{ $t('welcome.brand') }}
        </Link>
        <div class="flex items-center gap-3">
            <LocaleSwitcher />
            <Link v-if="!isAuthenticated" :href="login()" class="brand-link">
                {{ $t('welcome.nav.login') }}
            </Link>
            <Link
                v-if="!isAuthenticated"
                :href="register()"
                class="brand-button px-4 py-2 text-sm shadow-none hover:translate-y-0"
            >
                {{ $t('welcome.nav.register') }}
            </Link>
            <Link
                v-if="isAuthenticated"
                :href="reservationsIndex()"
                class="brand-link"
            >
                {{ $t('welcome.nav.reservations') }}
            </Link>
            <DropdownMenu v-if="user">
                <DropdownMenuTrigger :as-child="true">
                    <button
                        type="button"
                        class="brand-link p-1"
                        aria-label="User menu"
                    >
                        <Avatar class="h-8 w-8 overflow-hidden rounded-full">
                            <AvatarImage
                                v-if="user.avatar"
                                :src="user.avatar"
                                :alt="user.name"
                            />
                            <AvatarFallback
                                class="rounded-full bg-white text-sm font-semibold text-[color:var(--brand-ink)]"
                            >
                                {{ getInitials(user.name) }}
                            </AvatarFallback>
                        </Avatar>
                    </button>
                </DropdownMenuTrigger>
                <DropdownMenuContent align="end" class="w-56">
                    <UserMenuContent :user="user" />
                </DropdownMenuContent>
            </DropdownMenu>
        </div>
    </header>
</template>
