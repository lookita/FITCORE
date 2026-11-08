<script setup lang="ts">
import { ref, computed, watch, onMounted } from 'vue';
import { useRutina } from '@/stores/rutina.store';
import { useUsuarioVerificacion } from '@/stores/auth.store';

const auth = useUsuarioVerificacion();
const rutina = useRutina();

// Función para saber si el usuario ya tiene rutina persistida
const yaTieneRutina = () => Boolean(auth.usuario?.tiene_rutina) || rutina.tieneRutina;

// Estado del modal
const visible = ref(false);
const categoriaSeleccionada = ref('');

// Lista de los días de la semana
const listaDias = [
  { id: 1, etiqueta: 'Lunes' },
  { id: 2, etiqueta: 'Martes' },
  { id: 3, etiqueta: 'Miércoles' },
  { id: 4, etiqueta: 'Jueves' },
  { id: 5, etiqueta: 'Viernes' },
  { id: 6, etiqueta: 'Sábado' },
  { id: 7, etiqueta: 'Domingo' }
];

// Contar cuántos días seleccionó el usuario
const cantidadDias = computed(() => {
  return rutina.diasSeleccionados.length;
});

// Alternar selección con límite estricto de 1 a 6 días
function alternarDia(diaId: number) {
  if (rutina.diasSeleccionados.includes(diaId)) {
    rutina.seleccionarDia(diaId);
    rutina.error = '';
  } else {
    if (rutina.diasSeleccionados.length >= 6) {
      rutina.error = 'FITCORE permite como máximo 6 días de entrenamiento por semana.';
      return;
    }
    rutina.error = '';
    rutina.seleccionarDia(diaId);
  }
}

// ==============================================================
// ARQUITECTURA FITCORE - MOTOR GENERADOR:
// - 1 a 3 días: Full Body
// - 4 días: Upper / Lower
// - 5 días: Push / Pull / Legs
// - 6 días: Push / Pull / Legs × 2
// ==============================================================
watch(cantidadDias, (dias) => {
  rutina.error = '';
  if (dias >= 1 && dias <= 3) {
    categoriaSeleccionada.value = 'Full Body';
  } else if (dias === 4) {
    categoriaSeleccionada.value = 'Upper / Lower';
  } else if (dias === 5) {
    categoriaSeleccionada.value = 'Push / Pull / Legs';
  } else if (dias === 6) {
    categoriaSeleccionada.value = 'Push / Pull / Legs × 2';
  } else {
    categoriaSeleccionada.value = '';
  }
}, { immediate: true });

// Cargar catálogo y verificar si el usuario tiene rutina
onMounted(async () => {
  try {
    await rutina.cargarCatalogo();
    if (!rutina.tieneRutina && !auth.usuario?.tiene_rutina) {
      visible.value = true;
    } else {
      visible.value = false;
    }
  } catch (error) {
    if (!rutina.tieneRutina) {
      visible.value = true;
    }
  }
});

// Si cambia el estado en cualquier momento, mostrar si no tiene rutina
watch(
  () => rutina.tieneRutina,
  (tiene) => {
    if (!tiene) {
      visible.value = true;
    }
  }
);

// Función para enviar los datos y crear la rutina
async function generarRutina() {
  if (cantidadDias.value === 0) {
    rutina.error = 'Selecciona al menos un día para entrenar.';
    return;
  }

  if (!categoriaSeleccionada.value) {
    rutina.error = 'Selecciona una categoría para continuar.';
    return;
  }

  await rutina.crearRutina(categoriaSeleccionada.value);
  if (rutina.rutinaCreada) {
    if (auth.estadoSesion.usuarioConectado) {
      auth.estadoSesion.usuarioConectado.tiene_rutina = true;
    }
  }
}

// Cerrar la ventana
function cerrar() {
  visible.value = false;
}

// Abrir la ventana (para poder reabrirla desde un botón si se desea)
function abrir() {
  visible.value = true;
  rutina.error = '';
  rutina.rutinaCreada = null;
}

defineExpose({ abrir, cerrar });
</script>

