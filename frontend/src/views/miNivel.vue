<script setup lang="ts">
import { ref, computed } from 'vue';
import BarraUsuario from '@/components/BarraUsuario.vue';
import Header2 from '@/components/Header2.vue';

// ============================================================================
// DATOS DEL USUARIO (Listos para conectar luego con tu base de datos o Pinia)
// Todos los campos están inicializados vacíos / por defecto para que los conectes
// ============================================================================
const datosUsuario = ref({
  nombre: 'Lucas', // Puedes dejarlo vacío: ''
  nivel: 1,
  rango: 'Novato',
  xpActual: 0,
  xpSiguienteNivel: 500,
  totalXp: 0,
  entrenamientosCompletados: 0,
  rachaDias: 0,
  horasEntrenadas: 0
});

// Porcentaje de progreso de la barra de nivel (de 0 a 100%)
const porcentajeProgreso = computed(() => {
  if (datosUsuario.value.xpSiguienteNivel === 0) return 0;
  const progreso = (datosUsuario.value.xpActual / datosUsuario.value.xpSiguienteNivel) * 100;
  return Math.min(Math.max(progreso, 0), 100);
});

// Catálogo de todos los 10 niveles de FitCore
const listaNiveles = ref([
  { nivel: 1, titulo: 'Iniciación Calisténica', xp: '0 XP', recompensa: 'Rutina Calistenia Básica', desbloqueado: true },
  { nivel: 2, titulo: 'Potencia y Core', xp: '500 XP', recompensa: 'Rutina de Core y Fondos', desbloqueado: false },
  { nivel: 3, titulo: 'Fuerza Intermedia', xp: '1,200 XP', recompensa: 'Fondos en Paralelas', desbloqueado: false },
  { nivel: 4, titulo: 'Dominadas Estrictas', xp: '2,200 XP', recompensa: 'L-Sit y Dominadas', desbloqueado: false },
  { nivel: 5, titulo: 'Muscle-Up Mastery', xp: '3,500 XP', recompensa: 'Transición a Muscle-Up', desbloqueado: false },
  { nivel: 6, titulo: 'Fuerza Explosiva', xp: '5,000 XP', recompensa: 'Planchas y Palanca', desbloqueado: false },
  { nivel: 7, titulo: 'Front Lever Básico', xp: '7,000 XP', recompensa: 'Isométricos Avanzados', desbloqueado: false },
  { nivel: 8, titulo: 'Control Total', xp: '9,500 XP', recompensa: 'Dominadas a una mano', desbloqueado: false },
  { nivel: 9, titulo: 'Atleta Élite', xp: '12,500 XP', recompensa: 'Rutinas de Alta Intensidad', desbloqueado: false },
  { nivel: 10, titulo: 'Maestro FitCore', xp: '16,000 XP', recompensa: 'Acceso Total al Olimpo', desbloqueado: false }
]);
</script>

