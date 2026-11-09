<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted, watch } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { useRutina } from '@/stores/rutina.store';
import { useUsuarioVerificacion } from '@/stores/auth.store';
import { registrarSesion, type ResultadoSesion } from '@/services/Rutina_service';
import BarraInferior from '@/components/Barra-inferior.vue';

const router = useRouter();
const route  = useRoute();
const rutina = useRutina();
const auth   = useUsuarioVerificacion();

// ─── CARGA INICIAL ────────────────────────────────────────────────────────
onMounted(async () => {
  if (!rutina.rutinaCreada) {
    try { 
      
      await rutina.cargarCatalogo(); 

    } catch {}
  }
  inicializarSeries();
});

// ─── DATOS DE LA RUTINA ───────────────────────────────────────────────────
const listaEjercicios = computed(() => rutina.rutinaCreada?.ejercicios ?? []);
const nivelUsuario    = computed(() => auth.nivel || rutina.rutinaCreada?.nivel_actual || 1);

const diasSemana = computed(() =>
  rutina.rutinaCreada?.dias_semana ?? rutina.diasSeleccionados ?? []
);
const cantidadDias = computed(() => diasSemana.value.length || 1);

// Número ordinal de la sesión elegida en /entrenamiento (1 a 6).
const diaActual = computed(() => {
  const d = Number(route.query.numeroDia) || Number(route.query.dia) || diasSemana.value[0] || 1;
  return Math.min(6, Math.max(1, d));
});

// ─── FÓRMULA XP ───────────────────────────────────────────────────────────
// XP/día  = 250 / días_elegidos
// XP/ej   = XP/día / ejercicios_reales (siempre usa el conteo REAL de la lista)
// Se muestra solo la parte entera; precisión completa para el backend.
const xpPorEjercicioReal = computed(() => {
  const ejs = listaEjercicios.value.length || 1;
  return 250 / cantidadDias.value / ejs;        // precisión completa
});
const xpPorEjercicioEntero = computed(() => Math.floor(xpPorEjercicioReal.value));

// 50 % series / 50 % repeticiones
const xpSeriesPorEj = computed(() => xpPorEjercicioReal.value / 2);
const xpRepsPorEj   = computed(() => xpPorEjercicioReal.value / 2);

// ─── EJERCICIO ACTIVO ─────────────────────────────────────────────────────
const ejercicioActualIndex = ref(0);
const ejercicioActual = computed(() =>
  listaEjercicios.value[ejercicioActualIndex.value] ?? null
);

// ─── SERIES (entrada manual) ──────────────────────────────────────────────
interface SerieLog {
  numero:     number;
  reps:       number;    // lo que el usuario ingresa manualmente
  completada: boolean;
}

const seriesEjercicio = ref<SerieLog[]>([]);

function inicializarSeries() {
  const ej = ejercicioActual.value;
  if (!ej) { seriesEjercicio.value = []; return; }
  // Precargamos con las series objetivo del ejercicio como punto de partida
  seriesEjercicio.value = Array.from({ length: ej.series_objetivo || 3 }, (_, i) => ({
    numero:     i + 1,
    reps:       ej.reps_objetivo || 10,
    completada: false
  }));
}

// Reinicializar al cambiar de ejercicio
watch(ejercicioActualIndex, () => {
  inicializarSeries();
  reiniciarCronometro();
});

// Agregar / quitar serie manualmente
function agregarSerie() {
  const len     = seriesEjercicio.value.length;
  const repsRef = seriesEjercicio.value[len - 1]?.reps ?? ejercicioActual.value?.reps_objetivo ?? 10;
  seriesEjercicio.value.push({ numero: len + 1, reps: repsRef, completada: false });
}
function quitarSerie() {
  if (seriesEjercicio.value.length > 1) {
    seriesEjercicio.value.pop();
  }
}

// ─── XP EN TIEMPO REAL (por serie individual) ─────────────────────────────
// XP/serie individual = xpSeriesPorEj / num_series_del_ejercicio
// XP/rep individual   = xpRepsPorEj   / (num_series × reps_objetivo)
const cantidadSeries = computed(() => seriesEjercicio.value.length || 1);

