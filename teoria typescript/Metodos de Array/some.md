# .some() — ¿Hay algún Valiente?

### ¿Qué es esto? (La analogía) ❓
Imagina que preguntas en el gimnasio: "¿Hay alguien aquí que levante más de 200kg?". No te importa quién sea, ni si son muchos. Con que **una sola persona** levante la mano, la respuesta a tu pregunta es "SÍ" (true). Si nadie levanta la mano, es "NO" (false).

### ¿Cuándo lo uso en ObsidiantFit? ✅/❌
Cuando solo necesitas saber si una condición se cumple al menos una vez.
- Saber si **algún ejercicio** de la rutina es de "Alta Intensidad".
- Verificar si el usuario tiene **alguna suscripción** activa.
- Ver si hay **algún error** en los datos del entrenamiento.

### ¿Cómo se usa? (Paso a paso)
```typescript
const series = [
  { reps: 10, completada: true },
  { reps: 10, completada: false },
  { reps: 8, completada: true }
];

// ¿Hay alguna serie que no se completó?
const hayFallos = series.some((serie) => {
  return serie.completada === false;
});

console.log(hayFallos); // true (porque la segunda serie falló)
```

### El Truco del Experto 💡
`.some()` es muy rápido. En cuanto encuentra el primer elemento que cumple la regla, deja de mirar el resto del array.