<template>
  <div class="pantalla-nivel">
    <!-- Barra lateral fija del usuario -->
    <BarraUsuario class="columna-usuario" />

    <!-- Zona de contenido con cabecera y scroll independiente -->
    <div class="zona-contenido">
      <Header2 :nombre="datosUsuario.nombre || 'Atleta'" />

      <main class="cuerpo-scroll">
        <!-- Tarjeta gigante con el nivel y la barra de experiencia -->
        <section class="tarjeta-nivel-grande">
          <div class="fila-arriba-nivel">
            <div>
              <span class="etiqueta-roja">
                <i class="bi bi-trophy-fill"></i> PROGRESO DE NIVEL
              </span>
              <h1 class="titulo-nivel">Nivel {{ datosUsuario.nivel }}</h1>
              <p class="subtitulo-rango">Rango actual: <strong class="texto-rojo">{{ datosUsuario.rango }}</strong></p>
            </div>

            <!-- Gran medalla circular -->
            <div class="medalla-circular">
              <span class="numero-medalla">{{ datosUsuario.nivel }}</span>
              <small class="texto-medalla">NIVEL</small>
            </div>
          </div>

          <!-- Barra de experiencia XP -->
          <div class="caja-barra-xp">
            <div class="fila-info-xp">
              <span>Experiencia: <strong>{{ datosUsuario.xpActual }} / {{ datosUsuario.xpSiguienteNivel }} XP</strong></span>
              <span>{{ Math.round(porcentajeProgreso) }}%</span>
            </div>
            <div class="barra-xp-fondo">
              <div class="barra-xp-relleno" :style="{ width: porcentajeProgreso + '%' }"></div>
            </div>
            <small class="texto-faltante">
              Te faltan {{ Math.max(datosUsuario.xpSiguienteNivel - datosUsuario.xpActual, 0) }} XP para desbloquear el Nivel {{ datosUsuario.nivel + 1 }}
            </small>
          </div>
        </section>

        <!-- Cuadros con estadísticas del usuario -->
        <section class="caja-estadisticas">
          <div class="tarjeta-dato">
            <span class="icono-dato"><i class="bi bi-fire"></i></span>
            <div>
              <strong class="numero-dato">{{ datosUsuario.rachaDias }}</strong>
              <p class="etiqueta-dato">Días de racha</p>
            </div>
          </div>

          <div class="tarjeta-dato">
            <span class="icono-dato"><i class="bi bi-check2-circle"></i></span>
            <div>
              <strong class="numero-dato">{{ datosUsuario.entrenamientosCompletados }}</strong>
              <p class="etiqueta-dato">Entrenamientos hechos</p>
            </div>
          </div>

          <div class="tarjeta-dato">
            <span class="icono-dato"><i class="bi bi-star-fill"></i></span>
            <div>
              <strong class="numero-dato">{{ datosUsuario.totalXp }}</strong>
              <p class="etiqueta-dato">Puntos XP acumulados</p>
            </div>
          </div>

          <div class="tarjeta-dato">
            <span class="icono-dato"><i class="bi bi-clock-history"></i></span>
            <div>
              <strong class="numero-dato">{{ datosUsuario.horasEntrenadas }}h</strong>
              <p class="etiqueta-dato">Horas entrenadas</p>
            </div>
          </div>
        </section>

        <!-- Camino de los 10 Niveles -->
        <section class="seccion-camino">
          <div class="cabecera-seccion">
            <h2>Camino de Conquista</h2>
            <p>Supera rutinas para ganar experiencia y desbloquear cada escalón.</p>
          </div>

          <div class="cuadricula-niveles">
            <div
              v-for="item in listaNiveles"
              :key="item.nivel"
              :class="['tarjeta-nivel', { 'desbloqueado': item.nivel <= datosUsuario.nivel, 'bloqueado': item.nivel > datosUsuario.nivel }]"
            >
              <div class="fila-nivel-header">
                <span class="pildora-numero">NIVEL {{ item.nivel }}</span>
                <span v-if="item.nivel <= datosUsuario.nivel" class="candado-abierto">
                  <i class="bi bi-unlock-fill"></i> Desbloqueado
                </span>
                <span v-else class="candado-cerrado">
                  <i class="bi bi-lock-fill"></i> Bloqueado
                </span>
              </div>

              <h3 class="nombre-nivel">{{ item.titulo }}</h3>
              <p class="xp-requerido"><i class="bi bi-gem"></i> Requiere: {{ item.xp }}</p>

              <div class="pie-nivel">
                <small class="recompensa-texto">Desbloquea: <strong>{{ item.recompensa }}</strong></small>
              </div>
            </div>
          </div>
        </section>
      </main>
    </div>
  </div>
</template>

<style scoped>
.pantalla-nivel {
  display: flex;
  width: 100vw;
  height: 100vh;
  overflow: hidden;
  background-color: #0c0c0e;
  font-family: 'Rubik', sans-serif;
  color: #ffffff;
  box-sizing: border-box;
}

/* Barra del usuario lateral */
.columna-usuario {
  flex-shrink: 0;
  width: 280px;
  height: 100vh;
  position: sticky;
  top: 0;
  z-index: 20;
}

/* Zona derecha */
.zona-contenido {
  flex: 1;
  display: flex;
  flex-direction: column;
  height: 100vh;
  min-width: 0;
  background-color: #0c0c0e;
}

.cuerpo-scroll {
  flex: 1;
  overflow-y: auto;
  overflow-x: hidden;
  padding: 30px 40px 60px 40px;
  box-sizing: border-box;
}

/* Tarjeta gigante de nivel */
.tarjeta-nivel-grande {
  background: #18181b;
  border: 1px solid #27272a;
  border-radius: 16px;
  padding: 32px;
  margin-bottom: 28px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
}

.fila-arriba-nivel {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 20px;
  margin-bottom: 24px;
}

.etiqueta-roja {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  background: rgba(255, 59, 48, 0.15);
  color: #ff3b30;
  font-size: 0.75rem;
  font-weight: 800;
  letter-spacing: 0.8px;
  padding: 4px 10px;
  border-radius: 6px;
  margin-bottom: 8px;
}

.titulo-nivel {
  font-size: 2.2rem;
  font-weight: 900;
  margin: 0;
  letter-spacing: -0.5px;
}