const xpXSerieIndividual = computed(() =>
  Math.floor(xpSeriesPorEj.value / cantidadSeries.value)
);
const xpXRepIndividual = computed(() => {
  const repsObj = ejercicioActual.value?.reps_objetivo || 10;
  return Math.floor(xpRepsPorEj.value / (cantidadSeries.value * repsObj));
});

// ─── PROGRESO ─────────────────────────────────────────────────────────────
const seriesCompletadas = computed(() =>
  seriesEjercicio.value.filter(s => s.completada).length
);
const progresoSeries = computed(() =>
  seriesEjercicio.value.length
    ? Math.round((seriesCompletadas.value / seriesEjercicio.value.length) * 100)
    : 0
);
const progresoTotal = computed(() =>
  listaEjercicios.value.length
    ? Math.round((ejercicioActualIndex.value / listaEjercicios.value.length) * 100)
    : 0
);

function marcarSerie(serie: SerieLog) {
  serie.completada = !serie.completada;
  if (serie.completada) iniciarCronometro(60);
}

// ─── CRONÓMETRO ───────────────────────────────────────────────────────────
const segundos        = ref(60);
const timerActivo     = ref(false);
let intervalo: ReturnType<typeof setInterval> | null = null;

function iniciarCronometro(seg = 60) {
  pausarCronometro();
  segundos.value  = seg;
  timerActivo.value = true;
  intervalo = setInterval(() => {
    if (segundos.value > 0) { segundos.value--; }
    else { pausarCronometro(); }
  }, 1000);
}
function pausarCronometro() {
  timerActivo.value = false;
  if (intervalo !== null) { clearInterval(intervalo); intervalo = null; }
}
function toggleTimer() {
  timerActivo.value ? pausarCronometro() : iniciarCronometro(segundos.value);
}
function reiniciarCronometro() {
  pausarCronometro();
  segundos.value = 60;
}
onUnmounted(() => pausarCronometro());

// ─── LOG ACUMULADO ────────────────────────────────────────────────────────
interface EjercicioRegistrado {
  ejercicio_id?: string;
  series:    { numero_serie: number; 
                repeticiones: number; 
                completada: boolean; 
                peso_kg: number }[];
  xp_ganado: number;   // precisión completa, solo para sumar al final
}
const logEjercicios = ref<EjercicioRegistrado[]>([]);

function completarEjercicioActual() {
  seriesEjercicio.value.forEach(s => { s.completada = true; });

  const ej = ejercicioActual.value;
  if (!ej) return;

  // XP real: proporcional a las reps realizadas vs objetivo
  const repsObj     = ej.reps_objetivo || 10;
  const totalRepsObj = cantidadSeries.value * repsObj;
  const totalRepsHechas = seriesEjercicio.value.reduce((a, s) => a + s.reps, 0);
  const proporcion = Math.min(totalRepsHechas / totalRepsObj, 1);

  // 50 % series (completar las series = 100 % si las marcó) + 50 % reps
  const seriesMarcadas = seriesEjercicio.value.filter(s => s.completada).length;
  const propSeries     = Math.min(seriesMarcadas / cantidadSeries.value, 1);

  const xpGanado = (xpSeriesPorEj.value * propSeries) + (xpRepsPorEj.value * proporcion);

  logEjercicios.value.push({
    ejercicio_id: ej.id,
    series: seriesEjercicio.value.map(s => ({
      numero_serie: s.numero,
      repeticiones: s.reps,
      completada:   s.completada,
      peso_kg:      0
    })),
    xp_ganado: xpGanado
  });
}

// ─── FINALIZAR ────────────────────────────────────────────────────────────
const guardando    = ref(false);
const errorMsg     = ref('');
const modalVisible = ref(false);
const resultado = ref<ResultadoSesion | null>(null);

