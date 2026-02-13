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
const hitzorduak = ref([])
const ikasleak = ref([]) 
const user = ref(null)
const berria = ref({ bezeroa: '', data: '', deskribapena: '' })
const kargatzen = ref(true)
const ikasleIragazkia = ref('')

const kargatuDatuak = async () => {
  kargatzen.value = true
  try {
    const [resUser, resHitz] = await Promise.all([
      axios.get('/api/user'),
      axios.get('/api/hitzorduak')
    ])
    
    user.value = resUser.data
    hitzorduak.value = resHitz.data

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
  <div v-if="!kargatzen" class="max-w-7xl mx-auto space-y-6 md:space-y-12 animate-fade-in pb-20 pt-6 md:pt-10 px-4">
    
    <header class="bg-white p-6 md:p-12 rounded-[2rem] md:rounded-[3.5rem] shadow-xl border-b-[8px] md:border-b-[12px] border-indigo-600 flex flex-col md:flex-row justify-between items-start md:items-center relative overflow-hidden gap-4">
      <div class="relative z-10">
        <h1 class="text-4xl md:text-6xl font-black italic uppercase tracking-tighter text-indigo-950 leading-none">
          Agenda <span class="text-indigo-600">Zentroa</span>
        </h1>
        <p class="text-indigo-400 font-black uppercase text-[10px] md:text-sm tracking-[0.2em] md:tracking-[0.3em] mt-2 md:mt-4">Kudeaketa profesionala</p>
      </div>
      <div v-if="user" class="relative z-10">
        <span class="bg-indigo-600 text-white px-4 md:px-8 py-2 md:py-4 rounded-2xl md:rounded-3xl text-[10px] md:text-xs font-black uppercase shadow-lg tracking-widest">
          {{ user.rola }}
        </span>
      </div>
      <div class="absolute right-[-1rem] top-1/2 -translate-y-1/2 text-[6rem] md:text-[12rem] opacity-[0.03] font-black italic select-none pointer-events-none">AGENDA</div>
    </header>

    <section v-if="user?.rola?.toLowerCase().includes('irakasle')" 
             class="bg-indigo-950 p-6 md:p-10 rounded-[2rem] md:rounded-[3.5rem] shadow-2xl flex flex-col md:flex-row items-center justify-between gap-6 border-t-8 border-indigo-500">
      <div class="flex items-center gap-4 md:gap-6 w-full md:w-auto">
        <div class="bg-indigo-500 text-white p-3 md:p-5 rounded-2xl md:rounded-3xl shadow-lg text-xl md:text-3xl">📅</div>
        <div>
          <h3 class="font-black text-white uppercase text-lg md:text-xl italic tracking-tighter">Ikaslearen Agenda</h3>
          <p class="text-indigo-300 text-[10px] font-bold uppercase tracking-widest">Aukeratu ikaslea</p>
        </div>
      </div>
      <div class="w-full md:w-80">
        <select v-model="ikasleIragazkia" 
          class="w-full p-4 md:p-5 bg-white/10 text-white rounded-2xl md:rounded-[2rem] border-2 border-white/10 shadow-md focus:border-indigo-400 font-bold text-sm md:text-base outline-none cursor-pointer appearance-none">
          <option value="" class="text-indigo-900">✨ Denak</option>
          <option v-for="i in ikasleak" :key="i.id" :value="i.id" class="text-indigo-900">🎓 {{ i.name }}</option>
        </select>
      </div>
    </section>

    <section v-if="user?.rola?.toLowerCase().includes('ikasle') && !user?.rola?.toLowerCase().includes('irakasle')" 
             class="bg-slate-900 p-6 md:p-12 rounded-[2rem] md:rounded-[4rem] shadow-2xl relative overflow-hidden text-white border-b-8 border-indigo-500">
      <h2 class="text-2xl md:text-4xl font-black mb-6 md:mb-10 italic uppercase flex items-center gap-4 relative z-10">
        <span class="bg-indigo-500 p-2 md:p-3 rounded-xl md:rounded-2xl text-xl">➕</span> Hitzordu Berria
      </h2>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-8 relative z-10">
        <div class="space-y-2">
          <label class="text-[9px] md:text-[10px] font-black text-indigo-300 uppercase tracking-widest ml-4">Bezeroa</label>
          <input v-model="berria.bezeroa" type="text" placeholder="Nor dator?" 
                 class="w-full p-4 md:p-6 bg-white/10 rounded-2xl md:rounded-3xl border-2 border-white/10 focus:border-indigo-500 outline-none text-base md:text-xl font-bold transition-all">
        </div>
        <div class="space-y-2">
          <label class="text-[9px] md:text-[10px] font-black text-indigo-300 uppercase tracking-widest ml-4">Noiz</label>
          <input v-model="berria.data" type="datetime-local" 
                 class="w-full p-4 md:p-6 bg-white/10 rounded-2xl md:rounded-3xl border-2 border-white/10 focus:border-indigo-500 outline-none text-base md:text-xl font-bold transition-all">
        </div>
        <div class="md:col-span-2 space-y-2">
          <label class="text-[9px] md:text-[10px] font-black text-indigo-300 uppercase tracking-widest ml-4">Zer egingo diogu?</label>
          <textarea v-model="berria.deskribapena" placeholder="Xehetasunak..." 
                    class="w-full p-4 md:p-6 bg-white/10 rounded-2xl md:rounded-3xl border-2 border-white/10 focus:border-indigo-500 outline-none h-24 md:h-32 text-base md:text-xl font-bold transition-all"></textarea>
        </div>
        <button @click="gordeHitzordua" 
                class="md:col-span-2 bg-indigo-500 hover:bg-indigo-400 text-white font-black py-4 md:py-6 rounded-2xl md:rounded-[2rem] transition-all shadow-lg uppercase tracking-widest active:scale-95 text-xs md:text-base">
          Gorde Hitzordua 🚀
        </button>
      </div>
      <div class="absolute -right-10 -bottom-10 text-[6rem] md:text-[12rem] text-white/5 font-black italic select-none pointer-events-none hidden sm:block">BERRIA</div>
    </section>

    <section class="bg-white rounded-[2rem] md:rounded-[4rem] shadow-2xl overflow-hidden border border-indigo-50">
      <div v-if="hitzorduIragaziak.length === 0" class="p-16 md:p-32 text-center">
        <span class="text-6xl md:text-8xl block mb-6 animate-bounce">📭</span>
        <p class="text-slate-400 font-black uppercase tracking-[0.3em] md:tracking-[0.5em] text-xs md:text-base">Hitzordurik gabe</p>
      </div>
      
      <div v-else class="responsive-table">
        <table class="w-full text-left border-collapse">
          <thead class="bg-indigo-950 text-indigo-200 text-[10px] md:text-[11px] uppercase tracking-[0.2em] md:tracking-[0.4em] font-black hidden md:table-header-group">
            <tr>
              <th class="p-10 text-center">Data / Ordua</th>
              <th class="p-10">Bezeroa</th>
              <th v-if="user?.rola?.toLowerCase().includes('irakasle')" class="p-10">Ikaslea</th>
              <th class="p-10">Zerbitzua</th>
              <th class="p-10 text-right">Ekintzak</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-indigo-50 block md:table-row-group">
            <tr v-for="h in hitzorduIragaziak" :key="h.id" class="hover:bg-indigo-50/50 transition-all group block md:table-row p-4 md:p-0 border-b md:border-b-0">
              
              <td class="block md:table-cell p-2 md:p-10 text-center">
                <div class="bg-indigo-50 p-3 md:p-4 rounded-2xl md:rounded-3xl border-2 border-indigo-100 group-hover:bg-white transition-colors flex md:block items-center justify-between">
                  <div class="text-lg md:text-2xl font-black text-indigo-900 leading-none tracking-tighter">{{ formatua(h.data).split(',')[0] }}</div>
                  <div class="text-[10px] md:text-sm font-black text-indigo-500 uppercase md:mt-1 tracking-widest">{{ formatua(h.data).split(',')[1] }}</div>
                </div>
              </td>

              <td class="block md:table-cell p-2 md:p-10">
                <div class="text-xs font-black text-indigo-400 uppercase md:hidden">Bezeroa</div>
                <div class="text-xl md:text-3xl font-black text-slate-800 uppercase italic tracking-tighter truncate">{{ h.bezeroa }}</div>
              </td>

              <td v-if="user?.rola?.toLowerCase().includes('irakasle')" class="block md:table-cell p-2 md:p-10">
                <div class="text-xs font-black text-indigo-400 uppercase md:hidden mb-1">Ikaslea</div>
                <span class="bg-indigo-100 text-indigo-600 px-3 md:px-6 py-1 md:py-2 rounded-xl md:rounded-2xl text-[9px] md:text-[10px] font-black uppercase border-2 border-indigo-200 shadow-sm">
                  🎓 {{ h.user?.name || '---' }}
                </span>
              </td>

              <td class="block md:table-cell p-2 md:p-10">
                <div class="text-xs font-black text-indigo-400 uppercase md:hidden mb-1">Zerbitzua</div>
                <p class="text-slate-500 font-bold italic text-sm md:text-lg leading-tight">{{ h.deskribapena || '---' }}</p>
              </td>

              <td class="block md:table-cell p-4 md:p-10 text-right">
                <button @click="ezabatu(h.id)" 
                        class="w-full md:w-auto px-4 md:px-6 py-3 bg-rose-50 border-2 md:border-4 border-rose-100 text-rose-500 rounded-xl md:rounded-2xl font-black text-[10px] hover:bg-rose-500 hover:text-white transition-all active:scale-95 uppercase tracking-widest">
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
    <div class="w-16 h-16 md:w-24 md:h-24 border-4 md:border-8 border-indigo-600 border-t-transparent rounded-full animate-spin"></div>
    <p class="text-indigo-900 font-black uppercase italic tracking-widest text-xs md:text-base animate-pulse">Agendak kargatzen...</p>
  </div>
</template>

<style scoped>
.animate-fade-in { animation: fadeIn 0.8s ease-out; }
@keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

/* Izen luzeak ez mozteko */
.truncate {
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

/* Mugikorrean taula txartel moduan ikusteko truko txiki bat */
@media (max-width: 768px) {
  .responsive-table table, 
  .responsive-table thead, 
  .responsive-table tbody, 
  .responsive-table th, 
  .responsive-table td, 
  .responsive-table tr { 
    display: block; 
  }
}
</style>