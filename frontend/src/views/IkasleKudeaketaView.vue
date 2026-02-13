<script setup>
import { ref, onMounted, watch } from 'vue' // Añadimos watch
import { useRoute } from 'vue-router'      // Añadimos useRoute
import axios from 'axios'

const ikasleak = ref([])
const historiala = ref([])
const loading = ref(true)
const erakutsiModala = ref(false)
const route = useRoute() // Obtenemos la ruta actual

const berria = ref({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  rola: 'ikasle'
})

// Configuración Axios
const token = localStorage.getItem('token');
// Cambia la IP y el puerto según lo que hayas visto en el paso anterior
axios.defaults.baseURL = 'http://98.93.71.5';
axios.defaults.withCredentials = true; 

if (token) {
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
}

const kargatuDatuak = async () => {
  try {
    // Añadimos un timestamp para evitar que el navegador use datos viejos (cache)
    const timestamp = new Date().getTime();
    
    const [resIkas, resHist] = await Promise.all([
      axios.get(`/api/admin/ikasleak?t=${timestamp}`),
      axios.get(`/api/admin/stock-historiala?t=${timestamp}`)
    ])
    
    ikasleak.value = resIkas.data
    historiala.value = resHist.data
    
    console.log("Log-a eguneratua:", resHist.data); // Mira la consola (F12) para ver si llegan datos
  } catch (e) {
    console.error("Errorea datuak kargatzean:", e)
  } finally {
    loading.value = false
  }
}

// --- SOLUCIÓN AL PROBLEMA DEL LOG ---
// Este watcher detecta cuando el usuario navega a esta página
// y refresca los datos (incluido el historial) automáticamente.
watch(() => route.path, (newPath) => {
  console.log("Ruta actual detectada:", newPath); // Esto te dirá la verdad
  
  if (newPath === '/kontrola' || newPath === '/admin') {
    console.log("¡Coincide! Recargando datos...");
    kargatuDatuak()
  } else {
    console.log("No coincide, no se recarga el log.");
  }
})

const erregistratuIkaslea = async () => {
  try {
    await axios.post('/api/register', berria.value)
    alert("Ikaslea ondo sortu da!")
    erakutsiModala.value = false
    berria.value = { name: '', email: '', password: '', password_confirmation: '', rola: 'ikasle' }
    kargatuDatuak() 
  } catch (e) {
    const errores = e.response?.data?.errors;
    if (errores) {
      const msg = Object.values(errores).flat().join('\n');
      alert("Errorea validazioan:\n" + msg);
    } else {
      alert("Errorea: Ezin izan da ikaslea sortu.");
    }
  }
}

const ezabatuIkaslea = async (id, izena) => {
  if (!confirm(`Ziur zaude ${izena} ikaslea ezabatu nahi duzula?`)) return
  try {
    // Quitamos /admin para que coincida con la ruta estándar de Laravel
    await axios.delete(`/api/admin/ikasleak/${id}`)
    kargatuDatuak()
    alert("Ikaslea ezabatua!")
  } catch (e) {
    alert("Ezin izan da ikaslea ezabatu")
  }
}

onMounted(kargatuDatuak)
</script>

