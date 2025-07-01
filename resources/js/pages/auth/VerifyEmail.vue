<script setup lang="ts">
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import AuthLayout from '@/layouts/AuthLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';
import { ref } from 'vue';
import Input from '@/components/ui/input/Input.vue';
import axios from 'axios';
import LoadingSpinner from '@/components/LoadingSpinner.vue';
import { Loader2 } from 'lucide-vue-next';
import { router } from '@inertiajs/vue3';
import { useToast } from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-sugar.css';
import AuthSplitLayout from '@/layouts/auth/AuthSplitLayout.vue';

const $toast = useToast();

const props = defineProps<{
    status?: string;
    email?: string;
    remainingTime?: number,
}>();

const submitting = ref<boolean>(false);
const code = ref<string>('');

const errorMessage = ref<string>('');

const validateCode = (event) => {
    let value = event.target.value.replace(/[^+\d]/g, '');
    value = value.slice(0, 6);
    code.value = value;
};

const verifyCode = () => {
    submitting.value = true;
    axios.post(route('verify-code'), {
        code: code.value,
        email: props.email,
    })
        .then((response) => {
            console.log(response.status === 200)
            if (response.status === 200) {
                // window.location.href = '/dashboard';
                router.visit('/dashboard');

            } else {
                errorMessage.value = 'Incorrect code'
            }
            submitting.value = false;
        })
        .catch((error) => {
            console.error(error)
            errorMessage.value = 'Invalid code'
            submitting.value = false;

        })
};

const resendCode = () => {
    axios.post(route('resend-code'), {
        email: props.email,
    })
        .then((response) => {
            console.log(response.status === 200)
            if (response.status === 200) {
                $toast.info(response.data.message);
                timer.value = 300;
            }
        })
        .catch((error) => {
            console.error(error)
            errorMessage.value = 'Invalid code'
        })
};
const timer = ref(props.remainingTime);
setInterval(() => {
    timer.value--;
}, 1000);
</script>

<template>

    <AuthSplitLayout title="Verify Code">

        <Head title="Code Verification" />
        <div class="text-gray-500 text-center mb-3">
            <p>Please enter the code that we have sent to you over email.</p>
        </div>
        <div v-if="timer >= 1" class="text-3xl text-center mb-3">
            {{ timer }}
        </div>
        <p v-if="timer < 1" class="text-red-500 font-bold py-3  text-center text-sm">The code is expired</p>

        <div class="space-y-3 text-center">

            <Input type="text" v-model="code" @input="validateCode" />
            <p v-if="errorMessage != ''" class="text-red-500 text-sm">{{ errorMessage }}</p>
            <Button @click="verifyCode" class="mt-2" :disabled="code == ''">
                <!-- <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" /> -->
                <template v-if="submitting">
                    <Loader2 class="mr-2 h-4 w-4 animate-spin" />
                    Verifying...
                </template>
                <template v-else>
                    Verify Code
                </template>
            </Button>
            <p v-if="timer < 1" @click="resendCode" class="text-blue-500 text-sm cursor-pointer">Resend Code</p>
            <p v-else class="text-dark-500 text-sm">Resend Code</p>
        </div>

    </AuthSplitLayout>
</template>
