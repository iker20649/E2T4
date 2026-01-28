<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const ikasleak = ref([])
const historiala = ref([])
const loading = ref(true)
const erakutsiModala = ref(false)

// Datos del nuevo alumno
const berria = ref({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  rola: 'ikasle'
})

// Configuración de Axios
const token = localStorage.getItem('token');
axios.defaults.baseURL = 'http://127.0.0.1:8000';
// Importante para que Laravel acepte la petición desde localhost:5173
axios.defaults.withCredentials = true; 

if (token) {
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
}

const kargatuDatuak = async () => {
  try {
    const [resIkas, resHist] = await Promise.all([
      axios.get('/api/admin/ikasleak'),
      axios.get('/api/admin/stock-historiala')
    ])
    ikasleak.value = resIkas.data
    historiala.value = resHist.data
  } catch (e) {
    console.error("Errorea datuak kargatzean:", e)
    if (e.response?.status === 403) alert("Ez duzu baimenik orri hau ikusteko.")
  } finally {
    loading.value = false
  }
}

const erregistratuIkaslea = async () => {
  try {
    // 1. Llamamos a la ruta que acabamos de definir en api.php
    await axios.post('/api/register', berria.value)
    
    alert("Ikaslea ondo sortu da!")
    erakutsiModala.value = false
    
    // Limpiar formulario
    berria.value = { 
      name: '', 
      email: '', 
      password: '', 
      password_confirmation: '', 
      rola: 'ikasle' 
    }
    
    // Recargar la lista automáticamente
    kargatuDatuak() 

  } catch (e) {
    console.error("ERROREAREN XEHETASUNA:", e.response?.data)
    
    // Extraer mensajes de validación de Laravel (ej: "Emaila erregistratuta dago")
    const errores = e.response?.data?.errors;
    if (errores) {
      const msg = Object.values(errores).flat().join('\n');
      alert("Errorea validazioan:\n" + msg);
    } else {
      alert("Errorea: Ezin izan da ikaslea sortu. Ziurtatu pasahitzak 8 karaktere dituela eta emaila berria dela.");
    }
  }
}

const ezabatuIkaslea = async (id, izena) => {
  if (!confirm(`Ziur zaude ${izena} ikaslea ezabatu nahi duzula?`)) return
  try {
    await axios.delete(`/api/admin/ikasleak/${id}`)
    kargatuDatuak()
  } catch (e) {
    alert("Ezin izan da ikaslea ezabatu")
  }
}

onMounted(kargatuDatuak)
</script>

