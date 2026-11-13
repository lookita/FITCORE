<template>
  <!-- Promo Banner -->
  <Transition name="deslizar-abajo">
    <div v-if="showBanner" class="banner-promocional">
      <span>🎉 ¡Promoción limitada! Regístrate hoy y obtén 30 días gratis + 200 XP de bienvenida</span>
      <button class="cerrar-banner" @click="showBanner = false">
        <i class="bi bi-x-lg"></i>
      </button>
    </div>
  </Transition>

  <div class="envoltura-portada">

    <!-- ===== HERO ===== -->
    <section class="seccion-portada">
      <div class="interior-portada">
        <h1 class="titulo-portada">Convierte tu cuerpo en<br />tu mayor logro</h1>
        <div class="botones-portada">
          <button class="boton-primario" @click="router.push('/registro')">Comenzar gratis</button>
          <button class="boton-borde" @click="router.push('/login')">Iniciar sesión</button>
        </div>
        <div class="estadisticas-portada">
          <div class="elemento-estadistica">
            <span class="numero-estadistica">+12.000</span>
            <span class="etiqueta-estadistica">Atletas</span>
          </div>
          <div class="separador-estadistica"></div>
          <div class="elemento-estadistica">
            <span class="numero-estadistica">98%</span>
            <span class="etiqueta-estadistica">Retención</span>
          </div>
          <div class="separador-estadistica"></div>
          <div class="elemento-estadistica">
            <span class="numero-estadistica">10</span>
            <span class="etiqueta-estadistica">Niveles XP</span>
          </div>
        </div>
      </div>
    </section>

    <!-- ===== FEATURES ===== -->
    <section class="seccion-caracteristicas">
      <div class="encabezado-seccion">
        <h2 class="titulo-seccion">Todo lo que necesitás para <span class="rojo">rendir al máximo</span></h2>
        <p class="subtitulo-seccion">Una plataforma completa diseñada para atletas que buscan resultados reales.</p>
      </div>
      <div class="grilla-caracteristicas">
        <div v-for="feature in features" :key="feature.icon" class="tarjeta-caracteristica">
          <div class="icono-caracteristica">
            <i :class="`bi ${feature.icon}`"></i>
          </div>
          <h3 class="titulo-caracteristica">{{ feature.title }}</h3>
          <p class="descripcion-caracteristica">{{ feature.desc }}</p>
        </div>
      </div>
    </section>

    <!-- ===== STATS ANIMADAS ===== -->
    <section class="seccion-estadisticas">
      <div class="encabezado-seccion">
        <h2 class="titulo-seccion">Números que <span class="rojo">hablan por sí solos</span></h2>
      </div>
      <div class="grilla-estadisticas-animadas">
        <div v-for="stat in animatedStats" :key="stat.label" class="tarjeta-estadistica-animada">
          <span class="numero-estadistica-animada">{{ stat.display }}</span>
          <span class="etiqueta-estadistica-animada">{{ stat.label }}</span>
        </div>
      </div>
    </section>

    <!-- ===== PRICING ===== -->
    <section class="seccion-precios">
      <div class="encabezado-seccion">
        <h2 class="titulo-seccion">Elegí tu <span class="rojo">plan</span></h2>
        <p class="subtitulo-seccion">Sin compromisos. Empezá gratis y escalá cuando estés listo.</p>
      </div>
      <div class="grilla-precios">
        <div v-for="plan in plans" :key="plan.name" class="tarjeta-precio" :class="{ popular: plan.popular }">
          <div v-if="plan.popular" class="insignia-popular">MÁS POPULAR</div>
          <div class="nombre-plan">{{ plan.name }}</div>
          <div class="precio-plan">{{ plan.price }}</div>
          <div class="periodo-plan">{{ plan.period }}</div>
          <ul class="caracteristicas-plan">
            <li v-for="f in plan.features" :key="f">
              <i class="bi bi-check-circle-fill"></i> {{ f }}
            </li>
          </ul>
          <button class="boton-primario boton-plan" @click="router.push('/registro')">
            {{ plan.popular ? 'Comenzar ahora' : 'Elegir plan' }}
          </button>
        </div>
      </div>
    </section>

    <!-- ===== TESTIMONIOS ===== -->
    <section class="seccion-testimonios">
      <div class="encabezado-seccion">
        <h2 class="titulo-seccion">Lo que dicen nuestros <span class="rojo">atletas</span></h2>
      </div>
      <div class="deslizador-testimonios">
        <button class="flecha-deslizador izquierda" @click="prevTestimonial">
          <i class="bi bi-chevron-left"></i>
        </button>
        <div v-if="currentTestimonial" class="tarjeta-testimonio">
          <div class="avatar-testimonio">{{ currentTestimonial.initials }}</div>
          <p class="texto-testimonio">"{{ currentTestimonial.text }}"</p>
          <div class="autor-testimonio">{{ currentTestimonial.name }}</div>
          <div class="rango-testimonio">{{ currentTestimonial.rank }}</div>
        </div>
        <button class="flecha-deslizador derecha" @click="nextTestimonial">
          <i class="bi bi-chevron-right"></i>
        </button>
      </div>
      <div class="puntos-deslizador">
        <span
          v-for="(_, idx) in testimonials"
          :key="idx"
          class="punto"
          :class="{ activo: idx === testimonialIndex }"
          @click="testimonialIndex = idx"
        ></span>
      </div>
    </section>

    <!-- ===== CTA FINAL ===== -->
    <section class="seccion-cta">
      <h2 class="titulo-cta">¿Listo para transformarte?</h2>
      <p class="subtitulo-cta">Únete a más de 12.000 atletas que ya están construyendo su mejor versión.</p>
      <button class="boton-primario boton-grande" @click="router.push('/registro')">
        Crear mi cuenta gratis
      </button>
    </section>

    <!-- ===== FOOTER ===== -->
    <footer class="pie-sitio">
      <div class="interior-pie">
        <div class="marca-pie">
          <span class="logo-pie">FIT<span class="rojo">CORE</span></span>
          <p class="eslogan-pie">Tu rendimiento. Tu legado.</p>
          <div class="redes-pie">
            <a href="#" class="icono-social"><i class="bi bi-instagram"></i></a>
            <a href="#" class="icono-social"><i class="bi bi-twitter-x"></i></a>
            <a href="#" class="icono-social"><i class="bi bi-youtube"></i></a>
          </div>
        </div>
        <div class="enlaces-pie">
          <a href="#">Inicio</a>
          <a href="#" @click.prevent="router.push('/login')">Login</a>
          <a href="#" @click.prevent="router.push('/registro')">Registro</a>
          <a href="#">Rutinas</a>
        </div>
      </div>
      <div class="copia-pie">© 2026 FitCore. Todos los derechos reservados.</div>
    </footer>

  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()

