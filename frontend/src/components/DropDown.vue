<script lang="ts" setup>
import { Menu, MenuButton, MenuItems } from '@headlessui/vue'

const props = withDefaults(
  defineProps<{
    menuClass?: string
  }>(),
  {
    menuClass: 'w-fit',
  },
)
</script>

<template>
  <div class="relative">
    <Menu>
      <MenuButton>
        <slot name="button"></slot>
      </MenuButton>
      <transition
        enter-active-class="transition duration-100 ease-out"
        enter-from-class="transform scale-95 opacity-0"
        enter-to-class="transform scale-100 opacity-100"
        leave-active-class="transition duration-75 ease-in"
        leave-from-class="transform scale-100 opacity-100"
        leave-to-class="transform opacity-0"
      >
        <MenuItems
          class="bg-white border right-0 z-20 border-gray-100 absolute mt-2 p-1 rounded-lg overflow-hidden"
          :class="props.menuClass"
        >
          <slot name="items"></slot>
        </MenuItems>
      </transition>
    </Menu>
  </div>
</template>
