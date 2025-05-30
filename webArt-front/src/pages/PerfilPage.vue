<template>
  <q-page class="q-pa-md">
    <q-card class="q-pa-md">
      <q-card-section>
        <div class="text-h6">Perfil de Usuario</div>
        <div>Nombre: {{ user.name }}</div>
        <div>Email: {{ user.email }}</div>
        <div>Rol: {{ user.role }}</div>
      </q-card-section>

      <q-card-section>
        <q-btn label="Cerrar sesión" color="negative" @click="logout" />
      </q-card-section>

      <q-separator />

      <!-- HISTORIAL DE PEDIDOS DEL USUARIO LOGUEADO -->
      <q-card-section>
        <div class="text-h6 q-mb-md">Historial de Pedidos</div>
        <q-list bordered v-if="userOrders.length && hasValidOrders">
          <q-item v-for="order in userOrders" :key="order.id">
            <q-item-section>
              <q-item-section>
                Pedido #{{ order.id }} — Total: {{ Number(order.total).toFixed(2) }} € — Estado:
                <q-badge :color="order.status === 'pagado' ? 'green' : 'orange'" align="middle">
                  {{ order.status }}
                </q-badge>
              </q-item-section>
            </q-item-section>
          </q-item>
        </q-list>
        <div v-else class="text-grey">Sin pedidos registrados</div>
      </q-card-section>

      <!-- CARRITO DEL USUARIO (CLIENTE) -->
      <q-card-section v-if="user.role === 'client'">
        <div class="text-h6 q-mb-md">Tu carrito actual</div>
        <q-list bordered v-if="userCart.length">
          <q-item v-for="item in userCart" :key="item.id">
            <q-item-section>
              {{ item.product.name }} x {{ item.quantity }} —
              {{ (Number(item.product.price) * item.quantity).toFixed(2) }} €
            </q-item-section>
          </q-item>
        </q-list>
        <div v-else class="text-grey">Carrito vacío</div>
      </q-card-section>

      <!-- PANEL DE ADMINISTRACIÓN -->
      <q-card-section v-if="user.role === 'admin'">
        <div class="text-h6 q-mb-md">Panel de Administración</div>
        <q-list bordered class="bg-grey-1">
          <q-expansion-item
            v-for="u in allUsers"
            :key="u.id"
            :label="u.username + ' (' + u.email + ')'"
          >
            <q-card flat>
              <q-card-section>
                <!-- CARRITO DE CADA USUARIO -->
                <div class="text-subtitle2">Carrito</div>
                <q-list dense v-if="u.cart_items && u.cart_items.length">
                  <q-item v-for="item in u.cart_items" :key="item.id">
                    <q-item-section>
                      {{ item.product.name }} x {{ item.quantity }} —
                      {{ (Number(item.product.price) * item.quantity).toFixed(2) }} €
                    </q-item-section>
                  </q-item>
                </q-list>
                <div v-else class="text-grey">Carrito vacío</div>

                <!-- HISTORIAL DE PEDIDOS DE CADA USUARIO -->
                <div class="text-subtitle2 q-mt-md">Pedidos</div>
                <q-list dense v-if="u.orders && u.orders.length">
                  <q-item v-for="order in u.orders" :key="order.id">
                    <q-item-section>
                      Pedido #{{ order.id }} — Total: {{ order.total_amount }} €
                    </q-item-section>
                  </q-item>
                </q-list>
                <div v-else class="text-grey">Sin pedidos</div>

                <!-- BOTONES -->
                <div class="q-mt-md">
                  <q-btn label="Editar" color="primary" class="q-mr-sm" @click="editUser(u.id)" />
                  <q-btn label="Eliminar" color="negative" @click="deleteUser(u.id)" />
                </div>
              </q-card-section>
            </q-card>
          </q-expansion-item>
        </q-list>

        <!-- REGISTRO DE USUARIO SOLO VISIBLE PARA ADMIN -->
        <q-card-section>
          <div class="text-h6 text-center">Registrar Nuevo Usuario</div>
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
          <q-select
            v-model="role"
            label="Rol"
            :options="['client', 'admin']"
            emit-value
            map-options
            filled
          />
        </q-card-section>

        <q-card-actions align="right">
          <q-btn label="Registrar Usuario" color="primary" @click="register" />
        </q-card-actions>
      </q-card-section>
    </q-card>
  </q-page>
</template>

<script setup>
import { onMounted, ref, computed } from 'vue'
import { useUserStore } from 'src/stores/user'
import { api } from 'boot/axios'
import { Notify } from 'quasar'
import { useRouter } from 'vue-router'

const router = useRouter()
const userStore = useUserStore()
const user = userStore.user
const userOrders = ref([])
const allUsers = ref([])
const userCart = ref([])
const name = ref('')
const last_name = ref('')
const username = ref('')
const email = ref('')
const password = ref('')
const password_confirmation = ref('')
const role = ref('client')

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
      role: role.value,
    })

    Notify.create({ type: 'positive', message: 'Usuario registrado con éxito' })

    // Limpia el formulario
    name.value = ''
    last_name.value = ''
    username.value = ''
    email.value = ''
    password.value = ''
    password_confirmation.value = ''
    role.value = 'client'

    // Opcional: recarga los usuarios
    const usersResponse = await api.get('admin/users')
    allUsers.value = usersResponse.data
  } catch (err) {
    console.error(err)
    Notify.create({
      type: 'negative',
      message: err.response?.data?.message || 'Error al registrar',
    })
  }
}

const logout = async () => {
  await userStore.logout()
  router.push('/') // Redirige al inicio tras logout
}

const editUser = (id) => {
  console.log('Editar usuario', id)
}

const deleteUser = async (id) => {
  try {
    await api.delete(`users/${id}`)
    allUsers.value = allUsers.value.filter((u) => u.id !== id)
  } catch (error) {
    console.error('Error eliminando usuario', error)
  }
}

const hasValidOrders = computed(
  () =>
    Array.isArray(userOrders.value) && userOrders.value.some((order) => order.id && order.total),
)

const fetchCart = async () => {
  try {
    const response = await api.get('my-cart')
    userCart.value = response.data
  } catch (error) {
    console.error('Error cargando carrito del cliente:', error)
  }
}

onMounted(async () => {
  try {
    const ordersResponse = await api.get('orders')
    userOrders.value = ordersResponse.data

    if (user.role === 'client') {
      await fetchCart()
    }

    if (user.role === 'admin') {
      const usersResponse = await api.get('admin/users')
      allUsers.value = usersResponse.data
    }
  } catch (error) {
    console.error(error)
  }
})
</script>
