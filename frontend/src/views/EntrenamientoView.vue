<script setup lang="ts">
import { ref, onMounted, onUnmounted, computed } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();

// ==========================================
// CRONÓMETRO GENERAL DEL ENTRENAMIENTO
// ==========================================
const segundosTotales = ref<number>(0);
let intervaloCronometro: number | null = null;

const formatearTiempo = (totalSegundos: number) => {
  const min = Math.floor(totalSegundos / 60);
  const sec = totalSegundos % 60;
  return `${min.toString().padStart(2, '0')}:${sec.toString().padStart(2, '0')}`;
};

onMounted(() => {
  intervaloCronometro = window.setInterval(() => {
    segundosTotales.value++;
  }, 1000);
});

// ==========================================
// TEMPORIZADOR DE DESCANSO (60s A 90s)
// ==========================================
const enDescanso = ref<boolean>(false);
const duracionInicialDescanso = ref<number>(60);
const segundosRestantesDescanso = ref<number>(60);
let intervaloDescanso: number | null = null;

const porcentajeDescanso = computed(() => {
  if (duracionInicialDescanso.value === 0) return 0;
  return ((duracionInicialDescanso.value - segundosRestantesDescanso.value) / duracionInicialDescanso.value) * 100;
});

const iniciarDescanso = (duracion: number = 60) => {
  if (intervaloDescanso) clearInterval(intervaloDescanso);
  duracionInicialDescanso.value = duracion;
  segundosRestantesDescanso.value = duracion;
  enDescanso.value = true;

  intervaloDescanso = window.setInterval(() => {
    if (segundosRestantesDescanso.value > 0) {
      segundosRestantesDescanso.value--;
    } else {
      terminarDescanso();
    }
  }, 1000);
};

const terminarDescanso = () => {
  if (intervaloDescanso) {
    clearInterval(intervaloDescanso);
    intervaloDescanso = null;
  }
  enDescanso.value = false;

  // Si todas las series del ejercicio actual fueron completadas, pasa al siguiente ejercicio automáticamente
  if (
    seriesCompletadasEjercicio.value === totalSeriesEjercicio.value &&
    indiceEjercicioActual.value < listaEjercicios.value.length - 1
  ) {
    indiceEjercicioActual.value++;
  }
};

const cambiarTiempoDescanso = (nuevoTiempo: number) => {
  duracionInicialDescanso.value = nuevoTiempo;
  segundosRestantesDescanso.value = nuevoTiempo;
};

const sumarTiempo = (segundosExtra: number) => {
  segundosRestantesDescanso.value += segundosExtra;
  duracionInicialDescanso.value += segundosExtra;
};

onUnmounted(() => {
  if (intervaloCronometro) clearInterval(intervaloCronometro);
  if (intervaloDescanso) clearInterval(intervaloDescanso);
});

// ==========================================
// LISTA DE EJERCICIOS Y SERIES
// ==========================================
interface Serie {
  numero: number;
  repeticiones: number;
  peso: string;
  completada: boolean;
}

interface Ejercicio {
  id: number;
  nombre: string;
  musculos: string[];
  imagen: string;
  consejo: string;
  series: Serie[];
}

const indiceEjercicioActual = ref<number>(0);

