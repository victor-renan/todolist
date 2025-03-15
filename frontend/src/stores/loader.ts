import { ref } from 'vue'
import { defineStore } from 'pinia'

export const useLoader = defineStore('loader', () => {
  const isLoading = ref(false)

  function show() {
    isLoading.value = true
  }

  function hide() {
    isLoading.value = false
  }

  return { isLoading, show, hide }
})
