<script setup lang="ts">
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import BrandHeader from '@/components/BrandHeader.vue';
import BrandLayout from '@/layouts/BrandLayout.vue';
import { home } from '@/routes';

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <BrandLayout centered :show-locale="false">
        <template #header>
            <BrandHeader />
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
                        <p class="welcome-kicker tracking-[0.28em]">
                            {{ $t('welcome.brand') }}
                        </p>
                        <h1 class="welcome-title text-2xl">{{ title }}</h1>
                        <p
                            class="text-center text-sm text-[color:var(--welcome-ink-muted)]"
                        >
                            {{ description }}
                        </p>
                    </div>
                </div>
                <slot />
            </div>
        </div>
    </BrandLayout>
</template>