const listaEjercicios = ref<Ejercicio[]>([
  {
    id: 1,
    nombre: 'Flexiones de Pecho (Push-Ups)',
    musculos: ['Pectorales', 'Tríceps', 'Core'],
    imagen: 'https://images.unsplash.com/photo-1598971639058-fab3c3109a00?w=800&auto=format&fit=crop',
    consejo: 'Mantén la espalda recta y baja controladamente hasta casi rozar el suelo con el pecho.',
    series: [
      { numero: 1, repeticiones: 12, peso: 'Corporal', completada: false },
      { numero: 2, repeticiones: 12, peso: 'Corporal', completada: false },
      { numero: 3, repeticiones: 10, peso: 'Corporal', completada: false },
      { numero: 4, repeticiones: 8, peso: 'Corporal', completada: false },
    ]
  },
  {
    id: 2,
    nombre: 'Dominadas Pronas (Pull-Ups)',
    musculos: ['Dorsales', 'Bíceps', 'Espalda alta'],
    imagen: 'https://images.unsplash.com/photo-1534367507873-d2d7e24c797f?w=800&auto=format&fit=crop',
    consejo: 'Supera la barra con la barbilla sin impulsarte con las piernas (sin balanceo).',
    series: [
      { numero: 1, repeticiones: 8, peso: 'Corporal', completada: false },
      { numero: 2, repeticiones: 8, peso: 'Corporal', completada: false },
      { numero: 3, repeticiones: 6, peso: 'Corporal', completada: false },
    ]
  },
  {
    id: 3,
    nombre: 'Fondos en Paralelas (Dips)',
    musculos: ['Tríceps', 'Pectoral inferior', 'Hombros'],
    imagen: 'https://images.unsplash.com/photo-1581009146145-b5ef050c2e1e?w=800&auto=format&fit=crop',
    consejo: 'Baja formando un ángulo de 90 grados en los codos y empuja con fuerza hacia arriba.',
    series: [
      { numero: 1, repeticiones: 10, peso: 'Corporal', completada: false },
      { numero: 2, repeticiones: 10, peso: 'Corporal', completada: false },
      { numero: 3, repeticiones: 8, peso: 'Corporal', completada: false },
    ]
  }
]);

const ejercicioActual = computed(() => listaEjercicios.value[indiceEjercicioActual.value]);

// Marcar serie como completada e iniciar descanso automático
const alternarSerie = (serie: Serie) => {
  serie.completada = !serie.completada;

  if (serie.completada) {
    // Si terminó la última serie del ejercicio, descansar 90s, si es intermedia descansar 60s
    const todasCompletadas = ejercicioActual.value.series.every(s => s.completada);
    iniciarDescanso(todasCompletadas ? 90 : 60);
  }
};

// Navegación entre ejercicios
const irSiguienteEjercicio = () => {
  if (indiceEjercicioActual.value < listaEjercicios.value.length - 1) {
    indiceEjercicioActual.value++;
  }
};

const irAnteriorEjercicio = () => {
  if (indiceEjercicioActual.value > 0) {
    indiceEjercicioActual.value--;
  }
};

// Conteo de series
const totalSeriesEjercicio = computed(() => ejercicioActual.value.series.length);
const seriesCompletadasEjercicio = computed(() => ejercicioActual.value.series.filter(s => s.completada).length);

const finalizarEntrenamiento = () => {
  alert('¡Entrenamiento finalizado con éxito! Has ganado +100 XP');
  router.push('/rutinas');
};
</script>

