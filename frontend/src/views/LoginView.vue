<script setup lang="ts">
import FormLogin from '@/components/FormLogin.vue';
import type { Login } from '@/DTO/Auth.DTO';
import type { EstadoSesion } from '@/DTO/EstadoSesion';
import { ejecutarLogin } from '@/services/autenticar.service';
import { reactive } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();

// Estado de sesión
const sesion = reactive<EstadoSesion>({
  UsuarioConectado: null,
  Autenticado: false
});

const loguear = async (datos: Login) => {
  try {
    const respuesta = await ejecutarLogin(datos);

    if (respuesta.exito && respuesta.datos) {
      sesion.UsuarioConectado = respuesta.datos.nombre;
      sesion.Autenticado = true;
      console.log('Login exitoso:', respuesta);
      alert('¡Bienvenido, ' + respuesta.datos.nombre + '!');
      router.push('/rutinas');
    } else {
      alert(respuesta.mensaje ?? 'Error al iniciar sesión');
    }
  } catch (error) {
    console.error('Error al iniciar sesión:', error);
    alert('No se pudo conectar con el servidor. Error: ' + (error as Error).message);
  }
};

const cerrarSesion = () => {
  sesion.UsuarioConectado = null;
  sesion.Autenticado = false;
};
</script>

<template>
  <div class="pantalla-login">
    <main class="zona-centro">
      <div class="bloque-auth">
        <!-- Logo de la empresa centrado arriba -->
        <router-link to="/" class="enlace-logo" title="Volver al inicio">
          <img src="@/components/icons/logoV.jpg" alt="Logo FITCORE" class="foto-logo" />
          <h1 class="nombre-logo">FITCORE</h1>
        </router-link>

        <FormLogin
          :sesion="sesion"
          @guardar="loguear"
          @cerrar-sesion="cerrarSesion"
        />
      </div>
    </main>
  </div>
</template>

<style scoped>
.pantalla-login {
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
