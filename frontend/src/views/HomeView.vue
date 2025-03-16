<script setup lang="ts">
import DropDown from '@/components/DropDown.vue'
import NavBar from '@/components/NavBar.vue'
import { ApiService } from '@/services/api'
import {
  Dialog,
  DialogPanel,
  DialogTitle,
  MenuItem,
  TransitionChild,
  TransitionRoot,
} from '@headlessui/vue'
import { computed, onMounted, ref } from 'vue'

interface TodoSearch {
  title: string
  description: string
  is_done: boolean
}

interface Todo {
  id: number
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

interface TodoForm {
  id?: number
  title: string
  description: string
}

const rest = ref<TodosResponse>({} as TodosResponse)
const search = ref<TodoSearch>({} as TodoSearch)
const form = ref<TodoForm>({} as TodoForm)


const todos = computed(() => Object.values(rest.value.data ?? []))
const links = computed(() => {
  const links = Object.values(rest.value.links ?? [])
  return links.slice(1, links.length - 1)
})

async function getTodos(url: string = '/todos') {
  try {
    rest.value = (await ApiService.query<TodosResponse>(url, search.value)).data
  } catch {}
}

async function saveTodo() {
  try {
    if (form.value.id) {
      await ApiService.update<Todo>('/todos', form.value)
    } else {
      await ApiService.create<Todo>('/todos', form.value)
    }
    getTodos()
    closeModal()
  } catch {}
}

async function finishTodo(id: number) {
  try {
    await ApiService.update<Todo>('/todos', { id, is_done: true })
    getTodos()
  } catch {}
}

async function remarkTodo(id: number) {
  try {
    await ApiService.update<Todo>('/todos', { id, is_done: false })
    getTodos()
  } catch {}
}

async function deleteTodo(id: number) {
  try {
    await ApiService.delete<Todo>('/todos', id)
    getTodos()
  } catch {}
}

const modalOpen = ref(false)

function closeModal() {
  modalOpen.value = false
}

function openModal(data: Todo = {} as Todo) {
  form.value = { ...data }
  modalOpen.value = true
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
          <button @click="() => openModal()" class="btn gap-1">
            <i class="bx bx-plus text-2xl"></i>
            <span>Adicionar</span>
          </button>
        </div>
      </div>
      <div class="mt-4 rounded bg-gray-100 z-0">
        <div class="p-3 flex items-center justify-between gap-2 flex-col sm:flex-row">
          <h1 class="text-gray-600">
            <span class="font-bold">{{ rest.total }}</span>
            Tarefa{{ rest.total > 1 ? 's' : '' }}
          </h1>
          <div class="flex gap-1 overflow-scroll">
            <button
              class="btn-sm p-0 disabled:text-gray-300"
              :disabled="!rest.prev_page_url"
              @click="() => rest.prev_page_url && getTodos(rest.prev_page_url)"
            >
              <i class="bx bx-chevron-left text-2xl"></i>
            </button>
            <template v-for="(item, i) in links" :key="i">
              <button
                @click="getTodos(item.url)"
                class="btn-sm"
                :class="[item.active ? 'bg-blue-500 text-white' : 'bg-gray-300 text-black']"
              >
                {{ item.label }}
              </button>
            </template>

            <button
              class="btn-sm disabled:text-gray-300"
              :disabled="!rest.next_page_url"
              @click="() => rest.next_page_url && getTodos(rest.next_page_url)"
            >
              <i class="bx bx-chevron-right text-2xl"></i>
            </button>
          </div>
        </div>
        <table v-if="todos.length > 0" class="table z-0 bg-gray-100">
          <thead>
            <tr>
              <th class="text-sm sm:text-md">Título</th>
              <th class="hidden sm:block sm:text-md">Descrição</th>
              <th class="text-sm sm:text-md">Status</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <tr
              class="text-xs text-wrap sm:text-sm border-b-1 border-gray-300"
              v-for="todo in todos"
            >
              <td class="font-medium sm:text-nowrap">{{ todo.title }}</td>
              <td class="hidden sm:block text-gray-700">{{ todo.description }}</td>
              <td>
                <span
                  v-if="todo.is_done"
                  class="text-black bg-green-400/40 px-2 rounded-lg font-medium py-0.5"
                  >Finalizada</span
                >
                <span
                  v-else
                  class="sm:text-nowrap text-black bg-amber-400/40 text-nowrap px-2 rounded-lg font-medium py-0.5"
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
                      <button
                        @click="() => openModal(todo)"
                        class="btn-sm w-full gap-1 p-1 hover:bg-blue-200"
                      >
                        <i class="bx bx-pen text-lg text-blue-500"></i>
                        Editar
                      </button>
                    </MenuItem>
                    <MenuItem v-if="!todo.is_done">
                      <button
                        @click="() => finishTodo(todo.id)"
                        class="btn-sm w-full gap-1 p-1 hover:bg-blue-200"
                      >
                        <i class="bx bx-check text-lg text-blue-500"></i>
                        Concluir
                      </button>
                    </MenuItem>
                    <MenuItem v-else>
                      <button
                        @click="() => remarkTodo(todo.id)"
                        class="flex items-center btn-sm w-full gap-1 p-1 hover:bg-blue-200"
                      >
                        <i class="bx bx-undo text-lg text-blue-500"></i>
                        Desfazer
                      </button>
                    </MenuItem>
                    <MenuItem>
                      <button
                        @click="() => deleteTodo(todo.id)"
                        class="btn-sm w-full gap-1 p-1 hover:bg-red-200"
                      >
                        <i class="bx bx-trash text-lg text-red-500"></i>
                        Deletar
                      </button>
                    </MenuItem>
                  </template>
                </DropDown>
              </td>
            </tr>
          </tbody>
        </table>
        <div v-else class="text-center pt-4 pb-8">
          <i class="bx bx-notification-off text-3xl"></i>
          <p>Nenhuma tarefa adicionada</p>
        </div>
      </div>
    </div>
    <TransitionRoot appear :show="modalOpen" as="template">
      <Dialog as="div" @close="closeModal">
        <TransitionChild
          as="template"
          enter="duration-300 ease-out"
          enter-from="opacity-0"
          enter-to="opacity-100"
          leave="duration-200 ease-in"
          leave-from="opacity-100"
          leave-to="opacity-0"
        >
          <div class="fixed inset-0 bg-black/25 top-0 left-0 backdrop-blur z-20" />
        </TransitionChild>

        <div class="fixed inset-0 overflow-y-auto z-21">
          <div class="flex min-h-full items-center justify-center p-4 text-center">
            <TransitionChild
              as="template"
              enter="duration-300 ease-out"
              enter-from="opacity-0 scale-95"
              enter-to="opacity-100 scale-100"
              leave="duration-200 ease-in"
              leave-from="opacity-100 scale-100"
              leave-to="opacity-0 scale-95"
            >
              <DialogPanel
                class="z-30 w-full max-w-md transform overflow-hidden rounded bg-white p-6 text-left align-middle shadow-xl transition-all"
              >
                <DialogTitle as="h1" class="text-xl font-semibold leading-4">
                  {{ form.id ? 'Editar' : 'Adicionar' }} Tarefa
                </DialogTitle>
                <div class="mt-2">
                  <p class="text-sm text-gray-500">Preencha os dados abaixo</p>
                </div>
                <form @submit.prevent="saveTodo" class="flex flex-col gap-2 mt-2">
                  <div>
                    <label class="form-label" for="title">Título</label>
                    <input
                      id="title"
                      v-model="form.title"
                      class="form-control"
                      type="title"
                      placeholder="Digite o título"
                      required
                    />
                  </div>
                  <div>
                    <label class="form-label" for="description">Descrição</label>
                    <textarea
                      id="description"
                      v-model="form.description"
                      class="form-control"
                      type="description"
                      rows="4"
                      placeholder="Digite a descrição"
                      required
                    ></textarea>
                  </div>
                  <div class="mt-2 flex justify-end gap-2">
                    <button type="button" @click="closeModal()" class="btn-secondary">
                      Cancelar
                    </button>
                    <button type="submit" class="btn">Salvar</button>
                  </div>
                </form>
              </DialogPanel>
            </TransitionChild>
          </div>
        </div>
      </Dialog>
    </TransitionRoot>
  </main>
</template>
