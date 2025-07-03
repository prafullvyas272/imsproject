<script setup lang="ts">
import Pagination from '@/components/Pagination.vue';
import Button from '@/components/ui/button/Button.vue';
import Input from '@/components/ui/input/Input.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import { Eye, Plus } from 'lucide-vue-next';
import { onMounted, ref } from 'vue';
import { useToast } from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-sugar.css';
import { Edit } from 'lucide-vue-next';
import { Trash } from 'lucide-vue-next';
import { useAuthStore } from '../../stores/AuthStore.js';
import NavLink from '@/components/ui/nav-link/NavLink.vue';

const $toast = useToast();
// Breadcrumbs
const breadcrumbs: BreadcrumbItem[] = [{ title: 'Roles', href: '/role' }];

type UserActivity = {
    id: number;
    title: string;
    user_id: number;
    // Add other fields as needed based on your backend model
};

const props = defineProps<{
    userActivities?: UserActivity[];
}>();

const currentPage = ref(1);
const rowsPerPage = ref(10);
const activityBgColors: Array<string> = [
    'bg-yellow-100',
    'bg-blue-100',
    'bg-green-100',
    'bg-red-100',
];

const pageChanged = () => {
    filteredUserActivities.value = props.userActivities ? props.userActivities.slice(0, rowsPerPage.value) : [];
    const start = (currentPage.value - 1) * rowsPerPage.value;
    const end = start + rowsPerPage.value;
    filteredUserActivities.value = props.userActivities ? props.userActivities.slice(start, end) : [];
};


const searchBox = ref('');

const filteredUserActivities = ref<{ id: number, title: string, user_id: number }[]>([]);

const totalUserActivityLength = ref<number>(0);

const searchUser = () => {
    console.log(searchBox.value);
    const search = searchBox.value;
    filteredUserActivities.value = props.userActivities ? props.userActivities.filter((userActivity) => userActivity.title.toLowerCase().startsWith(search)) : [];
};

onMounted(() => {
    filteredUserActivities.value = props.userActivities ? props.userActivities.slice(0, rowsPerPage.value) : [];
    totalUserActivityLength.value = props.userActivities ? props.userActivities.length : 0;
});
</script>

<template>

    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">

        <!-- Searching -->
        <div class="grid grid-cols-1 md:grid-cols-12 gap-2 rounded-xl p-4 items-center">
            <!-- Search Input: full width on mobile, 8/12 on md+ -->
            <Input v-model="searchBox" @input="searchUser" placeholder="Search activity by title..."
                class="col-span-1 md:col-span-12 w-full" />
        </div>


        <!-- Show Listing of Roles -->
        <div class="col-4 flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="overflow-x-auto rounded-lg bg-white dark:bg-gray-900">
                <div v-if="filteredUserActivities?.length" class="bg-white rounded-lg">
                    <div v-for="(activity, id) in filteredUserActivities" :key="activity?.id" :class="[
                        'p-3 text-left rounded-2xl border border-gray-400 my-3 text-md text-gray-500 dark:text-gray-300 font-medium tracking-wider',
                        activityBgColors[id % activityBgColors.length]
                    ]">
                        <p v-html="activity?.title"></p>
                    </div>
                </div>
                <div v-else class="bg-white rounded-lg">
                    <div
                        class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min">
                        <div class="rounded-lg bg-gray-50 py-12 text-center">
                            <h3 class="mb-2 text-lg font-medium">No Activity found</h3>

                        </div>
                    </div>
                </div>
            </div>

            <!-- Pagination Component -->
            <Pagination v-if="totalUserActivityLength > rowsPerPage"
                :totalRecords="totalUserActivityLength" :rowsPerPage="rowsPerPage"
                v-model:currentPage="currentPage" @update:currentPage="pageChanged()" class="mt-4" />
        </div>
    </AppLayout>

</template>
