# Object.entries() — Parejas de Datos

### ¿Qué es esto? (La analogía) 👫
Imagina que vas a un baile y todos están en parejas: cada persona tiene su nombre y su número de documento. El `Object.entries()` te da una lista de esas parejas: `[ ["nombre", "Lucas"], ["edad", 25] ]`. Es la forma más completa de ver la información: la etiqueta y el contenido juntos.

### ¿Cuándo lo uso en ObsidiantFit? 🖇️
- Cuando quieres mostrar en la pantalla una lista tipo: **Nombre: Lucas**, **Racha: 10**.
- Cuando necesitas transformar un objeto en un mapa de datos para enviarlo a otro lugar.
- Para recorrer un objeto y usar tanto el nombre de la propiedad como su valor al mismo tiempo.

### ¿Cómo se usa? (Paso a paso)
```typescript
const estadisticas = {
  fuerza: 80,
  resistencia: 60
};

// Nos da una lista de listas (parejas)
const lista = Object.entries(estadisticas);

console.log(lista); 
// [ ["fuerza", 80], ["resistencia", 60] ]
```

### El Truco del Experto 💡
Es perfecto para usarlo con un `.forEach()` y desestructurar la pareja: `Object.entries(obj).forEach(([clave, valor]) => ...)`. ¡Queda muy limpio!
