<template>
  <q-page class="q-pa-md flex flex-center column">
    <q-card class="q-pa-lg" style="width: 300px">
      <q-card-section>
        <div class="text-h6">Login</div>
      </q-card-section>

      <q-card-section class="q-gutter-md">
        <q-input v-model="email" label="Email" />
        <q-input v-model="password" label="Password" type="password" />
      </q-card-section>

      <q-card-actions align="right">
        <q-btn label="Entrar" color="primary" @click="login" />
      </q-card-actions>

      <q-card-section class="q-pt-none text-center">
        <q-btn
          flat
          label="¿No tienes cuenta? Regístrate"
          color="primary"
          @click="goToRegister"
          size="sm"
        />
      </q-card-section>
    </q-card>
  </q-page>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { Notify } from 'quasar'
import { useRouter } from 'vue-router'
import { useUserStore } from '/src/stores/user'
import { api } from 'boot/axios'

const router = useRouter()
const email = ref('')
const password = ref('')
const userStore = useUserStore()

onMounted(() => {
  // ✅ Redirigir si ya está autenticado
  if (userStore.isLoggedIn) {
    router.push('/')
    return
  }

  // ✅ Limpieza de cookies basura
  const cookies = document.cookie.split(';')
  if (cookies.length > 5) {
    cookies.forEach((c) => {
      const name = c.trim().split('=')[0]
      if (!['XSRF-TOKEN', 'laravel_session'].includes(name)) {
        document.cookie = `${name}=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;`
      }
    })
    console.warn('[LIMPIEZA] Cookies basura eliminadas automáticamente.')
  }
})

const login = async () => {
  try {
    await api.post('login', {
      email: email.value,
      password: password.value,
    })

    await userStore.fetchUser()
    Notify.create({ type: 'positive', message: 'Login correcto' })
    router.push('/')
  } catch (err) {
    console.error(err)
    Notify.create({
      type: 'negative',
      message: err.response?.data?.message || 'Error al iniciar sesión',
    })
  }
}

const goToRegister = () => {
  router.push('/register')
}
</script>
