<template>
  <q-page class="q-pa-md">
    <q-card v-if="order" class="q-pa-md">
      <q-card-section>
        <div class="text-h6">Resumen del Pedido</div>
        <div v-if="order.order_id">Pedido #{{ order.order_id }}</div>
        <div>Total a pagar: €{{ Number(order.total ?? order.total_amount).toFixed(2) }}</div>
        <div v-if="order.status">Estado actual: {{ order.status }}</div>

        <q-separator class="q-my-md" />

        <div class="text-subtitle1">Productos:</div>
        <q-list bordered class="q-mb-md">
          <q-item v-for="(item, index) in order.items" :key="index">
            <q-item-section>
              <q-item-label>
                {{ item.product?.name || item.name }} (x{{ item.quantity }})
              </q-item-label>
              <div v-if="item.size" class="text-caption">Talla: {{ item.size }}</div>
              <q-item-label caption>
                Precio: €{{ Number(item.product?.price ?? item.price).toFixed(2) }}
              </q-item-label>
            </q-item-section>
          </q-item>
        </q-list>

        <q-btn
          label="Pagar ahora"
          color="primary"
          icon="credit_card"
          class="full-width"
          @click="redirectToStripe"
          :loading="loading"
          :disable="order.status === 'pagado'"
        />
      </q-card-section>
    </q-card>

    <div v-else class="text-center text-red q-mt-xl">
      <q-icon name="warning" size="md" color="red" />
      No se recibieron datos del pedido.
    </div>
  </q-page>
</template>

<script setup>
import { ref, watchEffect } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { api } from 'boot/axios'
import { Notify } from 'quasar'

const props = defineProps({
  orderData: {
    type: Object,
    required: false,
    default: () => null,
  },
})

const router = useRouter()
const route = useRoute()
const order = ref(
  props.orderData || (route.query.data ? JSON.parse(decodeURIComponent(route.query.data)) : null),
)

const loading = ref(false)

watchEffect(() => {
  if (!order.value) {
    Notify.create({
      type: 'negative',
      message: 'No se recibieron datos del pedido',
    })
    router.push('/')
  }
})

const redirectToStripe = async () => {
  loading.value = true
  try {
    const item = order.value.items[0]

    const response = await api.post('stripe/checkout', {
      product_name: item.product?.name || item.name,
      quantity: item.quantity,
      price: item.product?.price || item.price,
    })

    window.location.href = response.data.url
  } catch (err) {
    Notify.create({ type: 'negative', message: 'Error al redirigir a Stripe' })
    console.error(err)
  } finally {
    loading.value = false
  }
}
</script>
