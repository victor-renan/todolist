<script setup lang="ts">
import DropDown from '@/components/DropDown.vue'
import NavBar from '@/components/NavBar.vue'
import { ApiService } from '@/services/api'
import { MenuItem } from '@headlessui/vue'
import { computed, onMounted, ref } from 'vue'

interface TodoSearch {
  title: string
  description: string
  is_done: boolean
}

interface Todo {
  title: string
  description: string
  is_done: boolean
  created_at: boolean
  updated_at: boolean
}

interface TodosResponse {
  data: Todo[] | null
  total: number
  links: object
  first_page_url: string | null
  last_page_url: string | null
  next_page_url: string | null
  prev_page_url: string | null
  per_page: number
  current_page: number
  last_page: number
  path: string
  from: number
  to: number
}

const todos = ref<TodosResponse>({} as TodosResponse)
const search = ref<TodoSearch>({} as TodoSearch)

const links = computed(() => Object.values(todos.value.links ?? []))

async function getTodos(url: string = '/todos') {
  try {
    todos.value = (await ApiService.query<TodosResponse>(url, search.value)).data
  } catch {}
}

onMounted(() => {
  getTodos()
})
</script>

<template>
  <NavBar />
  <main class="mt-20 p-4">
    <div class="max-w-5xl mx-auto">
      <div
        class="flex justify-between items-center flex-col text-center sm:flex-row sm:text-left gap-2"
      >
        <div>
          <h1 class="text-xl font-bold">Lista de Tarefas</h1>
          <p class="text-sm">Busque e conclua suas tarefas</p>
        </div>
        <div>
          <button class="btn gap-1">
            <i class="bx bx-plus text-2xl"></i>
            <span>Adicionar</span>
          </button>
        </div>
      </div>
      <div class="mt-4 rounded bg-gray-100 overflow-hidden">
        <div class="p-3 flex justify-between items-center">
          <h1 class="text-gray-600">
            <i class="bx bx-menu me-1"></i>
            {{ todos.total }} Tarefa{{ todos.total > 1 ? 's' : '' }}
          </h1>
          <div class="flex gap-1 overflow-scroll">
            <button
              class="btn-sm p-0 disabled:text-gray-300"
              :disabled="!todos.prev_page_url"
              @click="() => todos.prev_page_url && getTodos(todos.prev_page_url)"
            >
              <i class="bx bx-chevron-left text-2xl"></i>
            </button>
            <template v-for="(item, i) in links">
              <button
                v-if="i !== 0 && i !== links.length - 1"
                @click="getTodos(item.url)"
                class="btn-sm"
                :class="[item.active ? 'bg-blue-500 text-white' : 'bg-gray-300 text-black']"
                :key="i"
              >
                {{ item.label }}
              </button>
            </template>

            <button
              class="btn-sm disabled:text-gray-300"
              :disabled="!todos.next_page_url"
              @click="() => todos.next_page_url && getTodos(todos.next_page_url)"
            >
              <i class="bx bx-chevron-right text-2xl"></i>
            </button>
          </div>
        </div>
        <table class="table">
          <thead>
            <tr>
              <th>Título</th>
              <th class="hidden sm:block">Descrição</th>
              <th>Status</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="todo in Object.values(todos.data ?? [])">
              <td>{{ todo.title }}</td>
              <td class="hidden sm:block">{{ todo.description }}</td>
              <td>
                <span v-if="todo.is_done" class="text-white bg-green-200 text-nowrap text-sm"
                  >Finalizada</span
                >
                <span
                  v-else
                  class="text-black bg-gray-300 text-nowrap px-2 rounded font-semibold text-sm"
                >
                  A Fazer
                </span>
              </td>
              <td>
                <DropDown class="ml-auto" menuClass="w-30">
                  <template #button>
                    <button class="cursor-pointer p-1 flex">
                      <i class="bx text-lg bx-dots-vertical-rounded"></i>
                    </button>
                  </template>
                  <template #items>
                    <MenuItem>
                      <button class="flex items-center btn-sm w-full gap-1 p-1 hover:bg-blue-200">
                        <i class="bx bx-pen text-lg text-blue-500"></i>
                        Editar
                      </button>
                    </MenuItem>
                    <MenuItem v-if="!todo.is_done">
                      <button class="flex items-center btn-sm w-full gap-1 p-1 hover:bg-blue-200">
                        <i class="bx bx-check text-lg text-blue-500"></i>
                        Concluir
                      </button>
                    </MenuItem>
                    <MenuItem v-else>
                      <button class="flex items-center btn-sm w-full gap-1 p-1 hover:bg-blue-200">
                        <i class="bx bx-undo text-lg text-blue-500"></i>
                        Desfazer
                      </button>
                    </MenuItem>
                  </template>
                </DropDown>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </main>
</template>
