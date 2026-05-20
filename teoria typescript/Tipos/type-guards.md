# Type Guards — El Control de Aduana

### ¿Qué es esto? (La analogía) 🕵️‍♂️🛂
Imagina que por una puerta pueden pasar tanto **Atletas** como **Entrenadores**. El Type Guard es como un detector que pregunta: "¿Tú tienes un silbato?". Si tiene silbato, el detector grita: "¡Es un Entrenador!". Si no, dice: "¡Es un Atleta!". Ayuda a TypeScript a saber exactamente con quién está hablando en ese momento.

### ¿Cuándo lo uso en ObsidiantFit? 🔍
- Tienes una lista que mezcla **Ejercicios de Fuerza** y **Ejercicios de Cardio**. Necesitas saber cuál es cuál para calcular las calorías de forma distinta.
- Cuando recibes una respuesta que puede ser un **Objeto con datos** o un **Error**.

### ¿Cómo se usa? (Paso a paso)
```typescript
interface Fuerza { peso: number }
interface Cardio { minutos: number }

function calcularEsfuerzo(ejercicio: Fuerza | Cardio) {
  // 'typeof' o 'in' sirven como detectores
  if ("peso" in ejercicio) {
    // Aquí adentro, TS sabe 100% que es un ejercicio de FUERZA.
    console.log("Levantaste:", ejercicio.peso);
  } else {
    // Aquí adentro, TS sabe 100% que es CARDIO.
    console.log("Corriste:", ejercicio.minutos);
  }
}
```

### El Truco del Experto 💡
Usar `if (typeof valor === "string")` es el Type Guard más simple de todos. ¡Úsalo siempre para evitar errores de "esto es undefined"!
