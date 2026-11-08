<script setup lang="ts">
import { useUsuarioVerificacion } from '@/stores/auth.store';
import { useEntrenamientoStore } from '@/stores/entrenamiento.store';
import {useRouter} from 'vue-router'

const path = useRouter();
const auth = useUsuarioVerificacion();
const entrenoStore = useEntrenamientoStore();
</script>

<template>
  <aside class="sidebar-volt">
    <!-- 1. Header con Logo FITCORE -->
    <div class="sidebar-brand">
      <div class="brand-badge">
        <i class="bi bi-lightning-charge-fill"></i>
      </div>
      <router-link to="/" >
        <span class="brand-name">FITCORE</span>
      </router-link>
    </div>

    <!-- 2. Tarjeta del Usuario Activo -->
    <div class="user-card">
      <div class="user-avatar-wrap">
        <img
          src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=120&auto=format&fit=crop&q=80"
          alt="Avatar Atleta"
          class="user-avatar-img"
        />
        <span class="online-indicator"></span>
      </div>
      <div class="user-meta">
        <router-link to="/perfil" >
          <h3 class="user-display-name">{{ auth.nombre}}</h3>
        </router-link>
      </div>
      <div class="user-soundwave" title="Sesión activa">
        <span></span>
        <span></span>
        <span></span>
      </div>
    </div>

    <!-- 3. Sección Amigos Activos -->
    <div class="sidebar-section">
      <div class="section-title-row">
        <span class="section-label">Amigos</span>
      </div>
      <div class="friends-grid-volt">
        <div v-for="amigo in entrenoStore.amigos" :key="amigo.id" class="friend-card">
          <div class="friend-ring">
            <img :src="amigo.foto" :alt="amigo.nombre" class="friend-photo" />
          </div>
          <span class="friend-nickname">{{ amigo.nombre }}</span>
        </div>
      </div>
    </div>

    <!-- 4. Sección Intereses -->
    <div class="sidebar-section">
      <div class="section-title-row">
        <span class="section-label">Intereses</span>
      </div>
      <ul class="interests-menu">
        <li v-for="interes in entrenoStore.intereses" :key="interes.id" class="interest-item">
          <div class="interest-icon-badge">
            <i :class="`bi ${interes.icono}`"></i>
          </div>
          <span class="interest-name">{{ interes.nombre }}</span>
        </li>
      </ul>
    </div>

    <!-- 5. Widget Inferior: ESTA SEMANA -->
    <div class="sidebar-footer-widget">
      <span class="widget-eyebrow">ESTA SEMANA</span>
      <div class="widget-metrics">
        <div class="widget-metric-item">
          <strong class="metric-num">{{ entrenoStore.entrenosSemanales }}</strong>
          <small class="metric-lbl">entrenos</small>
        </div>
        <div class="widget-metric-item">
          <strong class="metric-num">{{ entrenoStore.minutosSemanales }}</strong>
          <small class="metric-lbl">minutos</small>
        </div>
      </div>
    </div>
  </aside>
</template>

<style scoped>
.sidebar-volt {
  width: 270px;
  min-width: 270px;
  background-color: #0c0c0e;
  border-right: 1px solid #1c1c20;
  display: flex;
  flex-direction: column;
  padding: 24px 20px;
  color: #ffffff;
  min-height: 100vh;
  box-sizing: border-box;
  font-family: 'Rubik', sans-serif;
  gap: 22px;
}

/* 1. Header Logo */
.sidebar-brand {
  display: flex;
  align-items: center;
  gap: 10px;
}

.brand-badge {
  width: 32px;
  height: 32px;
  background-color: #ff3b30;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #ffffff;
  font-size: 1rem;
}

.brand-name {
  font-weight: 900;
  letter-spacing: 1.5px;
  font-size: 1.15rem;
  color: #ffffff;
}

/* 2. User Card */
.user-card {
  display: flex;
  align-items: center;
  gap: 12px;
  background: #141417;
  border: 1px solid #222226;
  padding: 10px 12px;
  border-radius: 14px;
}

.user-avatar-wrap {
  position: relative;
  width: 42px;
  height: 42px;
  flex-shrink: 0;
}

