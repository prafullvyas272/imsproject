<script setup lang="ts">
import { computed, ComputedRef, type HTMLAttributes } from 'vue'
import { cn } from '@/lib/utils'
import { Primitive, type PrimitiveProps } from 'reka-ui'
import { type ButtonVariants, buttonVariants } from '.'
import { useAuthStore } from '@/stores/AuthStore';

interface Props extends PrimitiveProps {
  variant?: ButtonVariants['variant']
  size?: ButtonVariants['size']
  class?: HTMLAttributes['class']
}

const props = withDefaults(defineProps<Props>(), {
  as: 'button',
})

const authStore = useAuthStore();

const bgThemeClass:ComputedRef = computed(() => {
    return authStore.bgTheme;
});

const formThemeClass: ComputedRef = computed(() => {
  let excludedVariants: Array<ButtonVariants['variant']> = ['ghost', 'destructive'];
  return excludedVariants.includes(props.variant) ? '' : authStore.formTheme;
});

console.log(bgThemeClass.value, formThemeClass.value);
</script>

<template>
  <Primitive
    class="cursor-pointer"
    data-slot="button"
    :as="as"
    :as-child="asChild"
    :class="cn(buttonVariants({ variant, size }), formThemeClass, props.class)"
  >
    <slot />
  </Primitive>
</template>
