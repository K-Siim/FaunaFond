<template>
  <button
    v-if="deferredPrompt"
    @click="installPwa"
    class="flex items-center gap-2 px-4 py-3 bg-[#275342] text-white rounded-full shadow-lg hover:bg-[#1e3f31] transition-all"
  >
    <span>📲</span>
    <span class="text-sm font-medium">Installi äpp</span>
  </button>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'

const deferredPrompt = ref(window.__pwaPrompt || null)

const handleBeforeInstallPrompt = (e) => {
  e.preventDefault()
  window.__pwaPrompt = e
  deferredPrompt.value = e
}

const installPwa = async () => {
  if (!deferredPrompt.value) return
  
  deferredPrompt.value.prompt()
  const { outcome } = await deferredPrompt.value.userChoice
  
  if (outcome === 'accepted') {
    window.__pwaPrompt = null
    deferredPrompt.value = null
  }
}

onMounted(() => {
  window.addEventListener('beforeinstallprompt', handleBeforeInstallPrompt)
})

onUnmounted(() => {
  window.removeEventListener('beforeinstallprompt', handleBeforeInstallPrompt)
})
</script>