<template>
  <div v-if="!loading" class="max-w-7xl mx-auto space-y-8 md:space-y-12 animate-fade-in pb-20 pt-6 md:pt-10 px-4">
    
    <header class="bg-indigo-900 text-white p-8 md:p-16 rounded-[2.5rem] md:rounded-[4rem] shadow-2xl border-b-[10px] md:border-b-[12px] border-violet-500 relative overflow-hidden">
      <div class="relative z-10 text-center md:text-left">
        <span class="bg-violet-400/30 text-violet-200 px-4 py-2 rounded-full text-[10px] font-black uppercase tracking-widest border border-violet-400/30">
          Admin Kontrol Panela
        </span>
        <h1 class="text-4xl md:text-8xl font-black italic mt-6 md:mt-10 tracking-tighter uppercase leading-none">
          Kudeaketa <span class="text-violet-400">Guztia</span>
        </h1>
        <div class="flex flex-col md:flex-row md:items-center gap-4 md:gap-6 mt-8">
            <p class="text-indigo-100/60 max-w-xl text-base md:text-xl font-medium italic">Ikasleen zerrenda eta materialaren mugimenduak.</p>
            <button @click="erakutsiModala = true" class="bg-violet-500 hover:bg-violet-400 text-white px-6 py-4 rounded-2xl font-black italic uppercase tracking-tighter transition-all active:scale-95 shadow-lg text-sm">
                + Gehitu Ikaslea
            </button>
        </div>
      </div>
      <div class="absolute right-[-5rem] top-[-5rem] text-[12rem] md:text-[20rem] opacity-5 font-black italic select-none uppercase pointer-events-none hidden sm:block">Admin</div>
    </header>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-10">
      
      <section class="lg:col-span-1 space-y-4 md:space-y-6">
        <div class="flex justify-between items-center px-2 md:px-4">
          <h2 class="text-2xl md:text-3xl font-black uppercase italic text-indigo-900 tracking-tighter">🎓 Ikasleak</h2>
          <span class="bg-indigo-100 text-indigo-600 px-4 py-1 rounded-full text-xs font-black">{{ ikasleak.length }}</span>
        </div>
        
        <div class="bg-white rounded-[2.5rem] md:rounded-[3.5rem] shadow-xl border border-indigo-50 overflow-hidden p-2 md:p-4">
          <div v-for="i in ikasleak" :key="i.id" 
               class="p-4 md:p-6 border-b border-indigo-50 last:border-0 flex justify-between items-center hover:bg-indigo-50/50 transition-all rounded-[1.5rem] md:rounded-[2rem] group">
            <div class="flex items-center gap-3 md:gap-4 overflow-hidden">
              <img :src="'https://ui-avatars.com/api/?background=6366f1&color=fff&name=' + (i.izena || i.name)" 
     class="w-10 h-10 md:w-12 md:h-12 rounded-xl md:rounded-2xl shadow-md flex-shrink-0">

<div class="truncate">
    <p class="font-black text-slate-800 uppercase italic text-xs md:text-sm leading-none truncate">
        {{ i.izena || i.name }}
    </p>
    <p class="text-[9px] md:text-[10px] text-indigo-400 font-bold mt-1 uppercase tracking-tighter truncate">
        {{ i.email }}
    </p>
