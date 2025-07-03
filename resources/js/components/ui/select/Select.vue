<template>
    <select :id="id" :name="name" v-model="modelValueProxy" :class="[
        'block w-full rounded-md border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 py-2 px-3 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 text-sm',
        error ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : '',
        disabled ? 'bg-gray-100 dark:bg-gray-800 cursor-not-allowed opacity-60' : '',
        customClass
    ]" :disabled="disabled" @change="onChange">
        <option v-if="placeholder" disabled value="">{{ placeholder }}</option>
        <option v-for="option in options" :key="optionKey(option)" :value="optionValue(option)">
            {{ optionLabel(option) }}
        </option>
    </select>
</template>

<script setup lang="ts">
import { computed, defineProps, defineEmits } from 'vue';

const props = defineProps<{
    modelValue: string | number | null,
    options: [] | undefined,
    label?: string,
    name?: string,
    id?: string,
    placeholder?: string,
    optionLabel?: string | ((option: any) => string),
    optionValue?: string | ((option: any) => string | number),
    optionKey?: string | ((option: any) => string | number),
    error?: string|boolean,
    disabled?: boolean,
    customClass?: string
}>();

const emit = defineEmits(['update:modelValue', 'change']);

const modelValueProxy = computed({
    get: () => props.modelValue,
    set: (val) => emit('update:modelValue', val)
});

const optionLabel = (option: any) => {
    if (typeof props.optionLabel === 'function') {
        return props.optionLabel(option);
    }
    return props.optionLabel ? option[props.optionLabel] : option.label ?? option.name ?? option;
};

const optionValue = (option: any) => {
    if (typeof props.optionValue === 'function') {
        return props.optionValue(option);
    }
    return props.optionValue ? option[props.optionValue] : option.value ?? option.id ?? option;
};

const optionKey = (option: any) => {
    if (typeof props.optionKey === 'function') {
        return props.optionKey(option);
    }
    return props.optionKey ? option[props.optionKey] : option.id ?? option.value ?? option;
};

const onChange = (event: Event) => {
    emit('change', (event.target as HTMLSelectElement).value);
};
</script>