<template>
  <Teleport to="body">
    <!-- Botón flotante para volver a abrir el generador si se cerró -->
    <button
      v-if="!visible"
      class="boton-flotante-abrir"
      type="button"
      title="Generar nueva rutina"
      @click="abrir"
    >
      <i class="bi bi-lightning-charge-fill"></i>
      <span>Nueva Rutina</span>
    </button>

    <!-- Ventana Modal del Generador -->
    <div v-if="visible" class="rutina-config-overlay">
      <section class="rutina-config-dialog">

        <!-- Botón cerrar (X) -->
        <button class="boton-cerrar" type="button" @click="cerrar">
          <i class="bi bi-x-lg"></i>
        </button>

        <!-- Encabezado -->
        <div class="encabezado-modal">
          <span class="etiqueta-modal">
            <i class="bi bi-lightning-charge-fill"></i>
            GENERADOR INTELIGENTE
          </span>
          <h2>{{ rutina.rutinaCreada ? '¡Tu rutina está lista!' : 'Configura tu plan de entrenamiento' }}</h2>
          <p v-if="!rutina.rutinaCreada">
            Elige los días de la semana en los que vas a entrenar. El sistema adaptará los ejercicios automáticamente según las reglas de entrenamiento.
          </p>
        </div>

        <!-- PANTALLA DE ÉXITO: Rutina ya creada -->
        <div v-if="rutina.rutinaCreada" class="resultado-rutina">
          <div class="alerta-exito">
            <i class="bi bi-check-circle-fill"></i>
            <div>
              <strong>¡Rutina guardada en tu calendario!</strong>
              <p>
                {{ rutina.rutinaCreada.categoria }} · Nivel {{ rutina.rutinaCreada.nivel_actual }} · 
                {{ rutina.rutinaCreada.dias_semana.map(id => listaDias[id - 1].etiqueta).join(', ') }}
              </p>
            </div>
          </div>

          <h3 class="subtitulo-resultado">Ejercicios asignados</h3>
          <ul class="lista-ejercicios">
            <li v-for="ejercicio in rutina.rutinaCreada.ejercicios" :key="ejercicio.id">
              <span class="punto-ejercicio">•</span>
              <span class="nombre-ejercicio">{{ ejercicio.nombre }}</span>
              <span class="series-reps">{{ ejercicio.series_objetivo }} series × {{ ejercicio.reps_objetivo }} reps</span>
            </li>
          </ul>

          <button class="boton-generar" type="button" @click="cerrar">
            Entendido, ¡a entrenar!
          </button>
        </div>

        <!-- FORMULARIO: Elección de días y categoría -->
        <div v-else class="formulario-rutina">

          <!-- 1. PASO: SELECCIÓN DE DÍAS -->
          <div class="bloque-paso">
            <div class="paso-titulo">
              <span class="numero-paso">1</span>
              <h3>¿Qué días vas a entrenar?</h3>
            </div>
            
            <div class="dias-rutina">
              <button
                v-for="dia in listaDias"
                :key="dia.id"
                class="tarjeta-dia"
                :class="{ 'tarjeta-dia-seleccionada': rutina.diasSeleccionados.includes(dia.id) }"
                type="button"
                :aria-pressed="rutina.diasSeleccionados.includes(dia.id)"
                @click="alternarDia(dia.id)"
              >
                <span v-if="rutina.diasSeleccionados.includes(dia.id)" class="marca-seleccion">
                  <i class="bi bi-check-lg"></i>
                </span>
                <span class="numero-dia">{{ String(dia.id).padStart(2, '0') }}</span>
                <h4>{{ dia.etiqueta }}</h4>
                <i class="bi bi-activity icono-mancuerna"></i>
                <span class="estado-dia">
                  {{ rutina.diasSeleccionados.includes(dia.id) ? 'Seleccionado' : 'Elegir' }}
                </span>
              </button>
            </div>
          </div>

          <!-- MENSAJE EXPLICATIVO DE LA REGLA SEGÚN LOS DÍAS ELEGIDOS -->
          <div v-if="cantidadDias === 0" class="aviso-regla aviso-info">
            <i class="bi bi-info-circle-fill"></i>
            <span>Toca arriba entre 1 y 6 días de la semana en los que realizarás tus entrenamientos.</span>
          </div>

          <div v-else-if="cantidadDias <= 3" class="aviso-regla aviso-fullbody">
            <i class="bi bi-shield-check"></i>
            <div>
              <strong>Regla para {{ cantidadDias }} {{ cantidadDias === 1 ? 'día' : 'días' }}: Full Body</strong>
              <p>Se utiliza el conjunto completo de ejercicios en cada día para estimular todo el cuerpo.</p>
            </div>
          </div>

          <div v-else-if="cantidadDias === 4" class="aviso-regla aviso-dividida">
            <i class="bi bi-shuffle"></i>
            <div>
              <strong>Regla para 4 días: Upper / Lower</strong>
              <p>Día 1 y 3: Upper (Push y Pull) · Día 2 y 4: Lower (Legs y Core).</p>
            </div>
          </div>

          <div v-else-if="cantidadDias === 5" class="aviso-regla aviso-dividida">
            <i class="bi bi-shuffle"></i>
            <div>
              <strong>Regla para 5 días: Push / Pull / Legs</strong>
              <p>Día 1: Push · Día 2: Pull · Día 3: Legs & Core · Día 4: Push · Día 5: Pull.</p>
            </div>
          </div>

          <div v-else class="aviso-regla aviso-dividida">
            <i class="bi bi-shuffle"></i>
            <div>
              <strong>Regla para 6 días: Push / Pull / Legs × 2</strong>
              <p>Doble frecuencia completa: Push / Pull / Legs / Push / Pull / Legs.</p>
            </div>
          </div>

          <!-- 2. PASO: CATEGORÍA GENERADA -->
          <div class="bloque-paso">
            <div class="paso-titulo">
              <span class="numero-paso">2</span>
              <h3>Distribución asignada por el motor</h3>
            </div>

            <div class="campo-rutina">
              <!-- Si ya eligió días: Muestra la categoría generada por la arquitectura -->
              <div v-if="cantidadDias >= 1" class="caja-fijada">
                <i class="bi bi-diagram-3-fill"></i>
                <span><strong>{{ categoriaSeleccionada }}</strong> · Generada automáticamente para {{ cantidadDias }} días</span>
              </div>

              <!-- Si aún no eligió días -->
              <div v-else class="caja-espera">
                <i class="bi bi-hand-index-thumb"></i>
                <span>Primero elige entre 1 y 6 días de entrenamiento arriba</span>
              </div>
            </div>
          </div>

          <!-- Información del nivel actual -->
          <div class="nivel-actual">
            <i class="bi bi-bar-chart-fill"></i>
            <span>Nivel actual del atleta: <strong>Nivel {{ rutina.nivelActual }}</strong></span>
          </div>

        </div>

        <!-- Mensaje de error si ocurre alguno -->
        <p v-if="rutina.error" class="error-rutina" role="alert">
          <i class="bi bi-exclamation-triangle-fill"></i>
          {{ rutina.error }}
        </p>

        <!-- Pie de la ventana modal -->
        <div v-if="!rutina.rutinaCreada" class="pie-modal">
          <span class="nota-semana">
            <i class="bi bi-calendar-check"></i>
            {{ cantidadDias === 0 ? 'Sin días seleccionados' : `${cantidadDias} días seleccionados` }}
          </span>

          <button
            class="boton-generar"
            type="button"
            :disabled="rutina.cargando || cantidadDias === 0 || !categoriaSeleccionada"
            @click="generarRutina"
          >
            <i v-if="rutina.cargando" class="bi bi-arrow-repeat girando"></i>
            <span v-if="rutina.cargando">Creando rutina...</span>
            <span v-else>Generar rutina</span>
            <i v-if="!rutina.cargando" class="bi bi-arrow-right"></i>
          </button>
        </div>

      </section>
    </div>
  </Teleport>