async function siguiente() {
  completarEjercicioActual();

  if (ejercicioActualIndex.value < listaEjercicios.value.length - 1) {
    ejercicioActualIndex.value++;
    return;
  }

  // ── Fin de sesión → guardar en backend ────────────────────────────────
  const xpTotal = logEjercicios.value.reduce((a, e) => a + e.xp_ganado, 0);
  const diaNum  = diaActual.value;

  guardando.value = true;
  errorMsg.value  = '';

  try {
    const res = await registrarSesion({
      numero_dia:      diaNum,
      xp_total_sesion: parseFloat(xpTotal.toFixed(2)),
      ejercicios:      logEjercicios.value
    });

    // Actualizar store de auth en memoria con datos reales de la BD
    if (auth.estadoSesion.usuarioConectado) {
      auth.estadoSesion.usuarioConectado.xp_total    = res.xp_total_nuevo;
      auth.estadoSesion.usuarioConectado.nivel_actual = res.nivel_nuevo;
    }

    // Recargar el estado persistido: puede haber avanzado a la próxima semana.
    await rutina.cargarCatalogo();

    resultado.value = res;
    modalVisible.value = true; // Solo abrir modal si guardó exitosamente en la BD
  } catch (e: any) {
    errorMsg.value  = e?.message ?? 'Error al guardar la sesión en la base de datos.';
    modalVisible.value = false;
    alert('⚠ No se pudo guardar en la base de datos: ' + errorMsg.value);
  } finally {
    guardando.value  = false;
  }
}

function volver() { 
  
  router.push('/entrenamiento'); 

}
</script>

