<script setup>
import { ref } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'

const email = ref('')
const password = ref('')
const router = useRouter()

const login = async () => {
  try {
    const response = await axios.post('login', {
      email: email.value,
      password: password.value
    })
    
    localStorage.setItem('token', response.data.token)
    axios.defaults.headers.common['Authorization'] = `Bearer ${response.data.token}`
    
    console.log('🔥 Login zuzena! Ongi etorri.')
    window.location.href = '/' // Orrialdea birkargatu Navbar eguneratzeko
  } catch (error) {
    alert('Errorea: Emaila edo pasahitza ez dira zuzenak')
  }
}
</script>

<template>
  <div class="min-h-[80vh] flex items-center justify-center p-6 animate-fade-in relative overflow-hidden">
    
    <div class="max-w-md w-full bg-white rounded-[4rem] shadow-[0_35px_60px_-15px_rgba(0,0,0,0.3)] overflow-hidden border border-indigo-50 relative z-10">
      
      <div class="h-4 bg-indigo-600 w-full"></div>
      
      <div class="p-12 md:p-16 relative overflow-hidden">
        <div class="absolute -right-8 top-10 text-9xl font-black text-indigo-50/50 italic select-none -z-0">
          LOGIN
        </div>

        <div class="relative z-10">
          <div class="flex justify-center mb-8">
            <div class="bg-indigo-600 text-white w-20 h-20 rounded-[2rem] flex items-center justify-center text-4xl shadow-lg shadow-indigo-200">
              ⚡
            </div>
          </div>

          <h2 class="text-5xl font-black text-center text-indigo-950 mb-2 uppercase italic tracking-tighter">
            Sartu <span class="text-indigo-600">Hemen</span>
          </h2>
          <p class="text-center text-slate-400 font-bold uppercase text-[10px] tracking-[0.3em] mb-10">
            Zentroko Kudeaketa Sistema
          </p>

          <form @submit.prevent="login" class="space-y-6">
            <div class="space-y-2">
              <label class="text-[10px] font-black text-indigo-400 uppercase tracking-widest ml-4">Zure Emaila</label>
              <input 
                v-model="email" 
                type="email" 
                class="w-full px-8 py-5 bg-indigo-50/50 border-2 border-transparent rounded-[2rem] focus:border-indigo-500 focus:bg-white outline-none transition-all font-bold text-slate-700 relative z-10" 
                placeholder="idatzi@email.com"
              >
            </div>

            <div class="space-y-2">
              <label class="text-[10px] font-black text-indigo-400 uppercase tracking-widest ml-4">Pasahitza</label>
              <input 
                v-model="password" 
                type="password" 
                class="w-full px-8 py-5 bg-indigo-50/50 border-2 border-transparent rounded-[2rem] focus:border-indigo-500 focus:bg-white outline-none transition-all font-bold text-slate-700 relative z-10" 
                placeholder="••••••••"
              >
            </div>

            <button 
              type="submit" 
              class="w-full py-6 bg-indigo-600 text-white font-black rounded-[2rem] hover:bg-indigo-700 transition-all shadow-[0_15px_30px_-5px_rgba(79,70,229,0.4)] active:scale-95 uppercase tracking-widest text-sm mt-4 relative z-10"
            >
              Hasi Saioa 🚀
            </button>
          </form>

          <div class="mt-10 text-center">
            <p class="text-slate-400 text-xs font-bold italic">
              Arazoak dituzu sartzeko? <br>
              <span class="text-indigo-500 not-italic uppercase tracking-tighter cursor-pointer hover:underline">Jarri harremanetan administratzailearekin</span>
            </p>
          </div>
        </div>
      </div>
    </div>

    <div class="absolute -bottom-20 -right-10 text-[25rem] font-black text-indigo-600/[0.03] italic select-none pointer-events-none">
      SARTU
    </div>
  </div>
</template>

<style scoped>
.animate-fade-in {
  animation: fadeIn 0.8s cubic-bezier(0.16, 1, 0.3, 1);
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(40px); }
  to { opacity: 1; transform: translateY(0); }
}

input:focus {
  box-shadow: 0 10px 20px -5px rgba(99, 102, 241, 0.1);
}
</style>