<template>
  <div class="pantalla-entrenamiento">
    <!-- Barra de arriba con cronómetro general y botón de finalizar -->
    <header class="barra-superior-entreno">
      <button class="boton-volver" @click="router.push('/rutinas')">
        <i class="bi bi-chevron-left"></i>
        <span>Volver a Rutinas</span>
      </button>

      <!-- Cronómetro general de la sesión -->
      <div class="caja-cronometro">
        <span class="puntito-cronometro"></span>
        <span class="texto-tiempo">{{ formatearTiempo(segundosTotales) }}</span>
      </div>

      <!-- Botón de terminar -->
      <button class="boton-terminar" @click="finalizarEntrenamiento">
        <i class="bi bi-check-circle-fill"></i>
        <span>Finalizar Sesión</span>
      </button>
    </header>

    <!-- Contenido principal en dos columnas -->
    <main class="zona-entrenamiento">
      <div class="rejilla-entrenamiento">
        <!-- COLUMNA IZQUIERDA: Tarjeta con la foto y datos del ejercicio -->
        <section class="tarjeta-ejercicio">
          <div class="cabecera-ejercicio">
            <span class="etiqueta-roja">
              EJERCICIO {{ indiceEjercicioActual + 1 }} DE {{ listaEjercicios.length }}
            </span>
            <h1 class="nombre-ejercicio">{{ ejercicioActual.nombre }}</h1>

            <!-- Etiquetas de músculos trabajados -->
            <div class="fila-musculos">
              <span v-for="musculo in ejercicioActual.musculos" :key="musculo" class="pildora-musculo">
                {{ musculo }}
              </span>
            </div>
          </div>

          <!-- Foto del ejercicio -->
          <div class="contenedor-foto">
            <img :src="ejercicioActual.imagen" :alt="ejercicioActual.nombre" class="foto-ejercicio" />
            <div class="sombra-foto">
              <span class="pildora-tecnica"><i class="bi bi-camera-video-fill"></i> Demostración</span>
            </div>
          </div>

          <!-- Consejo para hacer bien el ejercicio -->
          <div class="caja-consejo">
            <i class="bi bi-info-circle-fill icono-consejo"></i>
            <p>{{ ejercicioActual.consejo }}</p>
          </div>

          <!-- Botones para cambiar de ejercicio -->
          <div class="fila-cambiar-ejercicio">
            <button
              class="boton-cambio"
              :disabled="indiceEjercicioActual === 0"
              @click="irAnteriorEjercicio"
            >
              <i class="bi bi-arrow-left"></i> Anterior
            </button>
            <button
              class="boton-cambio rojo"
              :disabled="indiceEjercicioActual === listaEjercicios.length - 1"
              @click="irSiguienteEjercicio"
            >
              Siguiente <i class="bi bi-arrow-right"></i>
            </button>
          </div>
        </section>

        <!-- COLUMNA DERECHA: Cuadro de series y repeticiones a realizar -->
        <section class="tarjeta-series">
          <div class="cabecera-series">
            <div>
              <span class="etiqueta-roja">OBJETIVO DEL EJERCICIO</span>
              <h2 class="titulo-series">Series y Repeticiones</h2>
            </div>
            <div class="contador-series">
              <strong>{{ seriesCompletadasEjercicio }} / {{ totalSeriesEjercicio }}</strong>
              <small>Completadas</small>
            </div>
          </div>

          <!-- Lista de las series a realizar -->
          <div class="lista-series">
            <div
              v-for="serie in ejercicioActual.series"
              :key="serie.numero"
              :class="['fila-serie', { 'serie-hecha': serie.completada }]"
              @click="alternarSerie(serie)"
            >
              <div class="lado-izq-serie">
                <span class="circulo-numero">{{ serie.numero }}</span>
                <div>
                  <strong class="texto-reps">{{ serie.repeticiones }} Repeticiones</strong>
                  <p class="texto-peso">Carga: {{ serie.peso }}</p>
                </div>
              </div>

              <!-- Botón interactivo para marcar la serie -->
              <button
                type="button"
                :class="['boton-marcar-serie', { 'completado': serie.completada }]"
                @click.stop="alternarSerie(serie)"
              >
                <i v-if="serie.completada" class="bi bi-check-lg"></i>
                <span v-else>Listo</span>
              </button>
            </div>
          </div>

          <!-- Botón / Caja para activar descanso manualmente -->
          <div class="caja-descanso">
            <div class="lado-descanso">
              <i class="bi bi-stopwatch-fill icono-reloj"></i>
              <div>
                <strong>Descanso sugerido</strong>
                <p>60 a 90 segundos entre series</p>
              </div>
            </div>
            <div class="botones-descanso-rapido">
              <button class="boton-iniciar-descanso" @click="iniciarDescanso(60)">
                Descansar 60s
              </button>
              <button class="boton-iniciar-descanso" @click="iniciarDescanso(90)">
                90s
              </button>
            </div>
          </div>
        </section>
      </div>
    </main>

    <!-- ============================================================== -->
    <!-- MODAL / PANTALLA FLOTANTE DE DESCANSO CON CONTADOR (60s a 90s) -->
    <!-- ============================================================== -->
    <div v-if="enDescanso" class="fondo-descanso">
      <div class="tarjeta-descanso">
        <span class="etiqueta-roja">
          <i class="bi bi-heart-pulse-fill"></i> TIEMPO DE RECUPERACIÓN
        </span>

        <h2 class="titulo-descanso">¡Serie completada! Toma aire</h2>
        <p class="subtitulo-descanso">Respira profundamente antes de continuar con la siguiente ronda.</p>

        <!-- Contador gigante en segundos -->
        <div class="reloj-descanso-circular">
          <span class="numero-segundos">{{ segundosRestantesDescanso }}</span>
          <small class="texto-segundos">SEGUNDOS</small>
        </div>

        <!-- Barra de progreso del descanso -->
        <div class="barra-descanso-fondo">
          <div class="barra-descanso-relleno" :style="{ width: porcentajeDescanso + '%' }"></div>
        </div>

        <!-- Botones para alternar duración del descanso (60s o 90s) -->
        <div class="fila-duraciones">
          <button
            :class="['boton-tiempo', { 'activo': duracionInicialDescanso === 60 }]"
            @click="cambiarTiempoDescanso(60)"
          >
            60s
          </button>
          <button
            :class="['boton-tiempo', { 'activo': duracionInicialDescanso === 90 }]"
            @click="cambiarTiempoDescanso(90)"
          >
            90s
          </button>
          <button class="boton-tiempo extra" @click="sumarTiempo(15)">
            +15s
          </button>
        </div>

        <!-- BOTÓN PRINCIPAL PARA TERMINAR ANTES DE DESCANSAR -->
        <button class="boton-terminar-antes" @click="terminarDescanso">
          <i class="bi bi-play-fill"></i>
          <span>Terminar descanso ahora</span>
        </button>
      </div>
    </div>
  </div>