<template>
<div>
    <main class="workout-wrap">

    <!-- ── BARRA SUPERIOR ────────────────────────────────────────────── -->
    <div class="topbar">
      <button class="btn-volver" @click="volver">
        <i class="bi bi-arrow-left"></i> Volver
      </button>
      <span class="nivel-pill">
        <i class="bi bi-lightning-charge-fill"></i> Nivel {{ nivelUsuario }}
      </span>
    </div>

    <!-- ── SIN EJERCICIOS ────────────────────────────────────────────── -->
    <div v-if="listaEjercicios.length === 0" class="estado-vacio">
      <i class="bi bi-exclamation-circle" style="font-size:40px; color:#52525b;"></i>
      <p>No hay ejercicios en tu rutina. Configurá tu plan primero.</p>
      <button class="btn-accion" @click="volver">Volver</button>
    </div>

    <template v-else>

      <!-- ── PROGRESO GENERAL ──────────────────────────────────────── -->
      <div class="progreso-general">
        <div class="pg-info">
          <span class="eyebrow">SESIÓN EN PROGRESO</span>
          <span class="pg-num">
            {{ ejercicioActualIndex + 1 }} / {{ listaEjercicios.length }} ejercicios
          </span>
        </div>
        <div class="pg-barra">
          <div class="pg-fill" :style="{ width: `${progresoTotal}%` }"></div>
        </div>
      </div>

      <!-- ── GRID PRINCIPAL ────────────────────────────────────────── -->
      <div class="layout-principal">

        <!-- COLUMNA IZQUIERDA: lista de ejercicios -->
        <aside class="panel-lista">
          <p class="panel-titulo">Tu plan del día</p>
          <div class="lista-ej">
            <button
              v-for="(ej, idx) in listaEjercicios"
              :key="ej.id"
              class="item-ej"
              :class="{
                'item-activo':    idx === ejercicioActualIndex,
                'item-hecho':     idx < ejercicioActualIndex
              }"
              @click="ejercicioActualIndex = idx"
            >
              <span class="item-num">{{ String(idx + 1).padStart(2, '0') }}</span>
              <div class="item-texto">
                <span class="item-nombre">{{ ej.nombre }}</span>
                <img
                  v-if="ej.imagen_url"
                  class="item-imagen"
                  :src="ej.imagen_url"
                  :alt="`Imagen de ${ej.nombre}`"
                  loading="lazy"
                />
                <span class="item-sub">{{ ej.series_objetivo }}×{{ ej.reps_objetivo }}</span>
              </div>
              <span class="item-estado">
                {{ idx < ejercicioActualIndex ? '✓' : idx === ejercicioActualIndex ? '▶' : '·' }}
              </span>
            </button>
          </div>
        </aside>

        <!-- COLUMNA DERECHA: ejercicio activo -->
        <section class="panel-ejercicio" v-if="ejercicioActual">

          <!-- Cabecera del ejercicio -->
          <div class="ej-header">
            <div class="ej-maximo">
              <span class="eyebrow">EJERCICIO ACTUAL</span>
              <h2 class="ej-titulo">{{ ejercicioActual.nombre }}</h2>
              <img
                v-if="ejercicioActual.imagen_url"
                class="ej-imagen"
                :src="ejercicioActual.imagen_url"
                :alt="`Imagen de ${ejercicioActual.nombre}`"
              />
              <p>
                {{ ejercicioActual.descripcion }}
              </p>
            </div>
          </div>

          <!-- Desglose XP -->
          <div class="desglose-xp">
            <div class="dxp-item">
              <span class="dxp-label">XP por serie</span>
              <span class="dxp-valor">+{{ xpXSerieIndividual }}</span>
            </div>
            <div class="dxp-sep"></div>
            <div class="dxp-item">
              <span class="dxp-label">XP por repetición</span>
              <span class="dxp-valor">+{{ xpXRepIndividual }}</span>
            </div>
            <div class="dxp-sep"></div>
            <div class="dxp-item">
              <span class="dxp-label">Series × Reps obj.</span>
              <span class="dxp-valor">{{ ejercicioActual.series_objetivo }}×{{ ejercicioActual.reps_objetivo }}</span>
            </div>
          </div>

          <!-- Timer de descanso -->
          <div class="timer-bloque">
            <div class="timer-row">
              <span class="timer-label">DESCANSO</span>
              <strong class="timer-valor">{{ segundos }}s</strong>
            </div>
            <div class="timer-barra">
              <div
                class="timer-fill"
                :style="{ width: `${Math.min((segundos / 60) * 100, 100)}%` }"
              ></div>
            </div>
            <div class="timer-botones">
              <button class="timer-btn" @click="toggleTimer">
                <i :class="`bi bi-${timerActivo ? 'pause-fill' : 'play-fill'}`"></i>
                {{ timerActivo ? 'Pausar' : 'Iniciar' }}
              </button>
              <button class="timer-btn" @click="segundos += 15">+15s</button>
              <button class="timer-btn" @click="reiniciarCronometro">↺ Reset</button>
            </div>
          </div>

          <!-- TABLA DE SERIES (entrada manual) -->
          <div class="series-bloque">
            <div class="series-cabecera">
              <h3 class="series-titulo">Registrá tus series</h3>
              <div class="series-controles">
                <button class="btn-ctrl" @click="quitarSerie" :disabled="seriesEjercicio.length <= 1">
                  <i class="bi bi-dash"></i>
                </button>
                <span class="series-count">{{ seriesEjercicio.length }} series</span>
                <button class="btn-ctrl" @click="agregarSerie">
                  <i class="bi bi-plus"></i>
                </button>
              </div>
            </div>

            <!-- Cabecera de columnas -->
            <div class="serie-col-header">
              <span>Serie</span>
              <span>Repeticiones</span>
              <span>XP estimado</span>
              <span>✓</span>
            </div>

            <!-- Filas de cada serie -->
            <div
              v-for="serie in seriesEjercicio"
              :key="serie.numero"
              class="serie-fila"
              :class="{ 'serie-ok': serie.completada }"
            >
              <span class="serie-num">{{ serie.numero }}</span>

              <!-- Input de repeticiones manual -->
              <div class="reps-input-wrap">
                <button class="reps-btn" @click="serie.reps = Math.max(0, serie.reps - 1)">-</button>
                <input
                  v-model.number="serie.reps"
                  type="number"
                  class="reps-input"
                  min="0"
                  max="99"
                />
                <button class="reps-btn" @click="serie.reps++">+</button>
              </div>

              <!-- XP estimado para esta serie -->
              <span class="serie-xp">
                {{ Math.floor(xpXSerieIndividual + xpXRepIndividual * serie.reps) }} XP
              </span>

              <!-- Marcar completada -->
              <button
                class="serie-check"
                :class="{ 'check-ok': serie.completada }"
                @click="marcarSerie(serie)"
              >
                <i :class="`bi bi-${serie.completada ? 'check-lg' : 'circle'}`"></i>
              </button>
            </div>

            <!-- Barra de progreso de series -->
            <div class="series-prog">
              <div class="sp-barra">
                <div class="sp-fill" :style="{ width: `${progresoSeries}%` }"></div>
              </div>
              <span class="sp-txt">{{ seriesCompletadas }}/{{ seriesEjercicio.length }} series completadas</span>
            </div>
          </div>

          <!-- Error -->
          <p v-if="errorMsg" class="error-txt">⚠ {{ errorMsg }}</p>

          <!-- Botón principal -->
          <button
            class="btn-siguiente"
            :disabled="guardando"
            @click="siguiente"
          >
            <span v-if="guardando">
              <i class="bi bi-hourglass-split"></i> Guardando…
            </span>
            <span v-else-if="ejercicioActualIndex < listaEjercicios.length - 1">
              Guardar y siguiente ejercicio <i class="bi bi-arrow-right"></i>
            </span>
            <span v-else>
              <i class="bi bi-trophy-fill"></i> Finalizar sesión
            </span>
          </button>

        </section>
      </div>
    </template>

    <!-- ── MODAL DE FINALIZACIÓN ─────────────────────────────────────── -->
    <transition name="fade">
      <div v-if="modalVisible" class="modal-overlay" @click.self="volver">
        <div class="modal-caja">

          <div class="modal-emoji">🏆</div>
          <h2 class="modal-titulo">¡Sesión completada!</h2>
          <p class="modal-sub">Excelente trabajo. Seguí así.</p>

          <!-- Stats -->
          <div class="modal-stats">
            <div class="mstat">
              <span class="mstat-valor">+{{ Math.floor(resultado?.xp_ganado ?? 0) }}</span>
              <span class="mstat-label">XP ganado hoy</span>
            </div>
            <div class="mstat-sep"></div>
            <div class="mstat">
              <span class="mstat-valor">{{ Math.floor(resultado?.xp_total_nuevo ?? 0) }}</span>
              <span class="mstat-label">XP total acumulado</span>
            </div>
          </div>

          <p class="modal-sub">
            {{ resultado?.semana_completada ? '¡Semana completada!' : 'Progreso de la semana' }}
            Semana {{ resultado?.semana_actual }}:
            {{ resultado?.xp_semana }} / {{ resultado?.objetivo_xp_semanal }} XP
          </p>

          <!-- Nivel up -->
          <div v-if="resultado?.subio_de_nivel" class="modal-levelup">
            🎉 ¡Subiste al Nivel {{ resultado.nivel_nuevo }}!
          </div>

          <!-- Barra XP hacia el próximo nivel -->
          <div class="modal-xp-info">
            <span>Progreso Nivel {{ resultado?.nivel_nuevo ?? auth.nivel }}</span>
            <span>{{ Math.floor((resultado?.xp_total_nuevo ?? 0) % 1000) }} / 1000 XP</span>
          </div>
          <div class="modal-xp-barra">
            <div
              class="modal-xp-fill"
              :style="{
                width: `${Math.min(((resultado?.xp_total_nuevo ?? 0) % 1000) / 10, 100)}%`
              }"
            ></div>
          </div>

          <button class="btn-modal" @click="volver">
            Volver a mis sesiones
          </button>
        </div>
      </div>
    </transition>

  </main>
