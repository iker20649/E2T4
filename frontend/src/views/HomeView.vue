<script setup>
import { ref, onMounted, computed } from 'vue'
import axios from 'axios'

const user = ref({ name: '', rola: '' })
const stats = ref({ hitzorduak: 0, stockBaxua: 0, produktuGuztiak: 0 })
const hitzorduak = ref([]) 
const loading = ref(true)

// Configuración para que Axios use cookies de sesión
axios.defaults.withCredentials = true;

const kargatuDatuak = async () => {
  try {
    // CAMBIO: Rutas absolutas con /api/ y sin headers de Authorization
    const [resUser, resProd, resHitz] = await Promise.all([
      axios.get('/api/user'),
      axios.get('/api/produktuak'),
      axios.get('/api/hitzorduak').catch(() => ({ data: [] }))
    ])
    
    user.value = resUser.data
    hitzorduak.value = resHitz.data
    
    stats.value.produktuaGuztiak = resProd.data.length
    stats.value.stockBaxua = resProd.data.filter(p => p.stock <= p.stock_minimo).length
    stats.value.hitzorduak = resHitz.data.length
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

const gaurkoHitzorduak = computed(() => {
  return hitzorduak.value.slice(0, 3)
})

const formatua = (dataString) => {
  if (!dataString) return '--:--'
  const d = new Date(dataString)
  return d.toLocaleString('eu-ES', { hour: '2-digit', minute: '2-digit' })
}

onMounted(kargatuDatuak)
</script>

<template>
  <div v-if="!loading" class="space-y-12 animate-fade-in pb-20 pt-10 px-4">
    
    <header class="bg-indigo-900 text-white p-12 md:p-16 rounded-[3rem] md:rounded-[4rem] shadow-[0_35px_60px_-15px_rgba(79,70,229,0.4)] border-b-[12px] border-indigo-400 relative overflow-hidden">
      <div class="relative z-10">
        <span class="bg-indigo-400/30 text-indigo-200 px-6 py-2 rounded-full text-xs font-black uppercase tracking-widest border border-indigo-400/30">
          {{ user.rola === 'irakasle' ? 'Irakasle Sistema' : 'Ikasle Sistema' }}
        </span>
        <h1 class="text-6xl md:text-8xl font-black italic mt-10 tracking-tighter uppercase leading-none">
          {{ agurmezua }}, <span class="text-indigo-400">{{ user.name }}</span>!
        </h1>
        <p class="mt-8 text-indigo-100/70 max-w-2xl text-xl md:text-2xl font-medium italic leading-relaxed">
          {{ user.rola === 'irakasle' 
            ? 'Administrazio panel nagusia. Kudeatu inbentarioa eta ikasleak leku bakarretik.' 
            : 'Zure lan-eremua prest dago. Begiratu gaurko materiala eta txandak.' }}
        </p>
      </div>
      <div class="absolute right-[-2rem] top-[-2rem] text-[20rem] opacity-10 font-black italic select-none leading-none pointer-events-none">
        KAIXO
      </div>
    </header>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
      <div class="bg-white p-12 rounded-[3.5rem] shadow-2xl border-t-8 border-indigo-500 hover:-translate-y-3 transition-all duration-300">
        <p class="text-indigo-400 font-black uppercase text-sm tracking-[0.2em] mb-4">Hitzorduak</p>
        <p class="text-8xl font-black text-slate-900">{{ stats.hitzorduak }}</p>
      </div>
      <div class="bg-white p-12 rounded-[3.5rem] shadow-2xl border-t-8 border-rose-500 hover:-translate-y-3 transition-all duration-300">
        <p class="text-rose-400 font-black uppercase text-sm tracking-[0.2em] mb-4">Stock Alerta</p>
        <p class="text-8xl font-black text-rose-600">{{ stats.stockBaxua }}</p>
      </div>
      <div class="bg-white p-12 rounded-[3.5rem] shadow-2xl border-t-8 border-emerald-500 hover:-translate-y-3 transition-all duration-300">
        <p class="text-emerald-400 font-black uppercase text-sm tracking-[0.2em] mb-4">Produktuak</p>
        <p class="text-8xl font-black text-slate-900">{{ stats.produktuaGuztiak }}</p>
      </div>
    </div>

    <section v-if="user.rola === 'ikasle'" class="space-y-8 pt-6">
      <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center px-6 gap-4">
        <h2 class="text-4xl font-black uppercase italic text-indigo-900 tracking-tighter">📅 Gaurko Hitzorduak</h2>
        <router-link to="/hitzorduak" class="bg-indigo-100 text-indigo-600 px-6 py-2 rounded-full font-black uppercase text-xs hover:bg-indigo-600 hover:text-white transition-all shadow-sm">
          Agenda Osoa →
        </router-link>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div v-for="h in gaurkoHitzorduak" :key="h.id" 
             class="bg-white p-10 rounded-[3.5rem] shadow-xl border-l-[15px] border-indigo-500 group hover:bg-indigo-50 transition-all">
          <div class="flex justify-between items-start mb-6">
            <span class="bg-indigo-600 text-white px-5 py-2 rounded-2xl text-xs font-black uppercase italic shadow-md">
              {{ formatua(h.data) }}
            </span>
          </div>
          <h3 class="text-3xl font-black text-slate-800 uppercase italic tracking-tighter leading-none group-hover:text-indigo-600 transition-colors">{{ h.bezeroa }}</h3>
          <p class="text-slate-400 font-bold mt-4 italic text-lg leading-tight">{{ h.deskribapena || 'Zerbitzu orokorra' }}</p>
        </div>
        
        <div v-if="gaurkoHitzorduak.length === 0" class="col-span-full bg-slate-50 p-20 rounded-[4rem] text-center border-4 border-dashed border-slate-200">
          <p class="text-slate-300 font-black uppercase tracking-[0.3em] text-xl italic">Gaur ez daukazu hitzordurik</p>
        </div>
      </div>
    </section>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-12 pt-6">
      <router-link to="/stock" class="group bg-white p-12 md:p-16 rounded-[4rem] md:rounded-[4.5rem] shadow-2xl hover:shadow-indigo-200/50 transition-all relative overflow-hidden border border-indigo-100">
        <span class="text-7xl mb-8 block">🧴</span>
        <h3 class="text-4xl md:text-6xl font-black uppercase italic text-indigo-950 group-hover:translate-x-6 transition-transform leading-none">Inbentarioa</h3>
        <p class="text-indigo-600 mt-6 font-bold uppercase tracking-widest text-sm italic">Materialaren kontrol osoa</p>
        <div class="absolute right-[-2rem] bottom-[-2rem] text-[15rem] opacity-[0.03] font-black italic select-none pointer-events-none">STOCKA</div>
      </router-link>

      <router-link v-if="user.rola === 'irakasle'" to="/admin/ikasleak" class="group bg-slate-900 p-12 md:p-16 rounded-[4rem] md:rounded-[4.5rem] shadow-2xl hover:scale-[1.02] transition-all text-white relative overflow-hidden">
        <span class="text-7xl mb-8 block">📊</span>
        <h3 class="text-4xl md:text-6xl font-black uppercase italic group-hover:translate-x-6 transition-transform leading-none">Jarraipena</h3>
        <p class="text-indigo-400 mt-6 font-bold uppercase tracking-widest text-sm italic">Ikasleen log-ak kudeatu</p>
        <div class="absolute right-[-2rem] bottom-[-2rem] text-[15rem] opacity-10 font-black italic select-none pointer-events-none">LOGAK</div>
      </router-link>
      
      <router-link v-else to="/hitzorduak" class="group bg-violet-600 p-12 md:p-16 rounded-[4rem] md:rounded-[4.5rem] shadow-2xl hover:scale-[1.02] transition-all text-white relative overflow-hidden">
        <span class="text-7xl mb-8 block">📅</span>
        <h3 class="text-4xl md:text-6xl font-black uppercase italic group-hover:translate-x-6 transition-transform leading-none">Hitzorduak</h3>
        <p class="text-violet-100 mt-6 font-bold uppercase tracking-widest text-sm italic">Agenda eta txandak kudeatu</p>
        <div class="absolute right-[-2rem] bottom-[-2rem] text-[12rem] opacity-10 font-black italic select-none pointer-events-none">HITZORDUAK</div>
      </router-link>
    </div>
  </div>

  <div v-else class="h-screen flex flex-col items-center justify-center space-y-6">
    <div class="w-24 h-24 border-8 border-indigo-600 border-t-transparent rounded-full animate-spin"></div>
    <p class="text-indigo-900 font-black uppercase italic tracking-widest animate-pulse text-2xl">Datuak kargatzen...</p>
  </div>
</template>