import { createRouter, createWebHistory } from 'vue-router'
import axios from 'axios'

// Importaciones de las vistas
import LoginView from '../views/LoginView.vue'
import HitzorduakView from '../views/HitzorduakView.vue'
import BezeroakView from '../views/BezeroakView.vue'
import StockView from '../views/StockView.vue'
import IkasleKudeaketaView from '../views/IkasleKudeaketaView.vue'

// Configuración de Axios para que coincida con tu backend
// Cambia la IP y el puerto según lo que hayas visto en el paso anterior
axios.defaults.baseURL = 'http://98.93.71.5';

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    { 
      path: '/', 
      redirect: '/login' 
    },
    { 
      path: '/login', 
      name: 'login', 
      component: LoginView 
    },
    { 
      path: '/hitzorduak', 
      name: 'hitzorduak', 
      component: HitzorduakView 
    },
    { 
      path: '/bezeroak', 
      name: 'bezeroak', 
      component: BezeroakView 
    },
    { 
      path: '/stock', 
      name: 'stock', 
      component: StockView 
    },
    { 
      path: '/kontrola', 
      name: 'kontrola', 
      component: IkasleKudeaketaView 
    },
    // Añadimos /admin por si acaso algún enlace viejo todavía apunta ahí
    { 
      path: '/admin', 
      name: 'admin', 
      component: IkasleKudeaketaView 
    },
  ]
})

// --- GUARDIA DE NAVEGACIÓN ---
router.beforeEach((to, from, next) => {
  const token = localStorage.getItem('token');

  // 1. Si el usuario intenta entrar en cualquier sitio (menos login) sin token
  if (to.name !== 'login' && !token) {
    next({ name: 'login' });
  } 
  else {
    // 2. Si hay token, lo inyectamos en las cabeceras de Axios antes de cargar la vista.
    // Esto evita que las páginas de Stock y Clientes salgan en blanco.
    if (token) {
      axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
    }
    next();
  }
})

export default router