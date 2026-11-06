<script setup lang="ts">
import { computed, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useRutina } from '@/stores/rutina.store';
import { useUsuarioVerificacion } from '@/stores/auth.store';

const router = useRouter();
const rutina = useRutina();
const auth   = useUsuarioVerificacion();

onMounted(async () => {
  try {
    await rutina.cargarCatalogo();
  } catch {}
});

// ─── DATOS BÁSICOS ────────────────────────────────────────────────────────
const mapaDias: Record<number, string> = {
  1: 'Lunes', 2: 'Martes', 3: 'Miércoles',
  4: 'Jueves', 5: 'Viernes', 6: 'Sábado', 7: 'Domingo'
};

const nivelUsuario    = computed(() => auth.nivel || 1);
const ejercicios      = computed(() => rutina.rutinaCreada?.ejercicios ?? []);
const diasSemana      = computed(() => rutina.rutinaCreada?.dias_semana ?? rutina.diasSeleccionados ?? []);
const categoriaRutina = computed(() => rutina.rutinaCreada?.categoria ?? 'Full Body');
const tieneRutina     = computed(() => rutina.tieneRutina && diasSemana.value.length > 0);

// ─── FÓRMULA XP ───────────────────────────────────────────────────────────
// 250 XP/semana ÷ días ÷ ejercicios_por_día = XP por ejercicio
// Se muestra solo la parte entera; el backend mantiene precisión.
const xpPorEjercicio = computed(() => {
  const dias = diasSemana.value.length || 1;
  const ejs  = ejercicios.value.length || 4;
  return Math.floor(250 / dias / ejs);
});

// XP que vale cada sesión diaria completa
const xpPorSesion = computed(() => xpPorEjercicio.value * ejercicios.value.length);

// ─── PROGRESO SEMANAL ─────────────────────────────────────────────────────
// Usamos el XP total módulo 250 como aproximación del progreso semanal
const xpSemanaGanado = computed(() => Math.min(auth.xpActual % 250, 250));
const progresoSemana = computed(() => Math.round((xpSemanaGanado.value / 250) * 100));

// ─── TARJETAS DE SESIÓN ───────────────────────────────────────────────────
const diasCompletados = computed(() => rutina.rutinaCreada?.dias_completados ?? []);

const sesiones = computed(() =>
  diasSemana.value.map((diaNum, i) => {
    const hecho = diasCompletados.value.includes(diaNum) || diasCompletados.value.includes(i + 1);
    return {
      id:         diaNum,
      numero:     i + 1,
      diaNombre:  mapaDias[diaNum] ?? `Día ${diaNum}`,
      completado: hecho,
      bloqueado:  false,
    };
  })
);

// ─── NAVEGACIÓN ───────────────────────────────────────────────────────────
function irASesion(sesion: { id: number; numero: number; bloqueado: boolean }) {
  if (sesion.bloqueado) return;
  router.push({
    path: '/entrenamientoEjercicio',
    query: { dia: sesion.id, numeroDia: sesion.numero }
  });
}
</script>

