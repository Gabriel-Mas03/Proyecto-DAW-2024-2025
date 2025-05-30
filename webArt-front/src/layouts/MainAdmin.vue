<template>
  <q-layout view="lHh Lpr lFf">
    <img src="/image/fondo/fondo.jpg" alt="Fondo" class="fondo" />

    <q-header elevated class="bg-white text-dark" height-hint="100">
      <q-toolbar class="q-py-sm" style="min-height: 80px">
        <q-btn
          flat
          dense
          round
          icon="menu"
          @click="leftDrawerOpen = !leftDrawerOpen"
          aria-label="Menú"
        />

        <div class="flex items-center q-ml-sm">
          <img
            src="/image/logo/logo.png"
            alt="Logo"
            style="height: 65px; width: auto; object-fit: contain"
          />
        </div>

        <q-space />

        <!-- Perfil de usuario -->
        <div class="column items-center q-mr-sm">
          <q-btn
            flat
            dense
            round
            icon="account_circle"
            size="lg"
            aria-label="Perfil"
            @click="goToLogin"
          />
          <div
            v-if="userStore.isLoggedIn && userStore.user?.name"
            class="text-caption text-weight-medium text-dark q-mt-xs"
          >
            {{ userStore.user.name }}
          </div>
        </div>

        <!-- Botón cerrar sesión -->
        <q-btn
          v-if="userStore.isLoggedIn"
          flat
          dense
          icon="logout"
          label="Cerrar sesión"
          class="q-ml-sm"
          @click="logout"
        />
      </q-toolbar>
    </q-header>

    <q-drawer show-if-above v-model="leftDrawerOpen" side="left" bordered>
      <q-list>
        <q-item-label header class="text-grey-8">Navegación</q-item-label>

        <q-item clickable v-ripple to="/" exact>
          <q-item-section avatar>
            <q-icon name="home" />
          </q-item-section>
          <q-item-section>Inicio</q-item-section>
        </q-item>

        <q-item clickable v-ripple to="/art">
          <q-item-section avatar>
            <q-icon name="palette" />
          </q-item-section>
          <q-item-section>Galería de Arte</q-item-section>
        </q-item>

        <q-item clickable v-ripple to="/productos">
          <q-item-section avatar>
            <q-icon name="shopping_bag" />
          </q-item-section>
          <q-item-section>Productos</q-item-section>
        </q-item>

        <q-item v-if="userStore.isLoggedIn" clickable v-ripple to="/carrito">
          <q-item-section avatar>
            <q-icon name="shopping_cart" />
          </q-item-section>
          <q-item-section>Carrito</q-item-section>
        </q-item>

        <q-item v-if="userStore.isLoggedIn" clickable v-ripple to="/perfil">
          <q-item-section avatar>
            <q-icon name="account_circle" />
          </q-item-section>
          <q-item-section>Perfil</q-item-section>
        </q-item>
      </q-list>
    </q-drawer>

    <q-page-container>
      <router-view />

      <q-footer
        class="bg-white q-pa-sm"
        style="position: fixed; bottom: 0; left: 0; width: 100%; z-index: 1"
      >
        <div class="row justify-around items-center">
          <q-btn
            flat
            round
            dense
            icon="fa-brands fa-instagram"
            size="lg"
            href="https://www.instagram.com/joanmas.art/"
            color="black"
          />
          <q-btn flat round dense icon="fa-brands fa-facebook" size="lg" href="#" color="black" />
          <q-btn
            flat
            round
            dense
            icon="fa-brands fa-tiktok"
            size="lg"
            href="https://www.tiktok.com/@joanmas.art"
            color="black"
          />
        </div>
      </q-footer>
    </q-page-container>
  </q-layout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { Notify } from 'quasar'
import { useUserStore } from '/src/stores/user.js'

const router = useRouter()
const userStore = useUserStore()
const leftDrawerOpen = ref(false)

onMounted(() => {
  if (!userStore.user) {
    userStore.fetchUser()
  }
})

const goToLogin = () => {
  router.push('/login')
}

const logout = async () => {
  try {
    await userStore.logout()
    Notify.create({ type: 'positive', message: 'Sesión cerrada correctamente' })
    router.push('/login')
  } catch (err) {
    Notify.create({ type: 'negative', message: 'Error al cerrar sesión', err })
  }
}
</script>

<style>
.fondo {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  object-fit: cover;
  z-index: -1;
}
</style>
