<script setup lang="ts">
import { computed } from 'vue';
import { useRouter } from 'vue-router';
import BarraUsuario from '@/components/BarraUsuario.vue';
import Header2 from '@/components/Header2.vue';
import RutinaConfig from '@/components/RutinaConfig.vue';
import { useUsuarioVerificacion } from '@/stores/auth.store';
import { useEntrenamientoStore } from '@/stores/entrenamiento.store';

const router = useRouter();
const auth = useUsuarioVerificacion();
const entrenoStore = useEntrenamientoStore();

// Nivel y XP actuales del usuario obtenidos del Store
const nivelActual = computed(() => entrenoStore.nivelUsuario);
const xpActual = computed(() => entrenoStore.xpUsuario);
const xpMeta = 100;
const xpRestante = computed(() => Math.max(xpMeta - xpActual.value, 0));
const porcentajeXp = computed(() => Math.min(Math.round((xpActual.value / xpMeta) * 100), 100));

// Títulos dinámicos según el nivel


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
  <div class="rutinas-container">
    <RutinaConfig />

    <!-- 1. Barra Lateral del Usuario (Idéntica a PerfilView) -->
    <BarraUsuario class="rutinas-sidebar" />

    <!-- 2. Área de Contenido con Header2 Compartido -->
    <div class="rutinas-content-wrapper">
      
      <!-- Header2 global y consistente con Inicio y Perfil -->
      <Header2 :nombre="auth.nombre || 'Atleta'" class="rutinas-topbar" />

      <!-- Cuerpo principal con scroll independiente -->
      <main class="rutinas-body-scroll">
        
        <!-- Título de la página -->
        <div class="rutinas-header-intro">
          <span class="eyebrow-accent">ENTRENA. AVANZA. SUPÉRATE.</span>
          <h1 class="page-title">Rutinas</h1>
          <p class="page-subtitle">
            Tu camino del nivel 1 al 10. Cada nivel se desbloquea al completar el anterior.
          </p>
        </div>

        <!-- SECCIÓN: TU PROGRESIÓN (Grilla 5x2 de Niveles) -->
        <section class="seccion-progresion">
          
          <!-- Cabecera de progresión -->
          <div class="cabecera-progresion">
            <h2 class="titulo-progresion">Tu progresión</h2>
            <div class="tags-progresion">
              <span class="tag-disponible">{{ entrenoStore.nivelesDesbloqueados }} DISPONIBLE</span>
              <span class="tag-bloqueados">{{ entrenoStore.nivelesBloqueados }} niveles bloqueados</span>
            </div>
          </div>

          <!-- Fila 1 de Niveles: 01 → 05 -->
          <div class="subfila-guia">
            <span class="rango-texto">01 → 05</span>
            <span class="guia-texto">Avanza en orden, de izquierda a derecha</span>
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
              <div class="card-top-row">
                <span class="card-code">{{ nivel.codigo }} / 10</span>
                <span v-if="nivel.id === nivelActual" class="badge-actual">ACTUAL</span>
                <span v-else-if="nivel.id < nivelActual" class="badge-completado"><i class="bi bi-check-lg"></i></span>
                <i v-else class="bi bi-lock-fill icono-candado"></i>
              </div>

              <h3 class="card-nivel-nombre">{{ nivel.titulo }}</h3>

              <div class="card-bottom-row">
                <template v-if="nivel.id === nivelActual">
                  <span class="status-disponible">Disponible · En progreso</span>
                  <button class="btn-ver-ejercicios" @click="irAEjercicios(nivel.id)">
                    <span>Ver ejercicios</span>
                    <i class="bi bi-arrow-right"></i>
                  </button>
                </template>

                <template v-else-if="nivel.id < nivelActual">
                  <span class="status-listo">Completado</span>
                  <button class="btn-repasar" @click="irAEjercicios(nivel.id)">
                    Repasar
                  </button>
                </template>

                <template v-else>
                  <span class="status-bloqueado">Bloqueado</span>
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
              <div class="card-top-row">
                <span class="card-code">{{ nivel.codigo }} / 10</span>
                <span v-if="nivel.id === nivelActual" class="badge-actual">ACTUAL</span>
                <span v-else-if="nivel.id < nivelActual" class="badge-completado"><i class="bi bi-check-lg"></i></span>
                <i v-else class="bi bi-lock-fill icono-candado"></i>
              </div>

              <h3 class="card-nivel-nombre">{{ nivel.titulo }}</h3>

              <div class="card-bottom-row">
                <template v-if="nivel.id === nivelActual">
                  <span class="status-disponible">Disponible · En progreso</span>
                  <button class="btn-ver-ejercicios" @click="irAEjercicios(nivel.id)">
                    <span>Ver ejercicios</span>
                    <i class="bi bi-arrow-right"></i>
                  </button>
                </template>

                <template v-else-if="nivel.id < nivelActual">
                  <span class="status-listo">Completado</span>
                  <button class="btn-repasar" @click="irAEjercicios(nivel.id)">
                    Repasar
                  </button>
                </template>

                <template v-else>
                  <span class="status-bloqueado">Bloqueado</span>
                  <p class="texto-requisito">
                    Completa el nivel {{ nivel.id - 1 }} para desbloquear
                  </p>
                </template>
              </div>
            </div>
          </div>

        </section>

        <!-- CALLOUT INFORMATIVO (Un nivel a la vez) -->
        <div class="callout-informativo">
          <div class="callout-icono-box">
            <i class="bi bi-lock-fill"></i>
          </div>
          <div class="callout-textos">
            <h4 class="callout-titulo">Un nivel a la vez. Un paso más cerca.</h4>
            <p class="callout-p">
              Completa los ejercicios y alcanza los 100 XP de tu nivel para desbloquear el siguiente. Por ahora, solo puedes entrenar en el nivel {{ nivelActual }}.
            </p>
          </div>
        </div>

        <!-- FOOTER DISCRETO -->
        <footer class="footer-rutinas">
          <span>© 2026 FITCORE · Cada repetición suma</span>
          <div class="footer-links">
            <a href="#">Privacidad</a>
            <a href="#">Términos</a>
            <a href="#">Ayuda</a>
          </div>
        </footer>

      </main>
    </div>
  </div>
