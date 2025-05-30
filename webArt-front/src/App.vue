<template>
  <router-view />
</template>

<script setup>
import { onMounted } from 'vue'
import { useUserStore } from '/src/stores/user'

const userStore = useUserStore()

onMounted(() => {
  // ✅ Cargar usuario si aún no está cargado
  if (!userStore.user) {
    userStore.fetchUser()
  }

  // ✅ Limpiar cookies basura
  const cookies = document.cookie.split(';')
  if (cookies.length > 10) {
    const eliminadas = []
    cookies.forEach((c) => {
      const name = c.trim().split('=')[0]
      if (!['XSRF-TOKEN', 'laravel_session'].includes(name)) {
        document.cookie = `${name}=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;`
        eliminadas.push(name)
      }
    })
    console.warn(`[ALERTA] ${cookies.length} cookies detectadas. Eliminadas:`, eliminadas)
  } else {
    console.info(`[OK] Cookies dentro de límite. Total: ${cookies.length}`)
  }
})
</script>
