<script setup lang="ts">
import { ref } from 'vue';
import Header2 from '@/components/Header2.vue';
import BarraUsuario from '@/components/BarraUsuario.vue';
import { useUsuarioVerificacion } from '@/stores/auth.store';
import BarraInferior from '@/components/Barra-inferior.vue';

interface ComentarioDTO {
  id: number;
  autor: string;
  texto: string;
}

interface PublicacionDTO {
  id: number;
  autor: string;
  iniciales: string;
  tiempo: string;
  texto: string;
  tipo: 'entrenamiento' | 'reto' | 'texto';
  detalle?: string;
  icono?: string;
  meGusta: number;
  meGustaUsuario: boolean;
  comentarios: ComentarioDTO[];
  comentarioNuevo: string;
  mostrandoComentarios: boolean;
}

const auth = useUsuarioVerificacion();
const textoPublicacion = ref('');
const tipoPublicacion = ref<PublicacionDTO['tipo']>('texto');
const detallePublicacion = ref('');
let siguienteId = 4;

const publicaciones = ref<PublicacionDTO[]>([
  {
    id: 1,
    autor: 'Mara González',
    iniciales: 'MG',
    tiempo: 'hace 12 min',
    texto: 'Hoy tocó trabajar la técnica con calma. Cada repetición limpia cuenta más que hacerlas rápido. 💪',
    tipo: 'entrenamiento',
    detalle: 'Entrenamiento de torso · 48 min',
    icono: 'bi-activity',
    meGusta: 12,
    meGustaUsuario: false,
    comentarios: [
      { id: 1, autor: 'Leo', texto: '¡Esa es la actitud! 🔥' },
      { id: 2, autor: 'Inés', texto: 'La técnica siempre primero 🙌' }
    ],
    comentarioNuevo: '',
    mostrandoComentarios: true
  },
  {
    id: 2,
    autor: 'Leo Martínez',
    iniciales: 'LM',
    tiempo: 'hace 38 min',
    texto: '¡Reto cumplido! Después de varias semanas intentando, hoy salió mi primer muscle-up estricto. A seguir sumando.',
    tipo: 'reto',
    detalle: 'Reto completado · Primer muscle-up',
    icono: 'bi-trophy-fill',
    meGusta: 28,
    meGustaUsuario: false,
    comentarios: [
      { id: 3, autor: 'Dani', texto: '¡Qué grande! Felicidades 🏆' }
    ],
    comentarioNuevo: '',
    mostrandoComentarios: false
  },
  {
    id: 3,
    autor: 'Inés Romero',
    iniciales: 'IR',
    tiempo: 'hace 1 h',
    texto: 'Sesión terminada: 35 minutos de core y movilidad. ¿Qué ejercicio de abdomen no puede faltar en vuestra rutina?',
    tipo: 'entrenamiento',
    detalle: 'Core y movilidad · 35 min',
    icono: 'bi-lightning-charge-fill',
    meGusta: 9,
    meGustaUsuario: false,
    comentarios: [],
    comentarioNuevo: '',
    mostrandoComentarios: false
  }
]);

function inicialesDe(nombre: string): string {
  return nombre
    .trim()
    .split(/\s+/)
    .slice(0, 2)
    .map((parte) => parte.charAt(0).toUpperCase())
    .join('');
}

function publicar(): void {
  const texto = textoPublicacion.value.trim();
  const detalle = detallePublicacion.value.trim();
  if (!texto || (tipoPublicacion.value !== 'texto' && !detalle)) return;

  publicaciones.value.unshift({
    id: siguienteId++,
    autor: auth.nombre,
    iniciales: inicialesDe(auth.nombre),
    tiempo: 'ahora',
    texto,
    tipo: tipoPublicacion.value,
    ...(tipoPublicacion.value !== 'texto'
      ? {
          detalle,
          icono: tipoPublicacion.value === 'reto' ? 'bi-trophy-fill' : 'bi-activity'
        }
      : {}),
    meGusta: 0,
    meGustaUsuario: false,
    comentarios: [],
    comentarioNuevo: '',
    mostrandoComentarios: false
  });
  textoPublicacion.value = '';
  detallePublicacion.value = '';
  tipoPublicacion.value = 'texto';
}

