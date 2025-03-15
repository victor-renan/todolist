<script setup lang="ts">
import logo from '@/assets/img/logo.png'
</script>

<template>
  <main class="h-screen flex items-center justify-center">
    <div class="flex flex-col max-w-70 w-full gap-4">
      <div class="flex flex-col items-center text-center">
        <i class="bx bx-error text-4xl"></i>
        <h1 class="text-xl font-bold">Página não encontrada</h1>
        <p class="text-sm">A pagina que você quer acessar não existe</p>
        <RouterLink class="btn gap-1 mt-4" :to="{ name: 'home' }">
          <i class="bx bx-home text-xl"></i>
          Home
        </RouterLink>
      </div>
    </div>
  </main>
</template>

<style scoped>
main::after {
  color: var(--color-gray-200);
  content: '404';
  font-size: 20em;
  position: absolute;
  z-index: -1;
}
</style>
