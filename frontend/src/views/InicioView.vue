<script setup lang="ts">
import { onMounted } from 'vue';
import { useRouter } from 'vue-router';
import Header from '@/components/Header.vue';
import Main from '@/components/Main.vue';
import { useUsuarioVerificacion } from '@/stores/auth.store';
import BarraInferior from '@/components/Barra-inferior.vue';

const auth = useUsuarioVerificacion();
const router = useRouter();

// Si el usuario ya está logueado, redirigirlo al Dashboard
onMounted(() => {
  if (auth.estaAutenticado) {
    router.replace('/dashboard');
  }
});
</script>

<template>
  <div class="inicio">
    <Header />
    <Main />
  </div>
  <div>
    <BarraInferior/>
  </div>
</template>

<style scoped>
.inicio {
  background-color: #0c0c0e;
  min-height: 100vh;
}

.barra-inferior{
    display: none;
}

@media (max-width: 900px) {
    .barra-inferior{
        display: grid;
        grid-template-columns: repeat(3, auto);
        background-color: #ff3b30;
        position: fixed;        /* Lo fija a la ventana del navegador, ignorando el scroll */
        bottom: 0;
        width: 100vw;           /* Ocupa el 100% del ancho de la pantalla */
        height: 50px;          /* Ocupa el 100% de la altura de la pantalla */
        text-align: center;
        align-items: center;
        padding: 0;
        margin: 0;
        border-top-left-radius: 8px;
        border-top-right-radius: 8px;

    }

    h3{
        color: #ffffff;
        font-family: 'Rubik', sans-serif;
        font-size: 15px;
    }
}
</style>
