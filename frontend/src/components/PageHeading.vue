<script setup lang="ts">
defineProps<{ eyebrow: string; titulo: string; descripcion: string }>();
</script>

<template>
  <div class="page-heading">
    <p class="eyebrow">{{ eyebrow }}</p>
    <h1>{{ titulo }}</h1>
    <p class="description">{{ descripcion }}</p>
  </div>
</template>

<style scoped>
.page-heading { max-width: 670px; }
.eyebrow {
  margin: 0 0 12px;
  color: var(--fit-accent);
  font-size: 10px;
  font-weight: 800;
  letter-spacing: 0.14em;
  text-transform: uppercase;
}
h1 {
  margin: 0;
  font-size: clamp(31px, 4vw, 46px);
  line-height: 1.06;
  letter-spacing: -0.055em;
}
.description {
  margin: 14px 0 0;
  color: var(--fit-muted);
  font-size: 14px;
  line-height: 1.65;
}
</style>
