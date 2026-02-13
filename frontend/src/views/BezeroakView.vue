<script setup>
import { ref, onMounted, computed } from 'vue'
import axios from 'axios'

// Configuración de Axios - IP EGUNERATUA
const token = localStorage.getItem('token');
if (token) {
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
}
// Cambia la IP y el puerto según lo que hayas visto en el paso anterior
axios.defaults.baseURL = 'http://98.93.71.5';
const bezeroakRaw = ref([])
const rola = ref('')
const kargatzen = ref(true)
const berria = ref({ izena: '', abizenak: '', telefonoa: '', deskribapena: '' })

const kargatuDatuak = async () => {
  kargatzen.value = true
  try {
    const userRes = await axios.get('/api/user')
    rola.value = userRes.data.rola.toLowerCase().trim()
    
    const res = await axios.get('/api/bezeroak')
    bezeroakRaw.value = res.data
  } catch (e) {
    console.error("Errorea datuak kargatzean:", e)
  } finally {
    kargatzen.value = false
  }
}

const bezeroZerrenda = computed(() => {
  if (!bezeroakRaw.value) return []
  if (Array.isArray(bezeroakRaw.value)) return bezeroakRaw.value
  return Object.entries(bezeroakRaw.value).map(([izena, lista]) => ({
    ikasleIzena: izena,
    bezeroak: lista
  }))
})

const gordeBezeroa = async () => {
  if (!berria.value.izena) return
  try {
    await axios.post('/api/bezeroak', berria.value)
    berria.value = { izena: '', abizenak: '', telefonoa: '', deskribapena: '' }
    await kargatuDatuak()
  } catch (e) { 
    alert("Errorea gordetzean") 
  }
}

const ezabatuBezeroa = async (id) => {
  if (!confirm("Ziur zaude bezero hau ezabatu nahi duzula?")) return
  try {
    await axios.delete(`/api/bezeroak/${id}`)
    await kargatuDatuak()
  } catch (e) {
    alert("Ezin izan da bezeroa ezabatu")
  }
}

onMounted(kargatuDatuak)
</script>

