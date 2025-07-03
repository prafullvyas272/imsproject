<script setup lang="ts">
import ConfirmationDialog from '@/components/ConfirmationDialog.vue';
import Pagination from '@/components/Pagination.vue';
import Button from '@/components/ui/button/Button.vue';
import Input from '@/components/ui/input/Input.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import { Eye, Plus  } from 'lucide-vue-next';
import { onMounted, ref } from 'vue';
import { useToast } from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-sugar.css';
import { Edit } from 'lucide-vue-next';
import { Trash } from 'lucide-vue-next';
import AddUserModal from './AddUserModal.vue';
import EditUserModal from './EditUserModal.vue';
import {useAuthStore} from '../../stores/AuthStore.js';
import {Link} from '@inertiajs/vue3';
import NavLink from '@/components/ui/nav-link/NavLink.vue';

const $toast = useToast();
// Breadcrumbs
const breadcrumbs: BreadcrumbItem[] = [{ title: 'Roles', href: '/role' }];

// ✅ Reactive modal state
const addUserModalVisible = ref(false);
const editUserModalVisible = ref(false);
const deleteUserModalVisible = ref(false);

const props = defineProps<{
    users?: [];
    roles?: [];
    departments?: [];
}>();

const currentPage = ref(1);
const selectedUser = ref<{ id: number; name: string, email: string, department_id: number, role_id: number } | null>(null);
const rowsPerPage = ref(10);

const pageChanged = () => {
    filteredUsers.value = props.users ? props.users.slice(0, rowsPerPage.value) : [];
    const start = (currentPage.value - 1) * rowsPerPage.value;
    const end = start + rowsPerPage.value;
    filteredUsers.value = props.roles ? props.roles.slice(start, end) : [];
};


// ✅ Loading state for form submission
const isSubmitting = ref(false);
const searchBox = ref('');
// ✅ Open modal function
const openModal = () => {
    addUserModalVisible.value = true;
};

// ✅ Close modal function
const closeModal = () => {
    console.log('close modal called');
    addUserModalVisible.value = false;
};

// ✅ Open modal function TODO:
const openEditModal = (userData) => {
    selectedUser.value = userData;
    editUserModalVisible.value = true;
};

const openDeleteModal = (userData) => {
    isSubmitting.value = true;
    selectedUser.value = userData;
    deleteUserModalVisible.value = true;
};

const deleteUser = (userData, event) => {
    if (event) event.preventDefault(); // Prevent form submission

    isSubmitting.value = true;
    axios
        .delete(route('user.destroy', { user: userData.id }))
        .then((response) => {
            console.log(response);
            isSubmitting.value = false;
            deleteUserModalVisible.value = false;
            $toast.info(response.data.message);
            console.log('deleted')
            filteredUsers.value = filteredUsers.value.filter(role => role.id !== userData.id);
        })
        .catch((error) => {
            console.error(error);
            isSubmitting.value = false;
            $toast.error('Something went wrong.');
        });
};


const filteredUsers = ref<{id: number, name: string, role_id: number, department_id: number, email: string}[]>([]);
const searchUser = () => {
    console.log(searchBox.value);
    const search = searchBox.value;
    filteredUsers.value = props.users ? props.users.filter((userData) => userData.name.toLowerCase().startsWith(search)) : [];
};

const userDataUpdated = (data) => {
    console.log(data);
    filteredUsers.value = data;
};

const getRolebyId = (roleId:number) => {
    return props.roles?.find((role) => role?.id === roleId)?.name ?? null;
}

const getRoleThemebyId = (roleId:number) => {
    return props.roles?.find((role) => role?.id === roleId)?.form_theme ?? null;
}

const totalUsersLength = ref<number>(0);



onMounted(() => {
    const auth = useAuthStore();
    console.log(auth.user);
    filteredUsers.value = props.users ? props.users.slice(0, rowsPerPage.value) : [];
    totalUsersLength.value = props.users ? props.users.length : 0;

});
</script>

<template>

    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <!-- No Result Found -->
        <div v-if="!filteredUsers?.length" class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div
                class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min">
                <div class="rounded-lg bg-gray-50 py-12 text-center">
                    <h3 class="mb-2 text-lg font-medium">No User found</h3>
                    <p class="mb-4 text-gray-500">Get started by adding your first user.</p>

                    <div class="flex-gap-4">
                        <Button @click="openModal" class="ml-4">
                            <Plus class="mr-2 h-4 w-4" />
                            Add User
                        </Button>
                    </div>
                </div>
            </div>
            <!-- Show Listing of Role -->
        </div>

        <!-- Searching -->
        <div class="grid grid-cols-1 md:grid-cols-12 gap-2 rounded-xl p-4 items-center">
            <!-- Search Input: full width on mobile, 8/12 on md+ -->
            <Input
                v-model="searchBox"
                @input="searchUser"
                placeholder="Search user by name..."
                class="col-span-1 md:col-span-9 w-full"
            />

            <!-- Button: full width on mobile, 4/12 on md+ -->
            <div class="col-span-1 md:col-span-3 w-full">
                <Button @click="openModal" class="flex w-full items-center justify-center">
                    <Plus class="mr-2 h-4 w-4" />
                    Add User
                </Button>
            </div>
        </div>


        <!-- Show Listing of Roles -->
        <div class="col-4 flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="overflow-x-auto rounded-lg border border-gray-200 bg-white dark:bg-gray-900">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Name
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Email
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Role
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Department
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                        <tr v-for="(userData) in filteredUsers" :key="userData.id">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                {{ userData?.name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                {{ userData?.email }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                <b class="text-xs rounded-2xl py-1 px-2 inline-block min-w-[110px] text-center" :class="getRoleThemebyId(userData?.role_id)">
                                    {{ getRolebyId(userData?.role_id) }}
                                </b>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                {{ userData?.department_id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                <div class="flex gap-2">
                                    <Button @click="openEditModal(userData)" size="sm" variant="outline">
                                        <Edit class="h-4 w-4" />Edit
                                    </Button>
                                    <Button @click="openDeleteModal(userData)" size="sm" variant="destructive">
                                        <Trash class="h-4 w-4" />Delete
                                    </Button>
                                    <NavLink variant="outline" :href="route('user.showUserActivity', {user: userData?.id})">
                                        <Eye class="h-4 w-4" />View Activity
                                    </NavLink>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="!filteredUsers?.length" class="text-center">
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                No users found.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- Pagination Component -->
            <Pagination
                v-if="totalUsersLength > rowsPerPage"
                :totalRecords="totalUsersLength"
                :rowsPerPage="rowsPerPage"
                v-model:currentPage="currentPage"
                @update:currentPage="pageChanged()"
                class="mt-4"
            />
        </div>
    </AppLayout>

    <!-- ✅ Add User Dialog (Modal) -->
    <AddUserModal v-if="addUserModalVisible" :roles="props.roles" :departments="props.departments" @closeModal="closeModal" @userCreated="userDataUpdated" />

    <!-- ✅ Edit User Dialog (Modal) -->
    <EditUserModal v-if="editUserModalVisible" :roles="props.roles" :departments="props.departments" @closeModal="editUserModalVisible = false" :selectedUser="selectedUser" @userUpdated="userDataUpdated" />

    <!-- ✅ Delete User Dialog (Modal) -->

    <ConfirmationDialog v-if="deleteUserModalVisible" @closeModal="deleteUserModalVisible = false"
        :teacher="selectedUser" @deleteConfirmed="deleteUser(selectedUser, $event)" :module-name="'User'"
        @fileUploaded="userDataUpdated" />


</template>
