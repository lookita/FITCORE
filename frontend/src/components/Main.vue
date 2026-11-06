<template>
  <!-- Promo Banner -->
  <Transition name="slide-down">
    <div v-if="showBanner" class="promo-banner">
      <span>🎉 ¡Promoción limitada! Regístrate hoy y obtén 30 días gratis + 200 XP de bienvenida</span>
      <button class="banner-close" @click="showBanner = false">
        <i class="bi bi-x-lg"></i>
      </button>
    </div>
  </Transition>

  <div class="landing-wrapper">

    <!-- ===== HERO ===== -->
    <section class="hero-section">
      <div class="hero-inner">
        <div class="hero-badge">
          <i class="bi bi-fire"></i> NUEVA PLATAFORMA 4.0 — FITCORE
        </div>
        <h1 class="hero-title">Convierte tu cuerpo en<br />tu mayor logro</h1>
        <p class="hero-subtitle">
          Entrenamiento con estructura, niveles de experiencia y analytics en tiempo real.<br />
          Tu evolución empieza hoy.
        </p>
        <div class="hero-buttons">
          <button class="btn-primary" @click="router.push('/registro')">Comenzar gratis</button>
          <button class="btn-outline" @click="router.push('/login')">Iniciar sesión</button>
        </div>
        <div class="hero-stats">
          <div class="stat-item">
            <span class="stat-number">+12.000</span>
            <span class="stat-label">Atletas</span>
          </div>
          <div class="stat-divider"></div>
          <div class="stat-item">
            <span class="stat-number">98%</span>
            <span class="stat-label">Retención</span>
          </div>
          <div class="stat-divider"></div>
          <div class="stat-item">
            <span class="stat-number">10</span>
            <span class="stat-label">Niveles XP</span>
          </div>
        </div>
      </div>
    </section>

    <!-- ===== FEATURES ===== -->
    <section class="features-section">
      <div class="section-header">
        <h2 class="section-title">Todo lo que necesitás para <span class="red">rendir al máximo</span></h2>
        <p class="section-subtitle">Una plataforma completa diseñada para atletas que buscan resultados reales.</p>
      </div>
      <div class="features-grid">
        <div v-for="feature in features" :key="feature.icon" class="feature-card">
          <div class="feature-icon">
            <i :class="`bi ${feature.icon}`"></i>
          </div>
          <h3 class="feature-title">{{ feature.title }}</h3>
          <p class="feature-desc">{{ feature.desc }}</p>
        </div>
      </div>
    </section>

    <!-- ===== STATS ANIMADAS ===== -->
    <section class="stats-section">
      <div class="section-header">
        <h2 class="section-title">Números que <span class="red">hablan por sí solos</span></h2>
      </div>
      <div class="animated-stats-grid">
        <div v-for="stat in animatedStats" :key="stat.label" class="anim-stat-card">
          <span class="anim-stat-number">{{ stat.display }}</span>
          <span class="anim-stat-label">{{ stat.label }}</span>
        </div>
      </div>
    </section>

    <!-- ===== PRICING ===== -->
    <section class="pricing-section">
      <div class="section-header">
        <h2 class="section-title">Elegí tu <span class="red">plan</span></h2>
        <p class="section-subtitle">Sin compromisos. Empezá gratis y escalá cuando estés listo.</p>
      </div>
      <div class="pricing-grid">
        <div v-for="plan in plans" :key="plan.name" class="pricing-card" :class="{ popular: plan.popular }">
          <div v-if="plan.popular" class="popular-badge">MÁS POPULAR</div>
          <div class="plan-name">{{ plan.name }}</div>
          <div class="plan-price">{{ plan.price }}</div>
          <div class="plan-period">{{ plan.period }}</div>
          <ul class="plan-features">
            <li v-for="f in plan.features" :key="f">
              <i class="bi bi-check-circle-fill"></i> {{ f }}
            </li>
          </ul>
          <button class="btn-primary plan-btn" @click="router.push('/registro')">
            {{ plan.popular ? 'Comenzar ahora' : 'Elegir plan' }}
          </button>
        </div>
      </div>
    </section>

    <!-- ===== TESTIMONIOS ===== -->
    <section class="testimonials-section">
      <div class="section-header">
        <h2 class="section-title">Lo que dicen nuestros <span class="red">atletas</span></h2>
      </div>
      <div class="testimonial-slider">
        <button class="slider-arrow left" @click="prevTestimonial">
          <i class="bi bi-chevron-left"></i>
        </button>
        <div class="testimonial-card">
          <div class="testimonial-avatar">{{ currentTestimonial.initials }}</div>
          <p class="testimonial-text">"{{ currentTestimonial.text }}"</p>
          <div class="testimonial-author">{{ currentTestimonial.name }}</div>
          <div class="testimonial-rank">{{ currentTestimonial.rank }}</div>
        </div>
        <button class="slider-arrow right" @click="nextTestimonial">
          <i class="bi bi-chevron-right"></i>
        </button>
      </div>
      <div class="slider-dots">
        <span
          v-for="(_, idx) in testimonials"
          :key="idx"
          class="dot"
          :class="{ active: idx === testimonialIndex }"
          @click="testimonialIndex = idx"
        ></span>
      </div>
    </section>

    <!-- ===== CTA FINAL ===== -->
    <section class="cta-section">
      <h2 class="cta-title">¿Listo para transformarte?</h2>
      <p class="cta-subtitle">Únete a más de 12.000 atletas que ya están construyendo su mejor versión.</p>
      <button class="btn-primary btn-large" @click="router.push('/registro')">
        Crear mi cuenta gratis
      </button>
    </section>

    <!-- ===== FOOTER ===== -->
    <footer class="site-footer">
      <div class="footer-inner">
        <div class="footer-brand">
          <span class="footer-logo">FIT<span class="red">CORE</span></span>
          <p class="footer-slogan">Tu rendimiento. Tu legado.</p>
          <div class="footer-socials">
            <a href="#" class="social-icon"><i class="bi bi-instagram"></i></a>
            <a href="#" class="social-icon"><i class="bi bi-twitter-x"></i></a>
            <a href="#" class="social-icon"><i class="bi bi-youtube"></i></a>
          </div>
        </div>
        <div class="footer-links">
          <a href="#">Inicio</a>
          <a href="#" @click.prevent="router.push('/login')">Login</a>
          <a href="#" @click.prevent="router.push('/registro')">Registro</a>
          <a href="#">Rutinas</a>
        </div>
      </div>
      <div class="footer-copy">© 2026 FitCore. Todos los derechos reservados.</div>
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
  animatedStats.value.forEach((stat, i) => {
    const duration = 2000
    const steps = 60
    const increment = stat.target / steps
    let step = 0
    const interval = setInterval(() => {
      step++
      const val = Math.min(increment * step, stat.target)
      animatedStats.value[i].current = val
      const formatted =
        stat.decimals > 0
          ? val.toFixed(stat.decimals) + stat.suffix
          : Math.round(val).toLocaleString('es-AR') + stat.suffix
      animatedStats.value[i].display = formatted
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
const currentTestimonial = computed(() => testimonials[testimonialIndex.value])

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

.landing-wrapper {
  font-family: 'Rubik', sans-serif;
  background-color: #0c0c0e;
  color: #fff;
  overflow-x: hidden;
}

.red { color: #ff3b30; }

.section-header {
  text-align: center;
  margin-bottom: 3rem;
}

.section-title {
  font-size: 2.2rem;
  font-weight: 700;
  margin-bottom: 0.75rem;
  line-height: 1.2;
}

.section-subtitle {
  color: #71717a;
  font-size: 1.05rem;
  max-width: 560px;
  margin: 0 auto;
}

/* ── Buttons ─────────────────────────────────────────────────────────────── */
.btn-primary {
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
.btn-primary:hover {
  background: #e6342a;
  transform: translateY(-2px);
  box-shadow: 0 6px 24px rgba(255, 59, 48, 0.35);
}

.btn-outline {
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
.btn-outline:hover {
  border-color: #fff;
  background: rgba(255, 255, 255, 0.08);
  transform: translateY(-2px);
}

.btn-large {
  padding: 1.1rem 2.8rem;
  font-size: 1.15rem;
  border-radius: 12px;
}

/* ══════════════════════════════════════════════════════════════════════════════
   PROMO BANNER
══════════════════════════════════════════════════════════════════════════════ */
.promo-banner {
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

.banner-close {
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
.banner-close:hover { opacity: 1; }

/* Slide-down transition */
.slide-down-enter-active,
.slide-down-leave-active {
  transition: transform 0.4s ease, opacity 0.4s ease;
}
.slide-down-enter-from,
.slide-down-leave-to {
  transform: translateY(-100%);
  opacity: 0;
}

/* ══════════════════════════════════════════════════════════════════════════════
   HERO
══════════════════════════════════════════════════════════════════════════════ */
.hero-section {
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

.hero-inner {
  max-width: 780px;
  animation: fadeInUp 0.8s ease both;
}

.hero-badge {
  display: inline-flex;
  align-items: center;
  gap: 0.45rem;
  background: rgba(255, 59, 48, 0.12);
  border: 1px solid rgba(255, 59, 48, 0.35);
  color: #ff3b30;
  font-size: 0.78rem;
  font-weight: 700;
  letter-spacing: 0.08em;
  text-transform: uppercase;
  padding: 0.4rem 1rem;
  border-radius: 999px;
  margin-bottom: 2rem;
  animation: pulse 2.5s ease-in-out infinite;
}

.hero-title {
  font-size: 4.5rem;
  font-weight: 800;
  line-height: 1.08;
  letter-spacing: -0.02em;
  margin-bottom: 1.5rem;
}

.hero-subtitle {
  font-size: 1.15rem;
  color: #71717a;
  line-height: 1.7;
  max-width: 620px;
  margin: 0 auto 2.5rem;
}

.hero-buttons {
  display: flex;
  gap: 1rem;
  justify-content: center;
  flex-wrap: wrap;
  margin-bottom: 3.5rem;
}

.hero-stats {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 2rem;
  flex-wrap: wrap;
}

.stat-item {
  display: flex;
  flex-direction: column;
  align-items: center;
}

.stat-number {
  font-size: 1.6rem;
  font-weight: 800;
  color: #fff;
}

.stat-label {
  font-size: 0.8rem;
  color: #71717a;
  text-transform: uppercase;
  letter-spacing: 0.05em;
}

.stat-divider {
  width: 1px;
  height: 36px;
  background: #27272a;
}

/* ══════════════════════════════════════════════════════════════════════════════
   FEATURES
══════════════════════════════════════════════════════════════════════════════ */
.features-section {
  padding: 5rem 1.5rem;
  background: #0c0c0e;
  max-width: 1100px;
  margin: 0 auto;
}

.features-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 1.5rem;
}

.feature-card {
  background: #18181b;
  border: 1px solid #27272a;
  border-radius: 16px;
  padding: 2rem 1.5rem;
  transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
}

.feature-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 12px 40px rgba(0, 0, 0, 0.45);
  border-color: rgba(255, 59, 48, 0.4);
}

.feature-icon {
  font-size: 2rem;
  color: #ff3b30;
  margin-bottom: 1rem;
}

.feature-title {
  font-size: 1rem;
  font-weight: 700;
  margin-bottom: 0.5rem;
}

.feature-desc {
  font-size: 0.9rem;
  color: #71717a;
  line-height: 1.6;
}

/* ══════════════════════════════════════════════════════════════════════════════
   ANIMATED STATS
══════════════════════════════════════════════════════════════════════════════ */
.stats-section {
  background: #111113;
  padding: 5rem 1.5rem;
}

.animated-stats-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 1.5rem;
  max-width: 900px;
  margin: 0 auto;
}

.anim-stat-card {
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

.anim-stat-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
}

.anim-stat-number {
  font-size: 2.2rem;
  font-weight: 800;
  color: #ff3b30;
}

.anim-stat-label {
  font-size: 0.82rem;
  color: #71717a;
  text-align: center;
}

/* ══════════════════════════════════════════════════════════════════════════════
   PRICING
══════════════════════════════════════════════════════════════════════════════ */
.pricing-section {
  padding: 5rem 1.5rem;
  background: #0c0c0e;
  max-width: 1000px;
  margin: 0 auto;
}

.pricing-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 1.5rem;
  align-items: start;
}

.pricing-card {
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

.pricing-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 12px 40px rgba(0, 0, 0, 0.5);
}

.pricing-card.popular {
  border: 2px solid #ff3b30;
  background: #1a0f0e;
  box-shadow: 0 0 40px rgba(255, 59, 48, 0.15);
}

.popular-badge {
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

.plan-name {
  font-size: 1.05rem;
  font-weight: 700;
  color: #fff;
}

.plan-price {
  font-size: 1.9rem;
  font-weight: 800;
  color: #ff3b30;
}

.plan-period {
  font-size: 0.85rem;
  color: #71717a;
  margin-top: -0.5rem;
}

.plan-features {
  list-style: none;
  display: flex;
  flex-direction: column;
  gap: 0.55rem;
  margin: 0.5rem 0 1rem;
}

.plan-features li {
  font-size: 0.88rem;
  color: #a1a1aa;
  display: flex;
  align-items: center;
  gap: 0.4rem;
}

.plan-features li .bi {
  color: #ff3b30;
  font-size: 0.85rem;
}

.plan-btn {
  width: 100%;
  margin-top: auto;
}

/* ══════════════════════════════════════════════════════════════════════════════
   TESTIMONIALS
══════════════════════════════════════════════════════════════════════════════ */
.testimonials-section {
  background: #111113;
  padding: 5rem 1.5rem;
}

.testimonial-slider {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 1.5rem;
  max-width: 700px;
  margin: 0 auto 1.5rem;
}

.testimonial-card {
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

.testimonial-avatar {
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

.testimonial-text {
  font-size: 0.97rem;
  color: #a1a1aa;
  line-height: 1.7;
  font-style: italic;
}

.testimonial-author {
  font-weight: 700;
  font-size: 0.95rem;
}

.testimonial-rank {
  font-size: 0.8rem;
  color: #ff3b30;
  font-weight: 600;
}

.slider-arrow {
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
.slider-arrow:hover {
  background: #27272a;
  border-color: #ff3b30;
}

.slider-dots {
  display: flex;
  justify-content: center;
  gap: 0.5rem;
}

.dot {
  width: 8px;
  height: 8px;
  border-radius: 50%;
  background: #27272a;
  cursor: pointer;
  transition: background 0.2s, transform 0.2s;
}
.dot.active {
  background: #ff3b30;
  transform: scale(1.3);
}

/* ══════════════════════════════════════════════════════════════════════════════
   CTA FINAL
══════════════════════════════════════════════════════════════════════════════ */
.cta-section {
  padding: 6rem 1.5rem;
  text-align: center;
  background:
    radial-gradient(ellipse 80% 60% at 50% 50%, rgba(255, 59, 48, 0.18) 0%, transparent 70%),
    linear-gradient(180deg, #1a0808 0%, #0c0c0e 100%);
}

.cta-title {
  font-size: 2.8rem;
  font-weight: 800;
  margin-bottom: 1rem;
}

.cta-subtitle {
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
.site-footer {
  background: #0c0c0e;
  border-top: 1px solid #18181b;
  padding: 3rem 1.5rem 1.5rem;
}

.footer-inner {
  max-width: 900px;
  margin: 0 auto;
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  gap: 2rem;
  flex-wrap: wrap;
  margin-bottom: 2rem;
}

.footer-logo {
  font-size: 1.5rem;
  font-weight: 800;
  letter-spacing: 0.04em;
}

.footer-slogan {
  color: #71717a;
  font-size: 0.85rem;
  margin-top: 0.35rem;
  margin-bottom: 1rem;
}

.footer-socials {
  display: flex;
  gap: 0.75rem;
}

.social-icon {
  color: #71717a;
  font-size: 1.2rem;
  text-decoration: none;
  transition: color 0.2s;
}
.social-icon:hover { color: #ff3b30; }

.footer-links {
  display: flex;
  flex-direction: column;
  gap: 0.6rem;
}

.footer-links a {
  color: #71717a;
  text-decoration: none;
  font-size: 0.9rem;
  transition: color 0.2s;
}
.footer-links a:hover { color: #fff; }

.footer-copy {
  text-align: center;
  color: #3f3f46;
  font-size: 0.8rem;
  border-top: 1px solid #18181b;
  padding-top: 1.25rem;
}

/* ══════════════════════════════════════════════════════════════════════════════
   ANIMATIONS
══════════════════════════════════════════════════════════════════════════════ */
@keyframes fadeInUp {
  from { opacity: 0; transform: translateY(30px); }
  to   { opacity: 1; transform: translateY(0); }
}

@keyframes pulse {
  0%, 100% { box-shadow: 0 0 0 0 rgba(255, 59, 48, 0.25); }
  50%       { box-shadow: 0 0 0 8px rgba(255, 59, 48, 0); }
}

/* ══════════════════════════════════════════════════════════════════════════════
   RESPONSIVE
══════════════════════════════════════════════════════════════════════════════ */
@media (max-width: 768px) {
  .hero-title {
    font-size: 2.6rem;
  }

  .features-grid {
    grid-template-columns: 1fr;
  }

  .animated-stats-grid {
    grid-template-columns: repeat(2, 1fr);
  }

  .pricing-grid {
    grid-template-columns: 1fr;
  }

  .testimonial-slider {
    gap: 0.75rem;
  }

  .footer-inner {
    flex-direction: column;
    align-items: center;
    text-align: center;
  }

  .footer-links {
    align-items: center;
    flex-direction: row;
    flex-wrap: wrap;
    justify-content: center;
  }

  .hero-stats {
    gap: 1rem;
  }

  .stat-divider {
    display: none;
  }

  .cta-title {
    font-size: 2rem;
  }
}
</style>
