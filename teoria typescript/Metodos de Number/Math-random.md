# Math.random() — El Dado de la Suerte

### ¿Qué es esto? (La analogía) 🎲
Es como tirar un dado gigante que tiene millones de caras. Cada vez que lo tiras, sale un número distinto entre 0 y 1 (por ejemplo, `0.45781`). ¡Nunca sabes qué va a salir!

### ¿Cuándo lo uso en ObsidiantFit? 🎰
- Para dar un **consejo del día aleatorio** al usuario de una lista de 100 consejos.
- Para generar un **código de invitación** único y difícil de adivinar.
- Para elegir un **ejercicio sorpresa** si el usuario no sabe qué entrenar hoy.

### ¿Cómo se usa? (Paso a paso)
```typescript
// Si quieres un número entre 1 y 10:
const azar = Math.floor(Math.random() * 10) + 1;

console.log("Tu número de la suerte es:", azar);
```

### El Truco del Experto 💡
Como `Math.random()` da números muy feos (como `0.123...`), casi siempre se usa junto con `Math.floor()` para convertirlo en un número entero y limpio.
