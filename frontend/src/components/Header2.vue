<script setup lang="ts">
import { useUsuarioVerificacion } from '@/stores/auth.store';
import { ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';

const usuario = useUsuarioVerificacion();
const router = useRouter();
const route = useRoute();

interface MenuItem {
  name: string;
  path: string;
}

withDefaults(
  defineProps<{
    nombre?: string;
  }>(),
  {
    nombre: '',
  }
);

const menu = ref<MenuItem[]>([
  { name: 'Inicio', path: '/' },
  { name: 'Perfil', path: '/perfil' },
  { name: 'Rutinas', path: '/rutinas' }
]);

const abrirMenu = ref<boolean>(false);

const cerrar = async () => {
  await usuario.cerrarSesion();
  router.push('/login');
};
</script>

<template>
  <header class="barra-arriba">
    <div class="caja-barra">
      <!-- Saludo y Fecha/Hora -->
      <div class="caja-saludo">
        <div class="pastillita-hora">
          <span class="puntito-luz"></span>
          <span>FITCORE</span>
        </div>
        <h2 class="texto-saludo">HOLA, <span class="texto-resaltado">{{ usuario.nombre }}</span></h2>
      </div>

      <!-- Menú para computadora -->
      <nav class="menu-grande">
        <ul class="lista-menu">
          <li v-for="item in menu" :key="item.name">
            <router-link
              :to="item.path"
              :class="['boton-menu', { 'activo': route.path === item.path }]"
            >
              {{ item.name }}
            </router-link>
          </li>
        </ul>
      </nav>

      <!-- Botón de tres rayitas para celular -->
      <button
        type="button"
        class="boton-rayitas"
        aria-label="Abrir menú"
        @click="abrirMenu = !abrirMenu"
      >
        <span></span>
        <span></span>
        <span></span>
      </button>

      <!-- Menú que se abre en celular -->
      <nav v-if="abrirMenu" class="menu-celular">
        <ul class="lista-celular">
          <li v-for="item in menu" :key="item.name">
            <router-link
              class="enlace-celular"
              :to="item.path"
              @click="abrirMenu = false"
            >
              {{ item.name }}
            </router-link>
          </li>
          <li v-if="usuario.estaAutenticado">
            <button class="enlace-celular boton-salir-celular" @click="cerrar">
              Cerrar Sesión
            </button>
          </li>
        </ul>
      </nav>

      <!-- Cositas de la derecha: campanita, botón de acción y cerrar sesión -->
      <div class="lado-derecho">
        <button class="boton-campana" aria-label="Notificaciones">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
            <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2m.995-14.901a1 1 0 1 0-1.99 0A5 5 0 0 0 3 6c0 1.098-.5 6-2 7h14c-1.5-1-2-5.902-2-7 0-2.42-1.72-4.44-4.005-4.901"/>
          </svg>
          <span class="puntito-rojo"></span>
        </button>

        <router-link to="/rutinas" class="boton-rojo">
          <i class="bi bi-play-fill"></i>
          <span>Entrenar</span>
        </router-link>

        <button
          v-if="usuario.estaAutenticado"
          class="boton-salir"
          title="Cerrar sesión"
          aria-label="Cerrar sesión"
          @click="cerrar"
        >
          <i class="bi bi-box-arrow-right"></i>
        </button>
      </div>
    </div>
  </header>
</template>

<style scoped>
.barra-arriba {
  width: 100%;
  background-color: #121214;
  border-bottom: 1px solid #222226;
  box-sizing: border-box;
  font-family: 'Rubik', sans-serif;
}

.caja-barra {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 18px 40px;
  gap: 20px;
}

/* Saludo */
.caja-saludo {
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.pastillita-hora {
  display: flex;
  align-items: center;
  gap: 6px;
  font-size: 0.65rem;
  font-weight: 700;
  letter-spacing: 1px;
  color: #71717a;
}

.puntito-luz {
  width: 6px;
  height: 6px;
  background-color: #22c55e;
  border-radius: 50%;
  box-shadow: 0 0 8px rgba(34, 197, 94, 0.6);
}

.texto-saludo {
  color: #a1a1aa;
  font-size: 1.05rem;
  font-weight: 500;
  margin: 0;
}

.texto-resaltado {
  color: #ffffff;
  font-weight: 700;
}

/* Menú PC */
.menu-grande {
  display: flex;
}

.lista-menu {
  display: flex;
  list-style: none;
  margin: 0;
  padding: 4px;
  background-color: #18181b;
  border: 1px solid #27272a;
  border-radius: 12px;
  gap: 4px;
}

.boton-menu {
  display: block;
  text-decoration: none;
  color: #a1a1aa;
  font-size: 0.85rem;
  font-weight: 600;
  padding: 8px 16px;
  border-radius: 8px;
  transition: all 0.2s ease;
}

.boton-menu:hover {
  color: #ffffff;
}

.activo {
  color: #fff;
  background: #27272a;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

/* Cosas de la derecha */
.lado-derecho {
  display: flex;
  align-items: center;
  gap: 14px;
}

.boton-campana {
  position: relative;
  background: #18181b;
  border: 1px solid #27272a;
  color: #d4d4d8;
  width: 40px;
  height: 40px;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.2s ease;
}

.boton-campana:hover {
  background: #27272a;
  color: #fff;
}

.puntito-rojo {
  position: absolute;
  top: 8px;
  right: 8px;
  width: 7px;
  height: 7px;
  background-color: #ff3b30;
  border-radius: 50%;
  border: 1.5px solid #18181b;
}

.boton-rojo {
  background: #ff3b30;
  color: #fff;
  text-decoration: none;
  border: none;
  padding: 10px 18px;
  border-radius: 10px;
  font-size: 0.85rem;
  font-weight: 700;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 6px;
  transition: background-color 0.2s ease;
}

.boton-rojo:hover {
  background-color: #e03126;
}

.boton-salir {
  background: #18181b;
  border: 1px solid #27272a;
  color: #ef4444;
  width: 40px;
  height: 40px;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.2s ease;
  font-size: 1.1rem;
}

.boton-salir:hover {
  background: #27272a;
  color: #ff3b30;
  border-color: #ef4444;
}

.boton-salir-celular {
  background: transparent;
  border: none;
  color: #ef4444;
  cursor: pointer;
  text-align: left;
  font-weight: 700;
  padding: 12px 16px;
  width: 100%;
}

/* Celular */
.boton-rayitas {
  display: none;
  background: #18181b;
  border: 1px solid #27272a;
  border-radius: 8px;
  width: 40px;
  height: 40px;
  padding: 10px;
  cursor: pointer;
  flex-direction: column;
  justify-content: space-between;
}

.boton-rayitas span {
  display: block;
  width: 100%;
  height: 2px;
  background-color: #fff;
  border-radius: 2px;
}

.menu-celular {
  display: none;
  position: absolute;
  top: 76px;
  left: 0;
  width: 100%;
  background: #121214;
  border-bottom: 1px solid #222226;
  z-index: 99;
}

.lista-celular {
  list-style: none;
  margin: 0;
  padding: 16px 24px;
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.enlace-celular {
  text-decoration: none;
  color: #a1a1aa;
  font-size: 1rem;
  font-weight: 600;
  display: block;
  padding: 8px 0;
}

@media (max-width: 768px) {
  .menu-grande {
    display: none;
  }
  .boton-rayitas {
    display: flex;
  }
  .menu-celular {
    display: block;
  }
  .caja-barra {
    padding: 16px 20px;
  }
}
</style>
