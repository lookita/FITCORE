# .map() — El Traductor de Datos

### ¿Qué es esto? (La analogía) ⚙️
Imagina que tienes una lista de nombres de ejercicios en minúsculas y feos. El `.map()` es como una cinta transportadora de una fábrica: pones un ejercicio en un lado, la máquina le hace algo (como ponerlo en mayúsculas) y sale del otro lado transformado. ¡Al final tienes una lista nueva con todo arreglado!

### ¿Cuándo lo uso en ObsidiantFit? 🔄
Cuando quieres cambiar el formato de una lista sin romper la original.
- Tienes una lista de pesos en **Kilos** y los quieres pasar a **Libras**.
- Tienes una lista de objetos `Usuario` y solo quieres una lista de sus `nombres`.
- Quieres agregarle el texto " - Completado" a cada ejercicio de tu rutina.

### ¿Cómo se usa? (Paso a paso)
```typescript
const ejercicios = ["sentadilla", "press banca", "peso muerto"];

// .map() crea una lista NUEVA. No toca la original.
const listaBonita = ejercicios.map((nombre) => {
  return nombre.toUpperCase() + " ✅"; // Lo ponemos en mayúsculas y le damos un check.
});

console.log(listaBonita); 
// ["SENTADILLA ✅", "PRESS BANCA ✅", "PESO MUERTO ✅"]
```

### El Truco del Experto 💡
Recuerda que `.map()` siempre te devuelve un array del mismo tamaño que el original. Si entran 3 ejercicios, salen 3 ejercicios transformados.
