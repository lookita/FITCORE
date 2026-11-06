<script setup lang="ts">
import { onMounted } from 'vue';
import { useRouter } from 'vue-router';
import Header from '@/components/Header.vue';
import Main from '@/components/Main.vue';
import { useUsuarioVerificacion } from '@/stores/auth.store';

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
</template>

<style scoped>
.inicio {
  background-color: #0c0c0e;
  min-height: 100vh;
}
</style>
