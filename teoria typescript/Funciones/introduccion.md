# Introducción a Funciones — Las Máquinas del Gym

### ¿Qué es esto? (La analogía) ⚙️🏋️‍♂️
Imagina que vas a una máquina de **Press de Piernas**. Tú te sientas, pones tus piernas (esto es el **input** o entrada) y haces el esfuerzo. La máquina hace su trabajo y te devuelve unas piernas más fuertes (esto es el **output** o salida). Una función es exactamente eso: un bloque de código que recibe algo, hace un trabajo y te devuelve un resultado.

### ¿Cuándo lo uso en ObsidiantFit? 🛠️
- Para calcular el **Volumen Total** de un entrenamiento.
- Para transformar un nombre a **Mayúsculas**.
- Para verificar si un usuario tiene permiso para entrar a una zona VIP.

### Anatomía de una Función:

```typescript
// function nombre(parametro: tipo): retorno
function calcularFuerza(masa: number, aceleracion: number): number {
  return masa * aceleracion;
}

const resultado = calcularFuerza(100, 9.8); 
// masa es 100, aceleracion es 9.8, resultado es 980
```

### El Truco del Experto 💡
Intenta que tus funciones hagan **una sola cosa**. Si tienes una función que "calcula el peso, saluda al usuario y guarda en la base de datos", es mejor dividirla en tres máquinas pequeñas. ¡Es mucho más fácil de arreglar si algo falla!
