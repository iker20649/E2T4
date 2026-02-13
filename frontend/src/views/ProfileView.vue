<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const user = ref({ name: '', email: '', rola: '', argazkia: '' })
const password = ref('')
const password_confirmation = ref('')
const fitxategia = ref(null)

// AWS IP Helbidea
// Cambia la IP y el puerto según lo que hayas visto en el paso anterior
axios.defaults.baseURL = 'http://98.93.71.5';
axios.defaults.withCredentials = true;
axios.defaults.baseURL = API_URL;

const kargatu = async () => {
  try {
    const res = await axios.get('/api/user')
    user.value = res.data
  } catch (e) {
    console.error("Errorea kargatzean", e)
  }
}

const gorde = async () => {
  const fd = new FormData()
  fd.append('name', user.value.name)
  fd.append('email', user.value.email)
  
  if (password.value.trim() !== '') {
    fd.append('password', password.value)
    fd.append('password_confirmation', password_confirmation.value)
  }
  
  if (fitxategia.value) {
    fd.append('argazkia', fitxategia.value)
  }

  try {
    const res = await axios.post('/api/profile/update', fd, {
      headers: { 'Content-Type': 'multipart/form-data' }
    })
    
    alert("✅ Datuak ondo gorde dira!")
    if (res.data.user) {
        user.value = res.data.user
    }
    
    password.value = ''
    password_confirmation.value = ''
    fitxategia.value = null
    await kargatu()
  } catch (e) {
    console.error(e)
    alert("Errorea gordetzean")
  }
}

onMounted(kargatu)
</script>

