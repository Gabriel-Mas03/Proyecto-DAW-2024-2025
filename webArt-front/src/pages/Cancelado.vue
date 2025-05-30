<template>
  <q-page class="q-pa-xl flex flex-center column text-center">
    <q-icon name="cancel" size="100px" color="negative" />
    <div class="text-h5 q-mt-md">Pago cancelado</div>
    <div class="text-subtitle1 q-mb-md">Tu transacción fue cancelada o no se completó.</div>
    <q-btn label="Volver a la tienda" color="primary" to="/productos" />
  </q-page>
</template>

<script setup>
defineOptions({ name: 'CanceladoPage' })
</script>
