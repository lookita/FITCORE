# .toString() — De Número a Texto

### ¿Qué es esto? (La analogía) 🏷️
Imagina que tienes un billete de 100 (un número con el que puedes comprar cosas) y le sacas una fotocopia (un papel que dice "100"). Con la fotocopia no puedes comprar nada, pero la puedes pegar en una pared o mandarla por mail. El `.toString()` convierte ese "billete" real en un "papel" con el número escrito.

### ¿Cuándo lo uso en ObsidiantFit? ✉️
- Cuando quieres juntar un número con un texto para mostrarlo: `"Tu peso es " + peso.toString() + " kg"`.
- Cuando necesitas mandar un ID numérico a una función que solo acepta texto.
- Para mostrar números grandes de forma más clara en la pantalla.

### ¿Cómo se usa? (Paso a paso)
```typescript
const reps = 12;

const mensaje = "Hiciste " + reps.toString() + " repeticiones.";

console.log(mensaje); // "Hiciste 12 repeticiones."
```

### El Truco del Experto 💡
En JavaScript moderno, a veces no hace falta usarlo si usas las comillas mágicas (backticks): `` `Hiciste ${reps} repeticiones` `` lo hace automáticamente por ti. ¡Es mucho más cómodo!
