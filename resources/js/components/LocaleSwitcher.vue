<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { wTrans } from 'laravel-vue-i18n';
import { computed } from 'vue';

import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';

const page = usePage();
const locale = computed(() => page.props.locale ?? 'en');
const locales = computed(() => page.props.locales ?? ['en']);

const localeFlags: Record<string, string> = {
    cs: '🇨🇿',
    en: '🇬🇧',
};

const localeItems = computed(() =>
    locales.value.map((code) => ({
        code,
        label: wTrans(`app.locale.${code}`).value,
        flag: localeFlags[code] ?? '🌐',
    })),
);

const activeLocale = computed(() => {
    const match = localeItems.value.find((item) => item.code === locale.value);
    return match ?? {
        code: locale.value,
        label: wTrans(`app.locale.${locale.value}`).value,
        flag: localeFlags[locale.value] ?? '🌐',
    };
});
</script>

<template>
    <DropdownMenu>
        <DropdownMenuTrigger as-child>
            <button
                type="button"
                class="inline-flex items-center gap-2 rounded-full border border-border px-3 py-1 text-xs font-medium transition hover:bg-muted"
            >
                <span class="text-base leading-none" aria-hidden="true">
                    {{ activeLocale.flag }}
                </span>
                <span class="sr-only">{{ $t('app.locale.label') }}</span>
                <span>{{ activeLocale.label }}</span>
                <span class="text-muted-foreground">▾</span>
            </button>
        </DropdownMenuTrigger>
        <DropdownMenuContent align="end" class="min-w-[8.5rem]">
            <DropdownMenuItem
                v-for="item in localeItems"
                :key="item.code"
                :as-child="true"
            >
                <Link
                    href="/locale"
                    method="post"
                    as="button"
                    :data="{ locale: item.code }"
                    type="button"
                    class="flex w-full items-center gap-2"
                >
                    <span class="text-base leading-none" aria-hidden="true">
                        {{ item.flag }}
                    </span>
                    <span>{{ item.label }}</span>
                </Link>
            </DropdownMenuItem>
        </DropdownMenuContent>
    </DropdownMenu>
</template>
