<script setup lang="ts">
import { ref, computed } from "vue";
import PageHeading from "../components/PageHeading.vue";
import Header2 from "@/components/Header2.vue";
// Nivel del usuario (actualmente 1 en base de datos)
const nivelActual = ref<number>(1);
// Catálogo de rutinas del MVP 2 con candados condicionales
const rutinas = ref([
  {
    id: 1,
    nombre: "Calistenia Básica",
    descripcion: "Dominio de flexiones, fondos y dominadas asistidas.",
    nivelRequerido: 1,
    ejercicios: 4,
    duracion: "30 min"
  },
  {
    id: 2,
    nombre: "Potencia y Core",
    descripcion: "L-sit, planchas dinámicas y fondos en paralelas.",
    nivelRequerido: 2,
    ejercicios: 5,
    duracion: "45 min"
  },
  {
    id: 3,
    nombre: "Muscle-Up Mastery",
    descripcion: "Transiciones explosivas en barra y fuerza máxima.",
    nivelRequerido: 5,
    ejercicios: 6,
    duracion: "50 min"
  }
]);
</script>
<template>
  <div class="rutinas-container">
    <div>
      <Header2></Header2>
    </div>
    <div class="intro-row">
      <PageHeading
        eyebrow="Tu camino FitCore"
        titulo="Domina cada nivel."
        descripcion="Completa las rutinas en orden, gana experiencia y desbloquea nuevos desafíos diseñados para llevarte más lejos."
      />
      <div class="current-level">
        <span class="level-badge">
          <i class="bi bi-trophy-fill"></i> NIVEL ACTUAL
        </span>
        <strong class="level-number">{{ nivelActual }}</strong>
        <small class="level-total">de 10 niveles</small>
      </div>
    </div>
    <div class="section-title">
      <strong>Catálogo de Rutinas</strong>
      <span>1 desbloqueada · 2 por conquistar</span>
    </div>
    <!-- Grid de Rutinas con Sistema de Candados -->
    <div class="rutinas-grid">
      <div
        v-for="rutina in rutinas"
        :key="rutina.id"
        :class="['rutina-card', { 'bloqueada': nivelActual < rutina.nivelRequerido }]"
      >
        <div class="card-header">
          <span class="pill-duracion">{{ rutina.duracion }}</span>
          <!-- Candado o Estado Libre -->
          <span v-if="nivelActual < rutina.nivelRequerido" class="pill-bloqueo">
            <i class="bi bi-lock-fill"></i> Nivel {{ rutina.nivelRequerido }}
          </span>
          <span v-else class="pill-libre">
            <i class="bi bi-unlock-fill"></i> Desbloqueada
          </span>
        </div>
        <h3 class="rutina-titulo">{{ rutina.nombre }}</h3>
        <p class="rutina-desc">{{ rutina.descripcion }}</p>
        <div class="card-footer">
          <span class="ejercicios-count">{{ rutina.ejercicios }} ejercicios</span>
          <button
            v-if="nivelActual >= rutina.nivelRequerido"
            class="btn-comenzar"
            @click="$router.push('/entrenamiento')"
          >
            Comenzar
          </button>
          <button v-else class="btn-bloqueado" disabled>
            <i class="bi bi-lock-fill"></i> Bloqueado
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
<style scoped>

.rutinas-container {
  background-color: #0c0c0e;
  min-height: 100vh;
  color: #fff;
  font-family: "Rubik", sans-serif;
  box-sizing: border-box;
}
.intro-row {
  display: flex;
  align-items: flex-end;
  justify-content: space-between;
  gap: 32px;
  padding-left: 40px;
  padding-right: 40px;
  padding-top: 20px;
}
.current-level {
  display: flex;
  flex-direction: column;
  min-width: 170px;
  padding: 18px 20px;
  border: 1px solid #27272a;
  border-radius: 16px;
  background: #18181b;
}
.level-badge {
  display: flex;
  align-items: center;
  gap: 7px;
  color: #ff3b30;
  font-size: 11px;
  font-weight: 800;
  letter-spacing: 0.1em;
}
.level-number {
  margin-top: 6px;
  font-size: 34px;
  line-height: 1;
}
.level-total {
  margin-top: 4px;
  color: #71717a;
  font-size: 11px;
}
.section-title {
  display: flex;
  margin: 40px 0 20px;
  align-items: baseline;
  justify-content: space-between;
  border-bottom: 1px solid #222226;
  padding-bottom: 12px;
  padding-left: 40px;
  padding-right: 40px;
}
.section-title strong {
  font-size: 1.1rem;
}
.section-title span {
  color: #a1a1aa;
  font-size: 0.85rem;
}
.rutinas-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 20px;
  padding-left: 40px;
  padding-right: 40px;
}
.rutina-card {
  background: #18181b;
  border: 1px solid #27272a;
  border-radius: 16px;
  padding: 24px;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  gap: 16px;
  transition: transform 0.2s, border-color 0.2s;
}
.rutina-card:hover {
  border-color: #3f3f46;
}
.rutina-card.bloqueada {
  opacity: 0.65;
  background: #121214;
}
.card-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
}
.pill-duracion {
  font-size: 0.75rem;
  color: #a1a1aa;
  font-weight: 600;
}
.pill-bloqueo {
  background: rgba(255, 59, 48, 0.15);
  color: #ff3b30;
  font-size: 0.75rem;
  font-weight: 700;
  padding: 3px 8px;
  border-radius: 6px;
}
.pill-libre {
  background: rgba(52, 199, 89, 0.15);
  color: #34c759;
  font-size: 0.75rem;
  font-weight: 700;
  padding: 3px 8px;
  border-radius: 6px;
}
.rutina-titulo {
  font-size: 1.3rem;
  font-weight: 800;
  margin: 0;
}
.rutina-desc {
  font-size: 0.85rem;
  color: #a1a1aa;
  margin: 0;
  line-height: 1.5;
}
.card-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding-top: 12px;
  border-top: 1px solid #27272a;
}
.ejercicios-count {
  font-size: 0.8rem;
  color: #71717a;
}
.btn-comenzar {
  background: #ff3b30;
  color: white;
  border: none;
  padding: 8px 16px;
  border-radius: 8px;
  font-weight: 700;
  font-size: 0.85rem;
  cursor: pointer;
}
.btn-comenzar:hover {
  background: #e03229;
}
.btn-bloqueado {
  background: #27272a;
  color: #71717a;
  border: none;
  padding: 8px 16px;
  border-radius: 8px;
  font-weight: 700;
  font-size: 0.85rem;
  cursor: not-allowed;
}
</style>