function alternarMeGusta(publicacion: PublicacionDTO): void {
  publicacion.meGustaUsuario = !publicacion.meGustaUsuario;
  publicacion.meGusta += publicacion.meGustaUsuario ? 1 : -1;
}

function agregarComentario(publicacion: PublicacionDTO): void {
  const texto = publicacion.comentarioNuevo.trim();
  if (!texto) return;

  publicacion.comentarios.push({
    id: siguienteId++,
    autor: auth.nombre,
    texto
  });
  publicacion.comentarioNuevo = '';
  publicacion.mostrandoComentarios = true;
}
</script>

<template>
<div>
  <section class="barra-main">
    <div class="columna-usuario">
      <BarraUsuario />
    </div>

    <div class="pagina-panel">
      <Header2 />

      <main class="contenedor-feed">
        <div class="columna-feed">

          <section class="compositor tarjeta-feed" aria-label="Crear publicación">
            <div class="fila-compositor">
              <div class="avatar avatar-propio" aria-hidden="true">
                {{ inicialesDe(auth.nombre) }}
              </div>
              <label class="solo-lectores" for="texto-publicacion">¿Qué estás pensando?</label>
              <textarea
                id="texto-publicacion"
                v-model="textoPublicacion"
                rows="2"
                maxlength="500"
                placeholder="¿Qué estás pensando, atleta?"
                @keydown.ctrl.enter="publicar"
              ></textarea>
            </div>
            <div class="tipos-publicacion" role="group" aria-label="Tipo de publicación">
              
             
              
            </div>
            <label
              v-if="tipoPublicacion !== 'texto'"
              class="solo-lectores"
              for="detalle-publicacion"
            >
              {{ tipoPublicacion === 'reto' ? 'Reto cumplido' : 'Resumen del entrenamiento' }}
            </label>
            <input
              v-if="tipoPublicacion !== 'texto'"
              id="detalle-publicacion"
              v-model="detallePublicacion"
              class="detalle-compositor"
              type="text"
              maxlength="100"
              :placeholder="tipoPublicacion === 'reto'
                ? '¿Qué reto cumpliste? Ej.: 50 flexiones'
                : '¿Qué entrenaste y cuánto tiempo? Ej.: Torso · 45 min'"
            />
            <div class="pie-compositor">
              <button
                class="boton-publicar"
                type="button"
                :disabled="!textoPublicacion.trim() || (tipoPublicacion !== 'texto' && !detallePublicacion.trim())"
                @click="publicar"
              >
                Publicar
                <i class="bi bi-send-fill" aria-hidden="true"></i>
              </button>
            </div>
          </section>

          <div class="separador-feed">
            <h2>Últimas publicaciones</h2>
            <span>{{ publicaciones.length }} publicaciones</span>
          </div>

          <article
            v-for="publicacion in publicaciones"
            :key="publicacion.id"
            class="publicacion tarjeta-feed"
          >
            <header class="publicacion-encabezado">
              <div class="avatar" aria-hidden="true">{{ publicacion.iniciales }}</div>
              <div class="autor-info">
                <strong>{{ publicacion.autor }}</strong>
                <span>{{ publicacion.tiempo }} · <i class="bi bi-globe-americas" aria-label="Público"></i></span>
              </div>
              <button class="boton-mas" type="button" aria-label="Más opciones">
                <i class="bi bi-three-dots" aria-hidden="true"></i>
              </button>
            </header>

            <p class="texto-publicacion">{{ publicacion.texto }}</p>

            <div
              v-if="publicacion.detalle"
              :class="['detalle-entreno', `detalle-${publicacion.tipo}`]"
            >
              <span class="detalle-icono">
                <i :class="`bi ${publicacion.icono}`" aria-hidden="true"></i>
              </span>
              <span>{{ publicacion.detalle }}</span>
              <i class="bi bi-check-circle-fill detalle-check" aria-hidden="true"></i>
            </div>

            <div class="resumen-interacciones">
              <span>
                <i class="bi bi-heart-fill corazon-resumen" aria-hidden="true"></i>
                {{ publicacion.meGusta }} me gusta
              </span>
              <button type="button" @click="publicacion.mostrandoComentarios = !publicacion.mostrandoComentarios">
                {{ publicacion.comentarios.length }}
                {{ publicacion.comentarios.length === 1 ? 'comentario' : 'comentarios' }}
              </button>
            </div>

            <div class="acciones-publicacion">
              <button
                :class="['accion-social', { 'accion-me-gusta': publicacion.meGustaUsuario }]"
                type="button"
                :aria-pressed="publicacion.meGustaUsuario"
                @click="alternarMeGusta(publicacion)"
              >
                <i :class="publicacion.meGustaUsuario ? 'bi bi-heart-fill' : 'bi bi-heart'" aria-hidden="true"></i>
                Me gusta
              </button>
              <button
                class="accion-social"
                type="button"
                @click="publicacion.mostrandoComentarios = !publicacion.mostrandoComentarios"
              >
                <i class="bi bi-chat-dots" aria-hidden="true"></i>
                Comentar
              </button>
              <button class="accion-social accion-compartir" type="button">
                <i class="bi bi-share" aria-hidden="true"></i>
                Compartir
              </button>
            </div>

            <section
              v-if="publicacion.mostrandoComentarios"
              class="comentarios"
              :aria-label="`Comentarios de ${publicacion.autor}`"
            >
              <div v-for="comentario in publicacion.comentarios" :key="comentario.id" class="comentario">
                <div class="avatar avatar-comentario" aria-hidden="true">
                  {{ inicialesDe(comentario.autor) }}
                </div>
                <div class="burbuja-comentario">
                  <strong>{{ comentario.autor }}</strong>
                  <p>{{ comentario.texto }}</p>
                </div>
              </div>
              <form class="formulario-comentario" @submit.prevent="agregarComentario(publicacion)">
                <label class="solo-lectores" :for="`comentario-${publicacion.id}`">
                  Escribe un comentario
                </label>
                <input
                  :id="`comentario-${publicacion.id}`"
                  v-model="publicacion.comentarioNuevo"
                  type="text"
                  maxlength="240"
                  placeholder="Escribe un comentario..."
                />
                <button
                  type="submit"
                  :disabled="!publicacion.comentarioNuevo.trim()"
                  :aria-label="`Enviar comentario en la publicación de ${publicacion.autor}`"
                >
                  <i class="bi bi-arrow-up-circle-fill" aria-hidden="true"></i>
                </button>
              </form>
            </section>
          </article>
        </div>

        <aside class="columna-lateral">
          <section class="tarjeta-lateral">
            <span class="lateral-etiqueta">ESPÍRITU FITCORE</span>
            <div class="icono-comunidad"><i class="bi bi-people-fill" aria-hidden="true"></i></div>
            <h2>Entrenamos juntos</h2>
            <p>Cada avance inspira a alguien. Comparte tus sesiones y celebra los logros de la comunidad.</p>
          </section>

          <section class="tarjeta-lateral reto-lateral">
            <span class="lateral-etiqueta">RETO DE LA SEMANA</span>
            <div class="reto-icono"><i class="bi bi-trophy-fill" aria-hidden="true"></i></div>
            <h2>Reto de constancia</h2>
            <p>Comparte una actualización de tu entrenamiento esta semana.</p>
            <span class="reto-progreso"><i class="bi bi-fire" aria-hidden="true"></i> Cada sesión cuenta</span>
          </section>
        </aside>
      </main>
    </div>
  </section>
