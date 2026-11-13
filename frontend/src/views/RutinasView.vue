<script setup lang="ts">
import { computed } from 'vue';
import { useRouter } from 'vue-router';
import BarraUsuario from '@/components/BarraUsuario.vue';
import Header2 from '@/components/Header2.vue';
import RutinaConfig from '@/components/RutinaConfig.vue';
import { useUsuarioVerificacion } from '@/stores/auth.store';
import { useEntrenamientoStore } from '@/stores/entrenamiento.store';
import BarraInferior from '@/components/Barra-inferior.vue';

const router = useRouter();
const auth = useUsuarioVerificacion();
const entrenoStore = useEntrenamientoStore();

// Nivel y XP actuales del usuario obtenidos del Store
const nivelActual = computed(() => entrenoStore.nivelUsuario);
const xpActual = computed(() => entrenoStore.xpUsuario);
const xpMeta = 100;
const xpRestante = computed(() => Math.max(xpMeta - xpActual.value, 0));
const porcentajeXp = computed(() => Math.min(Math.round((xpActual.value / xpMeta) * 100), 100));


// Los 10 niveles divididos en dos filas de 5 columnas
const nivelesFila1 = computed(() => entrenoStore.catalogoNiveles.slice(0, 5));
const nivelesFila2 = computed(() => entrenoStore.catalogoNiveles.slice(5, 10));

const irAEjercicios = (nivelId: number) => {
  if (entrenoStore.estaDesbloqueado(nivelId)) {
    router.push('/entrenamiento');
  }
};
</script>

<template>
<div>
    <div class="rutinas-contenedor">
    <RutinaConfig />

    <!-- 1. Barra Lateral del Usuario -->
    <BarraUsuario class="rutinas-barra-lateral" />

    <!-- 2. Área de Contenido con Header2 Compartido -->
    <div class="rutinas-envoltorio-contenido">
      
      <!-- Header2 global y consistente con Inicio y Perfil -->
      <Header2 :nombre="auth.nombre || 'Atleta'" class="rutinas-barra-superior" />

      <!-- Cuerpo principal con scroll independiente -->
      <main class="rutinas-cuerpo-desplazable">
        
        <!-- Título de la página -->
        <div class="rutinas-encabezado-intro">
          <h1 class="titulo-pagina">Rutinas</h1>
          <p class="subtitulo-pagina">
            Tu camino del nivel 1 al 10. Cada nivel se desbloquea al completar el anterior.
          </p>
        </div>

        <!-- TU PROGRESIÓN  -->
        <section class="seccion-progresion">
          
          <!-- Cabecera de progresión -->
          <div class="cabecera-progresion">
            <h2 class="titulo-progresion">Tu progresión</h2>
          </div>

          <!-- Fila 1 de Niveles: 01 → 05 -->
          <div class="subfila-guia">
            <span class="rango-texto">01 → 05</span>
          </div>

          <div class="grilla-niveles-volt">
            <div
              v-for="nivel in nivelesFila1"
              :key="nivel.id"
              :class="[
                'tarjeta-nivel-volt',
                {
                  'tarjeta-actual': nivel.id === nivelActual,
                  'tarjeta-completada': nivel.id < nivelActual,
                  'tarjeta-bloqueada': nivel.id > nivelActual
                }
              ]"
            >

              <h3 class="tarjeta-nivel-nombre">{{ nivel.titulo }}</h3>

              <div class="tarjeta-fila-inferior">
                <template v-if="nivel.id === nivelActual">
                  <span class="estado-disponible">Disponible</span>
                  <button class="btn-ver-ejercicios" @click="irAEjercicios(nivel.id)">
                    <span>Ver ejercicios</span>
                    <i class="bi bi-arrow-right"></i>
                  </button>
                </template>

                <template v-else-if="nivel.id < nivelActual">
                  <span class="estado-listo">Completado</span>
                  <button class="btn-repasar" @click="irAEjercicios(nivel.id)">
                    Repasar
                  </button>
                </template>

                <template v-else>
                  <span class="estado-bloqueado">Bloqueado</span>
                  <p class="texto-requisito">
                    Completa el nivel {{ nivel.id - 1 }} para desbloquear
                  </p>
                </template>
              </div>
            </div>
          </div>

          <!-- Fila 2 de Niveles: 06 → 10 -->
          <div class="subfila-guia espacio-intermedio">
            <span class="rango-texto">06 → 10</span>
            <span class="guia-texto">La progresión continúa aquí ↓</span>
          </div>

          <div class="grilla-niveles-volt">
            <div
              v-for="nivel in nivelesFila2"
              :key="nivel.id"
              :class="[
                'tarjeta-nivel-volt',
                {
                  'tarjeta-actual': nivel.id === nivelActual,
                  'tarjeta-completada': nivel.id < nivelActual,
                  'tarjeta-bloqueada': nivel.id > nivelActual
                }
              ]"
            >
              <div class="tarjeta-fila-superior">
                <span class="tarjeta-codigo">{{ nivel.codigo }} / 10</span>
              </div>

              <h3 class="tarjeta-nivel-nombre">{{ nivel.titulo }}</h3>

              <div class="tarjeta-fila-inferior">
                <template v-if="nivel.id === nivelActual">
                  <span class="estado-disponible">Disponible · En progreso</span>
                  <button class="btn-ver-ejercicios" @click="irAEjercicios(nivel.id)">
                    <span>Ver ejercicios</span>
                    <i class="bi bi-arrow-right"></i>
                  </button>
                </template>

                <template v-else-if="nivel.id < nivelActual">
                  <span class="estado-listo">Completado</span>
                  <button class="btn-repasar" @click="irAEjercicios(nivel.id)">
                    Repasar
                  </button>
                </template>

                <template v-else>
                  <span class="estado-bloqueado">Bloqueado</span>
                  <p class="texto-requisito">
                    Completa el nivel {{ nivel.id - 1 }} para desbloquear
                  </p>
                </template>
              </div>
            </div>
          </div>

        </section>

        <!-- AVISO INFORMATIVO (Un nivel a la vez) -->
        <div class="aviso-informativo">
          <div class="aviso-caja-icono">
            <i class="bi bi-lock-fill"></i>
          </div>
          <div class="aviso-textos">
            <h4 class="aviso-titulo">Un nivel a la vez. Un paso más cerca.</h4>
            <p class="aviso-parrafo">
              Completa los ejercicios y alcanza los 100 XP de tu nivel para desbloquear el siguiente. Por ahora, solo puedes entrenar en el nivel {{ nivelActual }}.
            </p>
          </div>
        </div>

        <!-- PIE DISCRETO -->
        <footer class="pie-rutinas">
          <span>© 2026 FITCORE · Cada repetición suma</span>
          <div class="pie-enlaces">
            <a href="#">Privacidad</a>
            <a href="#">Términos</a>
            <a href="#">Ayuda</a>
          </div>
        </footer>

      </main>
    </div>
  </div>
