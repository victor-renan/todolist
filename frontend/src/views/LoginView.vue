<script setup lang="ts">
import logo from '@/assets/img/logo.png'
import { useAuthStore, type LoginForm } from '@/stores/auth'
import { ref } from 'vue'

const form = ref<LoginForm>({
  email: 'test@example.com',
  password: 'Test1234',
} as LoginForm)
</script>

<template>
  <main class="h-screen flex items-center justify-center">
    <div class="flex flex-col max-w-70 w-full gap-4">
      <div class="flex flex-col items-center text-center">
        <img class="w-12" :src="logo" alt="Logo" />
        <h1 class="text-xl font-bold">Login Tasklist</h1>
        <p class="text-sm">Bem vindo! Entre para ver suas tarefas</p>
      </div>
      <form @submit.prevent="() => useAuthStore().login(form)">
        <div>
          <label class="form-label" for="email">Email</label>
          <input
            id="email"
            v-model="form.email"
            class="form-control"
            type="email"
            placeholder="Digite seu email"
            required
          />
        </div>
        <div class="mt-2">
          <label class="form-label" for="password">Senha</label>
          <input
            id="password"
            v-model="form.password"
            class="form-control"
            type="password"
            placeholder="Digite sua senha"
            required
          />
        </div>
        <button class="btn mt-4 w-full" type="submit">
          Entrar
        </button>
      </form>
      <span class="text-center">
        Não tem uma conta?
        <RouterLink class="link" :to="{ name: 'register' }">
          Registre-se
        </RouterLink>
      </span>
    </div>
  </main>
</template>
