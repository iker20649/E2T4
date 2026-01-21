<script setup>
import { RouterLink } from 'vue-router'
</script>

<template>
  <div class="relative overflow-hidden">
    <div class="absolute -top-24 -left-24 w-96 h-96 bg-indigo-100 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob"></div>
    <div class="absolute top-0 -right-24 w-96 h-96 bg-emerald-100 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob animation-delay-2000"></div>

    <div class="relative max-w-7xl mx-auto">
      <div class="text-center space-y-8 py-12 md:py-24">
        
        <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-bold bg-indigo-100 text-indigo-700 uppercase tracking-widest shadow-sm">
          📍 CIFP Monte Albertia LHII
        </span>

        <h1 class="text-5xl md:text-7xl font-black text-gray-900 tracking-tight">
          Zure ileapaindegia, <br/>
          <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-emerald-500">
            klik batera.
          </span>
        </h1>

        <p class="max-w-2xl mx-auto text-lg md:text-xl text-gray-600 leading-relaxed font-medium">
          Ongi etorri <strong>Ilea-App</strong>-era. Ikasle eta irakasleentzako espazio digitala: hitzorduak kudeatu, stocka kontrolatu eta zure profila pertsonalizatu modu erraz eta modernoan.
        </p>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mt-10">
          <RouterLink to="/hitzorduak" class="w-full sm:w-auto px-8 py-4 bg-indigo-700 text-white font-black rounded-2xl shadow-xl hover:bg-indigo-800 transition-all hover:scale-105 active:scale-95 text-center uppercase tracking-wider">
            Nire Hitzorduak
          </RouterLink>
          <RouterLink to="/stock" class="w-full sm:w-auto px-8 py-4 bg-white text-gray-900 font-black rounded-2xl shadow-lg border border-gray-100 hover:bg-gray-50 transition-all hover:scale-105 active:scale-95 text-center uppercase tracking-wider">
            Stocka ikusi
          </RouterLink>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-24 pt-12 border-t border-gray-100">
          <div class="p-6 text-left space-y-3">
            <div class="text-3xl">📅</div>
            <h3 class="text-xl font-bold text-gray-900">Hitzorduen Kudeaketa</h3>
            <p class="text-gray-500 text-sm leading-relaxed">Antolatu zure egunerokoa modu errazean. Ikusi zure txandak eta ez galdu hitzordurik.</p>
          </div>
          <div class="p-6 text-left space-y-3">
            <div class="text-3xl">📦</div>
            <h3 class="text-xl font-bold text-gray-900">Stock Kontrola</h3>
            <p class="text-gray-500 text-sm leading-relaxed">Produktu guztiak kontrolpean. Irakasleek izakinak kudeatu ditzakete segundotan.</p>
          </div>
          <div class="p-6 text-left space-y-3">
            <div class="text-3xl">👤</div>
            <h3 class="text-xl font-bold text-gray-900">Profil Pertsonala</h3>
            <p class="text-gray-500 text-sm leading-relaxed">Zure argazkia, datuak eta pasahitza eguneratu zure eremu pribatutik.</p>
          </div>
        </div>

      </div>
    </div>
  </div>
</template>

<style scoped>
@keyframes blob {
  0% { transform: translate(0px, 0px) scale(1); }
  33% { transform: translate(30px, -50px) scale(1.1); }
  66% { transform: translate(-20px, 20px) scale(0.9); }
  100% { transform: translate(0px, 0px) scale(1); }
}
.animate-blob {
  animation: blob 7s infinite;
}
.animation-delay-2000 {
  animation-delay: 2s;
}
</style>