<template>
  <div class="max-w-5xl mx-auto space-y-6 md:space-y-8 animate-fade-in pb-20 pt-6 md:pt-10 px-4">
    
    <header class="relative overflow-hidden rounded-[2.5rem] md:rounded-[3.5rem] shadow-2xl border-b-[10px] md:border-b-[12px] border-indigo-500 bg-[#1e1b4b]">
      <div class="absolute top-0 right-0 w-1/2 h-full bg-gradient-to-l from-indigo-500/10 to-transparent"></div>
      
      <div class="relative z-10 p-8 md:p-16 flex flex-col md:flex-row items-center gap-8 md:gap-10">
        <div class="relative group">
          <div class="absolute -inset-2 bg-gradient-to-tr from-indigo-500 to-rose-500 rounded-[2.5rem] md:rounded-[3.5rem] blur opacity-40 group-hover:opacity-70 transition duration-500"></div>
          <img 
            :src="user.argazkia ? (API_URL + user.argazkia) : ('https://ui-avatars.com/api/?background=fff&color=000&name=' + user.name)" 
            class="relative w-32 h-32 md:w-44 md:h-44 rounded-[2rem] md:rounded-[3rem] border-4 border-white/20 object-cover shadow-2xl transition-transform group-hover:scale-105 duration-500"
            @error="(e) => e.target.src = 'https://ui-avatars.com/api/?name=' + user.name"
          >
          <div class="absolute -bottom-1 -right-1 md:-bottom-2 md:-right-2 bg-indigo-500 text-white p-2 md:p-3 rounded-xl md:rounded-2xl shadow-xl font-black text-sm md:text-xl border-4 border-[#1e1b4b]">✨</div>
        </div>

        <div class="text-center md:text-left text-white">
          <div class="inline-block bg-indigo-500/20 backdrop-blur-md text-indigo-300 px-4 py-1.5 rounded-full text-[9px] md:text-[10px] font-black uppercase tracking-[0.2em] md:tracking-[0.3em] border border-indigo-500/30 mb-4 md:mb-6">
            {{ user.rola }} modua
          </div>
          <h1 class="text-4xl md:text-7xl font-black italic tracking-tighter uppercase leading-none">
            {{ user.name ? user.name.split(' ')[0] : 'Erabiltzailea' }}<span class="text-indigo-500">.</span>
          </h1>
          <p class="mt-4 text-indigo-200/50 font-medium italic text-sm md:text-lg max-w-sm leading-relaxed mx-auto md:mx-0">
            Zure profilaren ezarpenak kudeatu eta segurtasuna bermatu.
          </p>
        </div>
      </div>
    </header>

    <div class="bg-white rounded-[2.5rem] md:rounded-[4rem] shadow-xl overflow-hidden border border-indigo-50">
      <div class="p-8 md:p-20 grid grid-cols-1 md:grid-cols-2 gap-10 md:gap-12">
        
        <div class="space-y-6 md:space-y-8">
          <h3 class="text-xl md:text-2xl font-black uppercase italic text-slate-800 flex items-center gap-4">
            <span class="w-8 md:w-10 h-2 bg-indigo-600 rounded-full block"></span> Datuak
          </h3>
          <div class="space-y-2">
            <label class="text-[10px] font-black text-indigo-400 uppercase tracking-widest ml-4">Izena</label>
            <input v-model="user.name" type="text" class="w-full p-5 md:p-6 bg-indigo-50/50 border-2 border-transparent rounded-[1.5rem] md:rounded-[2.5rem] focus:border-indigo-500 focus:bg-white outline-none transition-all font-bold text-slate-700 text-sm md:text-base">
          </div>
          <div class="space-y-2">
            <label class="text-[10px] font-black text-indigo-400 uppercase tracking-widest ml-4">Emaila</label>
            <input v-model="user.email" type="email" class="w-full p-5 md:p-6 bg-indigo-50/50 border-2 border-transparent rounded-[1.5rem] md:rounded-[2.5rem] focus:border-indigo-500 focus:bg-white outline-none transition-all font-bold text-slate-700 text-sm md:text-base">
          </div>
        </div>

        <div class="space-y-6 md:space-y-8">
          <h3 class="text-xl md:text-2xl font-black uppercase italic text-slate-800 flex items-center gap-4">
            <span class="w-8 md:w-10 h-2 bg-rose-500 rounded-full block"></span> Segurtasuna
          </h3>
          <div class="space-y-2">
            <label class="text-[10px] font-black text-rose-400 uppercase tracking-widest ml-4">Pasahitz Berria</label>
            <input v-model="password" type="password" placeholder="••••••••" class="w-full p-5 md:p-6 bg-rose-50/30 border-2 border-transparent rounded-[1.5rem] md:rounded-[2.5rem] focus:border-rose-500 focus:bg-white outline-none transition-all font-bold text-slate-700 text-sm md:text-base">
          </div>
          <div class="space-y-2">
            <label class="text-[10px] font-black text-rose-400 uppercase tracking-widest ml-4">Errepikatu</label>
            <input v-model="password_confirmation" type="password" placeholder="••••••••" class="w-full p-5 md:p-6 bg-rose-50/30 border-2 border-transparent rounded-[1.5rem] md:rounded-[2.5rem] focus:border-rose-500 focus:bg-white outline-none transition-all font-bold text-slate-700 text-sm md:text-base">
          </div>
        </div>

        <div class="md:col-span-2 bg-slate-900 p-6 md:p-10 rounded-[2rem] md:rounded-[3rem] text-white relative overflow-hidden group border-b-8 border-indigo-500">
          <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6 md:gap-8 text-center md:text-left">
            <div>
              <h4 class="text-2xl md:text-3xl font-black italic uppercase tracking-tighter">Profil Argazkia</h4>
              <p class="text-indigo-400 text-[10px] font-black uppercase tracking-[0.2em] mt-2">Aukeratu fitxategia (JPG, PNG)</p>
            </div>
            <input type="file" @change="e => fitxategia = e.target.files[0]" 
                   class="block w-full md:w-auto text-xs text-slate-400 
                          file:mr-4 md:file:mr-6 file:py-3 md:file:py-4 file:px-6 md:file:px-10 file:rounded-xl md:file:rounded-2xl file:border-0 
                          file:text-[10px] file:font-black file:uppercase file:bg-indigo-600 file:text-white hover:file:bg-indigo-500 
                          file:transition-all cursor-pointer">
          </div>
          <div class="absolute -right-10 -bottom-10 text-[6rem] md:text-[10rem] text-white/5 font-black italic select-none hidden sm:block">PHOTO</div>
        </div>

        <div class="md:col-span-2">
          <button @click="gorde" 
                  class="w-full py-6 md:py-8 bg-indigo-600 text-white font-black rounded-[1.5rem] md:rounded-[2.5rem] shadow-xl hover:bg-indigo-700 hover:scale-[1.01] transition-all active:scale-95 uppercase tracking-[0.2em] text-xs md:text-sm">
            Gorde aldaketa guztiak ⚡
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.animate-fade-in { animation: fadeIn 0.8s ease-out; }
@keyframes fadeIn { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
</style>