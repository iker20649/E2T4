<script setup>
import { ref } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'

const router = useRouter()
const email = ref('')
const password = ref('')
const error = ref('')

// Konfigurazioa
// Cambia la IP y el puerto según lo que hayas visto en el paso anterior
axios.defaults.baseURL = 'http://98.93.71.5';
axios.defaults.withCredentials = true;

const login = async () => {
  error.value = ''
  try {
    // 1. Laravelek JSON espero duela ziurtatu
    const response = await axios.post('/login', {
      email: email.value,
      password: password.value
    }, {
      headers: {
        'Accept': 'application/json'
      }
    });

    // 2. Tokena eta Rola jaso
    if (response.data.access_token) {
      const token = response.data.access_token;
      const userRola = response.data.user.rola; // Backendetik datorren rola

      // 3. Lokaluki gorde (Routerrak eta Axios-ek erabiltzeko)
      localStorage.setItem('token', token);
      localStorage.setItem('rol', userRola); // Gako hau ezinbestekoa da zure routerrako
      
      axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
      
      // 4. Bide zuzenera joan (Zure routerrean /hitzorduak da nagusia)
      router.push('/hitzorduak');
    }
  } catch (err) {
    if (err.response && err.response.status === 422) {
      error.value = "Emaila edo pasahitza okerra da.";
    } else {
      error.value = "Zerbitzari errore bat gertatu da.";
    }
    console.error("Login Errorea:", err.response?.data);
  }
}
</script>

<template>
  <div class="min-h-screen flex items-center justify-center bg-slate-100 uppercase italic p-4 font-sans">
    <div class="max-w-md w-full bg-white p-10 rounded-[3rem] shadow-2xl border-b-[12px] border-indigo-600 animate-fade-in">
      <h2 class="text-4xl font-black text-center text-indigo-950 mb-8 tracking-tighter italic">
        ILEA<span class="text-indigo-600">.</span>APP
      </h2>
      
      <form @submit.prevent="login" class="space-y-6 not-italic">
        <div>
          <label class="block text-[10px] font-black text-indigo-900 mb-1 ml-2 uppercase tracking-widest">Emaila</label>
          <input v-model="email" type="email" placeholder="admin@admin.com" 
            class="w-full p-5 bg-slate-50 rounded-2xl border-2 border-slate-100 outline-none focus:border-indigo-500 font-bold transition-all shadow-inner">
        </div>

        <div>
          <label class="block text-[10px] font-black text-indigo-900 mb-1 ml-2 uppercase tracking-widest">Pasahitza</label>
          <input v-model="password" type="password" placeholder="••••••••" 
            class="w-full p-5 bg-slate-50 rounded-2xl border-2 border-slate-100 outline-none focus:border-indigo-500 font-bold transition-all shadow-inner">
        </div>

        <p v-if="error" class="text-red-600 text-[10px] font-black text-center uppercase bg-red-50 py-2 rounded-lg border border-red-100">
          {{ error }}
        </p>

        <button type="submit" 
          class="w-full bg-indigo-600 text-white p-5 rounded-2xl font-black uppercase tracking-widest hover:bg-indigo-700 active:scale-95 transition-all shadow-lg border-b-4 border-indigo-800">
          Hasi Saioa 🚀
        </button>
      </form>
    </div>
  </div>
</template>

<style scoped>
.animate-fade-in {
  animation: fadeIn 0.5s ease-out;
}
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}
</style>