.user-avatar-img {
  width: 100%;
  height: 100%;
  border-radius: 50%;
  object-fit: cover;
  border: 1.5px solid #ff3b30;
}

.online-indicator {
  position: absolute;
  bottom: 0;
  right: 0;
  width: 10px;
  height: 10px;
  background-color: #22c55e;
  border-radius: 50%;
  border: 2px solid #141417;
}

.user-meta {
  flex: 1;
  min-width: 0;
}

.user-display-name {
  font-size: 0.88rem;
  font-weight: 700;
  margin: 0;
  color: #ffffff;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.user-streak-text {
  font-size: 0.72rem;
  color: #a1a1aa;
  margin: 2px 0 0 0;
}

.user-soundwave {
  display: flex;
  align-items: center;
  gap: 3px;
  height: 16px;
  padding-right: 4px;
}

.user-soundwave span {
  width: 2.5px;
  background-color: #71717a;
  border-radius: 2px;
  animation: pulseWave 1.4s ease-in-out infinite;
}

.user-soundwave span:nth-child(1) { height: 8px; animation-delay: 0.1s; }
.user-soundwave span:nth-child(2) { height: 16px; animation-delay: 0.3s; background-color: #ff3b30; }
.user-soundwave span:nth-child(3) { height: 10px; animation-delay: 0.2s; }

@keyframes pulseWave {
  0%, 100% { transform: scaleY(0.7); }
  50% { transform: scaleY(1.2); }
}

/* 3 & 4. Secciones comunes */
.sidebar-section {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.section-title-row {
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.section-label {
  font-size: 0.85rem;
  font-weight: 700;
  color: #ffffff;
  letter-spacing: 0.2px;
}

.badge-amigos {
  background: rgba(245, 158, 11, 0.12);
  color: #f59e0b;
  border: 1px solid rgba(245, 158, 11, 0.25);
  font-size: 0.65rem;
  font-weight: 800;
  padding: 2px 8px;
  border-radius: 12px;
  letter-spacing: 0.5px;
}

/* Grid de amigos */
.friends-grid-volt {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 12px;
}

.friend-card {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 6px;
  cursor: pointer;
}

.friend-ring {
  width: 44px;
  height: 44px;
  border-radius: 50%;
  padding: 2px;
  border: 1.5px solid rgba(255, 59, 48, 0.4);
  transition: all 0.2s ease;
  overflow: hidden;
}

.friend-card:hover .friend-ring {
  border-color: #ff3b30;
  transform: scale(1.05);
}

.friend-photo {
  width: 100%;
  height: 100%;
  border-radius: 50%;
  object-fit: cover;
  display: block;
}

.friend-nickname {
  font-size: 0.72rem;
  color: #a1a1aa;
  font-weight: 500;
}

/* Intereses */
.interests-menu {
  list-style: none;
  padding: 0;
  margin: 0;
  display: flex;
  flex-direction: column;
  gap: 6px;
}

.interest-item {
  display: flex;
  align-items: center;
  gap: 12px;
  background: #141417;
  border: 1px solid #1f1f23;
  padding: 8px 12px;
  border-radius: 10px;
  cursor: pointer;
  transition: all 0.2s ease;
}

.interest-item:hover {
  background: #1c1c20;
  border-color: #2e2e34;
}

.interest-icon-badge {
  width: 26px;
  height: 26px;
  background: rgba(255, 59, 48, 0.15);
  color: #ff3b30;
  border-radius: 6px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.8rem;
}

.interest-name {
  font-size: 0.8rem;
  color: #d4d4d8;
  font-weight: 500;
}

/* 5. Widget Inferior Esta Semana */
.sidebar-footer-widget {
  margin-top: auto;
  background: #141417;
  border: 1px solid #222226;
  border-radius: 14px;
  padding: 14px 16px;
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.widget-eyebrow {
  color: #eab308;
  font-size: 0.68rem;
  font-weight: 800;
  letter-spacing: 0.8px;
}

.widget-metrics {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.widget-metric-item {
  display: flex;
  flex-direction: column;
}

.metric-num {
  font-size: 1.4rem;
  font-weight: 800;
  color: #ffffff;
  line-height: 1;
}

.metric-lbl {
  color: #71717a;
  font-size: 0.72rem;
  font-weight: 500;
  margin-top: 4px;
}
</style>
