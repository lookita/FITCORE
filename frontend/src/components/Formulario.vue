<script setup lang="ts">
import { ref } from 'vue';

const nombre = ref<string>('');
const email = ref<string>('');
const password = ref<string>('');

const emit = defineEmits<{
  (e: 'guardar', payload: {
    nombre: string;
    password: string;
    email: string;
  }): void;
}>();

const enviar = (): void => {
  if (!nombre.value || !email.value || !password.value) {
    alert('Por favor completa todos los campos.');
    return;
  }
  emit('guardar', {
    nombre: nombre.value,
    password: password.value,
    email: email.value
  });
};
</script>

<template>
  <div class="caja-formulario">
    <!-- Tarjeta principal con el estilo de la tarjeta de rutinas -->
    <div class="tarjeta-formulario">
      <!-- Encabezado de la tarjeta -->
      <div class="cabecera-tarjeta">
        <span class="etiqueta-roja">
          <i class="bi bi-lightning-charge-fill"></i> PLAN INTELIGENTE
        </span>
        <h2 class="titulo-tarjeta">Crear Cuenta</h2>
        <p class="subtitulo-tarjeta">Regístrate en FitCore para comenzar tu entrenamiento.</p>
      </div>

      <!-- Formulario con sus campos -->
      <form class="formulario" @submit.prevent="enviar">
        <div class="campo">
          <label class="etiqueta-campo" for="campo-nombre">Nombre de usuario</label>
          <input
            id="campo-nombre"
            v-model="nombre"
            type="text"
            class="caja-escribir"
            placeholder="Ej. Lucas"
            required
          />
        </div>

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
            placeholder="Crea una contraseña segura"
            required
          />
        </div>

        <!-- Botón rojo para registrarse -->
        <button type="submit" class="boton-rojo-enviar">
          <span>Registrarme</span>
          <i class="bi bi-arrow-right flechita"></i>
        </button>
      </form>

      <!-- Pie de tarjeta -->
      <div class="pie-tarjeta">
        <span class="texto-pie">¿Ya tienes una cuenta?</span>
        <router-link to="/login" class="enlace-rojo">Iniciar sesión</router-link>
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

/* Tarjeta con los mismos colores y bordes que rutina-card */
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

/* Cabecera del formulario */
.cabecera-tarjeta {
  display: flex;
  flex-direction: column;
  gap: 8px;
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

/* Formulario y campos */
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

/* Botón rojo igual que btn-comenzar */
.boton-rojo-enviar {
  background: #ff3b30;
  color: #ffffff;
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

/* Pie de la tarjeta */
.pie-tarjeta {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  padding-top: 12px;
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

/* Responsividad para móviles */
@media (max-width: 480px) {
  .tarjeta-formulario {
    padding: 22px 18px;
  }
}
</style>