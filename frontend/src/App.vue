<script setup>
import { RouterLink, RouterView, useRouter, useRoute } from 'vue-router'
import { ref, onMounted, watch } from 'vue'
import axios from 'axios'
import logoImg from '@/assets/logoa.png'
import './assets/main.css' 

// --- CONFIGURACIÓN GLOBAL DE AXIOS ---
// Usamos la IP de tu servidor AWS
axios.defaults.baseURL = 'http://3.88.171.96';
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
    // Si el usuario llega, marcamos como logueado
    isLoggedIn.value = true
  } catch (error) {
    if (error.response && error.response.status === 401) {
      localStorage.clear()
      isLoggedIn.value = false
      user.value = null
      if (route.path !== '/api/login') router.push('/api/login')
    }
  }
}

onMounted(checkLogin)

watch(() => route.path, (newPath) => {
  if (newPath !== '/api/login') checkLogin()
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
    router.push('/api/login')
  }
}
</script>

<template>
  <div class="min-h-screen w-full bg-[#f1f5f9] font-sans">
    <nav v-if="route.path !== '/api/login' && isLoggedIn" class="bg-[#1e1b4b] border-b-4 border-[#6366f1] shadow-2xl">
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
            
            <template v-if="user?.rola === 'irakasle' || user?.rola === 'admin'">
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

    <main :class="route.path === '/api/login' ? '' : 'max-w-7xl mx-auto p-6'">
      <RouterView />
    </main>
  </div>
</template>

<style scoped>
/* Tus estilos originales se mantienen aquí debajo */
.nav-container { background-color: #1e1b4b !important; color: white !important; width: 100%; border-bottom: 4px solid #6366f1; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); }
.nav-content { max-width: 1280px; margin: 0 auto; min-height: 80px; display: flex; justify-content: space-between; align-items: center; padding: 10px 20px; flex-wrap: wrap; gap: 10px; }
.logo-img { height: 45px; border-radius: 10px; }
@media (min-width: 768px) { .logo-img { height: 60px; } .nav-content { height: 96px; } }
</style>
