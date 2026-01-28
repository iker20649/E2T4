<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const produktuak = ref([])
const loading = ref(true)
const userRola = ref('')

const nuevo = ref({
  izena: '',
  marka: '',
  stock: 0,
  stock_minimo: 5,
  prezioa: 0
})

// Configuración de seguridad para Sanctum
const token = localStorage.getItem('token');
if (token) {
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
}
axios.defaults.baseURL = 'http://127.0.0.1';
const kargatuProduktuak = async () => {
  try {
    // CAMBIO: Ya no usamos el token de localStorage. Sanctum usa la Cookie de sesión.
    // CAMBIO: Aseguramos que la ruta empieza por /api/ para evitar duplicados
    const [resProd, resUser] = await Promise.all([
      axios.get('/api/produktuak'),
      axios.get('/api/user')
    ])
    
    produktuak.value = resProd.data
    const rolaDatu = resUser.data.rola || ''
    userRola.value = rolaDatu.toLowerCase().trim()
    
  } catch (error) {
    console.error("Errorea kargatzean:", error)
    if (error.response && error.response.status === 401) {
       console.warn("Sesioa iraungi da edo ez dago baimenduta");
    }
  } finally {
    loading.value = false
  }
}

const gordeProduktua = async () => {
  if (!nuevo.value.izena || nuevo.value.prezioa < 0) {
    return alert("Izena beharrezkoa da eta prezioa ezin da negatiboa izan.")
  }
  try {
    // CAMBIO: Ruta con /api/ y sin headers de Authorization
    await axios.post('/api/produktuak', nuevo.value)
    
    nuevo.value = { izena: '', marka: '', stock: 0, stock_minimo: 5, prezioa: 0 }
    kargatuProduktuak()
    alert("✅ Produktu berria ondo gordeta!")
  } catch (error) {
    alert("Ezin izan da produktua gorde.")
  }
}

const aldatuStock = async (id, kantitatea, ekintza) => {
  try {
    // CAMBIO: Ruta corregida /api/
    await axios.patch(`/api/produktuak/${id}/stock`, { kantitatea, ekintza })
    kargatuProduktuak()
  } catch (error) {
    alert("Ezin izan da stock-a eguneratu")
  }
}

const ezabatuProduktua = async (id, izena) => {
  if (confirm(`Ziur zaude "${izena}" ezabatu nahi duzula?`)) {
    try {
      // CAMBIO: Ruta corregida /api/
      await axios.delete(`/api/produktuak/${id}`)
      kargatuProduktuak()
    } catch (error) {
      alert("Ezin izan da ezabatu")
    }
  }
}

onMounted(kargatuProduktuak)
</script>

