<template>
  <q-page class="q-pa-md flex flex-center">
    <q-card class="q-pa-lg q-mx-sm" style="width: 100%; max-width: 400px">
      <q-card-section>
        <div class="text-h6 text-center">Registro de Usuario</div>
      </q-card-section>

      <q-card-section class="q-gutter-md">
        <q-input v-model="name" label="Nombre" filled />
        <q-input v-model="last_name" label="Apellido" filled />
        <q-input v-model="username" label="Usuario" filled />
        <q-input v-model="email" label="Email" type="email" filled />
        <q-input v-model="password" label="Contraseña" type="password" filled />
        <q-input
          v-model="password_confirmation"
          label="Repetir Contraseña"
          type="password"
          filled
        />
      </q-card-section>

      <q-card-actions align="right">
        <q-btn label="Registrarse" color="primary" @click="register" />
      </q-card-actions>

      <q-card-section class="text-center q-mt-sm">
        <q-btn flat label="¿Ya tienes cuenta? Inicia sesión" color="primary" @click="goToLogin" />
      </q-card-section>
    </q-card>
  </q-page>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { Notify } from 'quasar'
import { api } from 'boot/axios'
const router = useRouter()

const name = ref('')
const last_name = ref('')
const username = ref('')
const email = ref('')
const password = ref('')
const password_confirmation = ref('')

const register = async () => {
  if (password.value !== password_confirmation.value) {
    Notify.create({
      type: 'negative',
      message: 'Las contraseñas no coinciden',
    })
    return
  }

  try {
    await api.post('users', {
      name: name.value,
      last_name: last_name.value,
      username: username.value,
      email: email.value,
      password: password.value,
    })

    Notify.create({ type: 'positive', message: 'Registro exitoso' })
    router.push('/login')
  } catch (err) {
    console.error(err)
    Notify.create({
      type: 'negative',
      message: err.response?.data?.message || 'Error al registrar',
    })
  }
}

const goToLogin = () => {
  router.push('/login')
}
</script>
