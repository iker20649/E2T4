<script setup>
import { ref } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'

const router = useRouter()
// Valores por defecto (útiles para desarrollo, bórralos para producción)
const email = ref('irakasle@test.com') 
const password = ref('password123') 

const login = async () => { // He cambiado el nombre a 'login' para que coincida con el HTML
  try {
    const response = await axios.post('/api/login', {
      email: email.value,
      password: password.value
    });

    // Extraemos token y datos del usuario
    const token = response.data.access_token || response.data.token;
    const user = response.data.user;

    if (token && user) {
      // GUARDADO EN LOCALSTORAGE
      localStorage.setItem('token', token);
      localStorage.setItem('rol', user.rola);
      
      // Configurar Axios para que la siguiente petición ya lleve el token
      axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;

      // Redirigir a Hitzorduak
      router.push('/hitzorduak');
    } else {
      alert("Errorea: Tokena edo erabiltzailea falta da.");
    }

  } catch (error) {
    console.error("Login errorea:", error);
    alert("Saioa hasten huts egin du. Egiaztatu zure datuak.");
  }
};
</script>

<template>
  <div class="min-h-screen w-full flex items-center justify-center bg-[#f1f5f9] italic uppercase font-sans">
    <div class="max-w-md w-full bg-white p-12 rounded-[3rem] shadow-2xl border-b-[12px] border-indigo-600 mx-4">
      
      <h2 class="text-4xl font-black text-center text-indigo-950 mb-8 tracking-tighter">
        ILEA<span class="text-indigo-600">.</span>APP
      </h2>

      <form @submit.prevent="login" class="space-y-5 not-italic">
        <div>
          <label class="block text-xs font-black text-indigo-900 mb-1 ml-2 uppercase">Emaila</label>
          <input v-model="email" type="email" required
            class="w-full p-5 bg-slate-50 rounded-2xl border-2 border-slate-100 focus:border-indigo-500 outline-none font-bold shadow-inner transition-all">
        </div>

        <div>
          <label class="block text-xs font-black text-indigo-900 mb-1 ml-2 uppercase">Pasahitza</label>
          <input v-model="password" type="password" required
            class="w-full p-5 bg-slate-50 rounded-2xl border-2 border-slate-100 focus:border-indigo-500 outline-none font-bold shadow-inner transition-all">
        </div>

        <button type="submit" 
          class="w-full bg-indigo-600 text-white p-5 rounded-2xl font-black uppercase tracking-widest hover:bg-indigo-700 transition-all active:scale-95 shadow-lg mt-4 border-b-4 border-indigo-800">
          Hasi Saioa 🚀
        </button>
      </form>

    </div>
  </div>
</template> 