<template>
  <div class="max-w-7xl mx-auto p-4 md:p-6 space-y-6 md:space-y-8 pt-6 md:pt-10 pb-20 animate-fade-in">
    
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <h1 class="text-4xl md:text-6xl font-black italic uppercase text-slate-900 tracking-tighter leading-none">
            Bezeroen <span class="text-indigo-600">Fitxategia</span>
        </h1>
        <div v-if="rola" class="bg-indigo-100 text-indigo-700 px-4 md:px-6 py-2 rounded-2xl text-[10px] md:text-xs font-black uppercase tracking-widest">
            {{ rola }}
        </div>
    </div>

    <div v-if="rola === 'ikasle'" class="bg-slate-900 p-6 md:p-12 rounded-[2rem] md:rounded-[3.5rem] shadow-2xl relative overflow-hidden text-white">
      <h3 class="font-black text-indigo-400 mb-6 md:mb-8 uppercase text-xs md:text-sm tracking-[0.2em] md:tracking-[0.3em] flex items-center gap-3">
        <span class="bg-indigo-500 text-white p-2 rounded-lg text-base md:text-lg">✨</span> Bezero Berria
      </h3>
      
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6 mb-6 relative z-10">
        <div class="space-y-1 md:space-y-2">
            <label class="text-[9px] md:text-[10px] font-black text-indigo-300 uppercase tracking-widest ml-4">Izena</label>
            <input v-model="berria.izena" placeholder="..." class="w-full p-3 md:p-4 bg-white/10 rounded-xl md:rounded-2xl border-2 border-white/5 focus:border-indigo-500 outline-none text-white font-bold transition-all text-sm md:text-base">
        </div>
        <div class="space-y-1 md:space-y-2">
            <label class="text-[9px] md:text-[10px] font-black text-indigo-300 uppercase tracking-widest ml-4">Abizenak</label>
            <input v-model="berria.abizenak" placeholder="..." class="w-full p-3 md:p-4 bg-white/10 rounded-xl md:rounded-2xl border-2 border-white/5 focus:border-indigo-500 outline-none text-white font-bold transition-all text-sm md:text-base">
        </div>
        <div class="space-y-1 md:space-y-2">
            <label class="text-[9px] md:text-[10px] font-black text-indigo-300 uppercase tracking-widest ml-4">Telefonoa</label>
            <input v-model="berria.telefonoa" placeholder="..." class="w-full p-3 md:p-4 bg-white/10 rounded-xl md:rounded-2xl border-2 border-white/5 focus:border-indigo-500 outline-none text-white font-bold transition-all text-sm md:text-base">
        </div>
      </div>
      
      <div class="space-y-1 md:space-y-2 mb-6 md:mb-8 relative z-10">
          <label class="text-[9px] md:text-[10px] font-black text-indigo-300 uppercase tracking-widest ml-4">Oharrak / Deskribapena</label>
          <textarea v-model="berria.deskribapena" placeholder="..." class="w-full p-3 md:p-4 bg-white/10 rounded-xl md:rounded-2xl border-2 border-white/5 focus:border-indigo-500 outline-none h-24 text-white font-bold transition-all text-sm md:text-base" rows="2"></textarea>
      </div>
      
      <button @click="gordeBezeroa" class="w-full bg-indigo-500 text-white p-4 md:p-5 rounded-2xl md:rounded-[2rem] font-black uppercase text-[10px] md:text-xs hover:bg-indigo-400 transition-all shadow-lg active:scale-95">
        Gorde Bezeroa Sistema Nagusian
      </button>
      
      <div class="absolute -right-10 -bottom-10 text-[6rem] md:text-[10rem] text-white/5 font-black italic select-none hidden sm:block">BEZEROA</div>
    </div>

    <div v-if="kargatzen" class="flex flex-col items-center py-20">
        <div class="w-12 h-12 md:w-16 md:h-16 border-4 border-indigo-600 border-t-transparent rounded-full animate-spin"></div>
        <p class="mt-4 font-black text-indigo-900 uppercase italic tracking-widest animate-pulse text-xs">Kargatzen...</p>
    </div>

    <div v-else>
      <div v-if="bezeroZerrenda.length > 0">
        
        <div v-if="rola === 'irakasle'" class="space-y-10 md:space-y-16">
          <div v-for="grupo in bezeroZerrenda" :key="grupo.ikasleIzena" class="space-y-4 md:space-y-6">
            <div class="flex items-center gap-4">
                <h2 class="bg-indigo-600 text-white px-4 md:px-8 py-2 md:py-3 rounded-xl md:rounded-2xl text-[10px] md:text-sm font-black uppercase italic shadow-lg">🎓 {{ grupo.ikasleIzena }}</h2>
                <div class="h-[2px] flex-grow bg-indigo-100"></div>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-8">
              <div v-for="b in grupo.bezeroak" :key="b.id" class="bg-white p-6 md:p-8 rounded-[2rem] md:rounded-[3rem] shadow-xl border border-indigo-50 group hover:border-indigo-200 transition-all relative">
                <div class="flex justify-between items-start mb-4 md:mb-6">
                  <div class="h-12 w-12 md:h-16 md:w-16 bg-indigo-50 text-indigo-600 rounded-2xl md:rounded-3xl flex items-center justify-center font-black text-xl md:text-2xl italic shadow-inner">
                    {{ b.izena ? b.izena[0] : '?' }}
                  </div>
                  <button @click="ezabatuBezeroa(b.id)" class="p-2 md:p-3 bg-rose-50 text-rose-300 hover:bg-rose-500 hover:text-white rounded-xl md:rounded-2xl transition-all active:scale-90">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 md:h-6 md:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                  </button>
                </div>
                <h3 class="font-black text-slate-900 text-xl md:text-2xl uppercase italic tracking-tighter truncate">{{ b.izena }} {{ b.abizenak }}</h3>
                <p class="text-indigo-500 text-xs md:text-sm font-black mt-1 mb-4 md:mb-6 tracking-widest">{{ b.telefonoa || '---' }}</p>
                <div class="bg-slate-50 p-4 md:p-6 rounded-[1.5rem] md:rounded-[2rem] border border-dashed border-slate-200 group-hover:bg-indigo-50/50 transition-colors">
                  <p class="text-xs md:text-sm text-slate-600 font-medium italic leading-relaxed">{{ b.deskribapena || 'Ez dago oharrik.' }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-8">
          <div v-for="b in bezeroZerrenda" :key="b.id" class="bg-white p-6 md:p-8 rounded-[2rem] md:rounded-[3rem] shadow-xl border border-indigo-50 group hover:border-indigo-200 transition-all relative">
            <div class="flex justify-between items-start mb-4 md:mb-6">
              <div class="h-12 w-12 md:h-16 md:w-16 bg-indigo-50 text-indigo-600 rounded-2xl md:rounded-3xl flex items-center justify-center font-black text-xl md:text-2xl italic shadow-inner">
                {{ b.izena ? b.izena[0] : '?' }}
              </div>
              <button @click="ezabatuBezeroa(b.id)" class="p-2 md:p-3 bg-rose-50 text-rose-300 hover:bg-rose-500 hover:text-white rounded-xl md:rounded-2xl transition-all active:scale-90 opacity-100 md:opacity-0 md:group-hover:opacity-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 md:h-6 md:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
              </button>
            </div>
            <h3 class="font-black text-slate-900 text-xl md:text-2xl uppercase italic tracking-tighter truncate">{{ b.izena }} {{ b.abizenak }}</h3>
            <p class="text-indigo-500 text-xs md:text-sm font-black mt-1 mb-4 md:mb-6 tracking-widest">{{ b.telefonoa || '---' }}</p>
            <div class="bg-slate-50 p-4 md:p-6 rounded-[1.5rem] md:rounded-[2rem] border border-dashed border-slate-200 group-hover:bg-indigo-50/50 transition-colors">
              <p class="text-xs md:text-sm text-slate-600 font-medium italic leading-relaxed">{{ b.deskribapena || 'Ez dago oharrik.' }}</p>
            </div>
          </div>
        </div>

      </div>

      <div v-else class="text-center py-16 md:py-32 bg-white rounded-[2rem] md:rounded-[4rem] border-4 border-dashed border-slate-100">
        <span class="text-4xl md:text-6xl block mb-4">👥</span>
        <p class="text-slate-400 font-black uppercase tracking-[0.2em] md:tracking-[0.4em] italic text-xs md:text-base">Ez dago bezerorik fitxategian</p>
      </div>
    </div>
  </div>
</template>

<style scoped>
.animate-fade-in { animation: fadeIn 0.8s ease-out; }
@keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

/* Mugikorrean izen luzeak moztu ez daitezen */
.truncate {
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}
</style>