// ── Promo Banner ──────────────────────────────────────────────────────────────
const showBanner = ref(false)

onMounted(() => {
  showBanner.value = true
  setTimeout(() => {
    showBanner.value = false
  }, 8000)

  // Animated stats
  startCounters()
})

// ── Features ──────────────────────────────────────────────────────────────────
const features = [
  {
    icon: 'bi-lightning-charge-fill',
    title: 'Sistema de Niveles XP',
    desc: 'Gana experiencia con cada entrenamiento completado. Sube de nivel y desbloquea rutinas exclusivas.',
  },
  {
    icon: 'bi-graph-up-arrow',
    title: 'Analytics en Tiempo Real',
    desc: 'Visualizá tu progreso, racha de días, calorías y rendimiento semanal en dashboards interactivos.',
  },
  {
    icon: 'bi-shield-fill-check',
    title: 'Rutinas Validadas',
    desc: 'Programas diseñados por expertos en calistenia, funcional y fuerza. Adaptados a tu nivel.',
  },
  {
    icon: 'bi-trophy-fill',
    title: 'Rankings y Logros',
    desc: 'Compite con otros atletas, desbloquea medallas y aparece en el ranking mensual de FitCore.',
  },
  {
    icon: 'bi-phone-fill',
    title: '100% Responsivo',
    desc: 'Entrena desde donde estés. La plataforma se adapta a tu celular, tablet y computadora.',
  },
  {
    icon: 'bi-lock-fill',
    title: 'Tu Progreso Seguro',
    desc: 'Datos cifrados, sesiones protegidas con tokens seguros y almacenamiento encriptado.',
  },
]

// ── Animated Stats ────────────────────────────────────────────────────────────
interface AnimStat {
  label: string
  target: number
  current: number
  suffix: string
  decimals: number
  display: string
}