</template>

<style scoped>
/* ==============================================================
   ESTILOS DEL MODAL DE CONFIGURACIÓN DE RUTINAS
   ============================================================== */

.boton-flotante-abrir {
  position: fixed;
  bottom: 24px;
  right: 24px;
  z-index: 999;
  display: flex;
  align-items: center;
  gap: 8px;
  background-color: #ff3b30;
  color: #ffffff;
  border: none;
  border-radius: 999px;
  padding: 12px 20px;
  font-size: 14px;
  font-weight: 700;
  cursor: pointer;
  box-shadow: 0 4px 18px rgba(255, 59, 48, 0.4);
  transition: transform 0.2s, background-color 0.2s;
  margin-bottom: 100px;
  width: auto;
}

.boton-flotante-abrir:hover {
  background-color: #e03229;
  transform: translateY(-2px);
}

.rutina-config-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
  background: rgba(8, 8, 10, 0.85);
  backdrop-filter: blur(8px);
  padding: 20px;
  z-index: 10000;
  color: #ffffff;
  font-family: 'Rubik', sans-serif;
  box-sizing: border-box;
}

.rutina-config-dialog {
  width: 100%;
  max-width: 820px;
  max-height: 90vh;
  overflow-y: auto;
  background: #18181b;
  border: 1px solid #303034;
  border-radius: 18px;
  padding: 32px;
  position: relative;
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.6);
}

