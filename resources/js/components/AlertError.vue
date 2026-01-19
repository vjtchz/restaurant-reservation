<script setup lang="ts">
import { wTrans } from 'laravel-vue-i18n';
import { AlertCircle } from 'lucide-vue-next';
import { computed } from 'vue';

import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert';

interface Props {
    errors: string[];
    title?: string;
}

const props = defineProps<Props>();

const uniqueErrors = computed(() => Array.from(new Set(props.errors)));
const titleText = computed(
    () => props.title ?? wTrans('app.errors.title').value,
);
</script>

<template>
    <Alert variant="destructive">
        <AlertCircle class="size-4" />
        <AlertTitle>{{ titleText }}</AlertTitle>
        <AlertDescription>
            <ul class="list-inside list-disc text-sm">
                <li v-for="(error, index) in uniqueErrors" :key="index">
                    {{ error }}
                </li>
            </ul>
        </AlertDescription>
    </Alert>
</template>
