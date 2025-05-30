<template>
  <q-page class="q-pa-xl">
    <div v-if="cartItems.length">
      <div class="text-h5 q-mb-md">Tu carrito</div>

      <q-card v-for="item in cartItems" :key="item.id" class="q-mb-md">
        <q-card-section class="row items-center">
          <q-img :src="item.product.URL01" contain style="width: 100px; height: 100px" />

          <div class="q-ml-md col">
            <div class="text-subtitle1">{{ item.product.name }}</div>
            <div class="row items-center q-gutter-sm">
              <div class="quantity-control row items-center q-gutter-xs">
                <q-btn
                  round
                  dense
                  flat
                  icon="remove"
                  color="primary"
                  @click="changeQuantity(item, -1)"
                  :disable="item.quantity <= 1"
                />
                <div class="quantity-display">{{ item.quantity }}</div>
                <q-btn
                  round
                  dense
                  flat
                  icon="add"
                  color="primary"
                  @click="changeQuantity(item, 1)"
                  :disable="item.quantity >= item.product.stock"
                />
              </div>
            </div>
            <div v-if="item.size" class="text-caption">Talla: {{ item.size }}</div>

            <div class="text-body2">
              Precio unitario: {{ Number(item.product.price).toFixed(2) }} €
            </div>
            <div class="text-body1 text-weight-bold">
              Total: {{ (Number(item.product.price) * item.quantity).toFixed(2) }} €
            </div>
          </div>

          <q-btn
            flat
            round
            icon="delete"
            color="negative"
            @click="removeItem(item.id)"
            class="q-ml-sm"
          />
        </q-card-section>
      </q-card>

      <q-separator class="q-my-md" />

      <div class="text-h6 text-right q-mt-md">
        Total del carrito: <span class="text-primary">{{ cartTotal }} €</span>
      </div>

      <div class="row justify-between q-mt-lg">
        <q-btn
          class="q-mb-lg"
          label="Vaciar carrito"
          color="negative"
          icon="delete_forever"
          @click="clearCart"
        />
        <q-btn
          label="Finalizar compra"
          color="positive"
          icon="shopping_cart_checkout"
          @click="checkout"
        />
      </div>
    </div>

    <div v-else class="text-center q-mt-xl">
      <q-icon name="shopping_cart" size="64px" color="grey-6" />
      <div class="text-subtitle1 q-mt-md">Tu carrito está vacío</div>
    </div>
  </q-page>
</template>

<script setup>
defineOptions({ name: 'CartPage' })

import { ref, computed, onMounted } from 'vue'
import { api } from 'boot/axios'
import { useQuasar } from 'quasar'
import { useRouter } from 'vue-router'

const $q = useQuasar()
const router = useRouter()
const cartItems = ref([])

const fetchCartItems = async () => {
  try {
    const response = await api.get(`my-cart`, {
      withCredentials: true,
    })
    cartItems.value = response.data
  } catch (error) {
    console.error('Error al cargar el carrito:', error)
    $q.notify({ type: 'negative', message: 'No se pudo cargar el carrito' })
  }
}

const cartTotal = computed(() =>
  cartItems.value
    .reduce((sum, item) => sum + Number(item.product.price) * item.quantity, 0)
    .toFixed(2),
)

const removeItem = (id) => {
  $q.dialog({
    title: 'Confirmar',
    message: '¿Eliminar este producto del carrito?',
    cancel: true,
    persistent: true,
  }).onOk(async () => {
    try {
      await api.delete(`cart-items/${id}`, {
        withCredentials: true,
      })
      cartItems.value = cartItems.value.filter((item) => item.id !== id)
      $q.notify({ type: 'positive', message: 'Producto eliminado del carrito' })
    } catch (err) {
      console.error('Error al eliminar producto:', err)
      $q.notify({ type: 'negative', message: 'No se pudo eliminar' })
    }
  })
}

const clearCart = () => {
  $q.dialog({
    title: 'Vaciar carrito',
    message: '¿Estás seguro de que quieres vaciar el carrito?',
    cancel: true,
    persistent: true,
  }).onOk(async () => {
    try {
      for (const item of cartItems.value) {
        await api.delete(`cart-items/${item.id}`, {
          withCredentials: true,
        })
      }
      cartItems.value = []
      $q.notify({ type: 'positive', message: 'Carrito vaciado correctamente' })
    } catch (err) {
      console.error('Error al vaciar el carrito:', err)
      $q.notify({ type: 'negative', message: 'No se pudo vaciar el carrito' })
    }
  })
}

const checkout = async () => {
  if (cartItems.value.length === 0) {
    $q.notify({ type: 'warning', message: 'El carrito está vacío' })
    return
  }

  try {
    const response = await api.post(
      `checkout`,
      {
        items: cartItems.value.map((item) => ({
          product_id: item.product.id,
          quantity: item.quantity,
        })),
      },
      { withCredentials: true },
    )

    $q.notify({ type: 'positive', message: 'Redirigiendo al pago...' })
    router.push({
      name: 'payment-page',
      query: {
        data: encodeURIComponent(JSON.stringify(response.data)),
      },
    })
  } catch (error) {
    console.error('Error en el checkout:', error)
    $q.notify({
      type: 'negative',
      message: error.response?.data?.message || 'No se pudo completar la compra',
    })
  }
}

const changeQuantity = async (item, delta) => {
  const newQty = item.quantity + delta
  if (newQty < 1 || newQty > item.product.stock) return

  try {
    await api.put(`cart-items/${item.id}`, { quantity: newQty }, { withCredentials: true })
    item.quantity = newQty
    $q.notify({ type: 'positive', message: 'Cantidad actualizada' })
  } catch (error) {
    console.error('Error al actualizar cantidad:', error)
    $q.notify({ type: 'negative', message: 'No se pudo actualizar la cantidad' })
  }
}

onMounted(() => {
  fetchCartItems()
})
</script>

<style scoped>
.quantity-control {
  border: 1px solid #ccc;
  border-radius: 8px;
  padding: 4px 8px;
  width: fit-content;
  background-color: #f9f9f9;
}

.quantity-display {
  width: 32px;
  text-align: center;
  font-weight: bold;
}
</style>
