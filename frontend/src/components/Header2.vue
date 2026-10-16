<script setup lang="ts">
import { ref } from 'vue';
import { useRoute } from 'vue-router';

interface MenuItem {
  name: string;
  path: string;
}

const props = withDefaults(
  defineProps<{
    nombre?: string;
  }>(),
  {
    nombre: 'Lucas',
  }
);

const route = useRoute();

const menu = ref<MenuItem[]>([
  { name: 'Inicio', path: '/' },
  { name: 'Perfil', path: '/perfil' },
  { name: 'Rutinas', path: '/rutinas' }
]);

const abrirMenu = ref<boolean>(false);
</script>

<template>
  <header class="barra-arriba">
    <div class="caja-barra">
      <!-- Saludo y Fecha/Hora -->
      <div class="caja-saludo">
        <div class="pastillita-hora">
          <span class="puntito-luz"></span>
          <span>SÁBADO · 18:42</span>
        </div>
        <h2 class="texto-saludo">Buenas tardes, <span class="texto-resaltado">{{ nombre }}</span></h2>
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
        </ul>
      </nav>

      <!-- Cositas de la derecha: campanita y botón de acción -->
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
  display: inline-flex;
  align-items: center;
  gap: 6px;
  background: rgba(255, 59, 48, 0.12);
  color: #ff3b30;
  font-size: 0.68rem;
  font-weight: 700;
  letter-spacing: 0.5px;
  padding: 3px 10px;
  border-radius: 20px;
  width: fit-content;
}

.puntito-luz {
  width: 6px;
  height: 6px;
  border-radius: 50%;
  background-color: #ff3b30;
  box-shadow: 0 0 6px rgba(255, 59, 48, 0.8);
}

.texto-saludo {
  font-size: 1.25rem;
  font-weight: 800;
  color: #fff;
  margin: 0;
  letter-spacing: -0.3px;
}

.texto-resaltado {
  color: #ff3b30;
}

/* Menú en la computadora */
.menu-grande {
  display: block;
}

.lista-menu {
  display: flex;
  align-items: center;
  gap: 8px;
  list-style: none;
  margin: 0;
  padding: 4px;
  background: #18181b;
  border-radius: 12px;
  border: 1px solid #27272a;
}

.boton-menu {
  display: block;
  padding: 8px 16px;
  font-size: 0.85rem;
  font-weight: 600;
  color: #a1a1aa;
  text-decoration: none;
  border-radius: 8px;
  transition: all 0.2s ease;
}

.boton-menu:hover {
  color: #fff;
  background: rgba(255, 255, 255, 0.05);
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
  gap: 8px;
  transition: background 0.2s ease;
}

.boton-rojo:hover {
  background: #e03229;
}

/* Botón de rayitas para celular */
.boton-rayitas {
  display: none;
  background: #18181b;
  border: 1px solid #27272a;
  border-radius: 8px;
  width: 38px;
  height: 38px;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  gap: 4px;
  cursor: pointer;
}

.boton-rayitas span {
  width: 18px;
  height: 2px;
  background: #fff;
  border-radius: 2px;
}

/* Menú que cae en el celular */
.menu-celular {
  position: absolute;
  top: 75px;
  right: 20px;
  background: #18181b;
  border: 1px solid #27272a;
  border-radius: 12px;
  padding: 12px;
  min-width: 200px;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5);
  z-index: 100;
}

.lista-celular {
  list-style: none;
  padding: 0;
  margin: 0;
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.enlace-celular {
  display: block;
  padding: 10px 14px;
  color: #d4d4d8;
  text-decoration: none;
  font-size: 0.9rem;
  font-weight: 600;
  border-radius: 8px;
  transition: background 0.2s;
}

.enlace-celular:hover {
  background: #27272a;
  color: #fff;
}

/* Celulares y tablets */
@media (max-width: 992px) {
  .menu-grande {
    display: none;
  }

  .boton-rayitas {
    display: flex;
  }

  .caja-barra {
    padding: 16px 20px;
  }
}

@media (max-width: 600px) {
  .boton-rojo {
    display: none;
  }
}
</style>