<script setup lang="ts">
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';
import type { BreadcrumbItemType } from '@/types';
// Import Auth Store and usePage
import {useAuthStore} from '../stores/AuthStore.js';
import { usePage } from '@inertiajs/vue3';
const page = usePage();

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}
// Initialize store
const authStore = useAuthStore();
// Call store methods to store data in state
authStore.setUser(page.props.auth.user);
const isDark = document.documentElement.classList.contains('dark');
authStore.toggleDarkTheme(isDark);

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <slot />
    </AppLayout>
</template>
