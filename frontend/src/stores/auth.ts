import { ref } from 'vue'
import { defineStore } from 'pinia'
import { ApiService } from '@/services/api'
import { useRouter } from 'vue-router'
import { useLoader } from '@/stores/loader'
import Cookies from 'js-cookie'

interface User {
  name: string
  email: string
  remaining_time: Date
}

export interface LoginForm {
  email: string
  password: string
}

export interface RegisterForm {
  name: string
  email: string
  password: string
  password_confirmation: string
}

export const useAuthStore = defineStore('auth', () => {
  const user = ref<User | null>(storageGet())

  const router = useRouter()
  const loader = useLoader()

  function storageGet(): User | null {
    const mUser = localStorage.getItem('user')
    return mUser ? (JSON.parse(mUser) as User) : null
  }

  function storageSet(user: User) {
    localStorage.setItem('user', JSON.stringify(user))
  }

  function persistToken(token: string) {
    Cookies.set('access_token', token, { expires: 1, sameSite: 'strict' })
  }

  function deleteToken() {
    Cookies.remove('access_token')
  }

  async function login(creds: LoginForm) {
    loader.show()
    try {
      const response = await ApiService.instance().post('/auth/login', creds)
      user.value = response.data.user
      storageSet(response.data.user as User)
      persistToken(response.data.token)
      ApiService.notifySuccess(response)
      router.replace({ name: 'home' })
    } catch (err: any) {
      ApiService.notifyError(err)
    }
    loader.hide()
  }

  async function register(form: RegisterForm) {
    loader.show()
    try {
      const response = await ApiService.instance().post('/auth/register', form)
      ApiService.notifySuccess(response)
      router.replace({ name: 'login' })
    } catch (err: any) {
      ApiService.notifyError(err)
    }
    loader.hide()
  }

  async function logout() {
    loader.show()
    const response = await ApiService.withAuth().post('/auth/logout')
    deleteToken()
    ApiService.notifySuccess(response)
    router.replace({ name: 'login' })
    loader.hide()
  }

  function validate() {
    const exp = user.value?.remaining_time
    return exp && new Date() < new Date(exp)
  }

  return { user, login, logout, register, validate }
})
