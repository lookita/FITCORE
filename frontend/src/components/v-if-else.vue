<template>
  <div>
    <h1>MENÚ DEL JUEGO</h1>

    <div v-if="pantalla === 'inicio'">
      <h3>
        Iniciar partida
      </h3>
      <button @click="pantalla = 'juego'">
        jugar
      </button>
    </div>

    <div v-else-if="pantalla === 'juego'">
      <h3>
        JUGANDO!!!!!1
      </h3>
      <button @click="pantalla = 'gameOver'">
        game Over 
      </button>
    </div>

    <div v-else-if="pantalla === 'gameOver'">
      <h3>
        Fin de la partida
      </h3>
        <button @click="pantalla = 'inicio'">
          volver a empezar
        </button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';

// Solo necesitas UNA variable para controlar todo el estado de la pantalla
const pantalla = ref<string>('inicio'); 
</script>
