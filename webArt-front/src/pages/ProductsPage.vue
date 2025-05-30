<template>
  <q-page class="q-pa-md">
    <div class="row q-col-gutter-md justify-center">
      <div
        v-for="product in products"
        :key="product.id"
        class="col-xs-10 col-sm-6 col-md-4 col-lg-3"
      >
        <q-card class="my-card">
          <q-img :src="product.URL01" :alt="product.name" class="product-img" fit="cover" />
          <q-card-section>
            <div class="text-h6">{{ product.name }}</div>
            <div class="text-subtitle2 text-primary">€ {{ Number(product.price).toFixed(2) }}</div>
          </q-card-section>
          <q-card-actions align="center">
            <q-btn label="Ver más" color="primary" flat @click="goToDetail(product.id)" />
          </q-card-actions>
        </q-card>
      </div>
    </div>
  </q-page>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { api } from 'boot/axios'
import { Notify } from 'quasar'

const router = useRouter()
const products = ref([])

const getProducts = async () => {
  try {
    const response = await api.get('products')
    products.value = response.data
  } catch (error) {
    console.error('Error fetching products:', error)
    Notify.create({
      type: 'negative',
      message: 'No se pudieron cargar los productos',
    })
  }
}

const goToDetail = (id) => {
  router.push({ name: 'product', params: { id } })
}

onMounted(() => {
  getProducts()
})
</script>

<style scoped lang="scss">
.my-card {
  transition: transform 0.3s ease;
  overflow: hidden;
  display: flex;
  flex-direction: column;
  height: 100%;
  &:hover {
    transform: scale(1.03);
  }
}

.product-img {
  height: 280px;
}
</style>
