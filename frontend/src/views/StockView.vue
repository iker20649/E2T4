<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const produktuak = ref([])
const loading = ref(true)

// Objeto para el formulario (Ahora incluye stock_minimo)
const nuevo = ref({
  izena: '',
  marka: '',
  stock: 0,
  stock_minimo: 5, // Valor por defecto
  prezioa: 0
})

// Cargar productos
const kargatuProduktuak = async () => {
  try {
    const token = localStorage.getItem('token')
    const res = await axios.get('produktuak', {
      headers: { Authorization: `Bearer ${token}` }
    })
    produktuak.value = res.data
  } catch (error) {
    console.error("Errorea kargatzean")
  } finally {
    loading.value = false
  }
}

// Guardar producto nuevo
const gordeProduktua = async () => {
  if (!nuevo.value.izena) return alert("Izena beharrezkoa da")
  try {
    const token = localStorage.getItem('token')
    await axios.post('produktuak', nuevo.value, {
      headers: { Authorization: `Bearer ${token}` }
    })
    // Resetear formulario
    nuevo.value = { izena: '', marka: '', stock: 0, stock_minimo: 5, prezioa: 0 }
    kargatuProduktuak()
    alert("✅ Gordeta!")
  } catch (error) {
    alert("Errorea gordetzean. Ziurtatu datu guztiak sartu dituzula.")
  }
}

// Sumar o restar stock
const aldatuStock = async (id, accion) => {
  const token = localStorage.getItem('token')
  await axios.patch(`produktuak/${id}/stock`, { action: accion }, {
    headers: { Authorization: `Bearer ${token}` }
  })
  kargatuProduktuak()
}

// Borrar producto
const ezabatuProduktua = async (id, izena) => {
  if (confirm(`Ziur zaude "${izena}" ezabatu nahi duzula?`)) {
    try {
      const token = localStorage.getItem('token')
      await axios.delete(`produktuak/${id}`, {
        headers: { Authorization: `Bearer ${token}` }
      })
      kargatuProduktuak()
    } catch (error) {
      alert("Ezin izan da ezabatu")
    }
  }
}

onMounted(kargatuProduktuak)
</script>

<template>
  <div class="max-w-7xl mx-auto p-6">
    <h2 class="text-3xl font-black text-indigo-900 mb-8">📦 Inventario Profesionala</h2>

    <div class="bg-white p-8 rounded-2xl shadow-lg mb-10 border-t-4 border-indigo-600">
      <h3 class="text-lg font-bold mb-6 text-gray-700">Produktu Berria Gehitu</h3>
      
      <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
        <div class="flex flex-col">
          <label class="text-[10px] font-bold text-gray-400 mb-1 uppercase">Produktua</label>
          <input v-model="nuevo.izena" type="text" placeholder="Xanpua..." class="p-3 border rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
        </div>
        <div class="flex flex-col">
          <label class="text-[10px] font-bold text-gray-400 mb-1 uppercase">Marka</label>
          <input v-model="nuevo.marka" type="text" placeholder="L'Oreal..." class="p-3 border rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
        </div>
        <div class="flex flex-col">
          <label class="text-[10px] font-bold text-gray-400 mb-1 uppercase">Prezioa (€)</label>
          <input v-model="nuevo.prezioa" type="number" step="0.01" class="p-3 border rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
        </div>
        <div class="flex flex-col">
          <label class="text-[10px] font-bold text-gray-400 mb-1 uppercase">Stock</label>
          <input v-model="nuevo.stock" type="number" class="p-3 border rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
        </div>
        <div class="flex flex-col">
          <label class="text-[10px] font-bold text-gray-400 mb-1 uppercase">Minimoa</label>
          <input v-model="nuevo.stock_minimo" type="number" class="p-3 border rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
        </div>
        <div class="flex flex-col justify-end">
          <button @click="gordeProduktua" class="bg-indigo-600 text-white font-bold py-3 rounded-xl hover:bg-indigo-700 transition shadow-md uppercase text-xs">
            Gorde
          </button>
        </div>
      </div>
    </div>

    <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-200">
      <table class="w-full text-left">
        <thead class="bg-gray-800 text-white text-[11px] uppercase tracking-wider">
          <tr>
            <th class="p-4">Info</th>
            <th class="p-4 text-center">Prezioa</th>
            <th class="p-4 text-center">Stock</th>
            <th class="p-4 text-center">Minimoa</th>
            <th class="p-4 text-center">Aldatu</th>
            <th class="p-4 text-center">Kudeatu</th>
          </tr>
        </thead>
        <tbody class="divide-y">
          <tr v-for="p in produktuak" :key="p.id" :class="{'bg-red-50': p.stock <= p.stock_minimo}" class="hover:bg-gray-50 transition">
            <td class="p-4">
              <div class="font-bold text-gray-800">{{ p.izena }}</div>
              <div class="text-[11px] text-gray-400 uppercase tracking-widest">{{ p.marka || 'Marka gabe' }}</div>
            </td>
            <td class="p-4 text-center font-mono text-gray-600">
              {{ p.prezioa }}€
            </td>
            <td class="p-4 text-center">
              <span class="text-2xl font-black" :class="p.stock <= p.stock_minimo ? 'text-red-600' : 'text-gray-700'">{{ p.stock }}</span>
            </td>
            <td class="p-4 text-center text-gray-400 font-bold">
              {{ p.stock_minimo }}
            </td>
            <td class="p-4">
              <div class="flex justify-center gap-2">
                <button @click="aldatuStock(p.id, 'sub')" class="w-8 h-8 bg-red-100 text-red-600 rounded-lg font-bold hover:bg-red-600 hover:text-white transition">-</button>
                <button @click="aldatuStock(p.id, 'add')" class="w-8 h-8 bg-green-100 text-green-600 rounded-lg font-bold hover:bg-green-600 hover:text-white transition">+</button>
              </div>
            </td>
            <td class="p-4 text-center">
              <button @click="ezabatuProduktua(p.id, p.izena)" class="bg-red-500 hover:bg-red-700 text-white px-3 py-2 rounded-lg font-bold transition flex items-center gap-2 mx-auto text-xs">
                <span>🗑️</span> EZABATU
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>