<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import {
    InputOTP,
    InputOTPGroup,
    InputOTPSlot,
} from '@/components/ui/input-otp';
import AuthLayout from '@/layouts/AuthLayout.vue';
import { store } from '@/routes/two-factor/login';

interface AuthConfigKeys {
    titleKey: string;
    descriptionKey: string;
    toggleKey: string;
}

const authConfigKeys = computed<AuthConfigKeys>(() => {
    if (showRecoveryInput.value) {
        return {
            titleKey: 'auth.two_factor.recovery_title',
            descriptionKey: 'auth.two_factor.recovery_description',
            toggleKey: 'auth.two_factor.use_auth_code',
        };
    }

    return {
        titleKey: 'auth.two_factor.auth_title',
        descriptionKey: 'auth.two_factor.auth_description',
        toggleKey: 'auth.two_factor.use_recovery_code',
    };
});

const showRecoveryInput = ref<boolean>(false);

const toggleRecoveryMode = (clearErrors: () => void): void => {
    showRecoveryInput.value = !showRecoveryInput.value;
    clearErrors();
    code.value = '';
};

const code = ref<string>('');
</script>

<template>
    <AuthLayout
        :title="$t(authConfigKeys.titleKey)"
        :description="$t(authConfigKeys.descriptionKey)"
    >
        <Head :title="$t('auth.two_factor.page_title')" />

        <div class="space-y-6">
            <template v-if="!showRecoveryInput">
                <Form
                    v-bind="store.form()"
                    class="space-y-4"
                    reset-on-error
                    @error="code = ''"
                    #default="{ errors, processing, clearErrors }"
                >
                    <input type="hidden" name="code" :value="code" />
                    <div
                        class="flex flex-col items-center justify-center space-y-3 text-center"
                    >
                        <div class="flex w-full items-center justify-center">
                            <InputOTP
                                id="otp"
                                v-model="code"
                                :maxlength="6"
                                :disabled="processing"
                                autofocus
                            >
                                <InputOTPGroup>
                                    <InputOTPSlot
                                        v-for="index in 6"
                                        :key="index"
                                        :index="index - 1"
                                    />
                                </InputOTPGroup>
                            </InputOTP>
                        </div>
                        <InputError :message="errors.code" />
                    </div>
                    <Button type="submit" class="w-full" :disabled="processing">
                        {{ $t('auth.two_factor.continue') }}
                    </Button>
                    <div class="text-center text-sm text-muted-foreground">
                        <span>{{ $t('auth.two_factor.or_you_can') }}</span>
                        <button
                            type="button"
                            class="text-foreground underline decoration-neutral-300 underline-offset-4 transition-colors duration-300 ease-out hover:decoration-current! dark:decoration-neutral-500"
                            @click="() => toggleRecoveryMode(clearErrors)"
                        >
                            {{ $t(authConfigKeys.toggleKey) }}
                        </button>
                    </div>
                </Form>
            </template>

            <template v-else>
                <Form
                    v-bind="store.form()"
                    class="space-y-4"
                    reset-on-error
                    #default="{ errors, processing, clearErrors }"
                >
                    <Input
                        name="recovery_code"
                        type="text"
                        :placeholder="$t('auth.two_factor.recovery_placeholder')"
                        :autofocus="showRecoveryInput"
                        required
                    />
                    <InputError :message="errors.recovery_code" />
                    <Button type="submit" class="w-full" :disabled="processing">
                        {{ $t('auth.two_factor.continue') }}
                    </Button>

                    <div class="text-center text-sm text-muted-foreground">
                        <span>{{ $t('auth.two_factor.or_you_can') }}</span>
                        <button
                            type="button"
                            class="text-foreground underline decoration-neutral-300 underline-offset-4 transition-colors duration-300 ease-out hover:decoration-current! dark:decoration-neutral-500"
                            @click="() => toggleRecoveryMode(clearErrors)"
                        >
                            {{ $t(authConfigKeys.toggleKey) }}
                        </button>
                    </div>
                </Form>
            </template>
        </div>
    </AuthLayout>
</template>
