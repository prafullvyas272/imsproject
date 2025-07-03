<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from "@/components/ui/dialog";
import { Trash2 } from 'lucide-vue-next';
import { ref, defineEmits } from "vue";

const isModalVisible = ref(true);

// ✅ Loading state for form submission
const isSubmitting = ref(false);

const emit = defineEmits(['deleteConfirmed', 'schoolUpdated']);


const closeModal = () => {
    emit('closeModal')
};

const deleteData = () => {
    emit('deleteConfirmed')
};

const props = defineProps<{
    name?: string;
    school?: object;
    moduleName?: string;
    authUser?: object;
}>();

</script>

<template>
    <!-- ✅ Delete Dialog (Modal) -->
    <Dialog :open="isModalVisible" @update:open="closeModal">
        <DialogContent class="sm:max-w-[500px]">
            <DialogHeader>
                <DialogTitle>Delete {{ moduleName }}</DialogTitle>
                <DialogDescription>
                    Are you sure you want to delete this?
                </DialogDescription>
            </DialogHeader>

            <!-- ✅ Form inside modal -->
            <form class="space-y-4">
                <!-- ✅ Footer Buttons -->
                <DialogFooter class="flex justify-end gap-2 mt-4">
                    <Button variant="outline" type="button" @click="closeModal">Cancel</Button>
                    <Button type="button" @click="deleteData()" :disabled="isSubmitting"  >
                        <template v-if="isSubmitting">
                            <Loader2 class="mr-2 h-4 w-4 animate-spin" />
                            Deleting...
                        </template>
                        <template v-else>
                            <Trash2 class="mr-2 h-4 w-4" />
                            Delete
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
