<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const user = ref({ name: '', email: '', rola: '', argazkia: '' })
const password = ref('')
const password_confirmation = ref('')
const fitxategia = ref(null)

const API_URL = 'http://localhost:8000'

const kargatu = async () => {
  const token = localStorage.getItem('token')
  try {
    const res = await axios.get('user', { headers: { Authorization: `Bearer ${token}` } })
    user.value = res.data
  } catch (e) {
    console.error("Errorea kargatzean", e)
  }
}

const gorde = async () => {
  const token = localStorage.getItem('token')
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
    const res = await axios.post('profile/update', fd, {
      headers: { 
        'Content-Type': 'multipart/form-data', 
        Authorization: `Bearer ${token}` 
      }
    })
    
    alert("✅ Datuak ondo gorde dira!")
    user.value = res.data.user
    password.value = ''
    password_confirmation.value = ''
    fitxategia.value = null
    await kargatu()
  } catch (e) {
    alert("Errorea gordetzean")
  }
}

onMounted(kargatu)
</script>

<template>
  <div class="max-w-5xl mx-auto space-y-8 animate-fade-in pb-20">
    
    <header class="relative overflow-hidden rounded-[3.5rem] shadow-2xl border-b-[12px] border-indigo-500 bg-[#1e1b4b]">
      <div class="absolute top-0 right-0 w-1/2 h-full bg-gradient-to-l from-indigo-500/10 to-transparent"></div>
      
      <div class="relative z-10 p-10 md:p-16 flex flex-col md:flex-row items-center gap-10">
        <div class="relative group">
          <div class="absolute -inset-2 bg-gradient-to-tr from-indigo-500 to-rose-500 rounded-[3.5rem] blur opacity-40 group-hover:opacity-70 transition duration-500"></div>
          <img 
            :src="user.argazkia ? (API_URL + user.argazkia) : ('https://ui-avatars.com/api/?background=fff&color=000&name=' + user.name)" 
            class="relative w-44 h-44 rounded-[3rem] border-4 border-white/20 object-cover shadow-2xl transition-transform group-hover:scale-105 duration-500"
            @error="(e) => e.target.src = 'https://ui-avatars.com/api/?name=' + user.name"
          >
          <div class="absolute -bottom-2 -right-2 bg-indigo-500 text-white p-3 rounded-2xl shadow-xl font-black text-xl border-4 border-[#1e1b4b]">✨</div>
        </div>

        <div class="text-center md:text-left text-white">
          <div class="inline-block bg-indigo-500/20 backdrop-blur-md text-indigo-300 px-6 py-1.5 rounded-full text-[10px] font-black uppercase tracking-[0.3em] border border-indigo-500/30 mb-6">
            {{ user.rola }} modua
          </div>
          <h1 class="text-6xl md:text-7xl font-black italic tracking-tighter uppercase leading-none">
            {{ user.name.split(' ')[0] }}<span class="text-indigo-500">.</span>
          </h1>
          <p class="mt-4 text-indigo-200/50 font-medium italic text-lg max-w-sm leading-relaxed">
            Zure profilaren ezarpenak kudeatu eta segurtasuna bermatu.
          </p>
        </div>
      </div>

      <div class="absolute right-[-2rem] top-1/2 -translate-y-1/2 text-[15rem] text-white/[0.03] font-black italic select-none leading-none pointer-events-none uppercase">
        Perfil
      </div>
    </header>

    <div class="bg-white rounded-[4rem] shadow-xl overflow-hidden border border-indigo-50">
      <div class="p-10 md:p-20 grid grid-cols-1 md:grid-cols-2 gap-12">
        
        <div class="space-y-8">
          <h3 class="text-2xl font-black uppercase italic text-slate-800 flex items-center gap-4">
            <span class="w-10 h-2 bg-indigo-600 rounded-full block"></span> Datuak
          </h3>
          
          <div class="space-y-2">
            <label class="text-[10px] font-black text-indigo-400 uppercase tracking-widest ml-4">Erabiltzaile Izena</label>
            <input v-model="user.name" type="text" class="w-full p-6 bg-indigo-50/50 border-2 border-transparent rounded-[2.5rem] focus:border-indigo-500 focus:bg-white outline-none transition-all font-bold text-slate-700 shadow-inner">
          </div>

          <div class="space-y-2">
            <label class="text-[10px] font-black text-indigo-400 uppercase tracking-widest ml-4">Posta Elektronikoa</label>
            <input v-model="user.email" type="email" class="w-full p-6 bg-indigo-50/50 border-2 border-transparent rounded-[2.5rem] focus:border-indigo-500 focus:bg-white outline-none transition-all font-bold text-slate-700 shadow-inner">
          </div>
        </div>

        <div class="space-y-8">
          <h3 class="text-2xl font-black uppercase italic text-slate-800 flex items-center gap-4">
            <span class="w-10 h-2 bg-rose-500 rounded-full block"></span> Segurtasuna
          </h3>
          
          <div class="space-y-2">
            <label class="text-[10px] font-black text-rose-400 uppercase tracking-widest ml-4">Pasahitz Berria</label>
            <input v-model="password" type="password" placeholder="••••••••" class="w-full p-6 bg-rose-50/30 border-2 border-transparent rounded-[2.5rem] focus:border-rose-500 focus:bg-white outline-none transition-all font-bold text-slate-700 shadow-inner">
          </div>

          <div class="space-y-2">
            <label class="text-[10px] font-black text-rose-400 uppercase tracking-widest ml-4">Errepikatu</label>
            <input v-model="password_confirmation" type="password" placeholder="••••••••" class="w-full p-6 bg-rose-50/30 border-2 border-transparent rounded-[2.5rem] focus:border-rose-500 focus:bg-white outline-none transition-all font-bold text-slate-700 shadow-inner">
          </div>
        </div>

        <div class="md:col-span-2 bg-slate-900 p-10 rounded-[3rem] text-white relative overflow-hidden group border-b-8 border-indigo-500">
          <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-8">
            <div>
              <h4 class="text-3xl font-black italic uppercase tracking-tighter">Profil Argazkia</h4>
              <p class="text-indigo-400 text-xs font-black uppercase tracking-[0.2em] mt-2">Aukeratu fitxategia (JPG, PNG)</p>
            </div>
            <input type="file" @change="e => fitxategia = e.target.files[0]" 
                   class="block w-full md:w-auto text-sm text-slate-400 
                          file:mr-6 file:py-4 file:px-10 file:rounded-2xl file:border-0 
                          file:text-xs file:font-black file:uppercase file:bg-indigo-600 file:text-white hover:file:bg-indigo-500 
                          file:transition-all cursor-pointer shadow-xl">
          </div>
          <div class="absolute -right-10 -bottom-10 text-[10rem] text-white/5 font-black italic select-none">PHOTO</div>
        </div>

        <div class="md:col-span-2">
          <button @click="gorde" 
                  class="w-full py-8 bg-indigo-600 text-white font-black rounded-[2.5rem] shadow-xl hover:bg-indigo-700 hover:scale-[1.01] transition-all active:scale-95 uppercase tracking-[0.2em] text-sm">
            Gorde aldaketa guztiak ⚡
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.animate-fade-in { animation: fadeIn 0.8s cubic-bezier(0.16, 1, 0.3, 1); }
@keyframes fadeIn { from { opacity: 0; transform: translateY(40px); } to { opacity: 1; transform: translateY(0); } }
input:-webkit-autofill { -webkit-box-shadow: 0 0 0 100px white inset !important; }
</style>