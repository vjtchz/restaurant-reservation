<script setup lang="ts">
import LocaleSwitcher from '@/components/LocaleSwitcher.vue';

defineProps<{
    centered?: boolean;
    showLocale?: boolean;
}>();
</script>

<template>
    <div class="theme-brand brand-shell relative min-h-svh overflow-hidden">
        <div
            class="pointer-events-none absolute top-16 -left-20 h-64 w-64 rounded-full bg-[radial-gradient(circle_at_30%_30%,var(--brand-highlight-1),transparent_60%)] opacity-70"
        ></div>
        <div
            class="pointer-events-none absolute top-0 right-0 h-80 w-80 rounded-full bg-[radial-gradient(circle_at_70%_30%,var(--brand-highlight-2),transparent_60%)] opacity-80"
        ></div>
        <div
            class="pointer-events-none absolute bottom-0 left-1/3 h-72 w-72 rounded-full bg-[radial-gradient(circle_at_50%_50%,var(--brand-accent-soft),transparent_65%)] opacity-30"
        ></div>

        <div v-if="showLocale !== false" class="absolute top-6 right-6 z-10">
            <LocaleSwitcher />
        </div>

        <slot name="header" />

        <div
            :class="
                centered
                    ? 'relative z-10 flex min-h-svh flex-col items-center justify-center p-6 md:p-10'
                    : 'relative z-10'
            "
        >
            <slot />
        </div>
    </div>
</template>
