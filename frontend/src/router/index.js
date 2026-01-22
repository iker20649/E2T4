import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import LoginView from '../views/LoginView.vue'
import HitzorduakView from '../views/HitzorduakView.vue'
import StockView from '../views/StockView.vue'
import ProfileView from '../views/ProfileView.vue'
import IkasleKudeaketaView from '../views/IkasleKudeaketaView.vue' // <--- 1. INPORTATU IRUDAKASLEEN KUDEAKETA

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    { 
      path: '/', 
      name: 'home', 
      component: HomeView 
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
      path: '/stock', 
      name: 'stock', 
      component: StockView 
    },
    { 
      path: '/perfila', 
      name: 'perfila', 
      component: ProfileView 
    },
    { 
      // 2. GEHITU IRAKASLEAREN KUDEAKETA BIDEA
      path: '/admin/ikasleak', 
      name: 'ikasle-kudeaketa', 
      component: IkasleKudeaketaView 
    }
  ]
})

// (Aukerakoa) Guard bat gehitu dezakegu baimenik gabeko erabiltzaileak kanporatzeko
router.beforeEach((to, from, next) => {
  const publicPages = ['/login', '/']
  const authRequired = !publicPages.includes(to.path)
  const loggedIn = localStorage.getItem('token')

  if (authRequired && !loggedIn) {
    return next('/login')
  }
  next()
})

export default router