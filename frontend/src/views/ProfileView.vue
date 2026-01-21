<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const user = ref({ name: '', email: '', rola: '', argazkia: '' })
const password = ref('')
const password_confirmation = ref('')
const fitxategia = ref(null)

// Cambia esto si tu backend corre en otro puerto
const API_URL = 'http://localhost:8000'

const kargatu = async () => {
  const token = localStorage.getItem('token')
  try {
    const res = await axios.get('user', { headers: { Authorization: `Bearer ${token}` } })
    user.value = res.data
  } catch (e) {
    console.error("Errorea kargatzean", e)
  }
}

const gorde = async () => {
  const token = localStorage.getItem('token')
  const fd = new FormData()
  
  fd.append('name', user.value.name)
  fd.append('email', user.value.email)
  
  if (password.value.trim() !== '') {
    fd.append('password', password.value)
    fd.append('password_confirmation', password_confirmation.value)
  }
  
  if (fitxategia.value) {
    fd.append('argazkia', fitxategia.value)
  }

  try {
    const res = await axios.post('profile/update', fd, {
      headers: { 
        'Content-Type': 'multipart/form-data', 
        Authorization: `Bearer ${token}` 
      }
    })
    
    alert("Datuak ondo gorde dira!")
    user.value = res.data.user
    password.value = ''
    password_confirmation.value = ''
    fitxategia.value = null
    
    // Forzamos recarga para ver la foto nueva
    await kargatu()
  } catch (e) {
    alert("Errorea gordetzean")
  }
}

onMounted(kargatu)
</script>

<template>
  <div class="max-w-2xl mx-auto p-6">
    <div class="bg-white rounded-3xl shadow-2xl overflow-hidden border border-gray-100">
      
      <div :class="['p-8 text-center text-white transition-colors duration-500', user.rola === 'irakasle' ? 'bg-indigo-700' : 'bg-emerald-600']">
        <div class="relative inline-block">
          <img 
            :src="user.argazkia ? (API_URL + user.argazkia) : ('https://ui-avatars.com/api/?name=' + user.name)" 
            class="w-32 h-32 rounded-full border-4 border-white mx-auto object-cover shadow-lg mb-4 bg-gray-100"
            @error="(e) => e.target.src = 'https://ui-avatars.com/api/?name=' + user.name"
          >
        </div>
        <h2 class="text-2xl font-black uppercase tracking-widest">{{ user.name }}</h2>
        <p class="text-xs font-bold bg-black/20 inline-block px-3 py-1 rounded-full mt-2 uppercase">{{ user.rola }}</p>
      </div>

      <div class="p-8 space-y-5">
        <div>
          <label class="text-xs font-bold text-gray-400 uppercase tracking-tighter">Izena</label>
          <input v-model="user.name" type="text" class="w-full border-none bg-gray-50 p-3 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none">
        </div>
        <div>
          <label class="text-xs font-bold text-gray-400 uppercase tracking-tighter">Email</label>
          <input v-model="user.email" type="email" class="w-full border-none bg-gray-50 p-3 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none">
        </div>
        
        <hr class="my-4 border-gray-100">
        
        <p class="text-xs font-black text-gray-400 uppercase">Pasahitz Berria</p>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <input v-model="password" type="password" placeholder="Aldatu nahi baduzu..." class="border-none bg-gray-50 p-3 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500">
          <input v-model="password_confirmation" type="password" placeholder="Errepikatu" class="border-none bg-gray-50 p-3 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500">
        </div>

        <div class="mt-4">
          <p class="text-xs font-black text-gray-400 uppercase mb-2">Profil Argazkia Hautatu</p>
          <input type="file" @change="e => fitxategia = e.target.files[0]" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
        </div>
        
        <button @click="gorde" class="w-full bg-gray-900 text-white py-4 rounded-2xl font-black hover:bg-black transition-all shadow-lg mt-6 uppercase tracking-widest active:scale-95">
          Gorde Aldaketak
        </button>
      </div>
    </div>
  </div>
</template>