</div>

<div>
  <barra-inferior/>
</div>
</template>

<style scoped>
/* ─── Variables ───────────────────────────────────────────────────────── */
.workout-wrap {
  min-height: 100vh;
  background: #0c0c0e;
  color: white;
  font-family: 'Rubik', sans-serif;
  padding: 24px 20px 60px;
  box-sizing: border-box;
}

.eyebrow {
  font-size: 11px;
  font-weight: 700;
  color: #ff3b30;
  letter-spacing: .1em;
  display: block;
  margin-bottom: 4px;
}

/* ─── Topbar ──────────────────────────────────────────────────────────── */
.topbar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 28px;
  max-width: 1100px;
}
.btn-volver {
  background: none;
  border: none;
  color: #71717a;
  cursor: pointer;
  font-size: 14px;
  font-family: 'Rubik', sans-serif;
  display: flex;
  align-items: center;
  gap: 6px;
  transition: color .2s;
}
.btn-volver:hover { color: white; }

.nivel-pill {
  display: flex;
  align-items: center;
  gap: 6px;
  background: rgba(234,179,8,.1);
  border: 1px solid rgba(234,179,8,.25);
  color: #eab308;
  font-size: 12px;
  font-weight: 800;
  padding: 6px 12px;
  border-radius: 999px;
}

/* ─── Estado vacío ────────────────────────────────────────────────────── */
.estado-vacio {
  text-align: center;
  padding: 80px 20px;
  color: #71717a;
}
.estado-vacio p { margin: 16px 0 24px; }

