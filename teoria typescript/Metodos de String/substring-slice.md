# Substring y Slice — Sacar un Trozo de Texto

### ¿Qué es esto? (La analogía) 🍕
Imagina que tienes una pizza entera (tu texto) y solo quieres comerte una porción (un pedazo del texto). Tú le dices: "Quiero desde la porción número 2 hasta la número 5". El `.slice()` te da ese trozo y deja la pizza original como estaba.

### ¿Cuándo lo uso en ObsidiantFit? ✂️
- Tienes una descripción de un ejercicio muy larga y solo quieres mostrar los primeros **50 caracteres** para que no ocupe toda la pantalla.
- Tienes una fecha como `2024-05-10` y quieres sacar solo el año (los primeros 4 números).

### ¿Cómo se usa? (Paso a paso)
```typescript
const descripcion = "Este ejercicio es excelente para el pectoral mayor...";

// Queremos solo las primeras 14 letras
const resumen = descripcion.slice(0, 14);

console.log(resumen); // "Este ejercicio"
```

### El Truco del Experto 💡
¿Cuál es la diferencia entre `slice` y `substring`? Son casi iguales, pero `slice` es más moderno y te permite usar números negativos para empezar a contar desde el final (ej: `-3` te da las últimas 3 letras). ¡Usa `slice` y no te compliques!
