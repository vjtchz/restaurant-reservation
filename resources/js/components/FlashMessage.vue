<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { X } from 'lucide-vue-next';
import { computed, ref, watch } from 'vue';

import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert';

const page = usePage();
const flash = computed(() => page.props.flash ?? {});
const message = computed(() => flash.value?.success ?? flash.value?.error ?? null);
const variant = computed(() => (flash.value?.error ? 'destructive' : 'success'));
const title = computed(() => (flash.value?.error ? 'Error' : 'Success'));
const dismissed = ref(false);

watch(message, () => {
    dismissed.value = false;
});

const close = () => {
    dismissed.value = true;
};
</script>

<template>
    <Alert v-if="message && !dismissed" :variant="variant">
        <AlertTitle>{{ title }}</AlertTitle>
        <AlertDescription>{{ message }}</AlertDescription>
        <button
            type="button"
            class="absolute right-2 top-2 rounded-md p-1 text-current/70 transition hover:text-current focus:outline-none focus:ring-2 focus:ring-current/40"
            aria-label="Close"
            @click="close"
        >
            <X class="size-4" />
        </button>
    </Alert>
</template>
