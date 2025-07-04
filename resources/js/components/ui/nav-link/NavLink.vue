<script setup lang="ts">
import type { HTMLAttributes } from 'vue'
import { cn } from '@/lib/utils'
import { Primitive, type PrimitiveProps } from 'reka-ui'
import { type ButtonVariants, buttonVariants } from '../button' // import from button for variants
import { Link } from '@inertiajs/vue3'

interface Props extends PrimitiveProps {
  variant?: ButtonVariants['variant']
  size?: ButtonVariants['size']
  class?: HTMLAttributes['class']
  href?: string
  target?: string
  rel?: string
}

const props = withDefaults(defineProps<Props>(), {
  as: 'a', // default to anchor
})
</script>

<template>
  <Primitive
    class="cursor-pointer"
    data-slot="nav-link"
    :as="Link"
    :as-child="asChild"
    :href="props.href"
    :target="props.target"
    :rel="props.rel"
    :class="cn(buttonVariants({ variant, size }), props.class)"
  >
    <slot />
  </Primitive>
</template>