.boton-cerrar {
  position: absolute;
  top: 18px;
  right: 18px;
  width: 36px;
  height: 36px;
  background: #101012;
  color: #a1a1aa;
  border: 1px solid #303034;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 14px;
  cursor: pointer;
  transition: all 0.2s;
}

.boton-cerrar:hover {
  color: #ffffff;
  border-color: #ff3b30;
}

.encabezado-modal {
  margin-bottom: 24px;
}

.etiqueta-modal {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  color: #eab308;
  font-size: 11px;
  font-weight: 800;
  letter-spacing: 0.08em;
  text-transform: uppercase;
}

.encabezado-modal h2 {
  font-size: 26px;
  font-weight: 800;
  margin: 8px 0;
  color: #ffffff;
}

.encabezado-modal p {
  color: #a1a1aa;
  font-size: 14px;
  line-height: 1.5;
  margin: 0;
}

.formulario-rutina {
  display: grid;
  gap: 20px;
}

.bloque-paso {
  display: grid;
  gap: 12px;
}

.paso-titulo {
  display: flex;
  align-items: center;
  gap: 10px;
}

.numero-paso {
  width: 24px;
  height: 24px;
  border-radius: 50%;
  background: #ff3b30;
  color: #ffffff;
  font-size: 12px;
  font-weight: 700;
  display: flex;
  align-items: center;
  justify-content: center;
}

.paso-titulo h3 {
  margin: 0;
  font-size: 16px;
  font-weight: 600;
  color: #f4f4f5;
}

.dias-rutina {
  display: grid;
  grid-template-columns: repeat(7, 1fr);
  gap: 10px;
}

.tarjeta-dia {
  position: relative;
  background: #101012;
  color: #ffffff;
  border: 1px solid #303034;
  border-radius: 12px;
  min-height: 120px;
  padding: 12px 6px;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.2s ease;
}

.tarjeta-dia:hover {
  border-color: #ff3b30;
  transform: translateY(-2px);
}

.tarjeta-dia-seleccionada {
  background: #271719;
  border-color: #ff3b30;
}

.marca-seleccion {
  position: absolute;
  top: 6px;
  right: 6px;
  background: #ff3b30;
  color: #ffffff;
  width: 18px;
  height: 18px;
  border-radius: 50%;
  display: flex;
  justify-content: center;
  align-items: center;
  font-size: 10px;
}

.numero-dia {
  color: #71717a;
  font-size: 11px;
  font-weight: 600;
}

.tarjeta-dia h4 {
  font-size: 13px;
  margin: 6px 0;
  font-weight: 600;
}

.icono-mancuerna {
  font-size: 18px;
  color: #ff3b30;
}

.estado-dia {
  color: #a1a1aa;
  font-size: 10px;
  margin-top: 6px;
}

/* Avisos explicativos de la regla */
.aviso-regla {
  display: flex;
  align-items: flex-start;
  gap: 12px;
  padding: 12px 16px;
  border-radius: 10px;
  font-size: 13px;
  line-height: 1.4;
}

.aviso-regla strong {
  display: block;
  margin-bottom: 2px;
}

.aviso-regla p {
  margin: 0;
}

.aviso-info {
  background: #141418;
  border: 1px dashed #3f3f46;
  color: #a1a1aa;
  align-items: center;
}

.aviso-fullbody {
  background: rgba(234, 179, 8, 0.1);
  border: 1px solid rgba(234, 179, 8, 0.3);
  color: #fde047;
}

.aviso-fullbody i {
  font-size: 20px;
  color: #eab308;
}

.aviso-dividida {
  background: rgba(59, 130, 246, 0.1);
  border: 1px solid rgba(59, 130, 246, 0.3);
  color: #93c5fd;
}

.aviso-dividida i {
  font-size: 20px;
  color: #3b82f6;
}

