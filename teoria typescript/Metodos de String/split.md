# .split() — Romper en Pedazos

### ¿Qué es esto? (La analogía) 🪓
Imagina que tienes una ristra de chorizos unidos por un hilo. El `.split()` es la tijera que corta el hilo y te deja los chorizos por separado en una lista. Tú le dices dónde tiene que cortar (por ejemplo, donde vea una coma o un espacio).

### ¿Cuándo lo uso en ObsidiantFit? 🧩
- Tienes una lista de músculos en un solo texto: `"Pecho, Espalda, Pierna"` y los quieres separar en una lista para ponerles un ícono a cada uno.
- Tienes el nombre completo de un usuario `"Lucas Mercado"` y quieres solo el primer nombre. Cortas por el espacio y te quedas con el primer pedazo.

### ¿Cómo se usa? (Paso a paso)
```typescript
const musculos = "Bíceps,Tríceps,Hombro";

// Cortamos donde veamos una coma ","
const listaMusculos = musculos.split(",");

console.log(listaMusculos); // ["Bíceps", "Tríceps", "Hombro"] (¡Ahora es una lista!)
```

### El Truco del Experto 💡
Si usas `.split("")` (con las comillas vacías), ¡romperás el texto letra por letra! Útil si quieres hacer algún efecto visual raro con el texto.