</template>

<style scoped>
.pantalla-entrenamiento {
  min-height: 100vh;
  background-color: #0c0c0e;
  color: #ffffff;
  font-family: 'Rubik', sans-serif;
  display: flex;
  flex-direction: column;
  box-sizing: border-box;
  position: relative;
}

/* Barra superior */
.barra-superior-entreno {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 16px 36px;
  background-color: #121214;
  border-bottom: 1px solid #222226;
  position: sticky;
  top: 0;
  z-index: 50;
}

.boton-volver {
  background: transparent;
  color: #a1a1aa;
  border: 1px solid #27272a;
  padding: 8px 16px;
  border-radius: 8px;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 8px;
  font-weight: 600;
  font-size: 0.85rem;
  transition: all 0.2s;
}

.boton-volver:hover {
  color: #ffffff;
  border-color: #3f3f46;
}

.caja-cronometro {
  display: flex;
  align-items: center;
  gap: 10px;
  background: #18181b;
  border: 1px solid #27272a;
  padding: 8px 22px;
  border-radius: 20px;
}

.puntito-cronometro {
  width: 8px;
  height: 8px;
  background: #ff3b30;
  border-radius: 50%;
  box-shadow: 0 0 10px #ff3b30;
  animation: pulso 1.5s infinite;
}

@keyframes pulso {
  0% { transform: scale(0.95); opacity: 0.7; }
  50% { transform: scale(1.2); opacity: 1; }
  100% { transform: scale(0.95); opacity: 0.7; }
}

.texto-tiempo {
  font-size: 1.25rem;
  font-weight: 800;
  letter-spacing: 1.5px;
}

.boton-terminar {
  background: #ff3b30;
  color: #ffffff;
  border: none;
  padding: 10px 20px;
  border-radius: 10px;
  font-weight: 700;
  font-size: 0.88rem;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 8px;
  transition: all 0.2s;
  box-shadow: 0 4px 14px rgba(255, 59, 48, 0.25);
}

.boton-terminar:hover {
  background: #e03229;
  transform: translateY(-1px);
}

/* Zona central de dos columnas */
.zona-entrenamiento {
  flex: 1;
  max-width: 1300px;
  width: 100%;
  margin: 0 auto;
  padding: 30px 24px 60px 24px;
  box-sizing: border-box;
}

.rejilla-entrenamiento {
  display: grid;
  grid-template-columns: 1.1fr 0.9fr;
  gap: 28px;
  align-items: start;
}

