import { createApp } from 'vue'
import { createPinia } from 'pinia'
import axios from 'axios'
import App from './App.vue'
import router from './router'
import './assets/main.css'

// Configuración global de Axios
axios.defaults.baseURL = 'http://localhost:8000/api/'
axios.defaults.withCredentials = true

// Si el usuario ya estaba logueado, recuperamos el token para todas las peticiones
const token = localStorage.getItem('token')
if (token) {
  axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
}

const app = createApp(App)
app.use(createPinia())
app.use(router)
app.mount('#app')