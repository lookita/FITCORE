# Operador Ternario — El Camino Corto

### ¿Qué es esto? (La analogía) 🛣️↔️
Imagina que llegas a un cruce de caminos. Hay un cartel que pregunta: "¿Eres mayor de edad?". Si dices que sí, vas por el camino de la derecha. Si dices que no, vas por la izquierda. Es un `if/else` escrito en una sola línea.

### ¿Cuándo lo uso en ObsidiantFit? ⚡
- Para mostrar un mensaje rápido: si el usuario entrenó hoy, muestra **"¡Genial! ✅"**, si no, muestra **"¡A darle! ❌"**.
- Para elegir un color: si la racha es alta, pon el texto en **Verde**, si es baja, en **Rojo**.

### ¿Cómo se usa? (Paso a paso)
```typescript
const racha = 10;

// condicion ? si_es_verdad : si_es_mentira
const mensaje = racha > 5 ? "¡Eres un Pro! 🔥" : "Sigue así 💪";

console.log(mensaje); // "¡Eres un Pro! 🔥"
```

### El Truco del Experto 💡
No encadenes muchos ternarios uno dentro de otro porque el código se vuelve imposible de leer. Si tienes más de dos opciones, ¡mejor usa un `if/else` normal!
