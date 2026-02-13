<script setup>
import { ref, onMounted, computed } from 'vue'
import axios from 'axios'

const user = ref({ name: '', rola: '' })
const stats = ref({ hitzorduak: 0, stockBaxua: 0, produktuaGuztiak: 0 })
const hitzorduak = ref([]) 
const loading = ref(true)

axios.defaults.withCredentials = true;
// Cambia la IP y el puerto según lo que hayas visto en el paso anterior
axios.defaults.baseURL = 'http://98.93.71.5';
const kargatuDatuak = async () => {
  try {
    // 1. Erabiltzailea jaso
    const resUser = await axios.get('/api/user')
    user.value = resUser.data

    // 2. Estatistikak eta Gaurko Hitzorduak jaso
    const resDashboard = await axios.get('/api/dashboard-stats')
    stats.value = resDashboard.data.stats
    hitzorduak.value = resDashboard.data.gaurkoHitzorduak

  } catch (e) { 
    console.error("Errorea datuak kargatzean:", e) 
  } finally { 
    loading.value = false 
  }
}

const agurmezua = computed(() => {
  const ordua = new Date().getHours()
  if (ordua < 14) return 'Egun on'
  if (ordua < 20) return 'Arratsalde on'
  return 'Gabon'
})

const formatua = (dataString) => {
  if (!dataString) return '--:--'
  const d = new Date(dataString)
  return d.toLocaleTimeString('eu-ES', { hour: '2-digit', minute: '2-digit' })
}

onMounted(kargatuDatuak)
</script>

<template>
  <div v-if="!loading" class="max-w-7xl mx-auto space-y-8 md:space-y-12 animate-fade-in pb-20 pt-6 md:pt-10 px-4">
    
    <header class="bg-indigo-900 text-white p-8 md:p-16 rounded-[2.5rem] md:rounded-[4rem] shadow-2xl border-b-[10px] border-indigo-400 relative overflow-hidden">
      <div class="relative z-10">
        <span class="bg-indigo-400/30 text-indigo-200 px-4 py-2 rounded-full text-[10px] md:text-xs font-black uppercase tracking-widest border border-indigo-400/30">
          {{ user.rola === 'irakasle' ? 'Irakasle Sistema' : 'Ikasle Sistema' }}
        </span>
        <h1 class="text-4xl md:text-8xl font-black italic mt-6 md:mt-10 tracking-tighter uppercase leading-none">
          {{ agurmezua }}, <span class="text-indigo-400">{{ user.name }}</span>!
        </h1>
      </div>
      <div class="absolute right-[-2rem] top-[-2rem] text-[12rem] md:text-[20rem] opacity-10 font-black italic pointer-events-none hidden sm:block">KAIXO</div>
    </header>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-10">
      <div class="bg-white p-8 md:p-12 rounded-[2.5rem] shadow-xl border-t-8 border-indigo-500">
        <p class="text-indigo-400 font-black uppercase text-xs tracking-widest mb-4">Hitzorduak</p>
        <p class="text-6xl md:text-8xl font-black text-slate-900">{{ stats.hitzorduak }}</p>
      </div>
      
      <div :class="stats.stockBaxua > 0 ? 'border-rose-500 bg-rose-50' : 'border-emerald-500'" class="bg-white p-8 md:p-12 rounded-[2.5rem] shadow-xl border-t-8 transition-colors">
        <p :class="stats.stockBaxua > 0 ? 'text-rose-500' : 'text-emerald-500'" class="font-black uppercase text-xs tracking-widest mb-4">Stock Alerta</p>
        <p class="text-6xl md:text-8xl font-black" :class="stats.stockBaxua > 0 ? 'text-rose-600' : 'text-slate-900'">{{ stats.stockBaxua }}</p>
      </div>

      <div class="bg-white p-8 md:p-12 rounded-[2.5rem] shadow-xl border-t-8 border-slate-800 lg:col-span-1 sm:col-span-2">
        <p class="text-slate-400 font-black uppercase text-xs tracking-widest mb-4">Produktuak</p>
        <p class="text-6xl md:text-8xl font-black text-slate-900">{{ stats.produktuaGuztiak }}</p>
      </div>
    </div>

    <section v-if="hitzorduak.length > 0" class="space-y-6">
      <h2 class="text-3xl font-black uppercase italic text-indigo-900 px-2">📅 Gaurko Agenda</h2>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div v-for="h in hitzorduak" :key="h.id" class="bg-white p-8 rounded-[2.5rem] shadow-lg border-l-[12px] border-indigo-500">
          <span class="bg-indigo-600 text-white px-3 py-1 rounded-lg text-[10px] font-bold uppercase">{{ formatua(h.data) }}</span>
          <h3 class="text-2xl font-black text-slate-800 mt-4 uppercase truncate">{{ h.bezeroa?.izena || 'Bezeroa' }}</h3>
          <p class="text-slate-400 font-medium mt-2 italic line-clamp-2">{{ h.deskribapena || 'Zerbitzu orokorra' }}</p>
        </div>
      </div>
    </section>
  </div>

  <div v-else class="h-screen flex flex-col items-center justify-center space-y-6">
    <div class="w-16 h-16 border-8 border-indigo-600 border-t-transparent rounded-full animate-spin"></div>
    <p class="text-indigo-900 font-black uppercase italic animate-pulse">Datuak kargatzen...</p>
  </div>
</template>

<style scoped>
.animate-fade-in { animation: fadeIn 0.6s ease-out; }
@keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
</style>