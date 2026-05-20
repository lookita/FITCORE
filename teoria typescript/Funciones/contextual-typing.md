# Contextual Typing — El Detective Inteligente

### ¿Qué es esto? (La analogía) 🕵️‍♂️💡
Imagina que ves a alguien con ropa de gimnasio, sudando y levantando una pesa. No hace falta que te diga: "¡Soy un deportista!", tú ya lo sabes por el **contexto**. TypeScript hace lo mismo: si pones una función dentro de un lugar que ya tiene tipo (como un `.map()`), TypeScript adivina los tipos por ti sin que tengas que escribirlos.

### ¿Cuándo lo uso en ObsidiantFit? 🔄
- Cuando usas funciones "callbacks" dentro de métodos de array.
- Cuando asignas una función a una variable que ya tiene un tipo definido.

### ¿Cómo se usa? (Paso a paso)
```typescript
const ejercicios = ["Sentadilla", "Prensa"];

// No hace falta poner (ejercicio: string)
// ¡TypeScript ya sabe que es un string por el contexto del array!
ejercicios.forEach((ejercicio) => {
  console.log(ejercicio.toUpperCase()); 
});
```

### El Truco del Experto 💡
Ahorra mucho tiempo y hace que el código sea más limpio. Pero recuerda: si la función está "suelta" (fuera de contexto), tendrás que decirle los tipos a mano.
