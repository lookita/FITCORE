# Año, Mes y Día — El Calendario

### ¿Qué es esto? (La analogía) 📅
Imagina que tienes una foto de tu primer entrenamiento. El objeto `Date` es esa foto, y estas funciones son como una lupa que te permite mirar partes específicas:
- **`getFullYear`**: Mira el año (ej: 2024).
- **`getMonth`**: Mira el mes (pero ¡ojo!, cuenta desde 0).
- **`getDate`**: Mira el número del día (ej: el día 15).

### ¿Cuándo lo uso en ObsidiantFit? 🗓️
- Para mostrar cuándo se hizo un entrenamiento: `"Entrenaste el 10 de Mayo de 2024"`.
- Para agrupar tus estadísticas por **mes** o por **año**.
- Para saber si hoy es tu **cumpleaños** y darte un logro especial.

### ¿Cómo se usa? (Paso a paso)
```typescript
const hoy = new Date();

console.log("Año:", hoy.getFullYear()); // 2024
console.log("Mes:", hoy.getMonth());     // 4 (¡Mayo es 4 porque empieza en 0!)
console.log("Día:", hoy.getDate());      // 10
```

### El Truco del Experto 💡
⚠️ ¡CUIDADO con los meses! Enero es `0`, Febrero es `1`, y así. Si quieres mostrar el número del mes real al usuario, siempre súmale 1: `hoy.getMonth() + 1`.
