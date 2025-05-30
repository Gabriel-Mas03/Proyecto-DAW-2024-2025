<template>
  <div class="product-page">
    <!-- Hero con imagen de fondo y logo -->
    <div
      class="bg-transparent shadow-2 rounded-borders overflow-hidden q-mx-auto"
      style="max-width: 100%; height: 450px; position: relative"
    >
      <q-img
        src="/image/paintings/drop02/00.jpg"
        alt="Cabecera con obra de arte"
        fit="cover"
        class="absolute-full"
      />

      <div
        class="row justify-center items-center full-height"
        style="position: relative; z-index: 1"
      >
        <img src="/image/logo/logo.png" alt="Logo Joan Mas" style="width: 200px; height: auto" />
      </div>
    </div>

    <!-- Introducción -->
    <div class="q-pa-md">
      <div class="bg-white bg-opacity-70 rounded-borders q-pa-md q-mt-lg">
        <p class="text-body1 text-justify">
          Joan Mas Ballester, pintor mallorquín, encuentra en su isla natal una fuente inagotable de
          inspiración. Sus pinceladas capturan la esencia del Mar Mediterráneo, desde los tonos
          azules profundos hasta los turquesas más vibrantes. Además, su obra se sumerge en la
          fascinante vida marina, plasmando la vitalidad y el colorido de sus habitantes submarinos.
          <br /><br />
          Adéntrate en un mundo donde el arte y la naturaleza se entrelazan, invitándote a explorar
          la belleza del mar. Ofrecemos la posibilidad de crear cuadros personalizados contigo,
          permitiéndote participar en el proceso creativo y obtener una obra única que refleje tus
          gustos y experiencias.
        </p>
      </div>
    </div>

    <!-- Carrusel de obras destacadas -->
    <div class="q-mt-xl q-pa-md">
      <div class="row justify-center q-mb-md">
        <div class="col-12">
          <h2 class="text-h5 text-center text-primary">Obras más relevantes:</h2>
        </div>
      </div>

      <div class="row justify-center">
        <div class="col-10">
          <q-carousel
            v-model="slide"
            animated
            swipeable
            autoplay
            infinite
            arrows
            transition-prev="fade"
            transition-next="fade"
            height="400px"
            class="rounded-borders shadow-2 q-mx-auto"
          >
            <q-carousel-slide
              v-for="(img, index) in images"
              :key="index"
              :name="index"
              :img-src="img"
              :img-alt="`Obra ${index + 1}`"
            />
          </q-carousel>
          <!---OPVION DE VER TODA LA CALERIA-->
          <div class="row justify-center q-mt-md">
            <q-btn
              label="Ver galería completa"
              color="primary"
              flat
              icon="visibility"
              :to="{ path: '/art' }"
            />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'IndexPage',
  data() {
    return {
      slide: 0,
      images: [
        '/image/paintings/drop01/03.jpg',
        '/image/paintings/drop02/00.jpg',
        '/image/paintings/drop03/01.jpg',
        '/image/paintings/drop04/01.jpg',
      ],
    }
  },
}
</script>

<style scoped>
/* No se necesita CSS personalizado, todo está hecho con clases de Quasar */
</style>