</div>

<div>
  <BarraInferior/>
</div>
</template>

<style scoped>
.barra-main {
  display: grid;
  grid-template-columns: 270px minmax(0, 1fr);
  width: 100%;
  min-height: 100vh;
  background: #0c0c0e;
  color: #ffffff;
  box-sizing: border-box;
  overflow-x: hidden;
  font-family: 'Rubik', sans-serif;
}

.columna-usuario {
  min-width: 0;
}

.pagina-panel {
  display: flex;
  flex-direction: column;
  min-width: 0;
  min-height: 100vh;
  background: #0c0c0e;
}

.contenedor-feed {
  display: grid;
  grid-template-columns: minmax(0, 680px) minmax(220px, 280px);
  justify-content: center;
  align-items: start;
  gap: 24px;
  width: 100%;
  max-width: 1160px;
  margin: 0 auto;
  padding: 34px 32px 60px;
  box-sizing: border-box;
}

.columna-feed {
  display: flex;
  min-width: 0;
  flex-direction: column;
  gap: 18px;
}

.lateral-etiqueta {
  color: #eab308;
  font-size: 0.75rem;
  font-weight: 900;
  letter-spacing: 1px;
}

.tarjeta-feed,
.tarjeta-lateral {
  border: 1px solid #222226;
  border-radius: 16px;
  background: #141417;
}

