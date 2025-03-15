import { notify } from '@kyvg/vue3-notification'
import axios, { AxiosError, type AxiosInstance, type AxiosResponse } from 'axios'
import Cookies from 'js-cookie'

interface ApiError {
  errors?: object
  message?: string
}

const instance = axios.create({
  baseURL: import.meta.env.VITE_BACKEND_URL,
  timeout: 5000,
})

export const ApiService = {
  instance(): AxiosInstance {
    return instance
  },
  withAuth() {
    instance.defaults.headers.common.Authorization = `Bearer ${Cookies.get('access_token')}`
    instance.interceptors.response.use(
      (response: AxiosResponse) => {
        return response
      },
      (error: AxiosError) => {
        if (error.status === 401) {
          notify({
            title: 'Aviso',
            text: 'Faça login novamente',
            type: 'warn',
          })
        }
      },
    )
    return instance
  },
  notifySuccess(res: AxiosResponse) {
    if (res.data.message) {
      notify({
        title: 'Sucesso',
        text: res.data.message,
        type: 'success',
      })
    }
  },
  notifyError(err: AxiosError) {
    const data = err.response?.data as ApiError
    const status = err.status || 500

    if (data?.errors) {
      Object.values(data.errors).forEach((item) => {
        notify({
          title: 'Dados Inválidos',
          text: item,
          type: 'warn',
        })
      })
      return
    }

    notify({
      title: status < 500 ? 'Atenção' : 'Erro',
      text: data?.message ?? 'Servidor indisponível',
      type: status < 500 ? 'warn' : 'error',
    })
  },
}
