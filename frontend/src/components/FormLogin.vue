<script setup lang="ts">
import { ref } from 'vue';
import type { EstadoSesion } from '@/DTO/EstadoSesion';

const email = ref<string>('');
const password = ref<string>('');

const props = defineProps<{
  sesion: EstadoSesion;
}>();

const emit = defineEmits<{
  (e: 'guardar', payload: { email: string; password: string }): void;
  (e: 'cerrar-sesion'): void;
}>();

const iniciarSesion = (): void => {
  if (!email.value || !password.value) {
    alert('Por favor completa todos los campos.');
    return;
  }
  emit('guardar', {
    email: email.value,
    password: password.value
  });
};
</script>

<template>
  <div class="caja-formulario">
    <div class="tarjeta-formulario">
      <!-- Si NO está autenticado: Formulario de Login -->
      <div v-if="!sesion.Autenticado">
        <div class="cabecera-tarjeta">
          <span class="etiqueta-roja">
            <i class="bi bi-shield-lock-fill"></i> ACCESO SEGURO
          </span>
          <h2 class="titulo-tarjeta">Iniciar Sesión</h2>
          <p class="subtitulo-tarjeta">Ingresa a FitCore para continuar tu entrenamiento.</p>
        </div>

        <form class="formulario" @submit.prevent="iniciarSesion">
          <div class="campo">
            <label class="etiqueta-campo" for="campo-email">Correo electrónico</label>
            <input
              id="campo-email"
              v-model="email"
              type="email"
              class="caja-escribir"
              placeholder="correo@ejemplo.com"
              required
            />
          </div>

          <div class="campo">
            <label class="etiqueta-campo" for="campo-password">Contraseña</label>
            <input
              id="campo-password"
              v-model="password"
              type="password"
              class="caja-escribir"
              placeholder="Tu contraseña"
              required
            />
          </div>

          <button type="submit" class="boton-rojo-enviar">
            <span>Iniciar Sesión</span>
            <i class="bi bi-arrow-right flechita"></i>
          </button>
        </form>

        <div class="pie-tarjeta">
          <span class="texto-pie">¿No tienes una cuenta?</span>
          <router-link to="/registro" class="enlace-rojo">Crear cuenta</router-link>
        </div>
      </div>

      <!-- Si YA está autenticado -->
      <div v-else class="sesion-activa-box">
        <span class="etiqueta-roja">SESIÓN ACTIVA</span>
        <h2 class="titulo-tarjeta">¡Hola, {{ sesion.UsuarioConectado }}!</h2>
        <p class="subtitulo-tarjeta">Ya tienes una sesión iniciada en tu cuenta.</p>
        
        <div class="fila-botones-sesion">
          <router-link to="/rutinas" class="boton-rojo-enviar">
            <span>Ir a Entrenar</span>
            <i class="bi bi-arrow-right flechita"></i>
          </router-link>
          <button type="button" class="boton-cerrar-sesion" @click="$emit('cerrar-sesion')">
            Cerrar Sesión
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.caja-formulario {
  width: 100%;
  max-width: 460px;
  margin: 0 auto;
  box-sizing: border-box;
  font-family: 'Rubik', sans-serif;
}

.tarjeta-formulario {
  background: #18181b;
  border: 1px solid #27272a;
  border-radius: 16px;
  padding: 32px;
  display: flex;
  flex-direction: column;
  gap: 24px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
  transition: border-color 0.2s ease;
}

.tarjeta-formulario:hover {
  border-color: #3f3f46;
}

.cabecera-tarjeta {
  display: flex;
  flex-direction: column;
  gap: 8px;
  margin-bottom: 20px;
}

.etiqueta-roja {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  background: rgba(255, 59, 48, 0.15);
  color: #ff3b30;
  font-size: 0.75rem;
  font-weight: 700;
  padding: 4px 10px;
  border-radius: 6px;
  width: fit-content;
  letter-spacing: 0.5px;
}

.titulo-tarjeta {
  font-size: 1.6rem;
  font-weight: 800;
  color: #ffffff;
  margin: 0;
  letter-spacing: -0.3px;
}

.subtitulo-tarjeta {
  font-size: 0.88rem;
  color: #a1a1aa;
  margin: 0;
  line-height: 1.4;
}

.formulario {
  display: flex;
  flex-direction: column;
  gap: 18px;
}

.campo {
  display: flex;
  flex-direction: column;
  gap: 6px;
}

.etiqueta-campo {
  font-size: 0.82rem;
  font-weight: 600;
  color: #d4d4d8;
}

.caja-escribir {
  height: 44px;
  width: 100%;
  background-color: #121214;
  border: 1px solid #27272a;
  border-radius: 10px;
  padding: 0 14px;
  color: #ffffff;
  font-size: 0.9rem;
  font-family: inherit;
  box-sizing: border-box;
  outline: none;
  transition: all 0.2s ease;
}

.caja-escribir::placeholder {
  color: #71717a;
}

.caja-escribir:hover {
  border-color: #3f3f46;
}

.caja-escribir:focus {
  border-color: #ff3b30;
  box-shadow: 0 0 0 2px rgba(255, 59, 48, 0.2);
}

.boton-rojo-enviar {
  background: #ff3b30;
  color: #ffffff;
  text-decoration: none;
  border: none;
  height: 46px;
  padding: 0 20px;
  border-radius: 10px;
  font-size: 0.92rem;
  font-weight: 700;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  margin-top: 6px;
  transition: all 0.2s ease;
  box-shadow: 0 4px 14px rgba(255, 59, 48, 0.25);
}

.boton-rojo-enviar:hover {
  background: #e03229;
  transform: translateY(-1px);
  box-shadow: 0 6px 18px rgba(255, 59, 48, 0.35);
}

.flechita {
  font-size: 1rem;
  transition: transform 0.2s ease;
}

.boton-rojo-enviar:hover .flechita {
  transform: translateX(3px);
}

.pie-tarjeta {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  padding-top: 20px;
  margin-top: 20px;
  border-top: 1px solid #27272a;
  font-size: 0.85rem;
}

.texto-pie {
  color: #71717a;
}

.enlace-rojo {
  color: #ff3b30;
  text-decoration: none;
  font-weight: 600;
  transition: color 0.2s ease;
}

.enlace-rojo:hover {
  color: #e03229;
  text-decoration: underline;
}

/* Sesión activa */
.sesion-activa-box {
  display: flex;
  flex-direction: column;
  gap: 12px;
  text-align: center;
  align-items: center;
}

.fila-botones-sesion {
  display: flex;
  flex-direction: column;
  gap: 10px;
  width: 100%;
  margin-top: 14px;
}

.boton-cerrar-sesion {
  background: transparent;
  color: #a1a1aa;
  border: 1px solid #27272a;
  height: 42px;
  border-radius: 10px;
  font-size: 0.88rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s ease;
}

.boton-cerrar-sesion:hover {
  color: #ffffff;
  border-color: #3f3f46;
}

@media (max-width: 480px) {
  .tarjeta-formulario {
    padding: 22px 18px;
  }
}
</style>