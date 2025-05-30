<template>
  <q-page class="q-pa-md">
    <q-card v-if="product" class="q-mx-auto" style="max-width: 450px">
      <q-card-section>
        <div class="text-h5 text-center">{{ product.name }}</div>
        <div class="text-subtitle1 text-center q-mt-xs">
          {{ product.description || 'Sin descripción.' }}
        </div>
      </q-card-section>

      <q-separator />

      <q-card-section>
        <q-carousel
          v-if="images.length"
          v-model="slide"
          arrows
          animated
          infinite
          swipeable
          control-color="white"
          height="500px"
        >
          <q-carousel-slide
            v-for="(img, index) in images"
            :key="index"
            :name="index"
            :img-src="img"
          />
        </q-carousel>
      </q-card-section>

      <q-card-section>
        <div class="text-h6">Precio unitario: € {{ Number(product.price).toFixed(2) }}</div>
        <div class="text-subtitle2">Stock disponible: {{ product.stock }}</div>

        <div class="q-mt-md">
          <q-input
            v-model.number="quantity"
            type="number"
            label="Cantidad"
            :min="1"
            :max="product.stock"
            outlined
            dense
          />
          <q-select
            v-if="['camiseta', 'pantalón'].includes(product.category?.toLowerCase())"
            v-model="selectedSize"
            :options="['XS', 'S', 'M', 'L', 'XL']"
            label="Selecciona una talla"
            outlined
          />
        </div>

        <div class="text-subtitle1 q-mt-md">
          <strong>Precio total: € {{ totalPrice }}</strong>
        </div>
      </q-card-section>

      <q-card-actions align="around" class="q-pb-md">
        <q-btn label="Añadir al carrito" color="primary" @click="addToCart" />
        <q-btn label="Comprar ahora" color="green" @click="buyNow" />
      </q-card-actions>

      <q-card-actions align="center" class="q-pb-md">
        <q-btn label="Volver a productos" color="secondary" flat @click="goBack" />
      </q-card-actions>
    </q-card>

    <div v-else class="text-center q-pa-md">
      <q-spinner color="primary" size="lg" />
      <div class="q-mt-sm">Cargando producto...</div>
    </div>
  </q-page>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { api } from 'boot/axios'
import { Notify } from 'quasar'

const route = useRoute()
const router = useRouter()

const product = ref(null)
const images = ref([])
const slide = ref(0)
const quantity = ref(1)
const selectedSize = ref(null)

const totalPrice = computed(() => {
  if (!product.value) return '0.00'
  return (Number(product.value.price) * quantity.value).toFixed(2)
})

const fetchProduct = async () => {
  try {
    const { data } = await api.get(`products/${route.params.id}`)
    product.value = data
    images.value = [data.URL01, data.URL02, data.URL03, data.URL04].filter(Boolean)
  } catch (error) {
    console.error('Error loading product:', error)
    Notify.create({
      type: 'negative',
      message: 'No se pudo cargar el producto',
    })
  }
}

const addToCart = async () => {
  try {
    await api.post('cart-items', {
      product_id: product.value.id,
      quantity: quantity.value,
      size: selectedSize.value ?? null,
    })
    Notify.create({ type: 'positive', message: 'Producto añadido al carrito' })
  } catch (err) {
    console.error(err)
    Notify.create({
      type: 'negative',
      message: err.response?.data?.message || 'Error al añadir al carrito',
    })
  }
}

const buyNow = async () => {
  if (
    ['camiseta', 'pantalón'].includes(product.value.category?.toLowerCase()) &&
    !selectedSize.value
  ) {
    Notify.create({ type: 'warning', message: 'Selecciona una talla para continuar' })
    return
  }

  try {
    const response = await api.post(
      'checkout',
      {
        items: [
          {
            product_id: product.value.id,
            quantity: quantity.value,
            size: selectedSize.value ?? null,
          },
        ],
      },
      { withCredentials: true },
    )

    Notify.create({ type: 'positive', message: 'Redirigiendo al pago...' })
    router.push({
      name: 'payment-page',
      query: {
        data: encodeURIComponent(JSON.stringify(response.data)),
      },
    })
  } catch (error) {
    console.error('Error al comprar directamente:', error)
    Notify.create({
      type: 'negative',
      message: error.response?.data?.message || 'No se pudo crear el pedido',
    })
  }
}

const goBack = () => {
  router.push('/productos')
}

onMounted(() => {
  fetchProduct()
})
</script>
