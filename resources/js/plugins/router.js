import { createRouter, createWebHistory } from 'vue-router'

const routes = [
  
  {
    path: '/',
    name: 'home',
    component: () => import('../pages/Home.vue')
  },
  {
    path: '/contact',
    name: 'contact',
    component: () => import('../pages/Contact.vue')
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router



