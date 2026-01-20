<script setup>
import { RouterLink, RouterView, useRouter } from 'vue-router'
import { ref, onMounted } from 'vue'

const router = useRouter()
const isLoggedIn = ref(false)

// Comprobamos si hay un token al cargar la página
onMounted(() => {
  isLoggedIn.value = !!localStorage.getItem('token')
})

const logout = () => {
  localStorage.removeItem('token')
  isLoggedIn.value = false
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
              <RouterLink v-if="isLoggedIn" to="/hitzorduak" class="hover:text-indigo-200 transition font-semibold">Nire Hitzorduak</RouterLink>
            </div>
          </div>

          <div class="flex items-center space-x-4">
            <template v-if="!isLoggedIn">
              <RouterLink to="/login" class="bg-white text-indigo-700 hover:bg-indigo-50 px-6 py-2.5 rounded-full text-sm font-bold shadow-md transition-all active:scale-95">
                LOG IN
              </RouterLink>
            </template>
            <template v-else>
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
.router-link-active {
  color: #c7d2fe; /* Este es el equivalente a indigo-200 */
  border-bottom: 2px solid #c7d2fe;
}
</style>