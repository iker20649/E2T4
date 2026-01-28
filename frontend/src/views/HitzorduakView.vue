<script setup>
import { ref, onMounted, computed } from 'vue'
import axios from 'axios'

// Configuración de Axios para usar la sesión (Puerto 80 por defecto)
const token = localStorage.getItem('token');
if (token) {
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
}
axios.defaults.baseURL = 'http://127.0.0.1';
const hitzorduak = ref([])
const ikasleak = ref([]) 
const user = ref(null)
const berria = ref({ bezeroa: '', data: '', deskribapena: '' })
const kargatzen = ref(true)
const ikasleIragazkia = ref('')

const kargatuDatuak = async () => {
  kargatzen.value = true
  try {
    // CAMBIO: Eliminamos localStorage y usamos rutas con el prefijo /api/
    const [resUser, resHitz] = await Promise.all([
      axios.get('/api/user'),
      axios.get('/api/hitzorduak')
    ])
    
    user.value = resUser.data
    hitzorduak.value = resHitz.data

    // Si es irakasle, cargamos la lista de alumnos para el filtro
    if (user.value.rola && user.value.rola.toLowerCase().includes('irakasle')) {
      const resIkas = await axios.get('/api/admin/ikasleak')
      ikasleak.value = resIkas.data
    }
  } catch (e) {
    console.error("Errorea kargatzean:", e)
  } finally {
    kargatzen.value = false
  }
}

const hitzorduIragaziak = computed(() => {
  if (!ikasleIragazkia.value) return hitzorduak.value
  return hitzorduak.value.filter(h => Number(h.user_id) === Number(ikasleIragazkia.value))
})

const gordeHitzordua = async () => {
  if (!berria.value.bezeroa || !berria.value.data) {
    alert("Izena eta data beharrezkoak dira")
    return
  }

  try {
    // CAMBIO: Petición POST simplificada sin headers manuales
    await axios.post('/api/hitzorduak', berria.value)
    
    alert("✅ Hitzordua ondo gorde da!")
    berria.value = { bezeroa: '', data: '', deskribapena: '' }
    await kargatuDatuak()
  } catch (e) { 
    alert("Errorea gordetzean") 
  }
}

const ezabatu = async (id) => {
  if (!confirm("Ziur zaude?")) return
  try {
    // CAMBIO: Ruta DELETE actualizada
    await axios.delete(`/api/hitzorduak/${id}`)
    await kargatuDatuak()
  } catch (e) {
    console.error("Errorea ezabatzean:", e)
  }
}

const formatua = (dataString) => {
  if (!dataString) return '---'
  const d = new Date(dataString)
  return d.toLocaleString('eu-ES', {
    day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit'
  })
}

onMounted(kargatuDatuak)
</script>

