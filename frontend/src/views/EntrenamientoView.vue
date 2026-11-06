<script setup lang="ts">
import BarraUsuario from '@/components/BarraUsuario.vue';
import Entrenamiento from '@/components/Entrenamiento.vue';
import Header from '@/components/Header.vue';
import Header2 from '@/components/Header2.vue';
import RutinaConfig from '@/components/RutinaConfig.vue';

</script>

<template>
  <section class="entrenamiento">
    <RutinaConfig />
    <div>
      <BarraUsuario/>
    </div>

    <div>
      <Header2/>

      <Entrenamiento/>
   </div>
  </section>
</template>

<style>
.entrenamiento{
  display: grid;
  grid-template-columns: 270px 1fr;
  width: 100%;
  min-height: 100vh;
  background-color: #0c0c0e;
  box-sizing: border-box;
  overflow-x: hidden;
}

@media (max-width: 900px) {
  .entrenamiento {
    grid-template-columns: minmax(0, 1fr);
  }

  .entrenamiento > div:first-child {
    display: none;
  }

  .entrenamiento > div:last-child {
    min-width: 0;
  }
}
</style>