/* Campos de entrada */
.campo-rutina {
  display: grid;
  gap: 8px;
}

.campo-rutina select {
  width: 100%;
  padding: 12px 14px;
  color: #ffffff;
  background: #101012;
  border: 1px solid #303034;
  border-radius: 10px;
  font-size: 14px;
  outline: none;
}

.campo-rutina select:focus {
  border-color: #ff3b30;
}

.caja-fijada {
  display: flex;
  align-items: center;
  gap: 10px;
  background: #101012;
  border: 1px solid #eab308;
  padding: 12px 14px;
  border-radius: 10px;
  color: #fef08a;
  font-size: 14px;
  font-weight: 500;
}

.caja-espera {
  display: flex;
  align-items: center;
  gap: 10px;
  background: #101012;
  border: 1px dashed #3f3f46;
  padding: 12px 14px;
  border-radius: 10px;
  color: #71717a;
  font-size: 14px;
}

.nivel-actual {
  display: flex;
  align-items: center;
  gap: 8px;
  color: #eab308;
  font-size: 13px;
  font-weight: 500;
}

/* Alertas de error */
.error-rutina {
  margin: 16px 0 0;
  background: rgba(239, 68, 68, 0.1);
  border: 1px solid rgba(239, 68, 68, 0.3);
  color: #fca5a5;
  padding: 10px 14px;
  border-radius: 8px;
  font-size: 13px;
  display: flex;
  align-items: center;
  gap: 8px;
}

/* Pantalla de éxito */
.resultado-rutina {
  display: grid;
  gap: 16px;
}

.alerta-exito {
  display: flex;
  align-items: center;
  gap: 14px;
  background: rgba(34, 197, 94, 0.1);
  border: 1px solid rgba(34, 197, 94, 0.3);
  padding: 16px;
  border-radius: 12px;
  color: #86efac;
}

.alerta-exito i {
  font-size: 28px;
  color: #22c55e;
}

.alerta-exito strong {
  display: block;
  font-size: 16px;
  color: #ffffff;
  margin-bottom: 4px;
}

.alerta-exito p {
  margin: 0;
  font-size: 13px;
  color: #bbf7d0;
}

.subtitulo-resultado {
  font-size: 15px;
  font-weight: 600;
  color: #f4f4f5;
  margin: 8px 0 0;
}

.lista-ejercicios {
  list-style: none;
  padding: 0;
  margin: 0;
  max-height: 240px;
  overflow-y: auto;
  display: grid;
  gap: 8px;
}

.lista-ejercicios li {
  background: #101012;
  border: 1px solid #27272a;
  border-radius: 8px;
  padding: 10px 14px;
  display: flex;
  align-items: center;
  gap: 10px;
  font-size: 13px;
}

.punto-ejercicio {
  color: #ff3b30;
  font-size: 18px;
}

.nombre-ejercicio {
  flex: 1;
  font-weight: 500;
  color: #ffffff;
}

.series-reps {
  color: #eab308;
  font-weight: 600;
  font-size: 12px;
}

/* Pie del modal */
.pie-modal {
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-top: 1px solid #303034;
  margin-top: 24px;
  padding-top: 20px;
}

.nota-semana {
  color: #eab308;
  font-size: 13px;
  font-weight: 600;
  display: flex;
  align-items: center;
  gap: 8px;
}

.boton-generar {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  background: #ff3b30;
  color: #ffffff;
  border: none;
  border-radius: 10px;
  padding: 14px 24px;
  font-size: 14px;
  font-weight: 700;
  cursor: pointer;
  transition: all 0.2s;
}

.boton-generar:hover:not(:disabled) {
  background: #e03229;
}

.boton-generar:disabled {
  background: #3f3f46;
  color: #a1a1aa;
  cursor: not-allowed;
}

.girando {
  animation: girar 1s linear infinite;
}

@keyframes girar {
  from { transform: rotate(0deg); }
  to { transform: rotate(360deg); }
}

/* Responsividad */
@media (max-width: 768px) {
  .dias-rutina {
    grid-template-columns: repeat(4, 1fr);
  }
}

@media (max-width: 480px) {
  .dias-rutina {
    grid-template-columns: repeat(2, 1fr);
  }
  .pie-modal {
    flex-direction: column;
    gap: 16px;
  }
  .boton-generar {
    width: 100%;
  }
}
</style>
