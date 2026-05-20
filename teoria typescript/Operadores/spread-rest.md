# Spread y Rest — Los Tres Puntitos Mágicos

### ¿Qué es esto? (La analogía) 🪄
Imagina que tienes una bolsa de caramelos (`...`).
- **Spread (Esparcir)**: Abres la bolsa y desparramas todos los caramelos sobre una mesa para mezclarlos con otros nuevos.
- **Rest (Juntar)**: Agarras un montón de caramelos sueltos de la mesa y los metes todos juntos dentro de una bolsa nueva.

### ¿Cuándo lo uso en ObsidiantFit? 🍬
- **Spread**: Para crear una **copia de un entrenamiento** pero cambiándole solo la fecha. O para juntar dos listas de ejercicios en una sola.
- **Rest**: Para recibir una **lista infinita de pesos** en una función y guardarlos todos en un array.

### ¿Cómo se usa? (Paso a paso)
```typescript
// --- SPREAD (Esparcir / Copiar) ---
const baseEjercicio = { nombre: "Sentadilla", series: 3 };
const ejercicioPersonalizado = { ...baseEjercicio, reps: 10 };
// Resultado: { nombre: "Sentadilla", series: 3, reps: 10 }

// --- REST (Juntar / Recoger) ---
function registrarPesos(...pesos: number[]) {
  console.log("Pesos recibidos:", pesos);
}
registrarPesos(10, 20, 30); // pesos se convierte en [10, 20, 30]
```

### El Truco del Experto 💡
El Spread `...` es la forma más moderna y segura de copiar objetos y arrays en JavaScript sin romper los originales. ¡Úsalo siempre!