/* ─── Progreso general ────────────────────────────────────────────────── */
.progreso-general {
  max-width: 1100px;
  margin-bottom: 24px;
}
.pg-info {
  display: flex;
  justify-content: space-between;
  align-items: flex-end;
  margin-bottom: 8px;
}
.pg-num { font-size: 14px; color: #9ca3af; }
.pg-barra {
  height: 6px;
  background: #222226;
  border-radius: 999px;
  overflow: hidden;
}
.pg-fill {
  height: 100%;
  background: linear-gradient(90deg, #ff3b30, #ff7a40);
  border-radius: 999px;
  transition: width .4s ease;
}

/* ─── Layout de dos columnas ──────────────────────────────────────────── */
.layout-principal {
  display: grid;
  grid-template-columns: 280px 1fr;
  gap: 20px;
  max-width: 1100px;
  align-items: start;
}
@media (max-width: 800px) {
  .layout-principal { grid-template-columns: 1fr; }
  .panel-lista { order: 2; }
  .panel-ejercicio { order: 1; }
}

/* ─── Panel lista (izquierda) ─────────────────────────────────────────── */
.panel-lista {
  background: #141417;
  border: 1px solid #222226;
  border-radius: 18px;
  overflow: hidden;
  position: sticky;
  top: 20px;
}
.panel-titulo {
  font-size: 11px;
  font-weight: 700;
  color: #52525b;
  letter-spacing: .08em;
  padding: 16px 20px 12px;
  border-bottom: 1px solid #222226;
  margin: 0;
}
.lista-ej { display: flex; flex-direction: column; }

.item-ej {
  display: grid;
  grid-template-columns: 36px 1fr 20px;
  align-items: center;
  gap: 12px;
  padding: 14px 16px;
  border: none;
  background: none;
  color: white;
  text-align: left;
  cursor: pointer;
  border-top: 1px solid #222226;
  font-family: 'Rubik', sans-serif;
  transition: background .15s;
}
.item-ej:first-child { border-top: none; }
.item-ej:hover { background: rgba(255,255,255,.03); }
.item-activo {
  background: rgba(255,59,48,.07) !important;
  border-left: 2px solid #ff3b30;
}
.item-hecho { opacity: .5; }

.item-num {
  width: 32px;
  height: 32px;
  border-radius: 8px;
  background: #18181b;
  font-size: 12px;
  font-weight: 700;
  color: #52525b;
  display: flex;
  align-items: center;
  justify-content: center;
}
.item-activo .item-num { color: #ff3b30; background: rgba(255,59,48,.12); }
.item-hecho  .item-num { color: #22c55e; }

.item-nombre { display: block; font-size: 13px; font-weight: 600; }
.item-sub    { display: block; font-size: 11px; color: #71717a; margin-top: 1px; }
.item-imagen {
  display: block;
  width: min(100%, 180px);
  max-height: 100px;
  object-fit: cover;
  border-radius: 8px;
  margin-top: 8px;
}
.item-estado { font-size: 14px; color: #52525b; text-align: right; }
.item-activo .item-estado { color: #ff3b30; }
.item-hecho  .item-estado { color: #22c55e; }

/* ─── Panel ejercicio (derecha) ───────────────────────────────────────── */
.panel-ejercicio {
  background: #141417;
  border: 1px solid #222226;
  border-radius: 18px;
  padding: 28px;
  display: flex;
  flex-direction: column;
  gap: 24px;
}

/* Cabecera ejercicio */
.ej-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
}


.ej-titulo {
  font-size: 26px;
  font-weight: 800;
  margin: 6px 0 0;
}
.ej-imagen {
  display: block;
  object-fit: contain;
  object-position: left center;
  border-radius: 12px;
  margin-top: 16px;
}
.xp-total-pill {
  display: flex;
  align-items: center;
  gap: 6px;
  background: rgba(234,179,8,.12);
  border: 1px solid rgba(234,179,8,.25);
  color: #eab308;
  font-size: 16px;
  font-weight: 800;
  padding: 8px 14px;
  border-radius: 999px;
  white-space: nowrap;
}

/* Desglose XP */
.desglose-xp {
  display: flex;
  align-items: center;
  background: #18181b;
  border: 1px solid #222226;
  border-radius: 14px;
  padding: 14px 20px;
  gap: 0;
}
.dxp-item { flex: 1; text-align: center; }
.dxp-label { display: block; font-size: 11px; color: #71717a; margin-bottom: 4px; }
.dxp-valor { font-size: 18px; font-weight: 800; color: #eab308; }
.dxp-sep { width: 1px; height: 32px; background: #222226; }

/* Timer */
.timer-bloque {
  background: #18181b;
  border: 1px solid #222226;
  border-radius: 14px;
  padding: 18px 20px;
}
.timer-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 10px;
}
.timer-label { font-size: 11px; font-weight: 700; color: #52525b; letter-spacing: .08em; }
.timer-valor { font-size: 28px; font-weight: 900; }
.timer-barra {
  height: 6px;
  background: #222226;
  border-radius: 999px;
  overflow: hidden;
  margin-bottom: 14px;
}
.timer-fill {
  height: 100%;
  background: linear-gradient(90deg, #ff3b30, #ff7a40);
  border-radius: 999px;
  transition: width 1s linear;
}
.timer-botones { display: flex; gap: 10px; }
.timer-btn {
  flex: 1;
  background: #222226;
  border: 1px solid #2a2a2e;
  color: white;
  border-radius: 10px;
  padding: 9px 0;
  font-size: 13px;
  font-weight: 600;
  font-family: 'Rubik', sans-serif;
  cursor: pointer;
  transition: background .15s;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 6px;
}
.timer-btn:hover { background: #2c2c30; }

/* Series */
.series-bloque {
  background: #18181b;
  border: 1px solid #222226;
  border-radius: 14px;
  padding: 18px 20px;
}
.series-cabecera {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 16px;
}
.series-titulo {
  font-size: 15px;
  font-weight: 700;
  margin: 0;
}
.series-controles {
  display: flex;
  align-items: center;
  gap: 8px;
}
.btn-ctrl {
  width: 30px;
  height: 30px;
  border-radius: 8px;
  border: 1px solid #222226;
  background: #222226;
  color: white;
  cursor: pointer;
  font-size: 16px;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: background .15s;
}
.btn-ctrl:hover:not(:disabled) { background: #2c2c30; }
.btn-ctrl:disabled { opacity: .3; cursor: not-allowed; }
.series-count { font-size: 13px; color: #9ca3af; min-width: 60px; text-align: center; }

/* Cabecera de columnas */
.serie-col-header {
  display: grid;
  grid-template-columns: 36px 1fr 1fr 44px;
  align-items: center;
  gap: 12px;
  font-size: 11px;
  color: #52525b;
  font-weight: 700;
  letter-spacing: .06em;
  padding: 0 4px;
  margin-bottom: 10px;
}

/* Fila de serie */
.serie-fila {
  display: grid;
  grid-template-columns: 36px 1fr 1fr 44px;
  align-items: center;
  gap: 12px;
  background: #141417;
  border: 1px solid #222226;
  border-radius: 12px;
  padding: 12px 14px;
  margin-bottom: 8px;
  transition: border-color .2s;
}
.serie-ok { border-color: rgba(34,197,94,.35); }

.serie-num {
  font-size: 13px;
  font-weight: 700;
  color: #52525b;
  text-align: center;
}
.serie-ok .serie-num { color: #22c55e; }

/* Input de reps con +/- */
.reps-input-wrap {
  display: flex;
  align-items: center;
  gap: 6px;
}
.reps-btn {
  width: 28px;
  height: 28px;
  border-radius: 7px;
  border: 1px solid #222226;
  background: #222226;
  color: white;
  font-size: 16px;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: background .15s;
  flex-shrink: 0;
}
.reps-btn:hover { background: #2c2c30; }
.reps-input {
  width: 44px;
  height: 36px;
  background: #0c0c0e;
  border: 1px solid #2a2a2e;
  border-radius: 8px;
  color: white;
  text-align: center;
  font-size: 16px;
  font-weight: 700;
  font-family: 'Rubik', sans-serif;
}
.reps-input:focus {
  outline: none;
  border-color: #ff3b30;
}
.reps-input::-webkit-inner-spin-button,
.reps-input::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }

.serie-xp {
  font-size: 13px;
  font-weight: 700;
  color: #eab308;
  text-align: center;
}

.serie-check {
  width: 40px;
  height: 40px;
  border-radius: 10px;
  border: none;
  background: #222226;
  color: white;
  font-size: 18px;
  cursor: pointer;
  transition: background .2s;
  display: flex;
  align-items: center;
  justify-content: center;
}
.check-ok { background: #22c55e; }

/* Barra de progreso series */
.series-prog {
  margin-top: 12px;
  display: flex;
  align-items: center;
  gap: 12px;
}
.sp-barra {
  flex: 1;
  height: 4px;
  background: #222226;
  border-radius: 999px;
  overflow: hidden;
}
.sp-fill {
  height: 100%;
  background: #22c55e;
  border-radius: 999px;
  transition: width .3s ease;
}
.sp-txt { font-size: 12px; color: #9ca3af; white-space: nowrap; }

/* Error */
.error-txt { color: #f87171; font-size: 13px; text-align: center; margin: 0; }

/* Botón principal */
.btn-siguiente {
  width: 100%;
  height: 56px;
  border: none;
  border-radius: 14px;
  background: linear-gradient(135deg, #ff3b30, #ff7a40);
  color: white;
  font-weight: 800;
  font-size: 15px;
  font-family: 'Rubik', sans-serif;
  cursor: pointer;
  transition: opacity .2s, transform .1s;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  box-shadow: 0 0 24px rgba(255,59,48,.25);
}
.btn-siguiente:hover  { opacity: .9; }
.btn-siguiente:active { transform: scale(.98); }
.btn-siguiente:disabled { opacity: .4; cursor: not-allowed; }

/* Botón acción genérico */
.btn-accion {
  background: #ff3b30;
  color: white;
  border: none;
  border-radius: 12px;
  padding: 12px 24px;
  font-weight: 700;
  cursor: pointer;
  font-family: 'Rubik', sans-serif;
}

/* ─── MODAL ───────────────────────────────────────────────────────────── */
.modal-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,.85);
  backdrop-filter: blur(6px);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
}
.modal-caja {
  background: #141417;
  border: 1px solid #222226;
  border-radius: 24px;
  padding: 40px 32px;
  text-align: center;
  width: min(460px, 92vw);
  box-shadow: 0 0 60px rgba(255,59,48,.15);
}
.modal-emoji { font-size: 52px; margin-bottom: 14px; }
.modal-titulo { font-size: 26px; font-weight: 900; margin: 0 0 8px; }
.modal-sub { color: #71717a; margin: 0 0 28px; }

.modal-stats {
  display: flex;
  align-items: center;
  background: #18181b;
  border: 1px solid #222226;
  border-radius: 16px;
  padding: 20px;
  margin-bottom: 20px;
}
.mstat { flex: 1; }
.mstat-valor {
  display: block;
  font-size: 26px;
  font-weight: 900;
  color: #eab308;
}
.mstat-label { display: block; font-size: 12px; color: #71717a; margin-top: 4px; }
.mstat-sep { width: 1px; height: 40px; background: #222226; }

.modal-levelup {
  background: rgba(34,197,94,.1);
  border: 1px solid rgba(34,197,94,.3);
  border-radius: 12px;
  color: #22c55e;
  font-weight: 700;
  padding: 12px;
  margin-bottom: 20px;
}

.modal-xp-info {
  display: flex;
  justify-content: space-between;
  font-size: 12px;
  color: #71717a;
  margin-bottom: 8px;
}
.modal-xp-barra {
  height: 8px;
  background: #222226;
  border-radius: 999px;
  overflow: hidden;
  margin-bottom: 24px;
}
.modal-xp-fill {
  height: 100%;
  background: linear-gradient(90deg, #eab308, #ff3b30);
  border-radius: 999px;
  transition: width .6s ease;
}

.btn-modal {
  width: 100%;
  height: 52px;
  border: none;
  border-radius: 14px;
  background: linear-gradient(135deg, #ff3b30, #ff7a40);
  color: white;
  font-weight: 800;
  font-size: 15px;
  font-family: 'Rubik', sans-serif;
  cursor: pointer;
  transition: opacity .2s;
}
.btn-modal:hover { opacity: .9; }

/* ─── Transición fade ─────────────────────────────────────────────────── */
.fade-enter-active, .fade-leave-active { transition: opacity .25s; }
.fade-enter-from, .fade-leave-to { opacity: 0; }


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

    .ej-imagen {
        display: block;
        width: min(100%, 440px);
        max-height: 300px;
        object-fit: contain;
        object-position: left center;
        border-radius: 12px;
        margin-top: 16px;
      }
}
</style>
