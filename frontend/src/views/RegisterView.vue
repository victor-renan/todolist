<script setup lang="ts">
import logo from '@/assets/img/logo.png'
import { useAuthStore, type RegisterForm } from '@/stores/auth'
import { ref } from 'vue'

const form = ref<RegisterForm>({} as RegisterForm)
</script>

<template>
  <main class="h-screen flex items-center justify-center">
    <div class="flex flex-col max-w-70 w-full gap-4">
      <div class="flex flex-col items-center text-center">
        <img class="w-12" :src="logo" alt="Logo" />
        <h1 class="text-xl font-bold">Registrar</h1>
        <p class="text-sm">Registre-se para gerenciar suas tarefas conosco</p>
      </div>
      <form @submit.prevent="() => useAuthStore().register(form)">
        <div>
          <label class="form-label" for="email">Nome</label>
          <input
            id="name"
            v-model="form.name"
            class="form-control"
            type="text"
            placeholder="Digite seu nome"
            required
          />
        </div>
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
        <div class="mt-2">
          <label class="form-label" for="password_confirmation">Repita a senha</label>
          <input
            id="password_confirmation"
            v-model="form.password_confirmation"
            class="form-control"
            type="password"
            placeholder="Repita a senha"
            required
          />
        </div>
        <button class="btn mt-4 w-full" type="submit">
          Registrar
        </button>
      </form>
      <span class="text-center">
        Já tem uma conta?
        <RouterLink class="link" :to="{ name: 'login' }"> Entre </RouterLink>
      </span>
    </div>
  </main>
</template>