</template>

<style scoped>
/* ==========================================
   LAYOUT Y ESTRUCTURA (Exactamente como PerfilView)
========================================== */
.rutinas-container {
  display: flex;
  width: 100vw;
  height: 100vh;
  overflow: hidden;
  background-color: #0c0c0e;
  font-family: 'Rubik', sans-serif;
  box-sizing: border-box;
  color: #ffffff;
}

.rutinas-sidebar {
  flex-shrink: 0;
  width: 270px;
  height: 100vh;
}

.rutinas-content-wrapper {
  flex: 1;
  display: flex;
  flex-direction: column;
  height: 100vh;
  min-width: 0;
  background-color: #0c0c0e;
}

.rutinas-topbar {
  flex-shrink: 0;
}

.rutinas-body-scroll {
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
.rutinas-header-intro {
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.eyebrow-accent {
  color: #eab308;
  font-size: 0.72rem;
  font-weight: 900;
  letter-spacing: 1px;
}

.page-title {
  font-size: 2.2rem;
  font-weight: 900;
  margin: 0;
  color: #ffffff;
  letter-spacing: -0.5px;
}

.page-subtitle {
  color: #a1a1aa;
  font-size: 0.9rem;
  margin: 2px 0 0 0;
}

/* ==========================================
   HERO BANNER (Nivel Actual + Barra XP)
========================================== */
.banner-progreso-hero {
  background: #141417;
  border: 1px solid #222226;
  border-radius: 18px;
  display: flex;
  align-items: center;
  position: relative;
  overflow: hidden;
  min-height: 155px;
}

.banner-nivel-box {
  background: #101012;
  border: 1px solid #1f1f23;
  border-radius: 14px;
  margin: 18px;
  padding: 16px 20px;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  min-width: 105px;
  flex-shrink: 0;
  z-index: 2;
}

.icono-rayo-dorado {
  color: #eab308;
  font-size: 1.25rem;
  margin-bottom: 2px;
}

.numero-nivel-grande {
  font-size: 2.3rem;
  font-weight: 900;
  line-height: 1;
  color: #ffffff;
}

.etiqueta-nivel-peq {
  font-size: 0.62rem;
  font-weight: 800;
  color: #eab308;
  letter-spacing: 0.5px;
  margin-top: 4px;
}

.banner-info-central {
  flex: 1;
  padding: 18px 24px 18px 6px;
  display: flex;
  flex-direction: column;
  justify-content: center;
  z-index: 2;
  min-width: 280px;
}

.banner-titulo {
  font-size: 1.35rem;
  font-weight: 800;
  margin: 0 0 4px 0;
  color: #ffffff;
}

.banner-descripcion {
  font-size: 0.85rem;
  color: #a1a1aa;
  margin: 0 0 14px 0;
}

.banner-xp-cifras {
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-size: 0.8rem;
  margin-bottom: 6px;
}

.xp-actual-tag {
  color: #eab308;
  font-weight: 800;
}

.xp-falta-tag {
  color: #71717a;
  font-weight: 500;
}

.barra-xp-fondo {
  width: 100%;
  height: 6px;
  background: #27272a;
  border-radius: 3px;
  overflow: hidden;
  margin-bottom: 8px;
}

.barra-xp-relleno {
  height: 100%;
  background: linear-gradient(90deg, #ff3b30 0%, #eab308 100%);
  border-radius: 3px;
  transition: width 0.4s ease;
}

.nota-bloqueo {
  font-size: 0.72rem;
  color: #71717a;
}

.banner-foto-gym {
  width: 320px;
  height: 100%;
  position: absolute;
  top: 0;
  right: 0;
  overflow: hidden;
  z-index: 1;
}

.banner-foto-gym img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  opacity: 0.85;
}

.foto-degradado-overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, #141417 0%, rgba(20, 20, 23, 0.7) 40%, transparent 100%);
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

.tags-progresion {
  display: flex;
  align-items: center;
  gap: 12px;
}

.tag-disponible {
  background: rgba(234, 179, 8, 0.12);
  border: 1px solid rgba(234, 179, 8, 0.3);
  color: #eab308;
  font-size: 0.7rem;
  font-weight: 800;
  padding: 3px 10px;
  border-radius: 12px;
  letter-spacing: 0.4px;
}

.tag-bloqueados {
  color: #71717a;
  font-size: 0.78rem;
  font-weight: 500;
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

.card-top-row {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 10px;
}

.card-code {
  font-size: 0.72rem;
  color: #71717a;
  font-weight: 600;
}

.badge-actual {
  background: rgba(255, 59, 48, 0.2);
  color: #ff3b30;
  font-size: 0.62rem;
  font-weight: 800;
  padding: 2px 6px;
  border-radius: 4px;
  letter-spacing: 0.5px;
}

.badge-completado {
  color: #22c55e;
  font-size: 0.9rem;
}

.icono-candado {
  color: #52525b;
  font-size: 0.85rem;
}

.card-nivel-nombre {
  font-size: 1.15rem;
  font-weight: 800;
  color: #ffffff;
  margin: 0 0 6px 0;
  letter-spacing: -0.2px;
}

.card-bottom-row {
  margin-top: auto;
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.status-disponible {
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

.status-listo {
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

.status-bloqueado {
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
   CALLOUT INFORMATIVO (Un nivel a la vez)
========================================== */
.callout-informativo {
  background: #141417;
  border: 1px solid #222226;
  border-radius: 14px;
  padding: 16px 20px;
  display: flex;
  align-items: center;
  gap: 16px;
}

.callout-icono-box {
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

.callout-textos {
  display: flex;
  flex-direction: column;
  gap: 3px;
}

.callout-titulo {
  font-size: 0.92rem;
  font-weight: 800;
  margin: 0;
  color: #ffffff;
}

.callout-p {
  font-size: 0.78rem;
  color: #a1a1aa;
  margin: 0;
  line-height: 1.4;
}

/* ==========================================
   FOOTER DISCRETO
========================================== */
.footer-rutinas {
  margin-top: 10px;
  padding-top: 20px;
  border-top: 1px solid #1a1a1e;
  display: flex;
  justify-content: space-between;
  align-items: center;
  color: #52525b;
  font-size: 0.75rem;
}

.footer-links {
  display: flex;
  gap: 16px;
}

.footer-links a {
  color: #52525b;
  text-decoration: none;
  transition: color 0.2s ease;
}

.footer-links a:hover {
  color: #a1a1aa;
}

/* Scrollbar fina */
.rutinas-body-scroll::-webkit-scrollbar {
  width: 6px;
}

.rutinas-body-scroll::-webkit-scrollbar-track {
  background: #0c0c0e;
}

.rutinas-body-scroll::-webkit-scrollbar-thumb {
  background: #27272a;
  border-radius: 4px;
}

.rutinas-body-scroll::-webkit-scrollbar-thumb:hover {
  background: #3f3f46;
}

/* ==========================================
   RESPONSIVIDAD
========================================== */
@media (max-width: 1200px) {
  .grilla-niveles-volt {
    grid-template-columns: repeat(3, 1fr);
  }

  .banner-foto-gym {
    width: 240px;
  }
}

@media (max-width: 900px) {
  .rutinas-container {
    flex-direction: row;
    height: auto;
    min-height: 100vh;
    overflow: visible;
  }

  .rutinas-sidebar {
    display: none;
  }

  .rutinas-content-wrapper {
    height: auto;
    min-height: 100vh;
  }

  .banner-foto-gym {
    display: none;
  }

  .grilla-niveles-volt {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 600px) {
  .rutinas-body-scroll {
    padding: 20px 16px;
  }

  .grilla-niveles-volt {
    grid-template-columns: 1fr;
  }

  .page-title {
    font-size: 1.8rem;
  }
}
</style>
