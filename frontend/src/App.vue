<script setup>
import { RouterLink, RouterView, useRouter, useRoute } from 'vue-router'
import { ref, onMounted, watch } from 'vue'
import axios from 'axios'

const router = useRouter()
const route = useRoute()
const isLoggedIn = ref(false)
const user = ref(null)

// Función para revisar si el usuario está logueado y obtener sus datos
const checkLogin = async () => {
  const token = localStorage.getItem('token')
  isLoggedIn.value = !!token
  
  if (token && !user.value) {
    try {
      const res = await axios.get('user', {
        headers: { Authorization: `Bearer ${token}` }
      })
      user.value = res.data
    } catch (error) {
      // Si el token es inválido o expiró
      logout()
    }
  } else if (!token) {
    user.value = null
  }
}

onMounted(checkLogin)

// Vigilamos la ruta: si cambia, comprobamos el estado
watch(() => route.path, checkLogin)

const logout = () => {
  localStorage.removeItem('token')
  isLoggedIn.value = false
  user.value = null
  alert('Saioa itxi duzu. Hurrengora arte!')
  router.push('/login')
}
</script>

<template>
  <div class="min-h-screen bg-gray-50 text-gray-900 font-sans">
    <nav class="bg-indigo-700 text-white shadow-xl">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20 items-center">
          
          <div class="flex items-center space-x-10">
            <h1 class="text-2xl font-black tracking-tighter flex items-center gap-2">
              <span class="text-3xl">💇‍♂️</span> Ilea-App
            </h1>
            <div class="hidden md:flex space-x-6">
              <RouterLink to="/" class="hover:text-indigo-200 transition font-semibold">Hasiera</RouterLink>
              
              <template v-if="isLoggedIn">
                <RouterLink to="/hitzorduak" class="hover:text-indigo-200 transition font-semibold">Nire Hitzorduak</RouterLink>
                <RouterLink to="/stock" class="hover:text-indigo-200 transition font-semibold flex items-center gap-1">
                   📦 Stocka
                </RouterLink>
              </template>
            </div>
          </div>

          <div class="flex items-center space-x-4">
            <template v-if="!isLoggedIn">
              <RouterLink to="/login" class="bg-white text-indigo-700 hover:bg-indigo-50 px-6 py-2.5 rounded-full text-sm font-bold shadow-md transition-all active:scale-95">
                LOG IN
              </RouterLink>
            </template>
            <template v-else>
              <RouterLink to="/perfila" class="flex items-center gap-3 hover:bg-indigo-800 transition p-1.5 pr-4 rounded-full border border-indigo-400/30 bg-indigo-800/50">
                <img 
                  :src="user?.argazkia ? 'http://localhost:8000' + user.argazkia : 'https://ui-avatars.com/api/?name=' + user?.name" 
                  class="w-8 h-8 rounded-full border border-white/50 object-cover"
                >
                <span class="text-sm font-bold">{{ user?.name || 'Erabiltzailea' }}</span>
              </RouterLink>

              <button @click="logout" class="bg-red-500 hover:bg-red-600 text-white px-6 py-2.5 rounded-full text-sm font-bold shadow-md transition-all active:scale-95">
                LOG OUT
              </button>
            </template>
          </div>

        </div>
      </div>
    </nav>

    <main class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
      <div class="transition-all duration-300">
        <RouterView />
      </div>
    </main>
  </div>
</template>

<style>
/* Estilo para el link activo */
.router-link-active {
  color: #c7d2fe;
}
.md\:flex .router-link-active {
  border-bottom: 2px solid #c7d2fe;
}
</style>