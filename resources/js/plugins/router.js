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
  },
  {
    path: '/produits',
    name: 'Produits',
    component: () => import('../pages/Produits.vue')
  },
  {
    path: '/fournisseurs',
    name: 'Fournisseurs',
    component: () => import('../pages/Fournisseurs.vue')
  },
  {
    path: '/entrees',
    name: 'Entrees',
    component: () => import('../pages/Entrees.vue')
  },
  {
    path: '/entreesProduits/:id',
    name: 'EntreesProduits',
    component: () => import('../pages/EntreesProduits.vue')
  },
  {
    path: '/entreesInfo',
    name: 'EntreesInfo',
    component: () => import('../pages/EntreesInfo.vue')
  },
  {
    path: '/rack',
    name: 'Rack',
    component: () => import('../pages/Rack.vue')
  }

]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router



