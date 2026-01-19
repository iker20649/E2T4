<script setup>
import { RouterLink, RouterView } from 'vue-router'
</script>

<template>
  <div class="min-h-screen bg-gray-100">
    <nav class="bg-indigo-600 text-white shadow-lg">
      <div class="max-w-7xl mx-auto px-4">
        <div class="flex justify-between h-16 items-center">
          <div class="flex items-center space-x-8">
            <h1 class="text-xl font-bold tracking-tight">💇‍♂️ Ilea-App</h1>
            <div class="flex space-x-4">
              <RouterLink to="/" class="hover:bg-indigo-500 px-3 py-2 rounded-md text-sm font-medium">Hasiera</RouterLink>
            </div>
          </div>
          <button class="bg-indigo-700 hover:bg-indigo-800 px-4 py-2 rounded-md text-sm font-medium">Login</button>
        </div>
      </div>
    </nav>

    <main class="max-w-7xl mx-auto py-10 px-4">
      <div class="bg-white shadow rounded-lg p-8">
        <RouterView />
      </div>
    </main>
  </div>
</template>
