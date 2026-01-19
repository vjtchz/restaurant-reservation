<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const locale = computed(() => page.props.locale ?? 'en');
const locales = computed(() => page.props.locales ?? ['en']);
</script>

<template>
    <div
        class="inline-flex items-center gap-1 rounded-full border border-border px-2 py-1 text-xs"
    >
        <span class="sr-only">{{ $t('app.locale.label') }}</span>
        <Link
            v-for="code in locales"
            :key="code"
            href="/locale"
            method="post"
            as="button"
            :data="{ locale: code }"
            type="button"
            class="rounded-full px-2 py-0.5 transition"
            :class="
                code === locale
                    ? 'bg-foreground text-background'
                    : 'text-muted-foreground hover:text-foreground'
            "
        >
            {{ $t(`app.locale.${code}`) }}
        </Link>
    </div>
</template>