</div>
            </div>
            <button @click="ezabatuIkaslea(i.id, i.name)" 
                    class="p-2 md:p-3 bg-rose-50 text-rose-500 hover:bg-rose-500 hover:text-white rounded-xl transition-all active:scale-90 flex-shrink-0">
              <span class="text-base md:text-lg">🗑️</span>
            </button>
          </div>
          <div v-if="ikasleak.length === 0" class="p-10 text-center italic text-slate-400 font-bold text-sm">Ez dago ikaslerik.</div>
        </div>
      </section>

      <section class="lg:col-span-2 space-y-4 md:space-y-6">
        <h2 class="text-2xl md:text-3xl font-black uppercase italic text-indigo-900 tracking-tighter px-2 md:px-4">📜 Stockaren Log-a</h2>
        <div class="bg-slate-900 rounded-[2.5rem] md:rounded-[4rem] p-4 md:p-10 shadow-2xl space-y-4 max-h-[600px] md:max-h-[800px] overflow-y-auto border-t-[8px] md:border-t-[12px] border-indigo-500 custom-scrollbar">
          <div v-for="h in historiala" :key="h.id" 
               class="flex flex-col sm:flex-row justify-between items-start sm:items-center bg-white/5 p-5 md:p-6 rounded-[2rem] md:rounded-[2.5rem] border border-white/5 hover:bg-white/10 transition-all group gap-4">
            <div class="space-y-2 md:space-y-3">
              <div class="flex flex-wrap items-center gap-2 md:gap-3">
                <span class="text-[9px] font-black bg-indigo-600 text-white px-2 py-1 rounded-md uppercase tracking-widest">
                  👤 {{ h.user?.name || 'Sistema' }}
                </span>
                <span class="text-[9px] font-black px-2 py-1 rounded-md uppercase tracking-widest"
                      :class="h.ekintza.includes('sortua') ? 'bg-sky-500 text-white' : (h.kantitatea > 0 ? 'bg-emerald-500 text-white' : 'bg-rose-500 text-white')">
                  {{ h.ekintza }}
                </span>
              </div>
              <p class="font-black text-white uppercase text-xl md:text-4xl italic tracking-tighter leading-none group-hover:text-indigo-400 transition-colors truncate max-w-[200px] md:max-w-none">
                {{ h.produktua }}
              </p>
            </div>
            <div class="text-left sm:text-right space-y-1 w-full sm:w-auto">
              <div class="text-3xl md:text-5xl font-black tabular-nums" 
                    :class="h.ekintza.includes('sortua') ? 'text-sky-400' : (h.kantitatea > 0 ? 'text-emerald-400' : 'text-rose-400')">
                {{ h.kantitatea > 0 ? '+' : '' }}{{ h.kantitatea }}
              </div>
              <p class="text-[9px] md:text-[10px] font-black text-slate-500 uppercase italic">{{ new Date(h.created_at).toLocaleString('eu-ES') }}</p>
            </div>
          </div>
        </div>
      </section>
    </div>

    <div v-if="erakutsiModala" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-indigo-950/90 backdrop-blur-md">
        <div class="bg-white w-full max-w-xl rounded-[2.5rem] p-6 md:p-10 shadow-2xl border-t-[10px] border-violet-500 animate-fade-in overflow-y-auto max-h-[90vh]">
            <div class="flex justify-between items-center mb-6 md:mb-8">
                <h3 class="text-2xl md:text-4xl font-black italic uppercase text-indigo-900 tracking-tighter">Ikasle Berria</h3>
                <button @click="erakutsiModala = false" class="text-slate-300 hover:text-rose-500 text-3xl">✕</button>
            </div>
            <form @submit.prevent="erregistratuIkaslea" class="space-y-3 md:space-y-4">
                <input v-model="berria.name" type="text" placeholder="IZEN OSOA" class="w-full p-4 md:p-5 bg-slate-100 rounded-xl md:rounded-2xl border-none font-bold uppercase text-sm" required>
                <input v-model="berria.email" type="email" placeholder="EMAILA" class="w-full p-4 md:p-5 bg-slate-100 rounded-xl md:rounded-2xl border-none font-bold uppercase text-sm" required>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4">
                    <input v-model="berria.password" type="password" placeholder="PASAHITZA" class="p-4 md:p-5 bg-slate-100 rounded-xl md:rounded-2xl border-none font-bold uppercase text-sm" required>
                    <input v-model="berria.password_confirmation" type="password" placeholder="REPETITU" class="p-4 md:p-5 bg-slate-100 rounded-xl md:rounded-2xl border-none font-bold uppercase text-sm" required>
                </div>
                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-500 text-white p-5 md:p-6 rounded-xl md:rounded-2xl font-black italic uppercase tracking-widest transition-all mt-4 text-xs md:text-base">
                    DATU-BASEAN GEHITU
                </button>
            </form>
        </div>
    </div>
  </div>

  <div v-else class="min-h-screen flex flex-col items-center justify-center space-y-6 p-4">
    <div class="w-16 h-16 md:w-24 md:h-24 border-8 border-indigo-600 border-t-transparent rounded-full animate-spin"></div>
    <p class="text-indigo-900 font-black uppercase italic tracking-widest animate-pulse text-xl md:text-2xl text-center">Datuak sinkronizatzen...</p>
  </div>
</template>

<style scoped>
.animate-fade-in { animation: fadeIn 0.5s ease-out; }
@keyframes fadeIn { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }

.custom-scrollbar::-webkit-scrollbar { width: 6px; }
.custom-scrollbar::-webkit-scrollbar-track { background: rgba(255,255,255,0.05); }
.custom-scrollbar::-webkit-scrollbar-thumb { background: #6366f1; border-radius: 10px; }
</style>