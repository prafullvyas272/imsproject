<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import { Plus, Loader2 } from 'lucide-vue-next';
import Input from '@/components/ui/input/Input.vue';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from "@/components/ui/dialog";
import { ref, defineEmits } from "vue";
import axios from 'axios';
import InputError from '@/components/InputError.vue';
import { onMounted } from 'vue';
import { useToast } from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-sugar.css';
import { Edit, Trash } from 'lucide-vue-next';
import Select from '@/components/ui/select/Select.vue';

const $toast = useToast();
const editUserModalVisible = ref(true);


const form = ref({
    name: "",
    email: "",
    department_id: null as number | null,
    role_id: null as number | null,
});

const formErrors = ref<Record<string, string[]>>({});

// ✅ Loading state for form submission
const isSubmitting = ref(false);

const emit = defineEmits(['closeModal', 'userUpdated']);

const props = defineProps<{
    selectedUser: { id: number; name: string, email: string, department_id: number, role_id: number }|null,
    roles: []|undefined,
    departments: []|undefined,
}>();

const submitForm = () => {
    isSubmitting.value = true;

    try {
        axios.put(route('user.update', {user: props.selectedUser?.id}), {
            name: form.value.name,
            email: form.value.email,
            department_id: form.value.department_id,
            role_id: form.value.role_id,
            // id: props.selectedRole?.id,
        })
            .then((response) => {
                console.log(response)
                isSubmitting.value = false;
                $toast.info(response.data.message);
                closeModal();
                emit('userUpdated', response.data.data);

            }).catch((error) => {
                formErrors.value = error.response.data.errors;
                isSubmitting.value = false;
                $toast.error('Something went wrong.');
            })

    } catch (error) {
        console.error("Error submitting form:", error);
    }
};

const closeModal = () => {
    emit('closeModal');
}

onMounted(() => {
    form.value.name = props.selectedUser?.name ?? "";
    form.value.email = props.selectedUser?.email ?? "";
    form.value.department_id = props.selectedUser?.department_id ?? null;
    form.value.role_id = props.selectedUser?.role_id ?? null;
});

</script>

<template>


    <!-- ✅ Edit User Dialog (Modal) -->
    <Dialog :open="editUserModalVisible" @update:open="closeModal">
        <DialogContent class="sm:max-w-[500px]">
            <DialogHeader>
                <DialogTitle>Update User</DialogTitle>
                <DialogDescription>
                    Enter the details of the user you want to update.
                </DialogDescription>
            </DialogHeader>

            <!-- ✅ Form inside modal -->
            <form @submit.prevent="submitForm" class="space-y-4">

                <!-- User Name -->
                <div>
                    <label class="block font-medium mb-1">Name</label>
                    <Input v-model="form.name" :class="[
                        formErrors.hasOwnProperty('name') ? 'error-border' : ''
                    ]" placeholder="Enter user name" class="w-full" />
                    <InputError v-if="formErrors.hasOwnProperty('name')" :message="formErrors.name[0]" />
                </div>

                <!-- User Email -->
                <div>
                    <label class="block font-medium mb-1">Email</label>
                    <Input v-model="form.email" :class="[
                        formErrors.hasOwnProperty('email') ? 'error-border' : ''
                    ]" placeholder="Enter user email" class="w-full" />
                    <InputError v-if="formErrors.hasOwnProperty('email')" :message="formErrors.email[0]" />
                </div>

                <!-- Role Name -->
                <div>
                    <label class="block font-medium mb-1">Role</label>
                    <Select
                        name="role_id"
                        v-model="form.role_id"
                        :options="props.roles"
                        optionLabel="name"
                        optionValue="id"
                        placeholder="Select Role"
                        :error="formErrors.hasOwnProperty('role_id')"
                        class="w-full border rounded p-2 text-sm"
                    />

                    <InputError v-if="formErrors.hasOwnProperty('role_id')" :message="formErrors.role_id[0]" />
                </div>

                <!-- Role Name -->
                <div>
                    <label class="block font-medium mb-1">Department</label>
                    <Select
                        name="department_id"
                        v-model="form.department_id"
                        :options="props.departments"
                        optionLabel="name"
                        optionValue="id"
                        placeholder="Select Department"
                        :error="formErrors.hasOwnProperty('department_id')"
                        class="w-full border rounded p-2 text-sm"
                    />
                    <InputError v-if="formErrors.hasOwnProperty('department_id')" :message="formErrors.department_id[0]" />
                </div>

                <!-- ✅ Footer Buttons -->
                <DialogFooter class="flex justify-end gap-2 mt-4">
                    <Button variant="outline" type="button" @click="closeModal">Cancel</Button>
                    <Button @click="submitForm()" :disabled="isSubmitting">
                        <template v-if="isSubmitting">
                            <Loader2 class="mr-2 h-4 w-4 animate-spin" />
                            Updating...
                        </template>
                        <template v-else>
                            <Plus class="mr-2 h-4 w-4" />
                            Update User
                        </template>
                    </Button>
                </DialogFooter>
            </form>
        </DialogContent>
    </Dialog>


</template>
<style scoped>
.error-border {
    border: 1px solid red;
}
</style>
