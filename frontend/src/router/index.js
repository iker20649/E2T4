import { createRouter, createWebHistory } from 'vue-router'
// Importa tus vistas aquí (ajusta las rutas si es necesario)
import LoginView from '../views/LoginView.vue'
import HitzorduakView from '../views/HitzorduakView.vue'
import BezeroakView from '../views/BezeroakView.vue'
import StockView from '../views/StockView.vue' // O la ruta que tengas
import IkasleKudeaketaView from '../views/IkasleKudeaketaView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    { path: '/', redirect: '/login' },
    { path: '/login', name: 'login', component: LoginView },
    { path: '/hitzorduak', name: 'hitzorduak', component: HitzorduakView },
    { path: '/bezeroak', name: 'bezeroak', component: BezeroakView },
    { path: '/stock', name: 'stock', component: StockView },
    { path: '/admin', name: 'admin', component: IkasleKudeaketaView },
  ]
})

// --- GUARDIA DE SEGURIDAD ---
router.beforeEach((to, from, next) => {
  const token = localStorage.getItem('token');
  const rol = localStorage.getItem('rol');

  // 1. Si no hay token y no va a login, mandarlo a login
  if (to.name !== 'login' && !token) {
    next({ name: 'login' });
  } 
  // 2. Si intenta ir a /admin pero NO es irakasle, mandarlo a hitzorduak
  else if (to.path === '/admin' && rol !== 'irakasle') {
    next('/hitzorduak');
  } 
  // 3. En cualquier otro caso, dejar pasar
  else {
    next();
  }
})

export default router