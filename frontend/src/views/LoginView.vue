<script setup>
import { ref } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'

const email = ref('')
const password = ref('')
const router = useRouter()

const login = async () => {
  try {
    const response = await axios.post('login', {
      email: email.value,
      password: password.value
    })
    
    localStorage.setItem('token', response.data.token)
    axios.defaults.headers.common['Authorization'] = `Bearer ${response.data.token}`
    
    alert('🔥 Login zuzena! Ongi etorri.')
    window.location.href = '/' // Recargamos para que el Navbar se actualice
  } catch (error) {
    alert('Errorea: Emaila edo pasahitza ez dira zuzenak')
  }
}
</script>

<template>
  <div class="max-w-md mx-auto mt-10 p-8 bg-white rounded-2xl shadow-xl border border-gray-100">
    <h2 class="text-3xl font-bold text-center text-indigo-700 mb-8">Hasi Saioa</h2>
    <form @submit.prevent="login" class="space-y-5">
      <div>
        <label class="block text-sm font-semibold text-gray-700">Emaila</label>
        <input v-model="email" type="email" class="mt-1 w-full px-4 py-3 bg-gray-50 border rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none" placeholder="adibidea@email.com">
      </div>
      <div>
        <label class="block text-sm font-semibold text-gray-700">Pasahitza</label>
        <input v-model="password" type="password" class="mt-1 w-full px-4 py-3 bg-gray-50 border rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none" placeholder="••••••••">
      </div>
      <button type="submit" class="w-full py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition shadow-lg">SARTU</button>
    </form>
  </div>
</template>