.compositor {
  padding: 21px;
}

.fila-compositor {
  display: flex;
  align-items: flex-start;
  gap: 12px;
}

.avatar {
  display: grid;
  width: 46px;
  height: 46px;
  flex: 0 0 46px;
  place-items: center;
  border: 1px solid rgb(255 59 48 / 38%);
  border-radius: 50%;
  background: linear-gradient(145deg, #30201f, #191416);
  color: #ff746c;
  font-size: 0.82rem;
  font-weight: 800;
}

.avatar-propio {
  background: linear-gradient(145deg, #ff3b30, #92241f);
  color: #ffffff;
}

.fila-compositor textarea {
  width: 100%;
  min-height: 68px;
  resize: vertical;
  padding: 12px 14px;
  border: 1px solid #29292e;
  border-radius: 12px;
  outline: none;
  background: #0f0f11;
  color: #ffffff;
  font: inherit;
  font-size: 0.96rem;
  line-height: 1.5;
  box-sizing: border-box;
  transition: border-color 160ms ease, box-shadow 160ms ease;
}

.fila-compositor textarea::placeholder,
.detalle-compositor::placeholder,
.formulario-comentario input::placeholder {
  color: #71717a;
}

.fila-compositor textarea:focus,
.detalle-compositor:focus,
.formulario-comentario input:focus {
  border-color: #ff3b30;
  box-shadow: 0 0 0 3px rgb(255 59 48 / 10%);
}

.tipos-publicacion {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
  margin: 14px 0 0 54px;
}

.detalle-compositor {
  width: calc(100% - 54px);
  min-height: 44px;
  margin: 12px 0 0 54px;
  padding: 0 12px;
  border: 1px solid #29292e;
  border-radius: 10px;
  outline: none;
  background: #0f0f11;
  color: #ffffff;
  font: inherit;
  font-size: 0.84rem;
  box-sizing: border-box;
}

.pie-compositor {
  display: flex;
  align-items: center;
  justify-content: flex-end;
  gap: 12px;
  margin-top: 15px;
  padding-top: 13px;
  border-top: 1px solid #252529;
}

.boton-publicar {
  display: inline-flex;
  min-height: 44px;
  align-items: center;
  justify-content: center;
  gap: 8px;
  padding: 0 18px;
  border: 0;
  border-radius: 10px;
  background: #ff3b30;
  color: white;
  font: inherit;
  font-size: 0.84rem;
  font-weight: 800;
  cursor: pointer;
  transition: background 160ms ease, opacity 160ms ease;
}

.boton-publicar:hover:not(:disabled) {
  background: #e03229;
}

.boton-publicar:disabled {
  opacity: 0.45;
  cursor: not-allowed;
}

.separador-feed {
  display: flex;
  align-items: baseline;
  justify-content: space-between;
  gap: 12px;
  margin-top: 7px;
}

.feed-separador h2 {
  margin: 0;
  font-size: 1.08rem;
  font-weight: 800;
}

.feed-separador span {
  color: #71717a;
  font-size: 0.78rem;
}

.publicacion {
  padding: 21px;
}

.publicacion-encabezado {
  display: flex;
  align-items: center;
  gap: 13px;
}

.autor-info {
  display: flex;
  min-width: 0;
  flex: 1;
  flex-direction: column;
  gap: 4px;
}

.autor-info strong {
  overflow: hidden;
  color: #f4f4f5;
  font-size: 0.92rem;
  font-weight: 800;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.autor-info span {
  color: #71717a;
  font-size: 0.75rem;
}

.boton-mas {
  display: grid;
  width: 40px;
  height: 40px;
  place-items: center;
  border: 0;
  border-radius: 9px;
  background: transparent;
  color: #a1a1aa;
  font-size: 1.1rem;
  cursor: pointer;
}

.boton-mas:hover {
  background: #222226;
  color: #ffffff;
}

.texto-publicacion {
  margin: 16px 0;
  color: #e4e4e7;
  font-size: 0.96rem;
  line-height: 1.7;
  white-space: pre-wrap;
  overflow-wrap: anywhere;
}

.detalle-entreno {
  display: flex;
  align-items: center;
  gap: 11px;
  padding: 14px;
  border: 1px solid #302729;
  border-radius: 12px;
  background: linear-gradient(100deg, #211719, #171416);
  color: #f4f4f5;
  font-size: 0.84rem;
  font-weight: 700;
}

.detalle-icono {
  display: grid;
  width: 40px;
  height: 40px;
  flex: 0 0 40px;
  place-items: center;
  border-radius: 9px;
  background: rgb(255 59 48 / 14%);
  color: #ff554b;
  font-size: 1rem;
}

.detalle-reto {
  border-color: #403616;
  background: linear-gradient(100deg, #211d10, #171611);
}

.detalle-reto .detalle-icono {
  background: rgb(234 179 8 / 14%);
  color: #eab308;
}

.detalle-check {
  margin-left: auto;
  color: #22c55e;
}

.resumen-interacciones {
  display: flex;
  justify-content: space-between;
  gap: 12px;
  padding: 14px 0 10px;
  color: #71717a;
  font-size: 0.78rem;
}

.resumen-interacciones button {
  padding: 0;
  border: 0;
  background: none;
  color: inherit;
  font: inherit;
  cursor: pointer;
}

.resumen-interacciones button:hover {
  color: #d4d4d8;
  text-decoration: underline;
}

.corazon-resumen {
  margin-right: 4px;
  color: #ff3b30;
}

.acciones-publicacion {
  display: grid;
  grid-template-columns: repeat(3, minmax(0, 1fr));
  padding: 6px 0;
  border-top: 1px solid #252529;
  border-bottom: 1px solid #252529;
}

.accion-social {
  display: inline-flex;
  min-height: 44px;
  align-items: center;
  justify-content: center;
  gap: 7px;
  border: 0;
  border-radius: 8px;
  background: transparent;
  color: #a1a1aa;
  font: inherit;
  font-size: 0.8rem;
  font-weight: 700;
  cursor: pointer;
  transition: background 160ms ease, color 160ms ease;
}

.accion-social:hover {
  background: #202024;
  color: #ffffff;
}

.accion-me-gusta {
  color: #ff5148;
}

.comentarios {
  display: flex;
  flex-direction: column;
  gap: 13px;
  padding-top: 16px;
}

.comentario {
  display: flex;
  align-items: flex-start;
  gap: 10px;
}

.avatar-comentario {
  width: 34px;
  height: 34px;
  flex-basis: 34px;
  font-size: 0.68rem;
}

.burbuja-comentario {
  min-width: 0;
  padding: 10px 13px;
  border: 1px solid #26262a;
  border-radius: 4px 12px 12px;
  background: #1b1b1f;
}

.burbuja-comentario strong {
  color: #e4e4e7;
  font-size: 0.75rem;
}

.burbuja-comentario p {
  margin: 4px 0 0;
  color: #b4b4bc;
  font-size: 0.82rem;
  line-height: 1.5;
  overflow-wrap: anywhere;
}

.formulario-comentario {
  display: flex;
  align-items: center;
  gap: 8px;
  margin-top: 3px;
}

.formulario-comentario input {
  width: 100%;
  min-width: 0;
  height: 42px;
  padding: 0 14px;
  border: 1px solid #29292e;
  border-radius: 999px;
  outline: none;
  background: #0f0f11;
  color: #ffffff;
  font: inherit;
  font-size: 0.82rem;
}

.formulario-comentario button {
  display: grid;
  width: 42px;
  height: 42px;
  flex: 0 0 42px;
  place-items: center;
  border: 0;
  background: transparent;
  color: #ff3b30;
  font-size: 1.25rem;
  cursor: pointer;
}

.formulario-comentario button:disabled {
  color: #52525b;
  cursor: not-allowed;
}

.columna-lateral {
  display: flex;
  flex-direction: column;
  gap: 16px;
  padding-top: 8px;
}

.tarjeta-lateral {
  padding: 22px;
}

.icono-comunidad,
.reto-icono {
  display: grid;
  width: 42px;
  height: 42px;
  margin: 18px 0 14px;
  place-items: center;
  border-radius: 12px;
  background: rgb(255 59 48 / 14%);
  color: #ff5148;
  font-size: 1.15rem;
}

.tarjeta-lateral h2 {
  margin: 0 0 8px;
  font-size: 1.06rem;
  font-weight: 800;
}

.tarjeta-lateral p {
  margin: 0;
  color: #a1a1aa;
  font-size: 0.84rem;
  line-height: 1.6;
}

.reto-lateral {
  background: linear-gradient(150deg, #19170f, #141417 68%);
}

.reto-icono {
  background: rgb(234 179 8 / 14%);
  color: #eab308;
}

.reto-progreso {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  margin-top: 16px;
  color: #eab308;
  font-size: 0.77rem;
  font-weight: 700;
}

.solo-lectores {
  position: absolute;
  width: 1px;
  height: 1px;
  overflow: hidden;
  clip: rect(0, 0, 0, 0);
  white-space: nowrap;
  clip-path: inset(50%);
}

@media (max-width: 1100px) {
  .contenedor-feed {
    grid-template-columns: minmax(0, 680px);
    max-width: 760px;
  }

  .columna-lateral {
    display: none;
  }
}

@media (max-width: 900px) {
  .barra-main {
    grid-template-columns: minmax(0, 1fr);
  }

  .columna-usuario {
    display: none;
  }
}

@media (max-width: 600px) {
  .contenedor-feed {
    padding: 24px 14px 40px;
  }

  .compositor,
  .publicacion {
    padding: 16px;
  }

  .pie-compositor {
    align-items: flex-end;
  }

  .tipos-publicacion {
    gap: 6px;
    margin-left: 0;
  }

  .detalle-compositor {
    width: 100%;
    margin-left: 0;
  }

  .boton-publicar {
    flex-shrink: 0;
  }

  .accion-social {
    gap: 6px;
    font-size: 0.72rem;
  }
}
</style>
