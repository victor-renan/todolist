<script setup lang="ts">
import { MenuItem } from '@headlessui/vue'
import { useAuthStore } from '@/stores/auth'
import { RouterLink } from 'vue-router'
import DropDown from '@/components/DropDown.vue'
import logo from '@/assets/img/logo.png'

const authStore = useAuthStore()
const user = authStore.user
</script>

<template>
  <nav
    class="bg-white fixed left-0 top-0 w-full h-20 flex items-center justify-center px-4 border-b-1 border-gray-200 border-dashed z-20"
  >
    <div class="max-w-5xl w-full flex justify-between">
      <RouterLink class="w-fit flex items-center gap-1" :to="{ name: 'home' }">
        <img class="w-10 object-cover" :src="logo" alt="Logo" />
        <h1 class="text-lg font-bold">Tasklist</h1>
      </RouterLink>
      <DropDown>
        <template #button>
          <div class="flex items-center gap-2 cursor-pointer">
            <div
              class="bg-gray-200 hover:bg-gray-300 w-12 h-12 rounded-full flex items-center justify-center transition"
            >
              <i class="bx bx-user text-2xl"></i>
            </div>
          </div>
        </template>
        <template #items>
          <MenuItem class="p-1" disabled>
            <div class="flex items-center gap-2 transition rounded-xl mb-2">
              <div class="bg-gray-200 w-11 h-11 rounded-full flex items-center justify-center">
                <i class="bx bx-user text-xl"></i>
              </div>
              <div>
                <h1 class="text-md font-bold text-nowrap">{{ user?.name }}</h1>
                <p class="text-xs">{{ user?.email }}</p>
              </div>
            </div>
          </MenuItem>
          <MenuItem>
            <button
              @click="authStore.logout"
              class="bg-black hover:bg-gray-800 text-white py-1 text-sm rounded w-full transition cursor-pointer flex items-center gap-1 justify-center"
            >
              <i class="bx bx-log-out-circle"></i>
              Logout
            </button>
          </MenuItem>
        </template>
      </DropDown>
    </div>
  </nav>
</template>
