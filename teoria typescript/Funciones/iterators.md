# Iterators — El Camino de Piedras

### ¿Qué es esto? (La analogía) 🛤️🦶
Imagina un camino de piedras que cruza un río. Un **Iterable** es el camino entero. Un **Iterator** es tu pie que sabe cuál es la "siguiente" piedra. El iterador es el encargado de decirte: "¿Hay más piedras?" y "Dame la que sigue". 

### ¿Cuándo lo uso en ObsidiantFit? 🚶‍♂️
- Cuando usas un bucle `for...of` para recorrer tus ejercicios.
- Cuando usas el spread `...` para copiar una lista.
- Para crear tus propias listas personalizadas que se puedan recorrer paso a paso.

### ¿Cómo se usa? (Paso a paso)
Casi siempre usamos iteradores que ya vienen hechos (como los de los Arrays), pero puedes usarlos manualmente:

```typescript
const ejercicios = ["Prensa", "Sentadilla"];

// Creamos el iterador
const iterador = ejercicios[Symbol.iterator]();

console.log(iterador.next()); // { value: "Prensa", done: false }
console.log(iterador.next()); // { value: "Sentadilla", done: false }
console.log(iterador.next()); // { value: undefined, done: true } (¡Terminamos!)
```

### El Truco del Experto 💡
Cualquier cosa que se pueda usar con un `for...of` es un **Iterable**. Si quieres que tu propia clase (ej: `MiRutina`) se pueda usar así, ¡tienes que implementarle un iterador!
