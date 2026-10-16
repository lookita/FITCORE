<script setup lang="ts">
import type { Registro } from '@/DTO/Auth.DTO';
import { ejecutarRegistro } from '@/services/autenticar.service';
import Formulario from '@/components/Formulario.vue';
import { useRouter } from 'vue-router';

const router = useRouter();

const FormularioDatos = async (datos: Registro) => {
  try {
    const respuesta = await ejecutarRegistro(datos);

    if (respuesta.exito) {
      console.log('Registro exitoso:', respuesta);
      alert('¡Cuenta creada correctamente!');
      router.push('/login');
    } else {
      alert(respuesta.mensaje ?? 'Error al registrar');
    }
  } catch (error) {
    console.error('Error al registrar:', error);
    alert('No se pudo conectar con el servidor. Error: ' + (error as Error).message);
  }
};
</script>

<template>
  <div class="pantalla-registro">
    <main class="zona-centro">
      <div class="bloque-auth">
        <!-- Logo de la empresa centrado arriba -->
        <router-link to="/" class="enlace-logo" title="Volver al inicio">
          <img src="@/components/icons/logoV.jpg" alt="Logo FITCORE" class="foto-logo" />
          <h1 class="nombre-logo">FITCORE</h1>
        </router-link>

        <Formulario @guardar="FormularioDatos" />
      </div>
    </main>
  </div>
</template>

<style scoped>
.pantalla-registro {
  background-color: #0c0c0e;
  min-height: 100vh;
  color: #ffffff;
  font-family: 'Rubik', sans-serif;
  box-sizing: border-box;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 40px 20px;
}

.zona-centro {
  width: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
}

.bloque-auth {
  width: 100%;
  max-width: 460px;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 20px;
}

.enlace-logo {
  display: flex;
  align-items: center;
  gap: 12px;
  text-decoration: none;
  transition: opacity 0.2s ease;
}

.enlace-logo:hover {
  opacity: 0.85;
}

.foto-logo {
  width: 42px;
  height: 42px;
  border-radius: 10px;
  object-fit: cover;
  border: 1px solid #27272a;
}

.nombre-logo {
  font-size: 1.5rem;
  font-weight: 900;
  color: #ffffff;
  letter-spacing: 1px;
  margin: 0;
}
</style>
