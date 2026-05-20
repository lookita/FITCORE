# Variadic Generics — Genéricos para Tuplas

### ¿Qué es esto? (La analogía) 🚂📦
Imagina un tren donde cada vagón puede llevar algo distinto (un vagón lleva carbón, otro lleva vacas, otro lleva gente). Un "Variadic Generic" te permite crear una lista de tipos (una Tupla) que puede tener cualquier largo y recordar exactamente qué hay en cada vagón.

### ¿Cuándo lo uso en ObsidiantFit? 🛤️
- Para crear una función `mezclar()` que recibe varios tipos distintos y los junta manteniendo el orden y el tipo de cada uno.
- Para manejar funciones que reciben una lista de argumentos de distintos tipos (como un sistema de eventos o plugins).

### ¿Cómo se usa? (Paso a paso)
```typescript
// Usamos los tres puntitos '...' con el genérico
function juntarListas<T extends unknown[], U extends unknown[]>(arr1: T, arr2: U): [...T, ...U] {
  return [...arr1, ...arr2];
}

const mezcla = juntarListas([1, 2], ["Hola", "Chau"]);
// TypeScript sabe que 'mezcla' es [number, number, string, string]
```

### El Truco del Experto 💡
Es un concepto muy avanzado, pero es lo que permite que funciones como `.concat()` o `.bind()` funcionen perfectamente en TypeScript. ¡Es como tener un súper-poder para manejar listas de tipos!
