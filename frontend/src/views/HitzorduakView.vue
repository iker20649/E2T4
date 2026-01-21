<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const hitzorduak = ref([])
const berria = ref({ data: '', ordua: '', bezero_id: '', izena: '' })
const editatzen = ref(null)

const kargatu = async () => {
  const res = await axios.get('hitzorduak', { headers: { Authorization: `Bearer ${localStorage.getItem('token')}` } })
  hitzorduak.value = res.data
}

const gorde = async () => {
  await axios.post('hitzorduak', berria.value, { headers: { Authorization: `Bearer ${localStorage.getItem('token')}` } })
  berria.value = { data: '', ordua: '', bezero_id: '', izena: '' }
  kargatu()
}

const toggleHecho = async (h) => {
  h.finalizatuta = !h.finalizatuta
  await axios.put(`hitzorduak/${h.id}`, { finalizatuta: h.finalizatuta }, { headers: { Authorization: `Bearer ${localStorage.getItem('token')}` } })
}

const ezabatu = async (id) => {
  if (confirm("Ziur?")) {
    await axios.delete(`hitzorduak/${id}`, { headers: { Authorization: `Bearer ${localStorage.getItem('token')}` } })
    kargatu()
  }
}

const eguneratu = async () => {
  await axios.put(`hitzorduak/${editatzen.value.id}`, editatzen.value, { headers: { Authorization: `Bearer ${localStorage.getItem('token')}` } })
  editatzen.value = null
  kargatu()
}

onMounted(kargatu)
</script>

<template>
  <div class="max-w-5xl mx-auto p-6">
    <h2 class="text-3xl font-black mb-8">📅 Hitzorduak</h2>

    <div class="bg-white p-6 rounded-2xl shadow mb-8 flex gap-3 items-end border-t-4 border-green-500">
      <input v-model="berria.data" type="date" class="border p-2 rounded-lg">
      <input v-model="berria.ordua" type="time" class="border p-2 rounded-lg">
      <input v-model="berria.bezero_id" type="number" placeholder="ID" class="border p-2 rounded-lg w-20">
      <input v-model="berria.izena" type="text" placeholder="Izen berria" class="border p-2 rounded-lg">
      <button @click="gorde" class="bg-green-600 text-white px-4 py-2 rounded-lg font-bold">Gorde</button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <div v-for="h in hitzorduak" :key="h.id" 
           :class="['p-5 rounded-2xl shadow border-l-4 transition relative group', h.finalizatuta ? 'bg-gray-100 border-gray-400 opacity-60' : 'bg-white border-indigo-500']">
        
        <div class="absolute top-2 right-2 flex gap-1 opacity-0 group-hover:opacity-100 transition">
          <button @click="editatzen = {...h}" class="text-blue-500">✏️</button>
          <button @click="ezabatu(h.id)" class="text-red-500">🗑️</button>
        </div>

        <input type="checkbox" :checked="h.finalizatuta" @change="toggleHecho(h)" class="mb-2 w-5 h-5 accent-green-600">
        <p class="text-xs font-bold text-gray-400">{{ h.data }}</p>
        <p :class="['text-2xl font-black', h.finalizatuta ? 'line-through' : '']">{{ h.ordua.substring(0,5) }}</p>
        <p class="text-xs mt-2 font-bold text-indigo-600">Bezeroa: #{{ h.bezero_id }}</p>
      </div>
    </div>

    <div v-if="editatzen" class="fixed inset-0 bg-black/50 flex items-center justify-center p-4">
      <div class="bg-white p-6 rounded-2xl w-full max-w-sm">
        <h3 class="font-bold mb-4">Editatu</h3>
        <input v-model="editatzen.data" type="date" class="w-full border p-2 mb-2 rounded">
        <input v-model="editatzen.ordua" type="time" class="w-full border p-2 mb-4 rounded">
        <div class="flex gap-2">
          <button @click="eguneratu" class="bg-indigo-600 text-white flex-1 py-2 rounded font-bold">Gorde</button>
          <button @click="editatzen = null" class="bg-gray-200 flex-1 py-2 rounded">Utzi</button>
        </div>
      </div>
    </div>
  </div>
</template>