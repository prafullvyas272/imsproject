<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

import HeadingSmall from '@/components/HeadingSmall.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { type BreadcrumbItem } from '@/types';
import axios from 'axios';
import { useToast } from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-sugar.css';
import { Switch } from '@headlessui/vue';

const $toast = useToast();
const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Password settings',
        href: '/settings/password',
    },
];

const passwordInput = ref<HTMLInputElement | null>(null);
const currentPasswordInput = ref<HTMLInputElement | null>(null);

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});


interface Props {
    authUser?: object;
    isTwoFaEnabled?: boolean;
}
const props = defineProps<Props>();
const twoFAEnabled = ref(props.isTwoFaEnabled);


const updatePassword = () => {
    form.put(route('password.update'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: (errors: any) => {
            if (errors.password) {
                form.reset('password', 'password_confirmation');
                if (passwordInput.value instanceof HTMLInputElement) {
                    passwordInput.value.focus();
                }
            }

            if (errors.current_password) {
                form.reset('current_password');
                if (currentPasswordInput.value instanceof HTMLInputElement) {
                    currentPasswordInput.value.focus();
                }
            }
        },
    });
};

const enableTwoFA = () => {
    console.log('called')
    axios.post(route('enableTwoFA'), {
        id: props?.authUser?.id,
    })
        .then((response) => {
            console.log(response)
            twoFAEnabled.value = response.data.current_status

        }).catch((error) => {
            console.log(error)
            $toast.error('Something went wrong.');
        })
}
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">

        <Head title="Password settings" />

        <SettingsLayout>
            <div class="space-y-6">
                <HeadingSmall title="Update password"
                    description="Ensure your account is using a long, random password to stay secure" />

                <form @submit.prevent="updatePassword" class="space-y-6">
                    <div class="grid gap-2">
                        <Label for="current_password">Current password</Label>
                        <Input id="current_password" ref="currentPasswordInput" v-model="form.current_password"
                            type="password" class="mt-1 block w-full" autocomplete="current-password"
                            placeholder="Current password" />
                        <InputError :message="form.errors.current_password" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="password">New password</Label>
                        <Input id="password" ref="passwordInput" v-model="form.password" type="password"
                            class="mt-1 block w-full" autocomplete="new-password" placeholder="New password" />
                        <InputError :message="form.errors.password" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="password_confirmation">Confirm password</Label>
                        <Input id="password_confirmation" v-model="form.password_confirmation" type="password"
                            class="mt-1 block w-full" autocomplete="new-password" placeholder="Confirm password" />
                        <InputError :message="form.errors.password_confirmation" />
                    </div>

                    <div class="flex items-center gap-4">
                        <Button :disabled="form.processing">Save password</Button>

                        <Transition enter-active-class="transition ease-in-out" enter-from-class="opacity-0"
                            leave-active-class="transition ease-in-out" leave-to-class="opacity-0">
                            <p v-show="form.recentlySuccessful" class="text-sm text-neutral-600">Saved.</p>
                        </Transition>
                    </div>
                </form>
            </div>

            <div>
                <!-- Two-Factor Authentication Section TODO: need to uncomment when needed -->
                <!-- <div>
                    <h3 class="text-xl font-semibold">Two-Factor Authentication</h3>
                    <div class="mt-4 flex items-center justify-between">
                        <div class="space-y-0.5">
                            <label for="twoFactorAuth" class="font-medium">Enable Two-Factor Authentication</label>
                            <p class="text-sm text-muted-foreground">
                                Add an extra layer of security to your account
                            </p>
                        </div>
                        <Switch v-model="twoFAEnabled" @update:modelValue="enableTwoFA"
                            :class="twoFAEnabled ? 'bg-teal-900' : 'bg-teal-700'"
                            class="relative inline-flex h-[38px] w-[74px] shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus-visible:ring-2 focus-visible:ring-white/75">
                            <span class="sr-only">Use setting</span>
                            <span aria-hidden="true" :class="twoFAEnabled ? 'translate-x-9' : 'translate-x-0'"
                                class="pointer-events-none inline-block h-[34px] w-[34px] transform rounded-full bg-white shadow-lg ring-0 transition duration-200 ease-in-out" />
                        </Switch>

                    </div>
                </div> -->

            </div>
        </SettingsLayout>
    </AppLayout>
</template>
