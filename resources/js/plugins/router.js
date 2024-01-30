import { createRouter, createWebHistory } from 'vue-router'

const routes = [
  
  {
    path: '/',
    name: 'Accueil',
    component: () => import('../pages/Home.vue')
  },
  {
    path: '/contact',
    name: 'Contact',
    component: () => import('../pages/Contact.vue')
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router



