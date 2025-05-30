<template>
  <div class="q-pa-md">
    <div class="gallery-container q-mx-xs q-my-xs q-pa-md">
      <div class="row q-col-gutter-md justify-center">
        <div v-for="item in artItems" :key="item.id" class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
          <q-card
            class="my-card"
            @mouseenter="!isMobile && (hovered = item.id)"
            @mouseleave="!isMobile && (hovered = null)"
            @click="handleCardInteraction(item.id)"
          >
            <div class="image-wrapper">
              <q-img :src="item.url01" :alt="item.name" class="image" fit="cover" />
              <div class="overlay" v-if="hovered === item.id">
                <q-btn label="Ver más" flat color="white" @click.stop="goToArt(item.id)" />
              </div>
            </div>
            <q-card-section class="text-center">
              <div class="text-h6">{{ item.name }}</div>
            </q-card-section>
          </q-card>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { api } from 'boot/axios'
import { Notify, useQuasar } from 'quasar'

const $q = useQuasar()
const isMobile = $q.screen.lt.md

const router = useRouter()
const artItems = ref([])
const hovered = ref(null)

const getData = async () => {
  try {
    const response = await api.get('gallery')
    artItems.value = response.data
  } catch (error) {
    console.error('Error fetching art items:', error)
    Notify.create({
      type: 'negative',
      message: 'Error al cargar las obras de arte',
    })
  }
}

const goToArt = (id) => {
  router.push({ name: 'art', params: { id } })
}

const handleCardInteraction = (id) => {
  if (isMobile) {
    hovered.value = hovered.value === id ? null : id
  }
}

onMounted(() => {
  getData()
})
</script>

<style scoped lang="scss">
.my-card {
  transition: transform 0.3s ease;
  overflow: hidden;
  position: relative;

  &:hover {
    transform: scale(1.03);
  }
}

.image-wrapper {
  position: relative;
  width: 100%;
  padding-top: 100%;
  overflow: hidden;
}

.image {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
}

.overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.4);
  display: flex;
  justify-content: center;
  align-items: center;
  transition: opacity 0.3s ease;
  z-index: 1;
}
</style>
