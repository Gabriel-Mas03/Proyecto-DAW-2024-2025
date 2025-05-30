<template>
  <q-page class="q-pa-md">
    <q-card class="q-mx-auto" style="max-width: 900px">
      <q-card-section class="q-px-md q-pt-md">
        <div class="text-h5 text-center">
          <q-input v-if="isEditing" v-model="editableName" label="Nombre" outlined />
          <div v-else>{{ art?.name }}</div>
        </div>
        <div class="text-subtitle1 text-center q-mt-xs">
          <q-input
            v-if="isEditing"
            v-model="editableDescription"
            label="Descripción"
            type="textarea"
            outlined
          />
          <div v-else>{{ art?.description || 'Sin descripción.' }}</div>
        </div>
      </q-card-section>

      <q-separator />

      <q-card-section v-if="isEditing">
        <div class="q-gutter-md">
          <q-input v-model="editableUrl01" label="Imagen 1 (url01)" filled />
          <q-input v-model="editableUrl02" label="Imagen 2 (url02)" filled />
          <q-input v-model="editableUrl03" label="Imagen 3 (url03)" filled />
          <q-input v-model="editableUrl04" label="Imagen 4 (url04)" filled />
        </div>
      </q-card-section>

      <q-card-section v-else>
        <q-carousel
          v-if="images.length"
          v-model="slide"
          arrows
          swipeable
          animated
          infinite
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

      <q-card-actions align="center" class="q-pb-md q-gutter-md">
        <q-btn label="Volver a la galería" color="primary" @click="router.push('/art')" />
        <q-btn
          v-if="user?.role === 'admin' && !isEditing"
          label="Editar"
          color="warning"
          @click="startEditing"
        />
        <q-btn
          v-if="user?.role === 'admin' && isEditing"
          label="Guardar cambios"
          color="positive"
          @click="saveChanges"
        />
      </q-card-actions>
    </q-card>
  </q-page>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { api } from 'boot/axios'
import { Notify } from 'quasar'
import { useUserStore } from 'stores/user'

const route = useRoute()
const router = useRouter()
const user = useUserStore().user

const art = ref(null)
const images = ref([])
const slide = ref(0)

const isEditing = ref(false)
const editableName = ref('')
const editableDescription = ref('')
const editableUrl01 = ref('')
const editableUrl02 = ref('')
const editableUrl03 = ref('')
const editableUrl04 = ref('')

const fetchArt = async () => {
  try {
    const { data } = await api.get(`gallery/${route.params.id}`)
    art.value = data
    editableName.value = data.name
    editableDescription.value = data.description
    editableUrl01.value = data.url01
    editableUrl02.value = data.url02
    editableUrl03.value = data.url03
    editableUrl04.value = data.url04
    images.value = [data.url01, data.url02, data.url03, data.url04].filter(Boolean)
  } catch (error) {
    console.error('Error loading artwork:', error)
    Notify.create({
      type: 'negative',
      message: 'No se pudo cargar la obra de arte',
    })
  }
}

const startEditing = () => {
  isEditing.value = true
}

const saveChanges = async () => {
  try {
    await api.put(`gallery/${art.value.id}`, {
      name: editableName.value,
      description: editableDescription.value,
      url01: editableUrl01.value,
      url02: editableUrl02.value,
      url03: editableUrl03.value,
      url04: editableUrl04.value,
    })

    Object.assign(art.value, {
      name: editableName.value,
      description: editableDescription.value,
      url01: editableUrl01.value,
      url02: editableUrl02.value,
      url03: editableUrl03.value,
      url04: editableUrl04.value,
    })

    images.value = [
      editableUrl01.value,
      editableUrl02.value,
      editableUrl03.value,
      editableUrl04.value,
    ].filter(Boolean)

    isEditing.value = false
    Notify.create({ type: 'positive', message: 'Obra actualizada correctamente' })
  } catch (err) {
    console.error(err)
    Notify.create({
      type: 'negative',
      message: err.response?.data?.message || 'Error al guardar cambios',
    })
  }
}

onMounted(() => {
  fetchArt()
})
</script>
