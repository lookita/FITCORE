<script setup lang="ts">
import type { Usuario } from '@/DTO/Auth.DTO';
import AvatarUsuario from '@/components/AvatarUsuario.vue';
import {useUsuarioVerificacion} from '../stores/auth.store'

const usuario = useUsuarioVerificacion();


</script>

<template>
  <section class="identidad-perfil" aria-label="Perfil del atleta">
    <AvatarUsuario
      :nombre="usuario.nombre"
      :imagenUrl?="usuario.imagen_url"
      :tamano="124"
    />

    <div class="datos-identidad">
      <span class="etiqueta-identidad">PERFIL</span>
      <h1>{{ usuario.nombre }}</h1>
      <div class="insignias-identidad">
        <span><i class="bi bi-lightning-charge-fill"></i> Nivel {{ usuario.nivel }}</span>
      </div>
    </div>

  </section>
</template>

<style scoped>
.identidad-perfil {
  position: relative;
  display: flex;
  align-items: center;
  gap: 26px;
  margin-bottom: 24px;
  padding: 28px 32px;
  overflow: hidden;
  border: 1px solid #27272a;
  border-radius: 18px;
  background:
    radial-gradient(ellipse at 8% 50%, rgba(255, 59, 48, 0.12), transparent 42%),
    linear-gradient(120deg, #1b1b1f, #141417 72%);
  box-shadow: 0 10px 28px rgba(0, 0, 0, 0.22);
}

.datos-identidad {
  min-width: 0;
}

.etiqueta-identidad {
  color: #ff6b60;
  font-size: 0.7rem;
  font-weight: 800;
  letter-spacing: 1.4px;
}

.datos-identidad h1 {
  margin: 6px 0 3px;
  color: #ffffff;
  font-size: clamp(1.45rem, 3vw, 2rem);
  font-weight: 800;
  letter-spacing: -0.5px;
  overflow-wrap: anywhere;
}

.insignias-identidad {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
  margin-top: 16px;
}

.insignias-identidad span {
  display: inline-flex;
  align-items: center;
  gap: 7px;
  padding: 7px 10px;
  border: 1px solid #303036;
  border-radius: 999px;
  background: #202024;
  color: #e4e4e7;
  font-size: 0.76rem;
  font-weight: 700;
}

.insignias-identidad i {
  color: #ff5146;
}

@media (max-width: 600px) {
  .identidad-perfil {
    flex-direction: column;
    gap: 16px;
    padding: 24px 18px;
    text-align: center;
  }

  .insignias-identidad {
    justify-content: center;
  }
}
</style>
