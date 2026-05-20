# Object.keys() — La Lista de Etiquetas

### ¿Qué es esto? (La analogía) 🏷️
Imagina que tienes una caja que representa a un "Usuario". Por fuera, la caja tiene etiquetas que dicen: "Nombre", "Edad", "Peso". El `Object.keys()` es como despegar todas esas etiquetas y ponerlas en una lista para ver qué información tiene la caja, sin importar lo que haya dentro.

### ¿Cuándo lo uso en ObsidiantFit? 📋
- Cuando quieres saber **qué datos** tiene un ejercicio (¿tiene descripción? ¿tiene imagen?).
- Para recorrer un objeto y mostrar cada título en una tabla.
- Para verificar si un usuario tiene todas las propiedades necesarias antes de guardarlo.

### ¿Cómo se usa? (Paso a paso)
```typescript
const usuario = {
  nombre: "Lucas",
  nivel: "Avanzado",
  racha: 15
};

// Sacamos solo los nombres de las etiquetas
const etiquetas = Object.keys(usuario);

console.log(etiquetas); // ["nombre", "nivel", "racha"]
```

### El Truco del Experto 💡
Es muy útil para saber cuántas cosas hay dentro de un objeto usando `Object.keys(objeto).length`.
