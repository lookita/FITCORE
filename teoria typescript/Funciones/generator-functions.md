# Generator Functions — El Dispensador de Ejercicios

### ¿Qué es esto? (La analogía) 🍬🎰
Imagina un dispensador de caramelos o una máquina de chicles. Tú no sacas todos los chicles de una vez; giras la palanca y sale **uno**. Cuando quieras otro, vuelves a girar la palanca. Una Función Generadora es igual: puede pausarse y devolver un valor, y quedarse esperando hasta que tú le pidas el siguiente. Se marcan con un asterisco `function*`.

### ¿Cuándo lo uso en ObsidiantFit? 🔄
- Para generar una **secuencia infinita** de números de serie.
- Para ir entregando ejercicios de una lista uno por uno, sin cargar toda la lista en memoria si es muy gigante.
- Para crear flujos de pasos (Step-by-step) donde el usuario decide cuándo avanzar al siguiente.

### ¿Cómo se usa? (Paso a paso)
```typescript
// El asterisco * dice que es un Generador
function* contadorDeSeries() {
  let serie = 1;
  while (true) {
    yield `Serie número ${serie}`; // Se pausa aquí y entrega el valor
    serie++;
  }
}

const miEntrenamiento = contadorDeSeries();

console.log(miEntrenamiento.next().value); // "Serie número 1"
console.log(miEntrenamiento.next().value); // "Serie número 2" (¡Se acordaba de dónde estaba!)
```

### El Truco del Experto 💡
El tipo de retorno de estas funciones es `IterableIterator<T>`. Son ideales para la "Evaluación Lazy" (perezosa), que significa: **no hagas el trabajo hasta que de verdad te lo pidan**.
