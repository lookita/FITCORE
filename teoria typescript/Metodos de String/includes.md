# .includes() — ¿Está esa palabra ahí?

### ¿Qué es esto? (La analogía) 🕵️‍♂️
Es como leer un libro buscando si aparece tu nombre. No importa en qué parte esté, si al principio, al final o en el medio. Si la palabra está "escondida" dentro del texto, el `.includes()` te dirá que sí (true).

### ¿Cuándo lo uso en ObsidiantFit? 🔍
- El usuario está buscando un ejercicio y escribe "Press". Tú usas `.includes("Press")` para mostrarle "Press Banca", "Press Militar", "Press Inclinado", etc.
- Saber si una nota de entrenamiento contiene la palabra clave **"#PR"** (Personal Record).

### ¿Cómo se usa? (Paso a paso)
```typescript
const nombreEjercicio = "Sentadilla con Barra Olímpica";

console.log(nombreEjercicio.includes("Barra")); // true
console.log(nombreEjercicio.includes("Mancuerna")); // false
```

### El Truco del Experto 💡
Recuerda que es sensible a las mayúsculas. "Barra" no es lo mismo que "barra". ¡Mejor convierte todo a minúsculas antes de preguntar con `.includes()`!
