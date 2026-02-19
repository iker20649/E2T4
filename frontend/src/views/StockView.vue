<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

// --- ESTATU ALDAGAIAK ---
const produktuak = ref([])
const materialezFungibleak = ref([]) 
const ikasleak = ref([])
const loading = ref(true)
const userRola = ref('')

// Bloqueo visual inmediato para UX fluida
const bloqueados = ref(new Map()) 

const nuevo = ref({
  izena: '',
  marka: '',
  stock: 1,
  stock_minimo: 1,
  prezioa: 0,
  tipo: 'fungible', 
  etiketa: '',
  ikasle_id: ''     
})

// --- KONFIGURAZIOA ---
// Cambia la IP y el puerto según lo que hayas visto en el paso anterior
const token = localStorage.getItem('token');
if (token) axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;

const kargatuDatuak = async () => {
  try {
    const [resUser, resProd, resIkas] = await Promise.all([
      axios.get('/api/user'),
      axios.get('/api/produktuak'),
      axios.get('/api/admin/ikasleak')
    ]);

    // 1. Rol del usuario - ya protegido, pero lo hacemos más explícito
    userRola.value = (resUser?.data?.rola || '').toLowerCase().trim();

    // 2. Ikasleak - protección crítica
    const ikasleakRaw = Array.isArray(resIkas?.data) ? resIkas.data : [];
    ikasleak.value = ikasleakRaw.map(i => ({
      ...i,
      name: i.izena || i.name || 'Ikasle Izengabea'
    }));

    // 3. Produktuak / Materialak - protección doble
    const produktuakRaw = Array.isArray(resProd?.data) ? resProd.data : [];
    
    // Fungibles
    produktuak.value = produktuakRaw.filter(p => 
      !p.etiketa || p.etiketa === 'null' || p.etiketa === ''
    );

    // No fungibles (tresneria) + lógica de estado
    materialezFungibleak.value = produktuakRaw
      .filter(p => p.etiketa && p.etiketa !== 'null' && p.etiketa !== '')
      .map(m => {
        const mailegua = m.azken_mailegua || m.azkenMailegua || (m.maileguak && m.maileguak[0]) || null;
        const itzulita = mailegua ? (mailegua.data_itzulera || mailegua.itzuli_data || mailegua.data_itzuli) : null;
        const estaOcupadoEnAPI = mailegua !== null && (itzulita === null || itzulita === "");
        
        if (estaOcupadoEnAPI) {
          bloqueados.value.delete(m.id);
        }
        
        const bloqueoManual = bloqueados.value.get(m.id);
        const mostrarRojo = estaOcupadoEnAPI || bloqueoManual;

        return {
          ...m,
          relacion: estaOcupadoEnAPI ? {
            ...mailegua,
            ikaslea: mailegua.ikaslea ? {
              ...mailegua.ikaslea,
              name: mailegua.ikaslea.izena || mailegua.ikaslea.name
            } : null
          } : (bloqueoManual ? { ikaslea: { name: bloqueoManual.nombre } } : null),
          libre: !mostrarRojo
        };
      });

  } catch (e) {
    console.error("Errorea datuak kargatzean", e);
    // Fallbacks para que la interfaz no se rompa del todo
    ikasleak.value = [];
    produktuak.value = [];
    materialezFungibleak.value = [];
  } finally {
    loading.value = false;
  }
};

// --- EKINTZAK (ACCIONES) ---

