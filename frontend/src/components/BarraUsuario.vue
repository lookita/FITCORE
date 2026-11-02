<template>
  <aside class="sidebar">
    <!-- Header con Logo -->
    <div class="sidebar-header">
      <div class="logo-icon">
        <img src="../components/icons/logoV.jpg" alt="FITCORE" class="logo-img" />
      </div>
      <span class="logo-text">FITCORE</span>
    </div>

    <!-- perfil -->
    <div class="user-profile-card">
      <div class="avatar-container">
        <div class="avatar-placeholder">LF</div>
      </div>
      <div class="user-info">
        <h3 class="user-name">{nombre reactivo}</h3>
      </div>
      <button class="options-btn" aria-label="Opciones">•••</button>
    </div>

    <!-- amigos -->
    <div class="section-container">
      <div class="section-header">
        <span class="section-title">Amigos</span>
        <span class="badge-active">cantidad de amigos</span>
      </div>
      <div class="friends-grid">
        <div v-for="amigo in amigos" :key="amigo.nombre" class="friend-item">
          <div class="friend-avatar-wrapper">
          </div>
          <span class="friend-name">{{ amigo.nombre }}</span>
        </div>
      </div>
    </div>

  </aside>
</template>

<script setup lang="ts">
import { ref } from 'vue'

const amigos = ref ([
  {nombre: 'x'},
  {nombre: 'y'},
  {nombre: 'z'}
]);

</script>

<style scoped>
.sidebar {
  width: 280px;
  background-color: #121214;
  border-right: 1px solid #222226;
  display: flex;
  flex-direction: column;
  padding: 24px;
  color: #fff;
  height: 100vh;
  box-sizing: border-box;
}

.sidebar-header {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-bottom: 32px;
}

.logo-icon {
  width: 36px;
  height: 36px;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
}

.logo-img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
}

.logo-text {
  font-weight: 800;
  letter-spacing: 1px;
  font-size: 1.1rem;
}

.user-profile-card {
  display: flex;
  align-items: center;
  background: #18181b;
  padding: 12px;
  border-radius: 12px;
  margin-bottom: 32px;
  border: 1px solid #27272a;
}

.avatar-container {
  display: flex;
  align-items: center;
  justify-content: center;
}

.avatar-placeholder {
  width: 44px;
  height: 44px;
  border-radius: 50%;
  background: #ff3b30;
  color: #fff;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 700;
  font-size: 1.1rem;
}

.user-info {
  margin-left: 12px;
  flex: 1;
}

.user-name {
  font-size: 0.95rem;
  font-weight: 600;
  margin: 0;
}

.user-streak {
  font-size: 0.75rem;
  color: #a1a1aa;
  margin: 2px 0 0 0;
}

.options-btn {
  background: transparent;
  border: none;
  color: #71717a;
  cursor: pointer;
  font-size: 1rem;
}

.section-container {
  margin-bottom: 28px;
}

.section-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 12px;
}

.section-title {
  font-size: 0.85rem;
  font-weight: 600;
  color: #a1a1aa;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.badge-active {
  background: rgba(255, 59, 48, 0.15);
  color: #ff3b30;
  font-size: 0.65rem;
  padding: 2px 6px;
  border-radius: 4px;
  font-weight: 700;
}

.friends-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 12px;
}

.friend-item {
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
}

.friend-avatar-wrapper {
  width: 42px;
  height: 42px;
  border-radius: 50%;
  padding: 2px;
  border: 2px solid #ff3b30;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 4px;
  background-color: #18181b;
}

.friend-initial {
  color: #fff;
  font-size: 0.85rem;
  font-weight: 700;
}

.friend-name {
  font-size: 0.75rem;
  color: #d4d4d8;
}

.interests-list {
  list-style: none;
  padding: 0;
  margin: 0;
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.interest-item {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 8px 10px;
  border-radius: 8px;
  background: #18181b;
  color: #d4d4d8;
  font-size: 0.85rem;
  cursor: pointer;
  transition: background 0.2s;
}

.interest-item:hover {
  background: #27272a;
}

.filter-icon {
  font-size: 0.9rem;
  cursor: pointer;
  opacity: 0.7;
}

.filter-icon:hover {
  opacity: 1;
}
</style>