# UpperCase y LowerCase — Mayúsculas y Minúsculas

### ¿Qué es esto? (La analogía) 🔡
Imagina que tienes un sello que convierte cualquier palabra en letras gigantes (mayúsculas) o letras pequeñitas (minúsculas). No importa cómo las escribas tú, el sello las deja todas iguales.

### ¿Cuándo lo uso en ObsidiantFit? ✍️
- Cuando el usuario escribe el nombre de un ejercicio (ej: "press banca") y tú quieres que en el título se vea imponente: **"PRESS BANCA"**.
- Cuando quieres comparar dos palabras y no te importa si una tiene mayúsculas y la otra no. Si conviertes ambas a minúsculas, ¡serán idénticas!

### ¿Cómo se usa? (Paso a paso)
```typescript
const ejercicio = "Sentadilla con Barra";

console.log(ejercicio.toUpperCase()); // "SENTADILLA CON BARRA" 📢 (Gritando)
console.log(ejercicio.toLowerCase()); // "sentadilla con barra" 🤫 (Susurrando)
```

### El Truco del Experto 💡
Úsalo siempre que busques algo en una lista. Ejemplo: Si el usuario busca "PECHO", conviértelo a minúsculas ("pecho") antes de buscarlo en tu base de datos para que siempre lo encuentre.
