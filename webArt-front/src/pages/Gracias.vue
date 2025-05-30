<template>
  <q-page class="q-pa-xl flex flex-center column text-center">
    <q-icon name="check_circle" size="100px" color="green" />
    <div class="text-h5 q-mt-md">¡Gracias por tu compra!</div>
    <div class="text-subtitle1 q-mb-md">Tu pedido ha sido procesado correctamente.</div>
    <q-btn label="Volver al inicio" color="primary" to="/" />
  </q-page>
</template>

<script setup>
defineOptions({ name: 'GraciasPage' })
</script>