const animatedStats = ref<AnimStat[]>([
  { label: 'Atletas activos',              target: 12847,  current: 0, suffix: '',  decimals: 0, display: '0' },
  { label: 'Tasa de retención',            target: 98.3,   current: 0, suffix: '%', decimals: 1, display: '0%' },
  { label: 'XP generados este mes',        target: 3.2,    current: 0, suffix: 'M', decimals: 1, display: '0M' },
  { label: 'Entrenamientos completados',   target: 47000,  current: 0, suffix: '',  decimals: 0, display: '0' },
])

function startCounters() {
  animatedStats.value.forEach((stat) => {
    const duration = 2000
    const steps = 60
    const increment = stat.target / steps
    let step = 0
    const interval = setInterval(() => {
      step++
      const val = Math.min(increment * step, stat.target)
      stat.current = val
      const formatted =
        stat.decimals > 0
          ? val.toFixed(stat.decimals) + stat.suffix
          : Math.round(val).toLocaleString('es-AR') + stat.suffix
      stat.display = formatted
      if (step >= steps) clearInterval(interval)
    }, duration / steps)
  })
}

// ── Plans ─────────────────────────────────────────────────────────────────────
const plans = [
  {
    name: 'Iniciado',
    price: '0 ARS',
    period: '/ mes',
    popular: false,
    features: ['3 rutinas básicas', 'Hasta nivel 3', 'Perfil de atleta'],
  },
  {
    name: 'Atleta ⭐',
    price: '$4.999 ARS',
    period: '/ mes',
    popular: true,
    features: ['Rutinas ilimitadas', 'Hasta nivel 10', 'Analytics completo', 'Ranking global'],
  },
  {
    name: 'Élite',
    price: '$9.999 ARS',
    period: '/ mes',
    popular: false,
    features: ['Todo del plan Atleta', 'Coaching personalizado', 'Rutinas exclusivas', 'Badge especial'],
  },
]

// ── Testimonials ──────────────────────────────────────────────────────────────
const testimonials = [
  {
    initials: 'LM',
    name: 'Lucas M.',
    rank: 'Nivel 8 – Avanzado',
    text: 'En 3 meses pasé de no poder hacer una flexión a completar muscle-ups. El sistema de XP me mantuvo motivado cada día.',
  },
  {
    initials: 'AP',
    name: 'Ana P.',
    rank: 'Nivel 5 – Intermedio',
    text: 'Los analytics me ayudaron a identificar que entrenaba demasiado los mismos grupos. Ahora mi progreso es constante.',
  },
  {
    initials: 'MG',
    name: 'Matías G.',
    rank: 'Nivel 10 – Leyenda',
    text: 'Llegué al nivel máximo en 8 meses. FitCore es lo más parecido a tener un entrenador personal con IA.',
  },
]

const testimonialIndex = ref(0)
const currentTestimonial = computed(() => testimonials[testimonialIndex.value] ?? testimonials[0])

function nextTestimonial() {
  testimonialIndex.value = (testimonialIndex.value + 1) % testimonials.length
}
function prevTestimonial() {
  testimonialIndex.value = (testimonialIndex.value - 1 + testimonials.length) % testimonials.length
}
</script>

<style scoped>
/* ══════════════════════════════════════════════════════════════════════════════
   BASE
══════════════════════════════════════════════════════════════════════════════ */
* {
  box-sizing: border-box;
  margin: 0;
  padding: 0;
}

.envoltura-portada {
  font-family: 'Rubik', sans-serif;
  background-color: #0c0c0e;
  color: #fff;
  overflow-x: hidden;
}