<template>
  <main class="pag-entrenamiento">

    <!-- ── ENCABEZADO ───────────────────────────────────────────────── -->
    <header class="enc-header">
      <div>
        <span class="eyebrow">TU PLAN SEMANAL</span>
        <h1 class="titulo-pag">Entrenamientos<span class="punto">.</span></h1>
        <p class="subtitulo">Completá tus sesiones · {{ categoriaRutina }}</p>
      </div>

      <div class="badge-nivel">
        <span class="badge-dot"></span>
        NIVEL {{ nivelUsuario }}
      </div>
    </header>

    <!-- ── PROGRESO XP SEMANAL ──────────────────────────────────────── -->
    <section class="bloque-progreso">
      <div class="progreso-info">
        <div>
          <span class="eyebrow">PROGRESO SEMANAL</span>
          <p class="progreso-texto">
            <strong>{{ xpSemanaGanado }}</strong> / 250 XP
          </p>
        </div>
        <span class="progreso-pct">{{ progresoSemana }}%</span>
      </div>
      <div class="barra-semanal">
        <div class="barra-relleno" :style="{ width: `${progresoSemana}%` }"></div>
      </div>
      <div class="progreso-semanas">
        <span
          v-for="s in 4"
          :key="s"
          class="dot-semana"
          :class="{ activa: s === 1 }"
        >
          S{{ s }}
        </span>
      </div>
    </section>

    <!-- ── SIN RUTINA ───────────────────────────────────────────────── -->
    <div v-if="!tieneRutina" class="aviso-vacio">
      <i class="bi bi-lock-fill icono-vacio"></i>
      <h3>Sin rutina configurada</h3>
      <p>Configurá tu plan desde la sección de Rutinas.</p>
      <button class="btn-ir-rutinas" @click="router.push('/rutinas')">
        Ir a Rutinas →
      </button>
    </div>

    <template v-else>

      <!-- ── GRILLA DE SESIONES ────────────────────────────────────── -->
      <section class="seccion-sesiones">

        <div class="ses-cabecera">
          <div>
            <span class="eyebrow">TUS DÍAS DE ENTRENAMIENTO</span>
            <h2>Sesiones de la semana</h2>
          </div>
          <span class="ses-contador">
            {{ sesiones.filter(s => s.completado).length }}/{{ sesiones.length }}
            <small>completados</small>
          </span>
        </div>

        <div class="grilla-sesiones">
          <article
            v-for="ses in sesiones"
            :key="ses.id"
            class="tarjeta-dia"
            :class="{
              'tarjeta-completada': ses.completado,
              'tarjeta-bloqueada':  ses.bloqueado
            }"
          >
            <!-- Número de fondo decorativo -->
            <span class="num-fondo">{{ ses.numero }}</span>

            <!-- Estado pill -->
            <div
              class="pill-estado"
              :class="{
                'pill-ok':  ses.completado,
                'pill-lock': ses.bloqueado
              }"
            >
              <i
                class="bi"
                :class="{
                  'bi-check-lg':    ses.completado,
                  'bi-lock-fill':   ses.bloqueado,
                  'bi-circle':      !ses.completado && !ses.bloqueado
                }"
              ></i>
              {{
                ses.completado  ? 'Completado' :
                ses.bloqueado   ? 'Bloqueado'  : 'Pendiente'
              }}
            </div>

            <!-- Info -->
            <div class="tarjeta-info">
              <p class="dia-label">DÍA {{ ses.numero }}</p>
              <h3 class="dia-nombre">{{ ses.diaNombre }}</h3>
              <p class="dia-detalle">
                {{ ejercicios.length }} ejercicio{{ ejercicios.length !== 1 ? 's' : '' }}
              </p>
            </div>

            <!-- Pie: XP + botón -->
            <div class="tarjeta-pie">
              <span class="xp-badge">
                <i class="bi bi-lightning-charge-fill"></i>
                {{ xpPorSesion }} XP
              </span>

              <button
                class="btn-iniciar"
                :disabled="ses.bloqueado"
                :aria-label="`Iniciar sesión del día ${ses.numero}`"
                @click="irASesion(ses)"
              >
                <i v-if="ses.completado" class="bi bi-check-lg"></i>
                <i v-else class="bi bi-play-fill"></i>
              </button>
            </div>
          </article>
        </div>
      </section>

      <!-- ── EJERCICIOS DEL DÍA (preview) ─────────────────────────── -->
      <section class="seccion-ejercicios" v-if="ejercicios.length > 0">
        <div class="ej-cabecera">
          <span class="eyebrow">EJERCICIOS DE CADA SESIÓN</span>
          <h2>Tu rutina</h2>
        </div>

        <div class="lista-ejercicios">
          <div
            v-for="(ej, i) in ejercicios"
            :key="ej.id"
            class="fila-ejercicio"
          >
            <span class="ej-num">{{ String(i + 1).padStart(2, '0') }}</span>
            <div class="ej-info">
              <span class="ej-nombre">{{ ej.nombre }}</span>
              <span class="ej-detalle">
                {{ ej.series_objetivo }} series × {{ ej.reps_objetivo }} reps
              </span>
            </div>
            <span class="ej-xp">
              <i class="bi bi-lightning-charge-fill"></i>
              {{ xpPorEjercicio }} XP
            </span>
          </div>
        </div>

        <!-- Resumen XP -->
        <div class="resumen-xp">
          <div class="resumen-item">
            <span class="res-valor">{{ xpPorSesion }}</span>
            <span class="res-label">XP por sesión</span>
          </div>
          <div class="resumen-sep"></div>
          <div class="resumen-item">
            <span class="res-valor">{{ xpPorSesion * sesiones.length }}</span>
            <span class="res-label">XP si completás la semana</span>
          </div>
          <div class="resumen-sep"></div>
          <div class="resumen-item">
            <span class="res-valor">250</span>
            <span class="res-label">XP objetivo semanal</span>
          </div>
        </div>
      </section>

    </template>

  </main>
</template>

<style scoped>
/* ─── Variables ───────────────────────────────────────────────────────── */
:root {
  --rojo:   #ff3b30;
  --dorado: #eab308;
  --verde:  #22c55e;
  --gris:   #52525b;
  --fondo:  #0c0c0e;
  --card:   #141417;
  --borde:  #222226;
}

/* ─── Layout ──────────────────────────────────────────────────────────── */
.pag-entrenamiento {
  padding: 36px 32px 60px;
  max-width: 920px;
  font-family: 'Rubik', sans-serif;
  color: white;
}

