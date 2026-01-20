<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const hitzorduak = ref([])
const loading = ref(true)

onMounted(async () => {
  try {
    // 1. Recuperamos el token del localStorage
    const token = localStorage.getItem('token')
    
    // 2. Llamada a la API con el Token de seguridad
    const response = await axios.get('hitzorduak', {
      headers: {
        Authorization: `Bearer ${token}`
      }
    })
    
    // 3. Guardamos los datos
    hitzorduak.value = response.data
    console.log("Datos recibidos:", response.data)
  } catch (error) {
    console.error("Datuak ezin izan dira kargatu:", error)
  } finally {
    // 4. Quitamos el estado de carga
    loading.value = false
  }
})
</script>

<template>
  <div class="max-w-4xl mx-auto p-6">
    <h2 class="text-2xl font-bold mb-6 text-gray-800 border-b pb-2">Nire Hitzorduak</h2>
    
    <div v-if="loading" class="text-center py-10 text-indigo-600 font-bold text-xl">
      Kargatzen...
    </div>
    
    <div v-else-if="hitzorduak.length > 0" class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200">
      <table class="w-full text-left">
        <thead class="bg-indigo-600 text-white font-bold">
          <tr>
            <th class="p-4 uppercase text-sm tracking-wider">Data</th>
            <th class="p-4 uppercase text-sm tracking-wider">Ordua</th>
            <th class="p-4 uppercase text-sm tracking-wider">Oharrak</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          <tr v-for="h in hitzorduak" :key="h.id" class="hover:bg-indigo-50 transition-colors">
            <td class="p-4 font-medium text-gray-700">{{ h.data }}</td>
            <td class="p-4 text-gray-600">{{ h.hasiera_ordua }}</td>
            <td class="p-4 text-gray-500 italic">{{ h.oharrak || 'Zerbitzurik gabe' }}</td>
          </tr>
        </tbody>
      </table>
    </div>

    <div v-else class="p-10 text-center text-gray-500 bg-gray-50 border-2 border-dashed border-gray-300 rounded-xl">
      <p class="text-lg">Ez duzu hitzordurik gordeta.</p>
    </div>
  </div>
</template>