<script setup>
import { ref, onMounted, computed } from 'vue'
import axios from 'axios'

const bezeroakRaw = ref([])
const rola = ref('')
const kargatzen = ref(true)
const berria = ref({ izena: '', abizenak: '', telefonoa: '', deskribapena: '' })

const kargatuDatuak = async () => {
  kargatzen.value = true
  try {
    const userRes = await axios.get('/user')
    // Rolaren konparaketa seguruagoa egiteko (lowercase)
    rola.value = userRes.data.rola.toLowerCase().trim()
    
    const res = await axios.get('/bezeroak')
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
    await axios.post('/bezeroak', berria.value)
    berria.value = { izena: '', abizenak: '', telefonoa: '', deskribapena: '' }
    await kargatuDatuak()
  } catch (e) { 
    alert("Errorea gordetzean") 
  }
}

const ezabatuBezeroa = async (id) => {
  if (!confirm("Ziur zaude bezero hau ezabatu nahi duzula?")) return
  try {
    await axios.delete(`/bezeroak/${id}`)
    await kargatuDatuak() // Zerrenda berritu
  } catch (e) {
    alert("Ezin izan da bezeroa ezabatu")
  }
}

onMounted(kargatuDatuak)
</script>

<template>
  <div class="max-w-7xl mx-auto p-6 space-y-8">
    <h1 class="text-4xl font-black italic uppercase text-slate-900">Bezeroen Fitxategia</h1>

    <div v-if="rola !== 'irakasle'" class="bg-white p-6 rounded-3xl shadow-xl border border-indigo-50">
      <h3 class="font-black text-indigo-900 mb-4 uppercase text-xs tracking-widest">✨ Bezero Berria</h3>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
        <input v-model="berria.izena" placeholder="Izena" class="p-3 bg-slate-50 rounded-xl border-none ring-1 ring-slate-200 focus:ring-2 focus:ring-indigo-500">
        <input v-model="berria.abizenak" placeholder="Abizenak" class="p-3 bg-slate-50 rounded-xl border-none ring-1 ring-slate-200 focus:ring-2 focus:ring-indigo-500">
        <input v-model="berria.telefonoa" placeholder="Telefonoa" class="p-3 bg-slate-50 rounded-xl border-none ring-1 ring-slate-200 focus:ring-2 focus:ring-indigo-500">
      </div>
      <textarea v-model="berria.deskribapena" placeholder="Deskribapena..." class="w-full p-3 bg-slate-50 rounded-xl border-none ring-1 ring-slate-200 mb-4" rows="2"></textarea>
      <button @click="gordeBezeroa" class="w-full bg-indigo-600 text-white p-3 rounded-xl font-bold uppercase text-xs hover:bg-indigo-700 transition">Gorde Bezeroa</button>
    </div>

    <div v-if="kargatzen" class="text-center py-10 font-bold text-slate-400">KARGATZEN...</div>

    <div v-else>
      <div v-if="bezeroZerrenda.length > 0">
        
        <div v-if="rola === 'irakasle'" class="space-y-12">
          <div v-for="grupo in bezeroZerrenda" :key="grupo.ikasleIzena" class="space-y-6">
            <h2 class="bg-indigo-600 text-white px-4 py-1 rounded-full text-xs font-black uppercase inline-block shadow-md">🎓 {{ grupo.ikasleIzena }}</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
              <div v-for="b in grupo.bezeroak" :key="b.id" class="bg-white p-6 rounded-[2rem] shadow-xl border border-slate-50 group">
                <div class="flex justify-between items-start mb-4">
                  <div class="h-12 w-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center font-black text-xl italic">
                    {{ b.izena ? b.izena[0] : '?' }}
                  </div>
                  <button @click="ezabatuBezeroa(b.id)" class="text-slate-300 hover:text-red-500 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                  </button>
                </div>
                <h3 class="font-black text-slate-900 text-lg">{{ b.izena }} {{ b.abizenak }}</h3>
                <p class="text-indigo-500 text-xs font-bold mb-4">{{ b.telefonoa || '---' }}</p>
                <div class="bg-slate-50 p-4 rounded-2xl border border-dashed border-slate-200">
                  <p class="text-sm text-slate-600 italic">{{ b.deskribapena || 'Ez dago oharrik.' }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div v-for="b in bezeroZerrenda" :key="b.id" class="bg-white p-6 rounded-[2rem] shadow-xl border border-slate-100 relative group">
            <div class="flex justify-between items-start mb-4">
              <div class="h-12 w-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center font-black text-xl italic">
                {{ b.izena ? b.izena[0] : '?' }}
              </div>
              <button @click="ezabatuBezeroa(b.id)" class="opacity-0 group-hover:opacity-100 text-slate-300 hover:text-red-500 transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
              </button>
            </div>
            <h3 class="font-black text-slate-900 text-lg">{{ b.izena }} {{ b.abizenak }}</h3>
            <p class="text-indigo-500 text-xs font-bold mb-4">{{ b.telefonoa || '---' }}</p>
            <div class="bg-slate-50 p-4 rounded-2xl border border-dashed border-slate-200">
              <p class="text-sm text-slate-600 italic">{{ b.deskribapena || 'Ez dago oharrik.' }}</p>
            </div>
          </div>
        </div>

      </div>

      <div v-else class="text-center py-20 bg-white rounded-[3rem] border-2 border-dashed border-slate-200">
        <p class="text-slate-400 font-bold uppercase italic">Ez dago bezerorik</p>
      </div>
    </div>
  </div>
</template>