<template>
  <div v-if="!loading" class="max-w-7xl mx-auto space-y-12 animate-fade-in pb-20 pt-10 px-4">
    
    <header class="bg-indigo-900 text-white p-12 md:p-16 rounded-[3rem] md:rounded-[4rem] shadow-[0_35px_60px_-15px_rgba(79,70,229,0.4)] border-b-[12px] border-violet-500 relative overflow-hidden">
      <div class="relative z-10">
        <span class="bg-violet-400/30 text-violet-200 px-6 py-2 rounded-full text-xs font-black uppercase tracking-widest border border-violet-400/30">
          Admin Kontrol Panela
        </span>
        <h1 class="text-6xl md:text-8xl font-black italic mt-10 tracking-tighter uppercase leading-none">
          Kudeaketa <span class="text-violet-400">Guztia</span>
        </h1>
        <div class="flex flex-col md:flex-row md:items-center gap-6 mt-8">
            <p class="text-indigo-100/60 max-w-xl text-xl font-medium italic">Ikasleen zerrenda eta materialaren mugimenduak.</p>
            <button @click="erakutsiModala = true" class="bg-violet-500 hover:bg-violet-400 text-white px-8 py-4 rounded-2xl font-black italic uppercase tracking-tighter transition-all active:scale-95 shadow-lg">
                + Gehitu Ikaslea
            </button>
        </div>
      </div>
      <div class="absolute right-[-5rem] top-[-5rem] text-[20rem] opacity-5 font-black italic select-none uppercase pointer-events-none">Admin</div>
    </header>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
      
      <section class="lg:col-span-1 space-y-6">
        <div class="flex justify-between items-center px-4">
          <h2 class="text-3xl font-black uppercase italic text-indigo-900 tracking-tighter">🎓 Ikasleak</h2>
          <span class="bg-indigo-100 text-indigo-600 px-4 py-1 rounded-full text-xs font-black">{{ ikasleak.length }}</span>
        </div>
        
        <div class="bg-white rounded-[3.5rem] shadow-2xl border border-indigo-50 overflow-hidden p-4">
          <div v-for="i in ikasleak" :key="i.id" 
               class="p-6 border-b border-indigo-50 last:border-0 flex justify-between items-center hover:bg-indigo-50/50 transition-all rounded-[2rem] group">
            <div class="flex items-center gap-4">
              <img :src="'https://ui-avatars.com/api/?background=6366f1&color=fff&name=' + i.name" class="w-12 h-12 rounded-2xl shadow-md">
              <div class="max-w-[150px] overflow-hidden">
                <p class="font-black text-slate-800 uppercase italic text-sm leading-none truncate">{{ i.name }}</p>
                <p class="text-[10px] text-indigo-400 font-bold mt-1 uppercase tracking-tighter truncate">{{ i.email }}</p>
              </div>
            </div>
            <button @click="ezabatuIkaslea(i.id, i.name)" 
                    class="p-3 bg-rose-50 text-rose-300 hover:bg-rose-500 hover:text-white rounded-xl transition-all active:scale-90">
              <span class="text-lg">🗑️</span>
            </button>
          </div>
          <div v-if="ikasleak.length === 0" class="p-20 text-center italic text-slate-400 font-bold">Ez dago ikaslerik.</div>
        </div>
      </section>

      <section class="lg:col-span-2 space-y-6">
        <h2 class="text-3xl font-black uppercase italic text-indigo-900 tracking-tighter px-4">📜 Stockaren Log-a</h2>
        <div class="bg-slate-900 rounded-[3rem] md:rounded-[4rem] p-6 md:p-10 shadow-2xl space-y-4 max-h-[800px] overflow-y-auto border-t-[12px] border-indigo-500">
          <div v-for="h in historiala" :key="h.id" 
               class="flex flex-col md:flex-row justify-between items-start md:items-center bg-white/5 p-6 rounded-[2.5rem] border border-white/5 hover:bg-white/10 transition-all group gap-4">
            <div class="space-y-3">
              <div class="flex flex-wrap items-center gap-3">
                <span class="text-[10px] font-black bg-indigo-600 text-white px-3 py-1 rounded-lg uppercase tracking-widest">
                  👤 {{ h.user?.name || 'Sistema' }}
                </span>
                <span class="text-[10px] font-black px-3 py-1 rounded-lg uppercase tracking-widest"
                      :class="h.ekintza.includes('sortua') ? 'bg-sky-500 text-white' : (h.kantitatea > 0 ? 'bg-emerald-500 text-white' : 'bg-rose-500 text-white')">
                  {{ h.ekintza }}
                </span>
              </div>
              <p class="font-black text-white uppercase text-2xl md:text-4xl italic tracking-tighter leading-none group-hover:text-indigo-400 transition-colors">
                {{ h.produktua }}
              </p>
            </div>
            <div class="text-left md:text-right space-y-2 w-full md:w-auto">
              <div class="text-4xl md:text-5xl font-black tabular-nums" 
                    :class="h.ekintza.includes('sortua') ? 'text-sky-400' : (h.kantitatea > 0 ? 'text-emerald-400' : 'text-rose-400')">
                {{ h.kantitatea > 0 ? '+' : '' }}{{ h.kantitatea }}
              </div>
              <p class="text-[10px] font-black text-slate-500 uppercase italic">{{ new Date(h.created_at).toLocaleString() }}</p>
            </div>
          </div>
        </div>
      </section>
    </div>

    <div v-if="erakutsiModala" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-indigo-950/80 backdrop-blur-sm">
        <div class="bg-white w-full max-w-xl rounded-[3rem] p-10 shadow-2xl border-t-[12px] border-violet-500 animate-in fade-in zoom-in duration-300">
            <div class="flex justify-between items-center mb-8">
                <h3 class="text-4xl font-black italic uppercase text-indigo-900 tracking-tighter">Ikasle Berria</h3>
                <button @click="erakutsiModala = false" class="text-slate-300 hover:text-rose-500 text-2xl">✕</button>
            </div>
            <form @submit.prevent="erregistratuIkaslea" class="space-y-4">
                <input v-model="berria.name" type="text" placeholder="IZEN OSOA" class="w-full p-5 bg-slate-100 rounded-2xl border-none font-bold uppercase placeholder:text-slate-400" required>
                <input v-model="berria.email" type="email" placeholder="EMAILA" class="w-full p-5 bg-slate-100 rounded-2xl border-none font-bold uppercase placeholder:text-slate-400" required>
                <div class="grid grid-cols-2 gap-4">
                    <input v-model="berria.password" type="password" placeholder="PASAHITZA" class="p-5 bg-slate-100 rounded-2xl border-none font-bold uppercase placeholder:text-slate-400" required>
                    <input v-model="berria.password_confirmation" type="password" placeholder="REPETITU" class="p-5 bg-slate-100 rounded-2xl border-none font-bold uppercase placeholder:text-slate-400" required>
                </div>
                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-500 text-white p-6 rounded-2xl font-black italic uppercase tracking-widest transition-all mt-4">
                    DATU-BASEAN GEHITU
                </button>
            </form>
        </div>
    </div>
  </div>

  <div v-else class="min-h-[60vh] flex flex-col items-center justify-center space-y-6">
    <div class="w-24 h-24 border-8 border-indigo-600 border-t-transparent rounded-full animate-spin"></div>
    <p class="text-indigo-900 font-black uppercase italic tracking-widest animate-pulse text-2xl">Datuak sinkronizatzen...</p>
  </div>
</template>