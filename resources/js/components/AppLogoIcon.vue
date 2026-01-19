<script setup lang="ts">
import type { HTMLAttributes } from 'vue';

defineOptions({
    inheritAttrs: false,
});

interface Props {
    className?: HTMLAttributes['class'];
}

defineProps<Props>();
</script>

<template>
    <svg
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 64 64"
        :class="className"
        v-bind="$attrs"
    >
        <text
            x="32"
            y="44"
            text-anchor="middle"
            font-family="Arial, sans-serif"
            font-size="40"
            font-weight="700"
            fill="#fff"
        >
            RR
        </text>
    </svg>
</template>