/* Columna izquierda: Ejercicio e Imagen */
.tarjeta-ejercicio {
  background: #18181b;
  border: 1px solid #27272a;
  border-radius: 16px;
  padding: 26px;
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.cabecera-ejercicio {
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
  font-size: 0.72rem;
  font-weight: 800;
  padding: 4px 10px;
  border-radius: 6px;
  width: fit-content;
  letter-spacing: 0.5px;
}

.nombre-ejercicio {
  font-size: 1.6rem;
  font-weight: 800;
  margin: 0;
  letter-spacing: -0.3px;
}

.fila-musculos {
  display: flex;
  gap: 8px;
  flex-wrap: wrap;
}

.pildora-musculo {
  background: #121214;
  border: 1px solid #27272a;
  color: #a1a1aa;
  font-size: 0.75rem;
  font-weight: 600;
  padding: 4px 10px;
  border-radius: 6px;
}

.contenedor-foto {
  position: relative;
  width: 100%;
  height: 280px;
  border-radius: 12px;
  overflow: hidden;
  border: 1px solid #27272a;
}

.foto-ejercicio {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
}

.sombra-foto {
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  padding: 14px;
  background: linear-gradient(to top, rgba(0, 0, 0, 0.7), transparent);
  display: flex;
  justify-content: flex-end;
}

.pildora-tecnica {
  background: rgba(0, 0, 0, 0.6);
  backdrop-filter: blur(4px);
  color: #ffffff;
  font-size: 0.75rem;
  font-weight: 600;
  padding: 4px 10px;
  border-radius: 6px;
  display: flex;
  align-items: center;
  gap: 6px;
}

.caja-consejo {
  background: #121214;
  border: 1px solid #27272a;
  border-radius: 12px;
  padding: 14px 18px;
  display: flex;
  align-items: flex-start;
  gap: 12px;
}

.icono-consejo {
  color: #ff3b30;
  font-size: 1.1rem;
  margin-top: 2px;
}

.caja-consejo p {
  color: #d4d4d8;
  font-size: 0.85rem;
  line-height: 1.5;
  margin: 0;
}

.fila-cambiar-ejercicio {
  display: flex;
  justify-content: space-between;
  gap: 12px;
  padding-top: 6px;
}

.boton-cambio {
  flex: 1;
  background: #121214;
  border: 1px solid #27272a;
  color: #ffffff;
  padding: 10px 16px;
  border-radius: 10px;
  font-weight: 700;
  font-size: 0.85rem;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  transition: all 0.2s;
}

.boton-cambio:hover:not(:disabled) {
  border-color: #3f3f46;
  background: #27272a;
}

.boton-cambio.rojo {
  background: #ff3b30;
  border-color: #ff3b30;
}

.boton-cambio.rojo:hover:not(:disabled) {
  background: #e03229;
}

.boton-cambio:disabled {
  opacity: 0.4;
  cursor: not-allowed;
}

/* Columna derecha: Tarjeta de Series */
.tarjeta-series {
  background: #18181b;
  border: 1px solid #27272a;
  border-radius: 16px;
  padding: 26px;
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.cabecera-series {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
}

.titulo-series {
  font-size: 1.4rem;
  font-weight: 800;
  margin: 4px 0 0 0;
}

.contador-series {
  background: #121214;
  border: 1px solid #27272a;
  padding: 8px 14px;
  border-radius: 10px;
  text-align: right;
  display: flex;
  flex-direction: column;
}

.contador-series strong {
  font-size: 1.1rem;
  color: #ff3b30;
}

.contador-series small {
  font-size: 0.7rem;
  color: #71717a;
}

/* Lista de series */
.lista-series {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.fila-serie {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background: #121214;
  border: 1px solid #27272a;
  padding: 14px 18px;
  border-radius: 12px;
  cursor: pointer;
  transition: all 0.2s ease;
}

.fila-serie:hover {
  border-color: #3f3f46;
}

.fila-serie.serie-hecha {
  border-color: rgba(52, 199, 89, 0.4);
  background: rgba(52, 199, 89, 0.05);
}

.lado-izq-serie {
  display: flex;
  align-items: center;
  gap: 14px;
}

.circulo-numero {
  width: 34px;
  height: 34px;
  border-radius: 8px;
  background: #18181b;
  border: 1px solid #27272a;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.9rem;
  font-weight: 800;
  color: #ffffff;
}

.texto-reps {
  font-size: 0.95rem;
  color: #ffffff;
  display: block;
}

.texto-peso {
  font-size: 0.78rem;
  color: #71717a;
  margin: 2px 0 0 0;
}

.boton-marcar-serie {
  background: #27272a;
  color: #ffffff;
  border: none;
  padding: 8px 18px;
  border-radius: 8px;
  font-size: 0.85rem;
  font-weight: 700;
  cursor: pointer;
  transition: all 0.2s;
  min-width: 70px;
}

.boton-marcar-serie.completado {
  background: #34c759;
  color: #ffffff;
}

.boton-marcar-serie:hover:not(.completado) {
  background: #ff3b30;
}

/* Caja de descanso en tarjeta */
.caja-descanso {
  background: #121214;
  border: 1px solid #27272a;
  border-radius: 12px;
  padding: 14px 18px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 10px;
}

.lado-descanso {
  display: flex;
  align-items: center;
  gap: 12px;
}

.icono-reloj {
  color: #ff3b30;
  font-size: 1.3rem;
}

.lado-descanso strong {
  font-size: 0.85rem;
  display: block;
}

.lado-descanso p {
  font-size: 0.75rem;
  color: #71717a;
  margin: 2px 0 0 0;
}

.botones-descanso-rapido {
  display: flex;
  gap: 8px;
}

.boton-iniciar-descanso {
  background: #27272a;
  color: #ffffff;
  border: none;
  padding: 8px 14px;
  border-radius: 8px;
  font-size: 0.8rem;
  font-weight: 700;
  cursor: pointer;
  transition: background 0.2s;
}

.boton-iniciar-descanso:hover {
  background: #ff3b30;
}

/* Modal de descanso */
.fondo-descanso {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.88);
  backdrop-filter: blur(8px);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 100;
  padding: 20px;
}

.tarjeta-descanso {
  background: #18181b;
  border: 1px solid #27272a;
  border-radius: 20px;
  padding: 40px;
  max-width: 480px;
  width: 100%;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 20px;
  text-align: center;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.6);
}

.titulo-descanso {
  font-size: 1.6rem;
  font-weight: 800;
  margin: 0;
}

.subtitulo-descanso {
  color: #a1a1aa;
  font-size: 0.88rem;
  margin: 0;
  line-height: 1.5;
}

.reloj-descanso-circular {
  width: 140px;
  height: 140px;
  border-radius: 50%;
  background: #121214;
  border: 3px solid #ff3b30;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  box-shadow: 0 0 30px rgba(255, 59, 48, 0.3);
}

.numero-segundos {
  font-size: 3rem;
  font-weight: 900;
  line-height: 1;
  color: #ffffff;
}

.texto-segundos {
  font-size: 0.65rem;
  font-weight: 800;
  letter-spacing: 1px;
  color: #ff3b30;
}

.barra-descanso-fondo {
  width: 100%;
  height: 8px;
  background: #121214;
  border: 1px solid #27272a;
  border-radius: 20px;
  overflow: hidden;
}

.barra-descanso-relleno {
  height: 100%;
  background: linear-gradient(90deg, #ff3b30, #ff6b60);
  border-radius: 20px;
  transition: width 1s linear;
}

.fila-duraciones {
  display: flex;
  gap: 10px;
}

.boton-tiempo {
  background: #27272a;
  color: #a1a1aa;
  border: 1px solid #3f3f46;
  padding: 8px 18px;
  border-radius: 8px;
  font-size: 0.85rem;
  font-weight: 700;
  cursor: pointer;
  transition: all 0.2s;
}

.boton-tiempo.activo,
.boton-tiempo:hover {
  background: #ff3b30;
  border-color: #ff3b30;
  color: #ffffff;
}

.boton-tiempo.extra {
  background: #121214;
  color: #71717a;
}

.boton-tiempo.extra:hover {
  background: #27272a;
  color: #ffffff;
}

.boton-terminar-antes {
  background: #ff3b30;
  color: #ffffff;
  border: none;
  padding: 14px 32px;
  border-radius: 12px;
  font-size: 1rem;
  font-weight: 800;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 10px;
  transition: all 0.2s;
  box-shadow: 0 4px 20px rgba(255, 59, 48, 0.35);
  width: 100%;
  justify-content: center;
}

.boton-terminar-antes:hover {
  background: #e03229;
  transform: translateY(-1px);
}

/* Responsive */
@media (max-width: 900px) {
  .rejilla-entrenamiento {
    grid-template-columns: 1fr;
  }
  .barra-superior-entreno {
    padding: 12px 16px;
  }
}
</style>