/* ─── Tipografía base ─────────────────────────────────────────────────── */
.eyebrow {
  font-size: 11px;
  font-weight: 700;
  color: #ff3b30;
  letter-spacing: .1em;
  display: block;
  margin-bottom: 6px;
}
.punto { color: #ff3b30; }

/* ─── Encabezado ──────────────────────────────────────────────────────── */
.enc-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 36px;
}
.titulo-pag {
  font-size: clamp(28px, 5vw, 44px);
  font-weight: 900;
  margin: 0 0 6px;
  line-height: 1.1;
}
.subtitulo {
  color: #71717a;
  font-size: 14px;
  margin: 0;
}
.badge-nivel {
  display: flex;
  align-items: center;
  gap: 8px;
  background: rgba(234, 179, 8, .12);
  border: 1px solid rgba(234, 179, 8, .3);
  color: #eab308;
  font-size: 12px;
  font-weight: 800;
  padding: 8px 14px;
  border-radius: 999px;
  white-space: nowrap;
}
.badge-dot {
  width: 7px;
  height: 7px;
  border-radius: 50%;
  background: #eab308;
}

/* ─── Progreso semanal ────────────────────────────────────────────────── */
.bloque-progreso {
  background: #141417;
  border: 1px solid #222226;
  border-radius: 18px;
  padding: 24px;
  margin-bottom: 36px;
}
.progreso-info {
  display: flex;
  justify-content: space-between;
  align-items: flex-end;
  margin-bottom: 12px;
}
.progreso-texto {
  margin: 4px 0 0;
  font-size: 20px;
  color: #9ca3af;
}
.progreso-texto strong {
  color: white;
  font-size: 28px;
  font-weight: 800;
}
.progreso-pct {
  font-size: 24px;
  font-weight: 800;
  color: #ff3b30;
}
.barra-semanal {
  height: 8px;
  background: #222226;
  border-radius: 999px;
  overflow: hidden;
}
.barra-relleno {
  height: 100%;
  background: linear-gradient(90deg, #ff3b30, #ff7a40);
  border-radius: 999px;
  transition: width .5s ease;
}
.progreso-semanas {
  display: flex;
  gap: 8px;
  margin-top: 14px;
}
.dot-semana {
  font-size: 11px;
  font-weight: 700;
  color: #52525b;
  background: #18181b;
  padding: 4px 10px;
  border-radius: 999px;
  border: 1px solid #222226;
}
.dot-semana.activa {
  color: #ff3b30;
  border-color: rgba(255, 59, 48, .4);
  background: rgba(255, 59, 48, .08);
}

/* ─── Sin rutina ──────────────────────────────────────────────────────── */
.aviso-vacio {
  text-align: center;
  padding: 60px 20px;
  background: #141417;
  border: 1px solid #222226;
  border-radius: 18px;
}
.icono-vacio {
  font-size: 40px;
  color: #52525b;
  display: block;
  margin-bottom: 16px;
}
.aviso-vacio h3 { font-size: 20px; font-weight: 700; margin: 0 0 8px; }
.aviso-vacio p  { color: #71717a; margin: 0 0 20px; }
.btn-ir-rutinas {
  background: #ff3b30;
  color: white;
  border: none;
  border-radius: 12px;
  padding: 12px 24px;
  font-weight: 700;
  cursor: pointer;
  font-family: 'Rubik', sans-serif;
}

/* ─── Cabeceras de sección ────────────────────────────────────────────── */
.seccion-sesiones,
.seccion-ejercicios {
  margin-bottom: 36px;
}
.ses-cabecera,
.ej-cabecera {
  display: flex;
  justify-content: space-between;
  align-items: flex-end;
  margin-bottom: 20px;
}
.ses-cabecera h2,
.ej-cabecera h2 {
  font-size: 22px;
  font-weight: 800;
  margin: 0;
}
.ses-contador {
  font-size: 14px;
  color: #71717a;
}
.ses-contador small {
  display: block;
  font-size: 11px;
}

/* ─── Tarjetas de sesión ──────────────────────────────────────────────── */
.grilla-sesiones {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
  gap: 16px;
}

.tarjeta-dia {
  position: relative;
  background: #141417;
  border: 1px solid #222226;
  border-radius: 18px;
  padding: 22px 20px 18px;
  display: flex;
  flex-direction: column;
  gap: 14px;
  overflow: hidden;
  transition: border-color .2s, box-shadow .2s;
}
.tarjeta-dia:hover {
  border-color: rgba(255, 59, 48, .35);
  box-shadow: 0 0 20px rgba(255, 59, 48, .1);
}
.tarjeta-completada {
  border-color: rgba(34, 197, 94, .3) !important;
  box-shadow: 0 0 16px rgba(34, 197, 94, .08);
}
.tarjeta-bloqueada {
  opacity: .5;
  pointer-events: none;
}

/* Número de fondo grande decorativo */
.num-fondo {
  position: absolute;
  top: -10px;
  right: 10px;
  font-size: 90px;
  font-weight: 900;
  color: rgba(255, 255, 255, .03);
  line-height: 1;
  pointer-events: none;
  user-select: none;
}

/* Pill de estado */
.pill-estado {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  font-size: 11px;
  font-weight: 700;
  letter-spacing: .06em;
  color: #ff3b30;
  background: rgba(255, 59, 48, .1);
  border-radius: 999px;
  padding: 5px 10px;
  width: fit-content;
}
.pill-ok   { color: #22c55e; background: rgba(34, 197, 94, .1); }
.pill-lock { color: #52525b; background: rgba(82, 82, 91, .15); }

/* Info del día */
.tarjeta-info { flex: 1; }
.dia-label {
  font-size: 11px;
  font-weight: 700;
  color: #52525b;
  margin: 0 0 4px;
  letter-spacing: .08em;
}
.dia-nombre {
  font-size: 20px;
  font-weight: 800;
  margin: 0 0 4px;
}
.dia-detalle {
  font-size: 13px;
  color: #71717a;
  margin: 0;
}

/* Pie con XP y botón */
.tarjeta-pie {
  display: flex;
  justify-content: space-between;
  align-items: center;
}
.xp-badge {
  display: flex;
  align-items: center;
  gap: 5px;
  color: #eab308;
  background: rgba(234, 179, 8, .12);
  border: 1px solid rgba(234, 179, 8, .2);
  font-size: 13px;
  font-weight: 700;
  padding: 5px 10px;
  border-radius: 999px;
}
.btn-iniciar {
  width: 42px;
  height: 42px;
  border-radius: 12px;
  background: #ff3b30;
  border: none;
  color: white;
  font-size: 18px;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: background .2s, transform .1s;
  box-shadow: 0 0 16px rgba(255, 59, 48, .3);
}
.btn-iniciar:hover  { background: #e03229; }
.btn-iniciar:active { transform: scale(.93); }
.btn-iniciar:disabled {
  background: #222226;
  box-shadow: none;
  cursor: not-allowed;
  color: #52525b;
}
.tarjeta-completada .btn-iniciar {
  background: #22c55e;
  box-shadow: 0 0 16px rgba(34, 197, 94, .3);
}

/* ─── Lista de ejercicios preview ─────────────────────────────────────── */
.lista-ejercicios {
  background: #141417;
  border: 1px solid #222226;
  border-radius: 18px;
  overflow: hidden;
}
.fila-ejercicio {
  display: grid;
  grid-template-columns: 44px 1fr auto;
  align-items: center;
  gap: 14px;
  padding: 16px 20px;
  border-bottom: 1px solid #222226;
  transition: background .15s;
}
.fila-ejercicio:last-child { border-bottom: none; }
.fila-ejercicio:hover { background: rgba(255,255,255,.02); }

.ej-num {
  width: 36px;
  height: 36px;
  border-radius: 10px;
  background: #18181b;
  border: 1px solid #222226;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 12px;
  font-weight: 700;
  color: #52525b;
  flex-shrink: 0;
}
.ej-nombre {
  display: block;
  font-size: 14px;
  font-weight: 600;
}
.ej-detalle {
  display: block;
  font-size: 12px;
  color: #71717a;
  margin-top: 2px;
}
.ej-xp {
  display: flex;
  align-items: center;
  gap: 4px;
  color: #eab308;
  font-size: 13px;
  font-weight: 700;
  white-space: nowrap;
}

/* ─── Resumen XP ──────────────────────────────────────────────────────── */
.resumen-xp {
  display: flex;
  align-items: center;
  gap: 0;
  background: rgba(234, 179, 8, .06);
  border: 1px solid rgba(234, 179, 8, .15);
  border-top: none;
  border-radius: 0 0 18px 18px;
  padding: 20px 24px;
}
.resumen-item { flex: 1; text-align: center; }
.res-valor {
  display: block;
  font-size: 24px;
  font-weight: 900;
  color: #eab308;
}
.res-label {
  display: block;
  font-size: 11px;
  color: #71717a;
  margin-top: 2px;
}
.resumen-sep {
  width: 1px;
  height: 36px;
  background: rgba(234, 179, 8, .2);
}

/* ─── Responsive ──────────────────────────────────────────────────────── */
@media (max-width: 600px) {
  .pag-entrenamiento  { padding: 24px 16px 48px; }
  .enc-header         { flex-direction: column; gap: 16px; }
  .grilla-sesiones    { grid-template-columns: 1fr; }
  .resumen-xp         { flex-direction: column; gap: 16px; }
  .resumen-sep        { width: 100%; height: 1px; }
}
</style>
