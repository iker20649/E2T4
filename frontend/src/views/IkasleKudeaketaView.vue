<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const ikasleak = ref([])
const historiala = ref([])
const loading = ref(true)

const kargatuDatuak = async () => {
  try {
    const token = localStorage.getItem('token')
    const [resIkas, resHist] = await Promise.all([
      axios.get('admin/ikasleak', { headers: { Authorization: `Bearer ${token}` } }),
      axios.get('admin/stock-historiala', { headers: { Authorization: `Bearer ${token}` } })
    ])
    ikasleak.value = resIkas.data
    historiala.value = resHist.data
  } catch (e) {
    console.error("Errorea datuak kargatzean:", e)
  } finally {
    loading.value = false
  }
}

const ezabatuIkaslea = async (id, izena) => {
  if (!confirm(`Ziur zaude ${izena} ikaslea ezabatu nahi duzula?`)) return
  try {
    const token = localStorage.getItem('token')
    await axios.delete(`admin/ikasleak/${id}`, { headers: { Authorization: `Bearer ${token}` } })
    kargatuDatuak()
  } catch (e) {
    alert("Ezin izan da ikaslea ezabatu")
  }
}

onMounted(kargatuDatuak)
</script>

<template>
  <div v-if="!loading" class="max-w-7xl mx-auto space-y-12 animate-fade-in pb-20">
    
    <header class="bg-indigo-900 text-white p-16 rounded-[4rem] shadow-[0_35px_60px_-15px_rgba(79,70,229,0.4)] border-b-[12px] border-violet-500 relative overflow-hidden">
      <div class="relative z-10">
        <span class="bg-violet-400/30 text-violet-200 px-6 py-2 rounded-full text-xs font-black uppercase tracking-widest border border-violet-400/30">
          Admin Kontrol Panela
        </span>
        <h1 class="text-8xl font-black italic mt-10 tracking-tighter uppercase leading-none">
          Kudeaketa <span class="text-violet-400">Guztia</span>
        </h1>
        <p class="mt-8 text-indigo-100/60 max-w-2xl text-2xl font-medium italic">Ikasleen zerrenda eta materialaren mugimendu guztiak denbora errealean.</p>
      </div>
      <div class="absolute right-[-5rem] top-[-5rem] text-[20rem] opacity-5 font-black italic select-none uppercase">Admin</div>
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
              <div>
                <p class="font-black text-slate-800 uppercase italic text-sm leading-none">{{ i.name }}</p>
                <p class="text-[10px] text-indigo-400 font-bold mt-1 uppercase tracking-tighter">{{ i.email }}</p>
              </div>
            </div>
            <button @click="ezabatuIkaslea(i.id, i.name)" 
                    class="p-3 bg-rose-50 text-rose-300 hover:bg-rose-500 hover:text-white rounded-xl transition-all active:scale-90">
              <span class="text-lg">🗑️</span>
            </button>
          </div>
          
          <div v-if="ikasleak.length === 0" class="p-20 text-center italic text-slate-400 font-bold">
            Ez dago ikaslerik.
          </div>
        </div>
      </section>

      <section class="lg:col-span-2 space-y-6">
        <h2 class="text-3xl font-black uppercase italic text-indigo-900 tracking-tighter px-4">📜 Stockaren Log-a</h2>
        
        <div class="bg-slate-900 rounded-[4rem] p-10 shadow-2xl space-y-4 max-h-[800px] overflow-y-auto border-t-[12px] border-indigo-500">
          <div v-for="h in historiala" :key="h.id" 
               class="flex justify-between items-center bg-white/5 p-6 rounded-[2.5rem] border border-white/5 hover:bg-white/10 transition-all group">
            
            <div class="space-y-3">
              <div class="flex items-center gap-3">
                <span class="text-[10px] font-black bg-indigo-600 text-white px-3 py-1 rounded-lg uppercase tracking-widest">
                  👤 {{ h.user?.name || 'Sistema' }}
                </span>
                <span class="text-[10px] font-black px-3 py-1 rounded-lg uppercase tracking-widest"
                      :class="h.ekintza.includes('sortua') ? 'bg-sky-500 text-white' : (h.kantitatea > 0 ? 'bg-emerald-500 text-white' : 'bg-rose-500 text-white')">
                  {{ h.ekintza }}
                </span>
              </div>
              <p class="font-black text-white uppercase text-4xl italic tracking-tighter leading-none group-hover:text-indigo-400 transition-colors">
                {{ h.produktua }}
              </p>
            </div>

            <div class="text-right space-y-2">
              <div class="text-5xl font-black tabular-nums" 
                   :class="h.ekintza.includes('sortua') ? 'text-sky-400' : (h.kantitatea > 0 ? 'text-emerald-400' : 'text-rose-400')">
                {{ h.kantitatea > 0 ? '+' : '' }}{{ h.kantitatea }}
              </div>
              <p class="text-[10px] font-black text-slate-500 uppercase italic">
                {{ new Date(h.created_at).toLocaleString() }}
              </p>
            </div>
          </div>

          <div v-if="historiala.length === 0" class="p-32 text-center">
            <p class="text-slate-600 font-black uppercase tracking-[0.3em]">Historiala hutsik dago</p>
          </div>
        </div>
      </section>
      
    </div>
  </div>

  <div v-else class="min-h-[60vh] flex flex-col items-center justify-center space-y-6">
    <div class="w-24 h-24 border-8 border-indigo-600 border-t-transparent rounded-full animate-spin"></div>
    <p class="text-indigo-900 font-black uppercase italic tracking-widest animate-pulse text-2xl">Datuak sinkronizatzen...</p>
  </div>
</template>

<style scoped>
.animate-fade-in { animation: fadeIn 0.8s ease-out; }
@keyframes fadeIn { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }

/* Scrollbar estilo modernoagoa log-erako */
.overflow-y-auto::-webkit-scrollbar { width: 8px; }
.overflow-y-auto::-webkit-scrollbar-track { background: transparent; }
.overflow-y-auto::-webkit-scrollbar-thumb { background: #312e81; border-radius: 10px; }
.overflow-y-auto::-webkit-scrollbar-thumb:hover { background: #4338ca; }
</style>