<template>
  <div v-if="!kargatzen" class="max-w-7xl mx-auto space-y-12 animate-fade-in pb-20 pt-10 px-4">
    
    <header class="bg-white p-12 rounded-[3.5rem] shadow-xl border-b-[12px] border-indigo-600 flex justify-between items-center relative overflow-hidden">
      <div class="relative z-10">
        <h1 class="text-6xl font-black italic uppercase tracking-tighter text-indigo-950 leading-none">
          Agenda <span class="text-indigo-600">Zentroa</span>
        </h1>
        <p class="text-indigo-400 font-black uppercase text-sm tracking-[0.3em] mt-4">Hitzorduen kudeaketa profesionala</p>
      </div>
      <div v-if="user" class="hidden md:block relative z-10">
        <span class="bg-indigo-600 text-white px-8 py-4 rounded-3xl text-xs font-black uppercase shadow-lg shadow-indigo-200 tracking-widest">
          {{ user.rola }} modua
        </span>
      </div>
      <div class="absolute right-[-2rem] top-1/2 -translate-y-1/2 text-[12rem] opacity-[0.03] font-black italic select-none pointer-events-none">AGENDA</div>
    </header>

    <section v-if="user?.rola?.toLowerCase().includes('irakasle')" 
             class="bg-indigo-950 p-10 rounded-[3.5rem] shadow-2xl flex flex-col md:flex-row items-center justify-between gap-8 border-t-8 border-indigo-500">
      <div class="flex items-center gap-6">
        <div class="bg-indigo-500 text-white p-5 rounded-3xl shadow-lg text-3xl">📅</div>
        <div>
          <h3 class="font-black text-white uppercase text-xl italic tracking-tighter">Ikaslearen Agenda</h3>
          <p class="text-indigo-300 text-xs font-bold uppercase tracking-widest mt-1">Aukeratu ikasle bat bere lana ikusteko</p>
        </div>
      </div>
      
      <div class="w-full md:w-96">
        <select v-model="ikasleIragazkia" 
          class="w-full p-6 bg-white/10 text-white rounded-[2rem] border-2 border-white/10 shadow-md focus:border-indigo-400 transition-all font-black text-lg outline-none cursor-pointer appearance-none">
          <option value="" class="text-indigo-900">✨ Ikasle guztienak</option>
          <option v-for="i in ikasleak" :key="i.id" :value="i.id" class="text-indigo-900 italic font-bold">🎓 {{ i.name }}</option>
        </select>
      </div>
    </section>

    <section v-if="user?.rola?.toLowerCase().includes('ikasle') && !user?.rola?.toLowerCase().includes('irakasle')" 
             class="bg-slate-900 p-12 rounded-[4rem] shadow-2xl relative overflow-hidden text-white border-b-8 border-indigo-500">
      <h2 class="text-4xl font-black mb-10 italic uppercase flex items-center gap-4 relative z-10">
        <span class="bg-indigo-500 p-3 rounded-2xl">➕</span> Hitzordu Berria
      </h2>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-8 relative z-10">
        <div class="space-y-2">
          <label class="text-[10px] font-black text-indigo-300 uppercase tracking-widest ml-4">Bezeroa</label>
          <input v-model="berria.bezeroa" type="text" placeholder="Izen-abizenak..." 
                 class="w-full p-6 bg-white/10 rounded-3xl border-2 border-white/10 focus:border-indigo-500 outline-none text-xl font-bold transition-all">
        </div>
        <div class="space-y-2">
          <label class="text-[10px] font-black text-indigo-300 uppercase tracking-widest ml-4">Data eta Ordua</label>
          <input v-model="berria.data" type="datetime-local" 
                 class="w-full p-6 bg-white/10 rounded-3xl border-2 border-white/10 focus:border-indigo-500 outline-none text-xl font-bold transition-all">
        </div>
        <div class="md:col-span-2 space-y-2">
          <label class="text-[10px] font-black text-indigo-300 uppercase tracking-widest ml-4">Zerbitzuaren Deskribapena</label>
          <textarea v-model="berria.deskribapena" placeholder="Zerbitzu xehetasunak (mozketa, kolorea...)" 
                    class="w-full p-6 bg-white/10 rounded-3xl border-2 border-white/10 focus:border-indigo-500 outline-none h-32 text-xl font-bold transition-all"></textarea>
        </div>
        <button @click="gordeHitzordua" 
                class="md:col-span-2 bg-indigo-500 hover:bg-indigo-400 text-white font-black py-6 rounded-[2rem] transition-all shadow-[0_0_30px_rgba(99,102,241,0.3)] uppercase tracking-widest active:scale-95">
          Gorde Hitzordua Sistema Nagusian 🚀
        </button>
      </div>
      <div class="absolute -right-10 -bottom-10 text-[12rem] text-white/5 font-black italic select-none pointer-events-none">BERRIA</div>
    </section>

    <section class="bg-white rounded-[4rem] shadow-2xl overflow-hidden border border-indigo-50">
      <div v-if="hitzorduIragaziak.length === 0" class="p-32 text-center">
        <span class="text-8xl block mb-6 animate-bounce">📭</span>
        <p class="text-slate-400 font-black uppercase tracking-[0.5em]">Hitzordurik gabe</p>
      </div>
      
      <div v-else class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead class="bg-indigo-950 text-indigo-200 text-[11px] uppercase tracking-[0.4em] font-black">
            <tr>
              <th class="p-10 text-center">Data / Ordua</th>
              <th class="p-10">Bezeroa</th>
              <th v-if="user?.rola?.toLowerCase().includes('irakasle')" class="p-10">Ikaslea</th>
              <th class="p-10">Zerbitzua</th>
              <th class="p-10 text-right">Ekintzak</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-indigo-50">
            <tr v-for="h in hitzorduIragaziak" :key="h.id" class="hover:bg-indigo-50/50 transition-all group">
              <td class="p-10 text-center">
                <div class="bg-indigo-50 p-4 rounded-3xl border-2 border-indigo-100 group-hover:bg-white transition-colors">
                  <div class="text-2xl font-black text-indigo-900 leading-none tracking-tighter">{{ formatua(h.data).split(',')[0] }}</div>
                  <div class="text-sm font-black text-indigo-500 uppercase mt-1 tracking-widest">{{ formatua(h.data).split(',')[1] }}</div>
                </div>
              </td>
              <td class="p-10">
                <div class="text-3xl font-black text-slate-800 uppercase italic tracking-tighter">{{ h.bezeroa }}</div>
              </td>
              <td v-if="user?.rola?.toLowerCase().includes('irakasle')" class="p-10">
                <span class="bg-indigo-100 text-indigo-600 px-6 py-2 rounded-2xl text-[10px] font-black uppercase border-2 border-indigo-200 shadow-sm">
                  🎓 {{ h.user?.name || 'Zehaztu gabe' }}
                </span>
              </td>
              <td class="p-10">
                <p class="text-slate-400 font-bold italic text-lg leading-tight max-w-xs">{{ h.deskribapena || '---' }}</p>
              </td>
              <td class="p-10 text-right">
                <button @click="ezabatu(h.id)" 
                        class="px-6 py-3 bg-white border-4 border-rose-100 text-rose-500 rounded-2xl font-black text-xs hover:bg-rose-500 hover:text-white transition-all active:scale-90 uppercase tracking-widest shadow-sm">
                  Ezabatu
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </section>

  </div>

  <div v-else class="h-screen w-full flex flex-col items-center justify-center space-y-6">
    <div class="w-24 h-24 border-8 border-indigo-600 border-t-transparent rounded-full animate-spin shadow-2xl"></div>
    <p class="text-indigo-900 font-black uppercase italic tracking-[0.3em] animate-pulse">Agendak kargatzen...</p>
  </div>
</template>