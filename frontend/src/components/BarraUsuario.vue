<template>
  <aside class="sidebar">
    <!-- Logo y marca -->
    <div class="sidebar-header">
      <div class="logo-icon">⚡</div>
      <span class="logo-text">VOLT</span>
    </div>

    <!-- Perfil resumido del usuario -->
    <div class="user-profile-card">
      <div class="avatar-container">
        <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=150" alt="Avatar" class="avatar-img" />
      </div>
      <div class="user-info">
        <h3 class="user-name">Lucas Fernández</h3>
        <p class="user-streak">Racha activa · 6 días</p>
      </div>
      <button class="options-btn" aria-label="Opciones">•••</button>
    </div>

    <!-- Sección Amigos -->
    <div class="section-container">
      <div class="section-header">
        <span class="section-title">Amigos</span>
        <span class="badge-active">6 ACTIVOS</span>
      </div>
      <div class="friends-grid">
        <div v-for="friend in friends" :key="friend.name" class="friend-item">
          <div class="friend-avatar-wrapper">
            <img :src="friend.avatar" :alt="friend.name" class="friend-avatar" />
          </div>
          <span class="friend-name">{{ friend.name }}</span>
        </div>
      </div>
    </div>

    <!-- Sección Intereses -->
    <div class="section-container">
      <div class="section-header">
        <span class="section-title">Intereses</span>
        <span class="filter-icon">⚙️</span>
      </div>
      <ul class="interests-list">
        <li v-for="interest in interests" :key="interest.text" class="interest-item">
          <span class="interest-icon">{{ interest.icon }}</span>
          <span class="interest-text">{{ interest.text }}</span>
        </li>
      </ul>
    </div>
  </aside>
</template>

<script setup lang="ts">
import { ref } from 'vue'

const friends = ref([
  { name: 'Mara', avatar: 'https://images.unsplash.com/photo-1517841905240-472988babdf9?w=100' },
  { name: 'Leo', avatar: 'https://images.unsplash.com/photo-1539571696357-5a69c17a67c6?w=100' },
  { name: 'Inés', avatar: 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=100' },
  { name: 'Dani', avatar: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100' },
  { name: 'Noa', avatar: 'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?w=100' },
  { name: 'Álex', avatar: 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=100' },
])

const interests = ref([
  { text: 'Fuerza funcional', icon: '⚡' },
  { text: 'Rendimiento', icon: '📈' },
  { text: 'HIIT express', icon: '⏱️' },
])
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
  background: #ff3b30;
  color: white;
  width: 32px;
  height: 32px;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
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

.avatar-img {
  width: 44px;
  height: 44px;
  border-radius: 50%;
  object-fit: cover;
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
}

.friend-avatar {
  width: 100%;
  height: 100%;
  border-radius: 50%;
  object-fit: cover;
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
</style>