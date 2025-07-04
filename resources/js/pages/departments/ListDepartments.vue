<script setup lang="ts">
import ConfirmationDialog from '@/components/ConfirmationDialog.vue';
import Pagination from '@/components/Pagination.vue';
import Button from '@/components/ui/button/Button.vue';
import Input from '@/components/ui/input/Input.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import { Plus  } from 'lucide-vue-next';
import { onMounted, ref } from 'vue';
import { useToast } from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-sugar.css';
import AddDepartmentModal from './AddDepartmentModal.vue';
import EditDepartmentModal from './EditDepartmentModal.vue';
import { Edit } from 'lucide-vue-next';
import { Trash } from 'lucide-vue-next';

const $toast = useToast();
// Breadcrumbs
const breadcrumbs: BreadcrumbItem[] = [{ title: 'Departments', href: '/department' }];

// ✅ Reactive modal state
const addDepartmentModalVisible = ref(false);
const editDepartmentModalVisible = ref(false);
const deleteDepartmentModalVisible = ref(false);

const currentPage = ref(1);
const selectedDepartment = ref<{ id: number; name: string } | null>(null);
const rowsPerPage = ref(10);

const pageChanged = () => {
    filteredDepartments.value = props.departments.slice(0, rowsPerPage.value);
    const start = (currentPage.value - 1) * rowsPerPage.value;
    const end = start + rowsPerPage.value;
    filteredDepartments.value = props.departments.slice(start, end);
};


// ✅ Loading state for form submission
const isSubmitting = ref(false);
const searchBox = ref('');
// ✅ Open modal function
const openModal = () => {
    addDepartmentModalVisible.value = true;
};

// ✅ Close modal function
const closeModal = () => {
    console.log('close modal called');
    addDepartmentModalVisible.value = false;
};

// ✅ Open modal function TODO:
const openEditModal = (departmentData) => {
    selectedDepartment.value = departmentData;
    editDepartmentModalVisible.value = true;
};

const openDeleteModal = (departmentData) => {
    isSubmitting.value = true;
    selectedDepartment.value = departmentData;
    deleteDepartmentModalVisible.value = true;
};

const deleteDepartment = (departmentData, event) => {
    if (event) event.preventDefault(); // Prevent form submission

    isSubmitting.value = true;
    axios
        .delete(route('department.destroy', { department: departmentData.id }))
        .then((response) => {
            console.log(response);
            isSubmitting.value = false;
            deleteDepartmentModalVisible.value = false;
            $toast.info(response.data.message);
            console.log('deleted')
            filteredDepartments.value = filteredDepartments.value.filter(department => department.id !== departmentData.id);
        })
        .catch((error) => {
            console.error(error);
            isSubmitting.value = false;
            $toast.error('Something went wrong.');
        });
};
const props = defineProps<{
    departments?: array;
}>();

const filteredDepartments = ref<{id: number, name: string}[]>([]);
const searchDepartment = () => {
    console.log(searchBox.value);
    const search = searchBox.value;
    filteredDepartments.value = props.departments.filter((departmentData) => departmentData.name.toLowerCase().startsWith(search));
};

const departmentDataUpdated = (data) => {
    console.log(data);
    filteredDepartments.value = data;
};


const totalDepartmentsLength = ref<number>(0);

onMounted(() => {
    filteredDepartments.value = props.departments.slice(0, rowsPerPage.value);
    totalDepartmentsLength.value = props.departments ? props.departments.length : 0;

});
</script>

<template>

    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <!-- No Result Found -->
        <div v-if="!filteredDepartments?.length" class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div
                class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min">
                <div class="rounded-lg bg-gray-50 py-12 text-center">
                    <h3 class="mb-2 text-lg font-medium">No Department found</h3>
                    <p class="mb-4 text-gray-500">Get started by adding your first department.</p>

                    <div class="flex-gap-4">
                        <Button @click="openModal" class="ml-4">
                            <Plus class="mr-2 h-4 w-4" />
                            Add Department
                        </Button>
                    </div>
                </div>
            </div>
            <!-- Show Listing of Department -->
        </div>

        <!-- Searching -->
        <div class="grid grid-cols-1 md:grid-cols-12 gap-2 rounded-xl p-4 items-center">
            <!-- Search Input: full width on mobile, 8/12 on md+ -->
            <Input
                v-model="searchBox"
                @input="searchDepartment"
                placeholder="Search department by name..."
                class="col-span-1 md:col-span-9 w-full"
            />

            <!-- Button: full width on mobile, 4/12 on md+ -->
            <div class="col-span-1 md:col-span-3 w-full">
                <Button @click="openModal" class="flex w-full items-center justify-center">
                    <Plus class="mr-2 h-4 w-4" />
                    Add Department
                </Button>
            </div>
        </div>


        <!-- Show Listing of Departments -->
        <div class="col-4 flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="overflow-x-auto rounded-lg border border-gray-200 bg-white dark:bg-gray-900">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Name
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                        <tr v-for="(departmentData) in filteredDepartments" :key="departmentData.id">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                {{ departmentData?.name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                <div class="flex gap-2">
                                    <Button @click="openEditModal(departmentData)" size="sm" variant="outline">
                                        <Edit class="h-4 w-4" />Edit
                                    </Button>
                                    <Button @click="openDeleteModal(departmentData)" size="sm" variant="destructive">
                                        <Trash class="h-4 w-4" />Delete
                                    </Button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="!filteredDepartments?.length">
                            <td colspan="3" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                No departments found.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- Pagination Component -->
            <Pagination
                v-if="totalDepartmentsLength > rowsPerPage"
                :totalRecords="totalDepartmentsLength"
                :rowsPerPage="rowsPerPage"
                v-model:currentPage="currentPage"
                @update:currentPage="pageChanged()"
                class="mt-4"
            />
        </div>
    </AppLayout>

    <!-- ✅ Add Department Dialog (Modal) -->
    <AddDepartmentModal v-if="addDepartmentModalVisible" @closeModal="closeModal" @departmentCreated="departmentDataUpdated" />

    <!-- ✅ Edit Department Dialog (Modal) -->
    <EditDepartmentModal v-if="editDepartmentModalVisible" @closeModal="editDepartmentModalVisible = false" :selectedDepartment="selectedDepartment" @department-updated="departmentDataUpdated" />

    <!-- ✅ Delete Department Dialog (Modal) -->

    <ConfirmationDialog v-if="deleteDepartmentModalVisible" @closeModal="deleteDepartmentModalVisible = false"
        :teacher="selectedDepartment" @deleteConfirmed="deleteDepartment(selectedDepartment, $event)" :module-name="'Department'"
        @fileUploaded="departmentDataUpdated" />


</template>