.rojo { color: #ff3b30; }

.encabezado-seccion {
  text-align: center;
  margin-bottom: 3rem;
}

.titulo-seccion {
  font-size: 2.2rem;
  font-weight: 700;
  margin-bottom: 0.75rem;
  line-height: 1.2;
}

.subtitulo-seccion {
  color: #71717a;
  font-size: 1.05rem;
  max-width: 560px;
  margin: 0 auto;
}

/* ── Botones ─────────────────────────────────────────────────────────────── */
.boton-primario {
  background: #ff3b30;
  color: #fff;
  border: none;
  border-radius: 10px;
  padding: 0.85rem 2rem;
  font-family: 'Rubik', sans-serif;
  font-size: 1rem;
  font-weight: 600;
  cursor: pointer;
  transition: background 0.2s, transform 0.2s, box-shadow 0.2s;
}
.boton-primario:hover {
  background: #e6342a;
  transform: translateY(-2px);
  box-shadow: 0 6px 24px rgba(255, 59, 48, 0.35);
}

.boton-borde {
  background: transparent;
  color: #fff;
  border: 2px solid rgba(255, 255, 255, 0.55);
  border-radius: 10px;
  padding: 0.85rem 2rem;
  font-family: 'Rubik', sans-serif;
  font-size: 1rem;
  font-weight: 600;
  cursor: pointer;
  transition: border-color 0.2s, background 0.2s, transform 0.2s;
}
.boton-borde:hover {
  border-color: #fff;
  background: rgba(255, 255, 255, 0.08);
  transform: translateY(-2px);
}

.boton-grande {
  padding: 1.1rem 2.8rem;
  font-size: 1.15rem;
  border-radius: 12px;
}

/* ══════════════════════════════════════════════════════════════════════════════
   PROMO BANNER
══════════════════════════════════════════════════════════════════════════════ */
.banner-promocional {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  z-index: 1000;
  background: rgba(150, 20, 15, 0.95);
  border-bottom: 2px solid #ff3b30;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 1rem;
  padding: 0.75rem 1.5rem;
  font-size: 0.92rem;
  font-weight: 500;
  text-align: center;
  backdrop-filter: blur(8px);
}

.cerrar-banner {
  background: transparent;
  border: none;
  color: #fff;
  cursor: pointer;
  font-size: 1rem;
  opacity: 0.7;
  transition: opacity 0.2s;
  margin-left: 0.5rem;
  line-height: 1;
}
.cerrar-banner:hover { opacity: 1; }

/* Slide-down transition */
.deslizar-abajo-enter-active,
.deslizar-abajo-leave-active {
  transition: transform 0.4s ease, opacity 0.4s ease;
}
.deslizar-abajo-enter-from,
.deslizar-abajo-leave-to {
  transform: translateY(-100%);
  opacity: 0;
}

/* ══════════════════════════════════════════════════════════════════════════════
   HERO
══════════════════════════════════════════════════════════════════════════════ */
.seccion-portada {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background:
    radial-gradient(ellipse 70% 55% at 50% 60%, rgba(255, 59, 48, 0.10) 0%, transparent 70%),
    #0c0c0e;
  padding: 6rem 1.5rem 4rem;
  text-align: center;
}

.interior-portada {
  max-width: 780px;
  animation: aparecerArriba 0.8s ease both;
}



.titulo-portada {
  font-size: 4.5rem;
  font-weight: 800;
  line-height: 1.08;
  letter-spacing: -0.02em;
  margin-bottom: 1.5rem;
}


.botones-portada {
  display: flex;
  gap: 1rem;
  justify-content: center;
  flex-wrap: wrap;
  margin-bottom: 3.5rem;
}

.estadisticas-portada {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 2rem;
  flex-wrap: wrap;
}

.elemento-estadistica {
  display: flex;
  flex-direction: column;
  align-items: center;
}

.numero-estadistica {
  font-size: 1.6rem;
  font-weight: 800;
  color: #fff;
}

.etiqueta-estadistica {
  font-size: 0.8rem;
  color: #71717a;
  text-transform: uppercase;
  letter-spacing: 0.05em;
}

.separador-estadistica {
  width: 1px;
  height: 36px;
  background: #27272a;
}

/* ══════════════════════════════════════════════════════════════════════════════
   FEATURES
══════════════════════════════════════════════════════════════════════════════ */
.seccion-caracteristicas {
  padding: 5rem 1.5rem;
  background: #0c0c0e;
  max-width: 1100px;
  margin: 0 auto;
}

.grilla-caracteristicas {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 1.5rem;
}

.tarjeta-caracteristica {
  background: #18181b;
  border: 1px solid #27272a;
  border-radius: 16px;
  padding: 2rem 1.5rem;
  transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
}

.tarjeta-caracteristica:hover {
  transform: translateY(-4px);
  box-shadow: 0 12px 40px rgba(0, 0, 0, 0.45);
  border-color: rgba(255, 59, 48, 0.4);
}

.icono-caracteristica {
  font-size: 2rem;
  color: #ff3b30;
  margin-bottom: 1rem;
}

.titulo-caracteristica {
  font-size: 1rem;
  font-weight: 700;
  margin-bottom: 0.5rem;
}

.descripcion-caracteristica {
  font-size: 0.9rem;
  color: #71717a;
  line-height: 1.6;
}

/* ══════════════════════════════════════════════════════════════════════════════
   ANIMATED STATS
══════════════════════════════════════════════════════════════════════════════ */
.seccion-estadisticas {
  background: #111113;
  padding: 5rem 1.5rem;
}

.grilla-estadisticas-animadas {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 1.5rem;
  max-width: 900px;
  margin: 0 auto;
}

.tarjeta-estadistica-animada {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.5rem;
  background: #18181b;
  border: 1px solid #27272a;
  border-radius: 14px;
  padding: 2rem 1rem;
  text-align: center;
  transition: transform 0.25s, box-shadow 0.25s;
}

.tarjeta-estadistica-animada:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
}

