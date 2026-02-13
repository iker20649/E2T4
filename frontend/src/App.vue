<script setup>
import { RouterLink, RouterView, useRouter, useRoute } from 'vue-router'
import { ref, onMounted, watch } from 'vue'
import axios from 'axios'
import logoImg from '@/assets/logoa.png'
import './assets/main.css' 

// --- CONFIGURACIÓN GLOBAL DE AXIOS ---
// IP eguneratua gaurkoarekin
// Cambia la IP y el puerto según lo que hayas visto en el paso anterior
axios.defaults.baseURL = 'http://98.93.71.5';
axios.defaults.withCredentials = false; 
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

const router = useRouter()
const route = useRoute()
const isLoggedIn = ref(false)
const user = ref(null)

const checkLogin = async () => {
  const token = localStorage.getItem('token')
  if (!token) {
    isLoggedIn.value = false
    user.value = null
    return
  }

  isLoggedIn.value = true

  try {
    const res = await axios.get('/api/user', {
      headers: { Authorization: `Bearer ${token}` }
    })
    user.value = res.data
  } catch (error) {
    if (error.response && error.response.status === 401) {
      localStorage.clear()
      isLoggedIn.value = false
      user.value = null
      if (route.path !== '/login') router.push('/login')
    }
  }
}

onMounted(checkLogin)

watch(() => route.path, (newPath) => {
  if (newPath !== '/login') checkLogin()
})

const logout = async () => {
  const token = localStorage.getItem('token')
  try {
    await axios.post('/api/logout', {}, {
      headers: { Authorization: `Bearer ${token}` }
    })
  } catch (error) {
    console.error("Errorea logout egitean", error)
  } finally {
    localStorage.clear()
    isLoggedIn.value = false
    user.value = null
    router.push('/login')
  }
}
</script>

<template>
  <div class="min-h-screen w-full bg-[#f1f5f9] font-sans">
    <nav v-if="route.path !== '/login' && isLoggedIn" class="bg-[#1e1b4b] border-b-4 border-[#6366f1] shadow-2xl">
      <div class="max-w-7xl mx-auto px-6 h-24 flex justify-between items-center">
        
        <div class="flex items-center gap-10">
          <RouterLink to="/hitzorduak" class="flex items-center gap-3 no-underline">
            <img :src="logoImg" class="h-12 rounded-xl">
            <h1 class="text-white font-black italic uppercase text-2xl tracking-tighter">Ilea<span class="text-indigo-500">.</span>App</h1>
          </RouterLink>

          <div class="flex gap-6">
            <RouterLink to="/hitzorduak" class="text-white no-underline font-bold text-sm uppercase hover:text-indigo-400">Hitzorduak</RouterLink>
            <RouterLink to="/bezeroak" class="text-white no-underline font-bold text-sm uppercase hover:text-indigo-400">Bezeroak</RouterLink>
            <RouterLink to="/stock" class="text-white no-underline font-bold text-sm uppercase hover:text-indigo-400">Stocka</RouterLink>
            
            <template v-if="user?.rola === 'irakasle'">
              <RouterLink to="/kontrola" class="bg-indigo-600/20 text-indigo-300 px-4 py-1 rounded-lg border border-indigo-500/50 no-underline font-black text-xs uppercase">
                Kontrola
              </RouterLink>
            </template>
          </div>
        </div>

        <div class="flex items-center gap-6">
          <div class="text-right hidden sm:block">
            <p class="text-indigo-400 font-black text-[10px] uppercase m-0">{{ user?.rola }}</p>
            <p class="text-white font-bold text-sm m-0">{{ user?.name }}</p>
          </div>
          <button @click="logout" class="bg-rose-600 text-white px-4 py-2 rounded-xl font-black text-[10px] border-b-4 border-rose-800 active:border-0 transition-all uppercase">OUT</button>
        </div>
      </div>
    </nav>

    <main :class="route.path === '/login' ? '' : 'max-w-7xl mx-auto p-6'">
      <RouterView />
    </main>
  </div>
</template>

<style scoped>
/* KONTEINER NAGUSIA */
.nav-container {
  background-color: #1e1b4b !important;
  color: white !important;
  width: 100%;
  border-bottom: 4px solid #6366f1;
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
}

.nav-content {
  max-width: 1280px;
  margin: 0 auto;
  min-height: 80px; /* Lehen 96px finkoa zen, orain minimoa da */
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 10px 20px;
  flex-wrap: wrap; /* Garrantzitsua mugikorrerako */
  gap: 10px;
}

.flex-section {
  display: flex;
  align-items: center;
  gap: 20px;
  flex-wrap: wrap;
}

/* LOGOA */
.logo-link {
  display: flex;
  align-items: center;
  gap: 10px;
  text-decoration: none;
}
.logo-img {
  height: 45px; /* Pixka bat txikiagoa mugikorrean */
  border-radius: 10px;
}
.logo-text {
  color: white;
  font-size: 20px;
  font-weight: 900;
  margin: 0;
  font-style: italic;
  text-transform: uppercase;
}

/* LOTURAK */
.nav-links {
  display: flex;
  gap: 15px;
  align-items: center;
  flex-wrap: wrap; /* Loturak azpira jaisteko lekua ez badago */
}

.nav-link {
  color: white;
  text-decoration: none;
  font-weight: bold;
  font-size: 13px;
  transition: color 0.3s;
}
.nav-link:hover {
  color: #818cf8;
}
.router-link-active {
  color: #6366f1 !important;
}

.admin-link {
  background-color: rgba(99, 102, 241, 0.2);
  color: #a5b4fc;
  padding: 5px 12px;
  border-radius: 8px;
  border: 1px solid rgba(99, 102, 241, 0.5);
  text-decoration: none;
  font-weight: bold;
  font-size: 12px;
}

/* USER ALDEA */
.user-section {
  display: flex;
  align-items: center;
  gap: 15px;
}
.user-info {
  text-align: right;
}
.role-badge {
  color: #818cf8;
  font-size: 9px;
  font-weight: 900;
  margin: 0;
  text-transform: uppercase;
}
.user-name {
  color: white;
  font-size: 12px;
  font-weight: bold;
  margin: 0;
}

.logout-btn {
  background-color: #e11d48;
  color: white;
  padding: 8px 15px;
  border-radius: 10px;
  font-weight: 900;
  border: none;
  border-bottom: 3px solid #9f1239;
  cursor: pointer;
  font-size: 9px;
}

/* MAIN */
.main-container {
  max-width: 1280px;
  margin: 0 auto;
  padding: 20px 15px; /* Padding-a gutxitu dugu mugikorrerako */
}

/* Pantaila handietarako doikuntzak */
@media (min-width: 768px) {
  .logo-img { height: 60px; }
  .logo-text { font-size: 24px; }
  .nav-link { font-size: 14px; }
  .nav-content { height: 96px; padding: 0 20px; }
}
</style>