<template>
  <div v-if="!loading" class="max-w-7xl mx-auto space-y-12 animate-fade-in pb-20 pt-10 px-4">
    <div class="relative flex flex-col md:flex-row justify-between items-center md:items-end bg-white p-8 md:p-12 rounded-[2rem] md:rounded-[3.5rem] shadow-xl border-b-[10px] border-indigo-600 gap-6 overflow-hidden">
      <div class="relative z-10 text-center md:text-left">
        <h2 class="text-5xl md:text-7xl font-black text-indigo-950 uppercase tracking-tighter italic leading-none">📦 Stocka</h2>
        <p class="text-indigo-500 font-black uppercase tracking-[0.2em] text-xs mt-4 ml-2">Inbentarioaren Kudeaketa Orokorra</p>
      </div>
      <div class="relative z-10 bg-slate-900 text-white px-8 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-lg border border-indigo-500/30">
        {{ userRola === 'irakasle' ? 'ADMIN MODUA' : 'IKASLE MODUA' }}
      </div>
    </div>

    <div class="bg-indigo-600 p-8 md:p-12 rounded-[2.5rem] md:rounded-[4rem] shadow-2xl relative overflow-hidden group">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
            <div class="flex flex-col space-y-2">
                <label class="text-[10px] font-black text-indigo-100 uppercase tracking-widest ml-4">Produktua</label>
                <input v-model="nuevo.izena" type="text" class="p-4 md:p-5 bg-white/20 border-2 border-transparent rounded-[1.5rem] md:rounded-[2rem] focus:bg-white focus:text-slate-900 focus:border-white outline-none transition-all font-bold text-white placeholder-white/50" placeholder="Izena...">
            </div>
            <div class="flex flex-col space-y-2">
                <label class="text-[10px] font-black text-indigo-100 uppercase tracking-widest ml-4">Prezioa (€)</label>
                <input v-model="nuevo.prezioa" type="number" step="0.01" class="p-4 md:p-5 bg-white/20 border-2 border-transparent rounded-[1.5rem] md:rounded-[2rem] focus:bg-white focus:text-slate-900 focus:border-white outline-none transition-all font-bold text-white">
            </div>
            <div class="flex flex-col space-y-2">
                <label class="text-[10px] font-black text-indigo-100 uppercase tracking-widest ml-4">Stock Hasiera</label>
                <input v-model="nuevo.stock" type="number" class="p-4 md:p-5 bg-white/20 border-2 border-transparent rounded-[1.5rem] md:rounded-[2rem] focus:bg-white focus:text-slate-900 focus:border-white outline-none transition-all font-bold text-white">
            </div>
            <div class="flex flex-col space-y-2">
                <label class="text-[10px] font-black text-indigo-100 uppercase tracking-widest ml-4">Stock Minimoa</label>
                <input v-model="nuevo.stock_minimo" type="number" class="p-4 md:p-5 bg-white/20 border-2 border-transparent rounded-[1.5rem] md:rounded-[2rem] focus:bg-white focus:text-slate-900 focus:border-white outline-none transition-all font-bold text-white">
            </div>
            <div class="flex flex-col justify-end">
                <button @click="gordeProduktua" class="bg-slate-900 hover:bg-black text-white font-black py-4 md:py-5 rounded-[1.5rem] md:rounded-[2rem] transition-all shadow-xl uppercase text-xs tracking-widest active:scale-95">
                    Gorde Materiala
                </button>
            </div>
        </div>
    </div>

    <div class="bg-white shadow-[0_40px_80px_-15px_rgba(0,0,0,0.15)] rounded-[2rem] md:rounded-[4.5rem] overflow-x-auto border border-indigo-50 relative">
      <table class="w-full text-left border-collapse min-w-[1000px] relative z-10">
        <thead class="bg-slate-900 text-slate-400 text-[11px] uppercase tracking-[0.4em] font-black">
          <tr>
            <th class="p-8 md:p-12">Produktua</th>
            <th class="p-8 md:p-12 text-center">Prezioa</th>
            <th class="p-8 md:p-12 text-center">Egoera</th>
            <th class="p-8 md:p-12 text-right">Kudeaketa</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-indigo-50">
          <tr v-for="p in produktuak" :key="p.id" class="hover:bg-indigo-50/40 transition-all group">
            <td class="p-8 md:p-12">
              <div class="font-black text-slate-800 uppercase text-3xl md:text-4xl italic tracking-tighter leading-none">{{ p.izena }}</div>
              <div class="text-[10px] text-indigo-500 font-black uppercase tracking-[0.2em] mt-3 ml-1">{{ p.marka || 'Stock Orokorra' }}</div>
            </td>
            <td class="p-8 md:p-12 text-center">
              <span class="text-xl md:text-2xl font-black text-slate-900 bg-slate-100 px-6 md:px-8 py-3 md:py-4 rounded-[1.5rem] md:rounded-[2rem] border-2 border-slate-200 shadow-inner">
                {{ Number(p.prezioa).toFixed(2) }}€
              </span>
            </td>
            <td class="p-8 md:p-12 text-center">
              <div class="flex flex-col items-center">
                <span :class="p.stock <= p.stock_minimo ? 'text-rose-600 bg-rose-50 border-rose-200' : 'text-emerald-600 bg-emerald-50 border-emerald-200'" 
                      class="text-4xl md:text-6xl font-black px-8 md:px-12 py-4 md:py-6 rounded-[2rem] md:rounded-[3rem] border-4 shadow-sm min-w-[120px] md:min-w-[160px]">
                  {{ p.stock }}
                </span>
              </div>
            </td>
            <td class="p-8 md:p-12 text-right">
                <div class="flex justify-end gap-3">
                    <button @click="aldatuStock(p.id, -1, 'Erabilita')" class="px-6 py-4 bg-white border-4 border-rose-100 text-rose-500 rounded-2xl font-black text-[10px] hover:bg-rose-500 hover:text-white uppercase transition-all">-1 Erabili</button>
                    <button @click="aldatuStock(p.id, 1, 'Erosita')" class="px-6 py-4 bg-white border-4 border-emerald-100 text-emerald-500 rounded-2xl font-black text-[10px] hover:bg-emerald-500 hover:text-white uppercase transition-all">+1 Gehitu</button>
                    <button @click="ezabatuProduktua(p.id, p.izena)" v-if="userRola === 'irakasle'" class="text-3xl hover:scale-125 transition">🗑️</button>
                </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <div v-else class="h-screen w-full flex flex-col items-center justify-center space-y-6">
    <div class="w-24 h-24 border-8 border-indigo-600 border-t-transparent rounded-full animate-spin"></div>
    <p class="text-indigo-950 font-black uppercase italic tracking-widest animate-pulse">Materiala kargatzen...</p>
  </div>
</template>