.numero-estadistica-animada {
  font-size: 2.2rem;
  font-weight: 800;
  color: #ff3b30;
}

.etiqueta-estadistica-animada {
  font-size: 0.82rem;
  color: #71717a;
  text-align: center;
}

/* ══════════════════════════════════════════════════════════════════════════════
   PRICING
══════════════════════════════════════════════════════════════════════════════ */
.seccion-precios {
  padding: 5rem 1.5rem;
  background: #0c0c0e;
  max-width: 1000px;
  margin: 0 auto;
}

.grilla-precios {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 1.5rem;
  align-items: start;
}

.tarjeta-precio {
  background: #18181b;
  border: 1px solid #27272a;
  border-radius: 18px;
  padding: 2.2rem 1.8rem;
  position: relative;
  transition: transform 0.25s, box-shadow 0.25s;
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.tarjeta-precio:hover {
  transform: translateY(-4px);
  box-shadow: 0 12px 40px rgba(0, 0, 0, 0.5);
}

.tarjeta-precio.popular {
  border: 2px solid #ff3b30;
  background: #1a0f0e;
  box-shadow: 0 0 40px rgba(255, 59, 48, 0.15);
}

.insignia-popular {
  position: absolute;
  top: -14px;
  left: 50%;
  transform: translateX(-50%);
  background: #ff3b30;
  color: #fff;
  font-size: 0.7rem;
  font-weight: 800;
  letter-spacing: 0.1em;
  padding: 0.25rem 0.9rem;
  border-radius: 999px;
  white-space: nowrap;
}

.nombre-plan {
  font-size: 1.05rem;
  font-weight: 700;
  color: #fff;
}

.precio-plan {
  font-size: 1.9rem;
  font-weight: 800;
  color: #ff3b30;
}

.periodo-plan {
  font-size: 0.85rem;
  color: #71717a;
  margin-top: -0.5rem;
}

.caracteristicas-plan {
  list-style: none;
  display: flex;
  flex-direction: column;
  gap: 0.55rem;
  margin: 0.5rem 0 1rem;
}

.caracteristicas-plan li {
  font-size: 0.88rem;
  color: #a1a1aa;
  display: flex;
  align-items: center;
  gap: 0.4rem;
}

.caracteristicas-plan li .bi {
  color: #ff3b30;
  font-size: 0.85rem;
}

.boton-plan {
  width: 100%;
  margin-top: auto;
}

/* ══════════════════════════════════════════════════════════════════════════════
   TESTIMONIALS
══════════════════════════════════════════════════════════════════════════════ */
.seccion-testimonios {
  background: #111113;
  padding: 5rem 1.5rem;
}

.deslizador-testimonios {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 1.5rem;
  max-width: 700px;
  margin: 0 auto 1.5rem;
}

.tarjeta-testimonio {
  background: #18181b;
  border: 1px solid #27272a;
  border-radius: 18px;
  padding: 2.5rem 2rem;
  text-align: center;
  flex: 1;
  min-height: 220px;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.75rem;
}

.avatar-testimonio {
  width: 60px;
  height: 60px;
  border-radius: 50%;
  background: #ff3b30;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.2rem;
  font-weight: 800;
  color: #fff;
}

.texto-testimonio {
  font-size: 0.97rem;
  color: #a1a1aa;
  line-height: 1.7;
  font-style: italic;
}

.autor-testimonio {
  font-weight: 700;
  font-size: 0.95rem;
}

.rango-testimonio {
  font-size: 0.8rem;
  color: #ff3b30;
  font-weight: 600;
}

.flecha-deslizador {
  background: #18181b;
  border: 1px solid #27272a;
  color: #fff;
  width: 44px;
  height: 44px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  font-size: 1.1rem;
  flex-shrink: 0;
  transition: background 0.2s, border-color 0.2s;
}
.flecha-deslizador:hover {
  background: #27272a;
  border-color: #ff3b30;
}

.puntos-deslizador {
  display: flex;
  justify-content: center;
  gap: 0.5rem;
}

.punto {
  width: 8px;
  height: 8px;
  border-radius: 50%;
  background: #27272a;
  cursor: pointer;
  transition: background 0.2s, transform 0.2s;
}
.punto.activo {
  background: #ff3b30;
  transform: scale(1.3);
}

/* ══════════════════════════════════════════════════════════════════════════════
   CTA FINAL
══════════════════════════════════════════════════════════════════════════════ */
.seccion-cta {
  padding: 6rem 1.5rem;
  text-align: center;
  background:
    radial-gradient(ellipse 80% 60% at 50% 50%, rgba(255, 59, 48, 0.18) 0%, transparent 70%),
    linear-gradient(180deg, #1a0808 0%, #0c0c0e 100%);
}

.titulo-cta {
  font-size: 2.8rem;
  font-weight: 800;
  margin-bottom: 1rem;
}

.subtitulo-cta {
  color: #71717a;
  font-size: 1.05rem;
  margin-bottom: 2.5rem;
  max-width: 500px;
  margin-left: auto;
  margin-right: auto;
}

/* ══════════════════════════════════════════════════════════════════════════════
   FOOTER
══════════════════════════════════════════════════════════════════════════════ */
.pie-sitio {
  background: #0c0c0e;
  border-top: 1px solid #18181b;
  padding: 3rem 1.5rem 1.5rem;
}

.interior-pie {
  max-width: 900px;
  margin: 0 auto;
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  gap: 2rem;
  flex-wrap: wrap;
  margin-bottom: 2rem;
}

.logo-pie {
  font-size: 1.5rem;
  font-weight: 800;
  letter-spacing: 0.04em;
}

.eslogan-pie {
  color: #71717a;
  font-size: 0.85rem;
  margin-top: 0.35rem;
  margin-bottom: 1rem;
}

.redes-pie {
  display: flex;
  gap: 0.75rem;
}

.icono-social {
  color: #71717a;
  font-size: 1.2rem;
  text-decoration: none;
  transition: color 0.2s;
}
.icono-social:hover { color: #ff3b30; }

.enlaces-pie {
  display: flex;
  flex-direction: column;
  gap: 0.6rem;
}

.enlaces-pie a {
  color: #71717a;
  text-decoration: none;
  font-size: 0.9rem;
  transition: color 0.2s;
}
.enlaces-pie a:hover { color: #fff; }

.copia-pie {
  text-align: center;
  color: #3f3f46;
  font-size: 0.8rem;
  border-top: 1px solid #18181b;
  padding-top: 1.25rem;
}

/* ══════════════════════════════════════════════════════════════════════════════
   ANIMATIONS
══════════════════════════════════════════════════════════════════════════════ */
@keyframes aparecerArriba {
  from { opacity: 0; transform: translateY(30px); }
  to   { opacity: 1; transform: translateY(0); }
}

@keyframes pulso {
  0%, 100% { box-shadow: 0 0 0 0 rgba(255, 59, 48, 0.25); }
  50%       { box-shadow: 0 0 0 8px rgba(255, 59, 48, 0); }
}

/* ══════════════════════════════════════════════════════════════════════════════
   RESPONSIVE
══════════════════════════════════════════════════════════════════════════════ */
@media (max-width: 768px) {
  .titulo-portada {
    font-size: 2.6rem;
  }

  .grilla-caracteristicas {
    grid-template-columns: 1fr;
  }

  .grilla-estadisticas-animadas {
    grid-template-columns: repeat(2, 1fr);
  }

  .grilla-precios {
    grid-template-columns: 1fr;
  }

  .deslizador-testimonios {
    gap: 0.75rem;
  }

  .interior-pie {
    flex-direction: column;
    align-items: center;
    text-align: center;
  }

  .enlaces-pie {
    align-items: center;
    flex-direction: row;
    flex-wrap: wrap;
    justify-content: center;
  }

  .estadisticas-portada {
    gap: 1rem;
  }

  .separador-estadistica {
    display: none;
  }

  .titulo-cta {
    font-size: 2rem;
  }
}
</style>
