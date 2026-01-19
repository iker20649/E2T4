import { createApp } from 'vue'
import { createPinia } from 'pinia'
import App from './App.vue'
import router from './router'
import './assets/main.css' // <-- Sin esto, el menú azul se verá feo y sin color

const app = createApp(App)
app.use(createPinia())
app.use(router)
app.mount('#app')