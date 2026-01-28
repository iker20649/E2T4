import { createApp } from 'vue'
import axios from 'axios'
import App from './App.vue'
import router from './router'
import './assets/main.css' // <--- ¡ESTA LÍNEA ES CLAVE! Debe importar tu CSS con Tailwind


axios.defaults.baseURL = 'http://127.0.0.1';

// CRÍTICO: withCredentials debe ser FALSE para evitar el error 419
axios.defaults.withCredentials = false;

// Cargar token si el usuario refresca el navegador
const token = localStorage.getItem('token');
if (token) {
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
}

const app = createApp(App)
app.use(router)
app.mount('#app')



