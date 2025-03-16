import { ref } from 'vue'
import { defineStore } from 'pinia'
import { ApiService, notifyErrorAdapter, notifySuccessAdapter } from '@/services/api'
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
  const user = ref<User | null>(getUser())

  const router = useRouter()

  function getUser(): User | null {
    const mUser = localStorage.getItem('user')
    return mUser ? (JSON.parse(mUser) as User) : null
  }

  function setUser(user: User) {
    localStorage.setItem('user', JSON.stringify(user))
  }

  function removeUser() {
    user.value = null
    localStorage.removeItem('user')
  }

  function persistToken(token: string) {
    Cookies.set('access_token', token, { expires: 1, sameSite: 'strict' })
  }

  function deleteToken() {
    Cookies.remove('access_token')
  }

  async function login(creds: LoginForm) {
    try {
      const response = await ApiService.instance().post('/auth/login', creds)
      user.value = response.data.user
      setUser(response.data.user as User)
      persistToken(response.data.token)
      notifySuccessAdapter(response)
      router.replace({ name: 'home' })
    } catch (err: any) {
      notifyErrorAdapter(err)
    }
  }

  async function register(form: RegisterForm) {
    try {
      const response = await ApiService.instance().post('/auth/register', form)
      notifySuccessAdapter(response)
      router.replace({ name: 'login' })
    } catch (err: any) {
      notifyErrorAdapter(err)
    }
  }

  async function logout() {
    const response = await ApiService.instance().post('/auth/logout')
    deleteToken()
    removeUser()
    notifySuccessAdapter(response)
    router.replace({ name: 'login' })
  }

  async function validate() {
    try {
      await ApiService.instance().post('/auth/validate')
      return true
    } catch (err: any) {
      return false
    }
  }

  return { user, login, logout, register, validate }
})
