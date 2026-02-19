import { createApp } from 'vue'
import axios from 'axios'
import App from './App.vue'
import router from './router'
import './assets/main.css'

// AWS IP-a eta portua, /api aurrizkiarekin
// Cambia la IP y el puerto según lo que hayas visto en el paso anterior
axios.defaults.baseURL = '/api';

// API bat denez, hau gehitzea komeni da Laravel-ek JSON espero duela jakiteko
axios.defaults.headers.common['Accept'] = 'application/json';

// withCredentials false-n utzi, Sanctum token bidez (Bearer) erabiltzen ari garelako
axios.defaults.withCredentials = false;

const token = localStorage.getItem('token');
if (token) {
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
}

const app = createApp(App)
app.use(router)
app.mount('#app')