.subtitulo-rango {
  color: #a1a1aa;
  font-size: 0.95rem;
  margin: 4px 0 0 0;
}

.texto-rojo {
  color: #ff3b30;
}

.medalla-circular {
  width: 80px;
  height: 80px;
  border-radius: 50%;
  background: #121214;
  border: 2px solid #ff3b30;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  box-shadow: 0 0 16px rgba(255, 59, 48, 0.35);
  flex-shrink: 0;
}

.numero-medalla {
  font-size: 2rem;
  font-weight: 900;
  line-height: 1;
  color: #ffffff;
}

.texto-medalla {
  font-size: 0.65rem;
  font-weight: 800;
  letter-spacing: 0.5px;
  color: #ff3b30;
}

/* Barra XP */
.caja-barra-xp {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.fila-info-xp {
  display: flex;
  justify-content: space-between;
  font-size: 0.85rem;
  color: #d4d4d8;
}

.barra-xp-fondo {
  width: 100%;
  height: 12px;
  background-color: #121214;
  border: 1px solid #27272a;
  border-radius: 20px;
  overflow: hidden;
}

.barra-xp-relleno {
  height: 100%;
  background: linear-gradient(90deg, #ff3b30, #ff6b60);
  border-radius: 20px;
  transition: width 0.4s ease;
  box-shadow: 0 0 10px rgba(255, 59, 48, 0.5);
}

.texto-faltante {
  color: #71717a;
  font-size: 0.8rem;
}

/* Cuadros de Estadísticas */
.caja-estadisticas {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 16px;
  margin-bottom: 40px;
}

.tarjeta-dato {
  background: #18181b;
  border: 1px solid #27272a;
  border-radius: 14px;
  padding: 18px;
  display: flex;
  align-items: center;
  gap: 16px;
}

.icono-dato {
  width: 44px;
  height: 44px;
  border-radius: 10px;
  background: rgba(255, 59, 48, 0.12);
  color: #ff3b30;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.3rem;
  flex-shrink: 0;
}

.numero-dato {
  font-size: 1.4rem;
  font-weight: 800;
  color: #ffffff;
  display: block;
  line-height: 1.1;
}

.etiqueta-dato {
  font-size: 0.8rem;
  color: #a1a1aa;
  margin: 4px 0 0 0;
}

/* Sección de los 10 niveles */
.seccion-camino {
  margin-top: 20px;
}

.cabecera-seccion h2 {
  font-size: 1.4rem;
  font-weight: 800;
  margin: 0 0 4px 0;
}

.cabecera-seccion p {
  color: #a1a1aa;
  font-size: 0.88rem;
  margin: 0 0 20px 0;
}

.cuadricula-niveles {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
  gap: 18px;
}

.tarjeta-nivel {
  background: #18181b;
  border: 1px solid #27272a;
  border-radius: 14px;
  padding: 20px;
  display: flex;
  flex-direction: column;
  gap: 12px;
  transition: all 0.2s ease;
}

.tarjeta-nivel:hover {
  border-color: #3f3f46;
}

.tarjeta-nivel.bloqueado {
  opacity: 0.6;
  background: #121214;
}

.tarjeta-nivel.desbloqueado {
  border-color: #ff3b30;
  box-shadow: 0 0 14px rgba(255, 59, 48, 0.15);
}

.fila-nivel-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.pildora-numero {
  font-size: 0.72rem;
  font-weight: 800;
  letter-spacing: 0.5px;
  color: #a1a1aa;
}

.candado-abierto {
  color: #34c759;
  font-size: 0.75rem;
  font-weight: 700;
  display: flex;
  align-items: center;
  gap: 4px;
}

.candado-cerrado {
  color: #ff3b30;
  font-size: 0.75rem;
  font-weight: 700;
  display: flex;
  align-items: center;
  gap: 4px;
}

.nombre-nivel {
  font-size: 1.1rem;
  font-weight: 700;
  margin: 0;
}

.xp-requerido {
  color: #a1a1aa;
  font-size: 0.82rem;
  margin: 0;
  display: flex;
  align-items: center;
  gap: 6px;
}

.pie-nivel {
  padding-top: 10px;
  border-top: 1px solid #27272a;
}

.recompensa-texto {
  color: #71717a;
  font-size: 0.75rem;
}

.recompensa-texto strong {
  color: #d4d4d8;
}

/* Responsividad */
@media (max-width: 900px) {
  .columna-usuario {
    display: none;
  }
  .cuerpo-scroll {
    padding: 20px;
  }
}
</style>