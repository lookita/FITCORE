<script setup lang="ts">
import { useUsuarioVerificacion } from '@/stores/auth.store';
import { useEntrenamientoStore } from '@/stores/entrenamiento.store';
import {useRouter} from 'vue-router'
import AvatarUsuario from '@/components/AvatarUsuario.vue';

const path = useRouter();
const auth = useUsuarioVerificacion();
const entrenoStore = useEntrenamientoStore();
</script>

<template>
  <aside class="barra-lateral-volt">
    <!-- 1. Header con Logo FITCORE -->
    <div class="marca-barra-lateral">
      <div class="insignia-marca">
        <img src="../components/icons/logoV.jpg" alt="">
      </div>
      <router-link to="/" >
        <span class="nombre-marca">FITCORE</span>
      </router-link>
    </div>

    <!-- 2. Tarjeta del Usuario Activo -->
    <div class="tarjeta-usuario">
      <div class="envoltorio-avatar-usuario">
        <AvatarUsuario :nombre="auth.nombre" :imagen-url="auth.usuario?.imagen_url" :tamano="42" />
        <span class="indicador-en-linea"></span>
      </div>
      <div class="meta-usuario">
        <router-link to="/perfil" >
          <h3 class="nombre-visible-usuario">{{ auth.nombre}}</h3>
        </router-link>
      </div>
      <div class="onda-sonido-usuario" title="Sesión activa">
        <span></span>
        <span></span>
        <span></span>
      </div>
    </div>

    <!-- 3. Sección Amigos Activos -->
    <div class="seccion-barra-lateral">
      <div class="fila-titulo-seccion">
        <span class="etiqueta-seccion">Amigos</span>
      </div>
      <div class="grilla-amigos-volt">
        <div v-for="amigo in entrenoStore.amigos" :key="amigo.id" class="tarjeta-amigo">
          <div class="anillo-amigo">
            <img :src="amigo.foto" :alt="amigo.nombre" class="foto-amigo" />
          </div>
          <span class="apodo-amigo">{{ amigo.nombre }}</span>
        </div>
      </div>
    </div>

    <!-- 4. Sección Intereses -->
    <div class="seccion-barra-lateral">
      <div class="fila-titulo-seccion">
        <span class="etiqueta-seccion">Intereses</span>
      </div>
      <ul class="menu-intereses">
        <li v-for="interes in entrenoStore.intereses" :key="interes.id" class="elemento-interes">
          <div class="insignia-icono-interes">
            <i :class="`bi ${interes.icono}`"></i>
          </div>
          <span class="nombre-interes">{{ interes.nombre }}</span>
        </li>
      </ul>
    </div>

    <!-- 5. Widget Inferior: ESTA SEMANA -->
    <div class="widget-pie-barra-lateral">
      <span class="sobretitulo-widget">ESTA SEMANA</span>
      <div class="metricas-widget">
        <div class="elemento-metrica-widget">
          <strong class="numero-metrica">{{ entrenoStore.entrenosSemanales }}</strong>
          <small class="etiqueta-metrica">entrenos</small>
        </div>
        <div class="elemento-metrica-widget">
          <strong class="numero-metrica">{{ entrenoStore.minutosSemanales }}</strong>
          <small class="etiqueta-metrica">minutos</small>
        </div>
      </div>
    </div>
  </aside>
</template>

<style scoped>
.barra-lateral-volt {
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
.marca-barra-lateral {
  display: flex;
  align-items: center;
  gap: 10px;
}

.insignia-marca img {
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

.nombre-marca {
  font-weight: 900;
  letter-spacing: 1.5px;
  font-size: 1.15rem;
  color: #ffffff;
}

/* 2. User Card */
.tarjeta-usuario {
  display: flex;
  align-items: center;
  gap: 12px;
  background: #141417;
  border: 1px solid #222226;
  padding: 10px 12px;
  border-radius: 14px;
}

.envoltorio-avatar-usuario {
  position: relative;
  width: 42px;
  height: 42px;
  flex-shrink: 0;
}

.indicador-en-linea {
  position: absolute;
  bottom: 0;
  right: 0;
  width: 10px;
  height: 10px;
  background-color: #22c55e;
  border-radius: 50%;
  border: 2px solid #141417;
}

.meta-usuario {
  flex: 1;
  min-width: 0;
}

.nombre-visible-usuario {
  font-size: 0.88rem;
  font-weight: 700;
  margin: 0;
  color: #ffffff;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.onda-sonido-usuario {
  display: flex;
  align-items: center;
  gap: 3px;
  height: 16px;
  padding-right: 4px;
}

.onda-sonido-usuario span {
  display: none;
  width: 2.5px;
  background-color: #71717a;
  border-radius: 2px;
  animation: pulsoOnda 1.4s ease-in-out infinite;
}

.onda-sonido-usuario span:nth-child(1) { height: 8px; animation-delay: 0.1s; }
.onda-sonido-usuario span:nth-child(2) { height: 16px; animation-delay: 0.3s; background-color: #ff3b30; }
.onda-sonido-usuario span:nth-child(3) { height: 10px; animation-delay: 0.2s; }

@keyframes pulsoOnda {
  0%, 100% { transform: scaleY(0.7); }
  50% { transform: scaleY(1.2); }
}

/* 3 & 4. Secciones comunes */
.seccion-barra-lateral {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.fila-titulo-seccion {
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.etiqueta-seccion {
  font-size: 0.85rem;
  font-weight: 700;
  color: #ffffff;
  letter-spacing: 0.2px;
}

/* Grid de amigos */
.grilla-amigos-volt {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 12px;
}

.tarjeta-amigo {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 6px;
  cursor: pointer;
}

.anillo-amigo {
  width: 44px;
  height: 44px;
  border-radius: 50%;
  padding: 2px;
  border: 1.5px solid rgba(255, 59, 48, 0.4);
  transition: all 0.2s ease;
  overflow: hidden;
}

.tarjeta-amigo:hover .anillo-amigo {
  border-color: #ff3b30;
  transform: scale(1.05);
}

.foto-amigo {
  width: 100%;
  height: 100%;
  border-radius: 50%;
  object-fit: cover;
  display: block;
}

.apodo-amigo {
  font-size: 0.72rem;
  color: #a1a1aa;
  font-weight: 500;
}

/* Intereses */
.menu-intereses {
  list-style: none;
  padding: 0;
  margin: 0;
  display: flex;
  flex-direction: column;
  gap: 6px;
}

.elemento-interes {
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

.elemento-interes:hover {
  background: #1c1c20;
  border-color: #2e2e34;
}

.insignia-icono-interes {
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

.nombre-interes {
  font-size: 0.8rem;
  color: #d4d4d8;
  font-weight: 500;
}

/* 5. Widget Inferior Esta Semana */
.widget-pie-barra-lateral {
  margin-top: auto;
  background: #141417;
  border: 1px solid #222226;
  border-radius: 14px;
  padding: 14px 16px;
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.sobretitulo-widget {
  color: #eab308;
  font-size: 0.68rem;
  font-weight: 800;
  letter-spacing: 0.8px;
}

.metricas-widget {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.elemento-metrica-widget {
  display: flex;
  flex-direction: column;
}

.numero-metrica {
  font-size: 1.4rem;
  font-weight: 800;
  color: #ffffff;
  line-height: 1;
}

.etiqueta-metrica {
  color: #71717a;
  font-size: 0.72rem;
  font-weight: 500;
  margin-top: 4px;
}
</style>
