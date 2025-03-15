<script setup lang="ts">
import { RouterView } from 'vue-router'
import LoaderOverlay from '@/components/LoaderOverlay.vue'
</script>

<template>
  <RouterView />
  <notifications classes="notification" position="bottom right" width="300px" />
  <LoaderOverlay />
</template>

<style scoped></style>
