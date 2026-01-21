import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import LoginView from '../views/LoginView.vue'
import HitzorduakView from '../views/HitzorduakView.vue'
import StockView from '../views/StockView.vue'
import ProfileView from '../views/ProfileView.vue' // <--- 1. IMPORTA LA VISTA DE PERFIL

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    { path: '/', name: 'home', component: HomeView },
    { path: '/login', name: 'login', component: LoginView },
    { path: '/hitzorduak', name: 'hitzorduak', component: HitzorduakView },
    { path: '/stock', name: 'stock', component: StockView },
    { path: '/perfila', name: 'perfila', component: ProfileView } // <--- 2. AÑADE LA RUTA DE PERFIL
  ]
})

export default router