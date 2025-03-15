<script lang="ts" setup>
import { useLoader } from '@/stores/loader'
const loader = useLoader()
</script>

<template>
  <div
    v-show="loader.isLoading"
    class="w-screen h-screen absolute top-0 left-0 flex items-center justify-center backdrop-blur-2xl"
  >
    <i class="bx bx-loader-alt animate-spin text-5xl"></i>
  </div>
</template>
