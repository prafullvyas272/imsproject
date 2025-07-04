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

const $toast = useToast();
const editDepartmentModalVisible = ref(true);

const form = ref({
    name: "",
});

const formErrors = ref<Record<string, string[]>>({});

// ✅ Loading state for form submission
const isSubmitting = ref(false);

const emit = defineEmits(['closeModal', 'departmentUpdated']);

const props = defineProps<{
    selectedDepartment: { id: number; name: string },
    name?: string;
    department_id?: number;
}>();

const submitForm = () => {
    isSubmitting.value = true;

    try {
        axios.put(route('department.update', {department: props.selectedDepartment?.id}), {
            name: form.value.name,
            id: props.selectedDepartment?.id,
        })
            .then((response) => {
                console.log(response)
                isSubmitting.value = false;
                $toast.info(response.data.message);
                closeModal();
                emit('departmentUpdated', response.data.data);

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
    form.value.name = props.selectedDepartment.name ?? "";
});

</script>

<template>


    <!-- ✅ Add Department Dialog (Modal) -->
    <Dialog :open="editDepartmentModalVisible" @update:open="closeModal">
        <DialogContent class="sm:max-w-[500px]">
            <DialogHeader>
                <DialogTitle>Update Department</DialogTitle>
                <DialogDescription>
                    Enter the details of the department you want to update.
                </DialogDescription>
            </DialogHeader>

            <!-- ✅ Form inside modal -->
            <form @submit.prevent="submitForm" class="space-y-4">
                <!-- Department Name -->
                <div>
                    <label class="block font-medium mb-1">Department Name</label>
                    <Input v-model="form.name" :class="[
                        formErrors.hasOwnProperty('name') ? 'error-border' : ''
                    ]" placeholder="Enter department name" class="w-full" />
                    <InputError v-if="formErrors.hasOwnProperty('name')" :message="formErrors.name[0]" />
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
                            Update Department
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
