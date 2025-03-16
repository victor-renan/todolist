import { useLoader } from '@/stores/loader'
import { notify } from '@kyvg/vue3-notification'
import Cookies from 'js-cookie'
import axios, { AxiosError, type AxiosInstance, type AxiosResponse } from 'axios'

interface ApiError {
  errors?: object
  message?: string
}

export function notifyErrorAdapter(err: AxiosError) {
  const data = err.response?.data as ApiError
  const status = err.status || 500

  if (data?.errors) {
    return Object.values(data.errors).forEach((item) => {
      item.forEach((err: string) => {
        notify({
          title: 'Dados Inválidos',
          text: err,
          type: 'warn',
        })
      })
    })
  }

  notify({
    title: status < 500 ? 'Atenção' : 'Erro',
    text: data?.message ?? 'Servidor indisponível',
    type: status < 500 ? 'warn' : 'error',
  })
}

export function notifySuccessAdapter(response: AxiosResponse) {
  if (response.data.message) {
    notify({
      title: 'Sucesso',
      text: response.data.message,
      type: 'success',
    })
  }
}

const instance = axios.create({
  baseURL: import.meta.env.VITE_BACKEND_URL,
  timeout: 5000,
})

instance.interceptors.request.use((config) => {
  useLoader().show()
  return config
})

instance.interceptors.response.use(
  (response) => {
    useLoader().hide()
    return response
  },
  (err) => {
    useLoader().hide()
    return Promise.reject(err)
  },
)

export const ApiService = {
  instance(): AxiosInstance {
    instance.defaults.headers.common.Authorization = `Bearer ${Cookies.get('access_token')}`
    return instance
  },
  async query<T = any>(route: string, params: any) {
    try {
      return await this.instance().get<T>(route, { params })
    } catch (err: any) {
      notifyErrorAdapter(err)
      throw err
    }
  },
}
