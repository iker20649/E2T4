<script setup>
import { RouterLink, RouterView, useRouter, useRoute } from 'vue-router'
import { ref, onMounted, watch } from 'vue'
import axios from 'axios'

// Irudia inportatzeko modu seguruagoa
import logoImg from '@/assets/logoa.png'

const router = useRouter()
const route = useRoute()
const isLoggedIn = ref(false)
const user = ref(null)

const checkLogin = async () => {
  const token = localStorage.getItem('token')
  isLoggedIn.value = !!token
  if (token && !user.value) {
    try {
      const res = await axios.get('user', { headers: { Authorization: `Bearer ${token}` } })
      user.value = res.data
    } catch (error) { logout() }
  } else if (!token) { user.value = null }
}

onMounted(checkLogin)
watch(() => route.path, checkLogin)

const logout = () => {
  localStorage.removeItem('token')
  isLoggedIn.value = false
  user.value = null
  router.push('/login')
}
</script>

<template>
  <div class="min-h-screen w-full bg-[#f1f5f9] text-slate-900 font-sans">
    
    <nav class="bg-[#1e1b4b] text-white shadow-2xl relative z-50 border-b-4 border-indigo-500 transition-all duration-500">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-24 items-center">
          
          <div class="flex items-center space-x-10">
            <RouterLink to="/" class="flex items-center gap-4 group transition-all">
              <div class="relative flex items-center">
                <div class="absolute -inset-2 bg-indigo-500 rounded-full blur-md opacity-0 group-hover:opacity-25 transition duration-500"></div>
                <img 
                  :src="logoImg" 
                  alt="Ilea.App Logo" 
                  class="relative h-16 w-auto object-contain rounded-2xl drop-shadow-2xl transform group-hover:scale-105 transition duration-300"
                >
              </div>
              
              <h1 class="text-2xl font-black tracking-tighter italic uppercase hidden sm:block leading-none">
                Ilea<span class="text-indigo-500">.</span>App
              </h1>
            </RouterLink>
            
            <div v-if="route.path !== '/login'" class="hidden md:flex items-center space-x-6">
              <RouterLink to="/" class="nav-link">Hasiera</RouterLink>
              
              <template v-if="isLoggedIn">
                <RouterLink to="/hitzorduak" class="nav-link">Hitzorduak</RouterLink>
                <RouterLink to="/stock" class="nav-link">📦 Stocka</RouterLink>
                <RouterLink v-if="user?.rola === 'irakasle'" to="/admin/ikasleak" 
                            class="bg-indigo-600/40 hover:bg-indigo-600 px-4 py-2 rounded-xl font-black border border-indigo-400/50 transition-all text-xs uppercase tracking-widest">
                  🎓 KUDEAKETA
                </RouterLink>
              </template>
            </div>
          </div>

          <div class="flex items-center space-x-4">
            <template v-if="isLoggedIn">
              <RouterLink to="/perfila" class="flex items-center gap-3 bg-white/5 hover:bg-white/10 p-2 pr-5 rounded-3xl border border-white/10 transition-all group">
                <img :src="'https://ui-avatars.com/api/?background=6366f1&color=fff&name=' + user?.name" 
                     class="w-10 h-10 rounded-full border-2 border-indigo-400 group-hover:scale-110 transition-transform">
                <div class="flex flex-col text-left">
                  <span class="text-[9px] font-black uppercase text-indigo-300 leading-none">{{ user?.rola }}</span>
                  <span class="text-sm font-bold leading-tight">{{ user?.name.split(' ')[0] }}</span>
                </div>
              </RouterLink>
              
              <button @click="logout" class="bg-rose-600 hover:bg-rose-500 px-6 py-2.5 rounded-2xl text-[10px] font-black shadow-lg transition-all active:scale-95 uppercase tracking-widest border-b-4 border-rose-800">
                LOG OUT
              </button>
            </template>
            
            <template v-else-if="route.path !== '/login'">
              <RouterLink to="/login" class="bg-indigo-600 hover:bg-indigo-500 px-8 py-3 rounded-2xl text-xs font-black shadow-xl transition-all uppercase tracking-widest border-b-4 border-indigo-800">
                Sartu ⚡
              </RouterLink>
            </template>
          </div>

        </div>
      </div>
    </nav>

    <main class="w-full">
      <div class="max-w-7xl mx-auto py-12 px-6">
        <RouterView />
      </div>
    </main>
  </div>
</template>

<style>
body { 
  margin: 0; 
  background-color: #f1f5f9; 
}

.nav-link {
  font-weight: 700;
  color: #94a3b8;
  transition: all 0.3s;
  font-size: 0.9rem;
}

.nav-link:hover {
  color: white;
}

/* Garbiagoa: Aktibo dagoen linkaren estiloa */
.router-link-active:not(.group) { 
  color: white;
  border-bottom: 3px solid #6366f1; 
}

/* Kudeaketa botoiarentzat distira berezia kentzeko aktibo dagoenean */
.bg-indigo-600\/40.router-link-active {
  border-bottom: none;
}
</style>