</div>

<div>
  <BarraInferior/>
</div>
</template>

<style scoped>
/* ==========================================
   LAYOUT Y ESTRUCTURA
========================================== */
.rutinas-contenedor {
  display: flex;
  width: 100vw;
  height: 100vh;
  overflow: hidden;
  background-color: #0c0c0e;
  font-family: 'Rubik', sans-serif;
  box-sizing: border-box;
  color: #ffffff;
}

.rutinas-barra-lateral {
  flex-shrink: 0;
  width: 270px;
  height: 100vh;
}

.rutinas-envoltorio-contenido {
  flex: 1;
  display: flex;
  flex-direction: column;
  height: 100vh;
  min-width: 0;
  background-color: #0c0c0e;
}

.rutinas-barra-superior {
  flex-shrink: 0;
}

.rutinas-cuerpo-desplazable {
  flex: 1;
  overflow-y: auto;
  padding: 32px 40px 60px 40px;
  display: flex;
  flex-direction: column;
  gap: 28px;
  box-sizing: border-box;
}

/* ==========================================
   ENCABEZADO DE LA PÁGINA
========================================== */
.rutinas-encabezado-intro {
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.titulo-pagina {
  font-size: 2.2rem;
  font-weight: 900;
  margin: 0;
  color: #ffffff;
  letter-spacing: -0.5px;
}

.subtitulo-pagina {
  color: #a1a1aa;
  font-size: 0.9rem;
  margin: 2px 0 0 0;
}

/* ==========================================
   SECCIÓN TU PROGRESIÓN (GRILLA 5x2)
========================================== */
.seccion-progresion {
  display: flex;
  flex-direction: column;
  gap: 14px;
}

.cabecera-progresion {
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.titulo-progresion {
  font-size: 1.3rem;
  font-weight: 800;
  margin: 0;
  color: #ffffff;
}

.subfila-guia {
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-size: 0.75rem;
  color: #71717a;
}

.espacio-intermedio {
  margin-top: 14px;
}

.rango-texto {
  font-weight: 700;
  color: #a1a1aa;
}

/* Grilla de 5 columnas exactas */
.grilla-niveles-volt {
  display: grid;
  grid-template-columns: repeat(5, 1fr);
  gap: 14px;
}

.tarjeta-nivel-volt {
  background: #141417;
  border: 1px solid #1f1f23;
  border-radius: 14px;
  padding: 18px 16px;
  display: flex;
  flex-direction: column;
  min-height: 155px;
  box-sizing: border-box;
  transition: all 0.2s ease;
}

/* Estado Actual */
.tarjeta-actual {
  border-color: #ff3b30;
  background: #161214;
  box-shadow: 0 0 20px rgba(255, 59, 48, 0.15);
}

.tarjeta-completada {
  border-color: #22c55e;
  background: rgba(34, 197, 94, 0.03);
}

.tarjeta-fila-superior {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 10px;
}

.tarjeta-codigo {
  font-size: 0.72rem;
  color: #71717a;
  font-weight: 600;
}

.tarjeta-nivel-nombre {
  font-size: 1.15rem;
  font-weight: 800;
  color: #ffffff;
  margin: 0 0 6px 0;
  letter-spacing: -0.2px;
}

.tarjeta-fila-inferior {
  margin-top: auto;
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.estado-disponible {
  color: #eab308;
  font-size: 0.75rem;
  font-weight: 600;
}

.btn-ver-ejercicios {
  background: #ff3b30;
  color: #ffffff;
  border: none;
  padding: 9px 12px;
  border-radius: 8px;
  font-size: 0.8rem;
  font-weight: 700;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 6px;
  cursor: pointer;
  transition: all 0.2s ease;
  width: 100%;
}

.btn-ver-ejercicios:hover {
  background: #e03229;
  transform: translateY(-1px);
}

.estado-listo {
  color: #22c55e;
  font-size: 0.75rem;
  font-weight: 600;
}

.btn-repasar {
  background: transparent;
  color: #a1a1aa;
  border: 1px solid #27272a;
  padding: 6px 10px;
  border-radius: 6px;
  font-size: 0.75rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s ease;
}

.btn-repasar:hover {
  color: #ffffff;
  border-color: #52525b;
}

.estado-bloqueado {
  color: #71717a;
  font-size: 0.75rem;
  font-weight: 500;
}

.texto-requisito {
  font-size: 0.68rem;
  color: #52525b;
  margin: 0;
  line-height: 1.3;
}

/* ==========================================
   AVISO INFORMATIVO (Un nivel a la vez)
========================================== */
.aviso-informativo {
  background: #141417;
  border: 1px solid #222226;
  border-radius: 14px;
  padding: 16px 20px;
  display: flex;
  align-items: center;
  gap: 16px;
}

.aviso-caja-icono {
  width: 38px;
  height: 38px;
  background: rgba(234, 179, 8, 0.12);
  color: #eab308;
  border: 1px solid rgba(234, 179, 8, 0.25);
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.1rem;
  flex-shrink: 0;
}

.aviso-textos {
  display: flex;
  flex-direction: column;
  gap: 3px;
}

.aviso-titulo {
  font-size: 0.92rem;
  font-weight: 800;
  margin: 0;
  color: #ffffff;
}

.aviso-parrafo {
  font-size: 0.78rem;
  color: #a1a1aa;
  margin: 0;
  line-height: 1.4;
}

/* ==========================================
   PIE DISCRETO
========================================== */
.pie-rutinas {
  margin-top: 10px;
  padding-top: 20px;
  border-top: 1px solid #1a1a1e;
  display: flex;
  justify-content: space-between;
  align-items: center;
  color: #52525b;
  font-size: 0.75rem;
}

.pie-enlaces {
  display: flex;
  gap: 16px;
}

.pie-enlaces a {
  color: #52525b;
  text-decoration: none;
  transition: color 0.2s ease;
}

.pie-enlaces a:hover {
  color: #a1a1aa;
}

/* Scrollbar fina */
.rutinas-cuerpo-desplazable::-webkit-scrollbar {
  width: 6px;
}

.rutinas-cuerpo-desplazable::-webkit-scrollbar-track {
  background: #0c0c0e;
}

.rutinas-cuerpo-desplazable::-webkit-scrollbar-thumb {
  background: #27272a;
  border-radius: 4px;
}

.rutinas-cuerpo-desplazable::-webkit-scrollbar-thumb:hover {
  background: #3f3f46;
}

/* ==========================================
   RESPONSIVIDAD
========================================== */
@media (max-width: 1200px) {
  .grilla-niveles-volt {
    grid-template-columns: repeat(3, 1fr);
  }
}

@media (max-width: 900px) {
  .rutinas-contenedor {
    flex-direction: row;
    height: auto;
    min-height: 100vh;
    overflow: visible;
  }

  .rutinas-barra-lateral {
    display: none;
  }

  .rutinas-envoltorio-contenido {
    height: auto;
    min-height: 100vh;
  }

  .grilla-niveles-volt {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 600px) {
  .rutinas-cuerpo-desplazable {
    padding: 20px 16px;
  }

  .grilla-niveles-volt {
    grid-template-columns: 1fr;
  }

  .titulo-pagina {
    font-size: 1.8rem;
  }
}
</style>