const gordeProduktua = async () => {
  if (!nuevo.value.izena) return alert("Izena beharrezkoa da");
  
  try {
    // 1. DETERMINAR LA URL CORRECTA
    // Si el tipo es 'tresneria', usamos  '/api/tresnak', si no  '/api/produktuak'
    const url = nuevo.value.tipo === 'tresneria' ?  '/api/tresnak' :  '/api/produktuak';

    const res = await axios.post(url, {
      izena: nuevo.value.izena,
      marka: nuevo.value.marka || 'Generikoa',
      prezioa: nuevo.value.prezioa,
      etiketa: nuevo.value.tipo === 'tresneria' ? (nuevo.value.etiketa || 'T-BERRIA') : null,
      stock: nuevo.value.tipo === 'tresneria' ? 1 : nuevo.value.stock,
      stock_minimo: nuevo.value.tipo === 'tresneria' ? 0 : nuevo.value.stock_minimo
    });

    // 2. Lógica de préstamo automático (se mantiene igual)
    if (nuevo.value.tipo === 'tresneria' && nuevo.value.ikasle_id) {
      await axios.post('/maileguak', { 
        materiala_id: res.data.id, 
        ikasle_ekipoa_id: nuevo.value.ikasle_id 
      });
    }
    
    // 3. Limpiar formulario y recargar
    nuevo.value = { izena: '', marka: '', stock: 1, stock_minimo: 1, prezioa: 0, tipo: 'fungible', etiketa: '', ikasle_id: '' };
    await kargatuDatuak();
    alert("✅ GORDETA!");
  } catch (e) { 
    alert("Errorea gordetzean"); 
  }
}

const maileguaHasi = async (id) => {
  const selectEl = document.getElementById(`sel-${id}`);
  if (!selectEl.value) return alert("Hautatu ikaslea!");
  
  const ikasleIzena = selectEl.options[selectEl.selectedIndex].text;
  bloqueados.value.set(id, { nombre: ikasleIzena });
  
  // Actualización reactiva inmediata
  const item = materialezFungibleak.value.find(m => m.id === id);
  if (item) { item.libre = false; item.relacion = { ikaslea: { name: ikasleIzena } }; }

  try {
    await axios.post('/maileguak', { materiala_id: id, ikasle_ekipoa_id: selectEl.value });
    setTimeout(kargatuDatuak, 1000);
  } catch (error) {
    bloqueados.value.delete(id);
    await kargatuDatuak();
    alert("Errorea maileguan");
  }
}

// MODO "GUARRO" PERO EFECTIVO: Forzado visual instantáneo
const itzuliMateriala = async (maileguId, materialId) => {
  // 1. Limpieza visual inmediata (Se pone verde YA)
  bloqueados.value.delete(materialId);
  const item = materialezFungibleak.value.find(m => m.id === materialId);
  
  let idFinal = maileguId || item?.relacion?.id;

  if (item) {
    item.libre = true;
    item.relacion = null;
  }

  // 2. Ejecución en segundo plano sin bloquear al usuario
  if (idFinal) {
    try {
      await axios.put(`/api/maileguak/${idFinal}`);
    } catch (e) {
      console.warn("API Error silencioso al devolver");
    }
  }
  
  // Sincronizar con el servidor después de un momento
  setTimeout(kargatuDatuak, 800);
}

const aldatuStock = async (id, kant, e) => {
  try {
    await axios.patch(`/api/produktuak/${id}/stock`, { kantitatea: kant, ekintza: e });
    await kargatuDatuak();
  } catch (err) { alert("Stock errorea"); }
}

const ezabatuProduktua = async (id, tipo = 'producto') => {
  if (!confirm("Ziur zaude?")) return;
  try {
    const url = tipo === 'tresna' ? `/api/tresnak/${id}` : `/api/produktuak/${id}`;
    await axios.delete(url);
    await kargatuDatuak();
  } catch (e) { 
    alert("Ezin da ezabatu"); 
  }
}

onMounted(kargatuDatuak)
</script>

