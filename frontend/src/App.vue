<script setup>
import { RouterLink, RouterView, useRouter, useRoute } from 'vue-router'
import { ref, onMounted, watch } from 'vue'
import axios from 'axios'
import logoImg from '@/assets/logoa.png'
import './assets/main.css' 

// --- CONFIGURACIÓN GLOBAL DE AXIOS ---
axios.defaults.baseURL = 'http://127.0.0.1'; 
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
  <div class="min-h-screen w-full bg-[#f1f5f9]" style="background-color: #f1f5f9; min-height: 100vh;">
    
    <nav v-if="route.path !== '/login' && isLoggedIn" 
         style="background-color: #1e1b4b !important; color: white !important; display: block; width: 100%; border-bottom: 4px solid #6366f1; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);">
      
      <div class="max-w-7xl mx-auto px-4 h-24 flex justify-between items-center" 
           style="max-width: 1280px; margin: 0 auto; height: 96px; display: flex; justify-content: space-between; align-items: center; padding: 0 20px;">
        
        <div class="flex items-center space-x-10" style="display: flex; align-items: center; gap: 40px;">
          <RouterLink to="/hitzorduak" style="display: flex; align-items: center; gap: 15px; text-decoration: none;">
            <img :src="logoImg" style="height: 60px; border-radius: 15px; display: block;">
            <h1 style="color: white; font-size: 24px; font-weight: 900; margin: 0; font-style: italic; text-transform: uppercase;">
              Ilea<span style="color: #6366f1;">.</span>App
            </h1>
          </RouterLink>

          <div class="hidden md:flex" style="display: flex; gap: 25px; align-items: center;">
            <RouterLink to="/hitzorduak" class="nav-link">Hitzorduak</RouterLink>
            <RouterLink to="/bezeroak" class="nav-link">Bezeroak</RouterLink>
            <RouterLink to="/stock" class="nav-link">Stocka</RouterLink>
            
            <template v-if="user?.rola === 'irakasle'">
              <div style="width: 2px; height: 24px; background-color: rgba(99, 102, 241, 0.3);"></div>
              <RouterLink to="/admin" 
                style="background-color: rgba(99, 102, 241, 0.2); color: #a5b4fc; padding: 8px 16px; border-radius: 12px; border: 1px solid rgba(99, 102, 241, 0.5); text-decoration: none; font-weight: bold;">
                Ikasleen Kontrola & Log
              </RouterLink>
            </template>
          </div>
        </div>

        <div style="display: flex; align-items: center; gap: 20px;">
          <div style="text-align: right;">
            <p style="color: #818cf8; font-size: 10px; font-weight: 900; margin: 0; text-transform: uppercase; letter-spacing: 1px;">
              {{ user?.rola || 'Kargatzen...' }}
            </p>
            <p style="color: white; font-size: 14px; font-weight: bold; margin: 0;">{{ user?.name }}</p>
          </div>
          <button @click="logout" 
            style="background-color: #e11d48; color: white; padding: 10px 20px; border-radius: 12px; font-weight: 900; border: none; border-bottom: 4px solid #9f1239; cursor: pointer; font-size: 10px;">
            LOG OUT
          </button>
        </div>

      </div>
    </nav>

    <main :class="route.path === '/login' ? '' : 'max-w-7xl mx-auto py-12 px-6'" 
          style="max-width: 1280px; margin: 0 auto; padding: 40px 20px;">
      <RouterView />
    </main>

  </div>
</template>

<style scoped>
.nav-link {
  color: white;
  text-decoration: none;
  font-weight: bold;
  font-size: 14px;
  transition: color 0.3s;
}
.nav-link:hover {
  color: #818cf8;
}
.router-link-active {
  color: #6366f1 !important;
}
</style>