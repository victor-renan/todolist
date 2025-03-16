import { useAuthStore } from '@/stores/auth'
import { notify } from '@kyvg/vue3-notification'
import { createRouter, createWebHistory } from 'vue-router'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      meta: {
        auth: true,
        visible: true,
      },
      component: () => import('@/views/HomeView.vue'),
    },
    {
      path: '/login',
      name: 'login',
      meta: {
        auth: false,
        visible: true,
      },
      component: () => import('@/views/LoginView.vue'),
    },
    {
      path: '/register',
      name: 'register',
      meta: {
        auth: false,
        visible: true,
      },
      component: () => import('@/views/RegisterView.vue'),
    },
    {
      path: '/:pathMatch(.*)*',
      name: 'notfound',
      meta: {
        auth: true,
        visible: false,
      },
      component: () => import('@/views/NotFoundView.vue'),
    },
  ],
})

router.beforeEach(async (to, from) => {
  const validate = await useAuthStore().validate()

  if (to.meta.auth && !validate) {
    notify({
      title: 'Autorização expirada',
      text: 'Faça login novamente',
      type: 'warn',
    })
    return { name: 'login' }
  }

if (!to.meta.auth && validate) {
    return { name: 'home' }
  }
})

export default router