<template>
  <div v-if="!loading" class="max-w-7xl mx-auto space-y-12 p-6 font-sans italic uppercase">
    
    <div class="bg-slate-900 text-white p-10 rounded-[3rem] border-b-[12px] border-indigo-600 flex justify-between items-center shadow-2xl">
      <h2 class="text-6xl font-black tracking-tighter">📦 STOCKA</h2>
      <div class="bg-indigo-600 px-8 py-3 rounded-2xl font-black text-sm not-italic shadow-lg border border-indigo-400">
        {{ userRola }}
      </div>
    </div>

    <div v-if="userRola === 'irakasle'" class="bg-indigo-600 p-10 rounded-[3rem] shadow-2xl space-y-6">
        <h3 class="text-white font-black text-2xl italic">➕ GEHITU BERRIA</h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 not-italic">
            <input v-model="nuevo.izena" type="text" class="p-4 bg-white/20 rounded-xl text-white font-bold placeholder:text-white/50 border-2 border-transparent focus:bg-white focus:text-slate-900 outline-none transition-all" placeholder="Izena...">
            <select v-model="nuevo.tipo" class="p-4 bg-white/20 rounded-xl text-white font-bold border-2 border-transparent focus:bg-white focus:text-slate-900 outline-none transition-all">
                <option value="fungible" class="text-slate-900">MOTA: FUNGIBLEA</option>
                <option value="tresneria" class="text-slate-900">MOTA: TRESNERIA</option>
            </select>
            <input v-model="nuevo.prezioa" type="number" class="p-4 bg-white/20 rounded-xl text-white font-bold" placeholder="Prezioa €">
            <button @click="gordeProduktua" class="bg-black text-white font-black rounded-xl hover:scale-95 transition-all shadow-xl">GORDE</button>
        </div>
        
        <div v-if="nuevo.tipo === 'tresneria'" class="grid grid-cols-1 md:grid-cols-2 gap-4 not-italic">
            <input v-model="nuevo.etiketa" type="text" class="p-4 bg-white/10 rounded-xl text-white font-bold border border-white/20" placeholder="Etiketa (SEC-01)">
            <select v-model="nuevo.ikasle_id" class="p-4 bg-white/10 rounded-xl text-white font-bold">
                <option value="" class="text-slate-900">Esleitu gabe</option>
                <option v-for="i in ikasleak" :key="i.id" :value="i.id" class="text-slate-900">{{ i.name }}</option>
            </select>
        </div>
        <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-4 not-italic">
            <input v-model="nuevo.stock" type="number" class="p-4 bg-white/10 rounded-xl text-white font-bold" placeholder="Stock hasierakoa...">
            <input v-model="nuevo.stock_minimo" type="number" class="p-4 bg-white/10 rounded-xl text-white font-bold" placeholder="Gutxieneko Stock-a...">
        </div>
    </div>

    <div class="space-y-8">
        <h3 class="text-4xl font-black text-slate-900 border-l-[12px] border-indigo-600 pl-6 italic">🛠️ TRESNERIA</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div v-for="m in materialezFungibleak" :key="m.id" 
                 class="p-8 rounded-[3rem] border-[6px] transition-all duration-300 relative overflow-hidden shadow-xl"
                 :class="!m.libre ? 'bg-rose-600 border-black shadow-[12px_12px_0px_0px_rgba(0,0,0,1)] scale-[1.02]' : 'bg-white border-slate-200'">
                
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h4 class="text-3xl font-black tracking-tighter leading-none" :class="!m.libre ? 'text-white' : 'text-slate-800'">{{ m.izena }}</h4>
                        <span class="text-[10px] font-black px-3 py-1 rounded-full mt-2 inline-block shadow-sm"
                              :class="!m.libre ? 'bg-black text-white' : 'bg-indigo-50 text-indigo-600'">
                          {{ m.etiketa }}
                        </span>
                    </div>
                    <div v-if="!m.libre" class="bg-white text-rose-600 px-3 py-1 rounded-lg text-[10px] font-black animate-pulse shadow-lg uppercase">Okupatuta</div>
                </div>

                <div v-if="m.libre" class="space-y-4 not-italic">
                    <select :id="`sel-${m.id}`" class="w-full p-4 bg-slate-100 rounded-2xl font-black text-sm border-2 border-transparent focus:border-indigo-600 outline-none transition-all">
                        <option value="">Aukeratu ikaslea...</option>
                        <option v-for="i in ikasleak" :key="i.id" :value="i.id">{{ i.name }}</option>
                    </select>
                    <button @click="maileguaHasi(m.id)" class="w-full py-5 bg-emerald-500 hover:bg-black text-white font-black rounded-2xl shadow-lg transition-all text-xl uppercase tracking-tighter">Mailegua Hasi</button>
                </div>

                <div v-else class="space-y-4 not-italic">
                    <div class="bg-black/20 p-5 rounded-2xl border border-white/20">
                        <p class="text-[10px] font-black text-rose-100 uppercase tracking-widest leading-none">⚠️ Nori utzita:</p>
                        <p class="font-black text-white italic text-3xl tracking-tighter leading-tight">
                            {{ m.relacion?.ikaslea?.user?.name || m.relacion?.ikaslea?.name || 'ADMIN' }}
                        </p>
                    </div>
                    <button @click="itzuliMateriala(m.relacion?.id, m.id)" class="w-full py-5 bg-white text-rose-600 hover:bg-black hover:text-white font-black rounded-2xl shadow-xl transition-all uppercase text-xl">Itzuli 🔄</button>
                </div>
                <button v-if="userRola === 'irakasle' && m.libre" @click="ezabatuProduktua(m.id)" class="mt-4 text-[10px] font-black text-rose-400 hover:text-rose-600 uppercase">Ezabatu 🗑️</button>
            </div>
        </div>
    </div>

    <div class="bg-white shadow-2xl rounded-[3rem] overflow-hidden border-2 border-slate-100">
      <table class="w-full text-left">
        <thead class="bg-slate-900 text-slate-400 text-[10px] font-black uppercase">
          <tr><th class="p-8">PRODUKTUA</th><th class="p-8 text-center">STOCK</th><th class="p-8 text-right">KUDEAKETA</th></tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
          <tr v-for="p in produktuak" :key="p.id" class="hover:bg-indigo-50/50 transition-all">
            <td class="p-8">
              <div class="font-black text-slate-800 text-3xl tracking-tighter leading-none">{{ p.izena }}</div>
              <div class="text-[10px] text-indigo-500 font-black mt-2 uppercase tracking-widest">{{ p.marka || 'Stock Orokorra' }}</div>
            </td>
            <td class="p-8 text-center">
              <span :class="p.stock <= p.stock_minimo ? 'text-rose-600 bg-rose-50 border-rose-200' : 'text-emerald-600 bg-emerald-50 border-emerald-200'" class="text-5xl font-black px-10 py-4 rounded-[2rem] border-4 inline-block shadow-sm">
                {{ p.stock }}
              </span>
            </td>
            <td class="p-8 text-right not-italic">
              <div class="flex justify-end gap-3 items-center">
                <button @click="aldatuStock(p.id, -1, 'Erabilita')" class="px-6 py-4 border-2 border-rose-100 text-rose-500 rounded-2xl font-black hover:bg-rose-500 hover:text-white transition-all">-1</button>
                <button @click="aldatuStock(p.id, 1, 'Erosita')" class="px-6 py-4 border-2 border-emerald-100 text-emerald-500 rounded-2xl font-black hover:bg-emerald-500 hover:text-white transition-all">+1</button>
                <button v-if="userRola === 'irakasle'" @click="ezabatuProduktua(p.id)" class="ml-4 p-4 bg-rose-100 text-rose-600 rounded-xl hover:bg-rose-600 hover:text-white transition-all shadow-sm">🗑️</button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <div v-else class="h-screen w-full flex flex-col items-center justify-center space-y-6">
    <div class="w-20 h-20 border-[10px] border-indigo-600 border-t-transparent rounded-full animate-spin"></div>
    <p class="text-2xl font-black text-indigo-600 animate-pulse uppercase tracking-tighter italic">Datuak kargatzen...</p>
  </div>
</template>
