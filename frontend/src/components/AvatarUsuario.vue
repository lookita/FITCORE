<script setup lang="ts">
import { computed, ref, watch } from 'vue';

const props = withDefaults(defineProps<{
  nombre: string;
  imagenUrl?: string | null;
  tamano?: number;
}>(), {
  imagenUrl: null,
  tamano: 48
});

const imagenError = ref(false);

watch(() => props.imagenUrl, () => {
  imagenError.value = false;
});

const mostrarImagen = computed(() => Boolean(props.imagenUrl?.trim()) && !imagenError.value);
const iniciales = computed(() =>
  props.nombre
    .trim()
    .split(/\s+/)
    .slice(0, 2)
    .map((parte) => parte.charAt(0))
    .join('')
    .toUpperCase()
);
</script>

<template>
  <div
    class="avatar-usuario"
    :style="{ width: `${tamano}px`, height: `${tamano}px` }"
    role="img"
    :aria-label="`Foto de perfil de ${nombre}`"
  >
    <img
      v-if="mostrarImagen"
      :src="imagenUrl || undefined"
      alt=""
      @error="imagenError = true"
    />
    <span v-else>{{ iniciales || 'FC' }}</span>
  </div>
</template>

<style scoped>
.avatar-usuario {
  display: grid;
  flex: 0 0 auto;
  place-items: center;
  overflow: hidden;
  border: 2px solid #ff3b30;
  border-radius: 50%;
  background: linear-gradient(145deg, #29292e, #141417);
  color: #ffffff;
  font-size: 1.15rem;
  font-weight: 800;
  box-shadow: 0 0 20px rgba(255, 59, 48, 0.2);
}

.avatar-usuario img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}
</style>
