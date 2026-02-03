<script setup lang="ts">
import BrandHeader from '@/components/BrandHeader.vue';
import BrandLayout from '@/layouts/BrandLayout.vue';
</script>

<template>
    <BrandLayout :show-locale="false">
        <template #header>
            <BrandHeader />
        </template>

        <slot />
    </BrandLayout>
</template>
