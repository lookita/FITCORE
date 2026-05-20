# Tipado de Funciones — El Contrato de Trabajo

### ¿Qué es esto? (La analogía) 📝
Imagina que contratas a un entrenador personal. En el contrato dice: "Yo te doy **dinero** (entrada) y tú me das **una rutina** (salida)". Si tú le das un zapato en vez de dinero, el contrato se rompe. El tipado es ese contrato: obliga a la función a recibir lo que prometió y a devolver lo que prometió.

### ¿Cuándo lo uso en ObsidiantFit? 🤝
- Para asegurar que una función que calcula el IMC reciba **números** y no texto.
- Para que TypeScript te avise si te olvidaste de devolver el objeto de "Usuario" al final de una función de registro.
- Para que sea imposible pasarle una "Manzana" a una función que espera un "Ejercicio".

### ¿Cómo se usa? (Paso a paso)
```typescript
// (peso: number) -> Entrada: solo acepta números.
// : string        -> Salida: promete devolver texto.
function evaluarPeso(peso: number): string {
  if (peso > 100) {
    return "¡Estás levantando mucho!";
  }
  return "Buen ritmo.";
}

const resultado = evaluarPeso(80); // Correcto
// const error = evaluarPeso("ochenta"); // ❌ TypeScript te grita: "¡Eso no es un número!"
```

### El Truco del Experto 💡
Si una función no devuelve nada (solo hace algo, como un `console.log`), el tipo de salida es `void` (vacío). Es como un entrenador que solo te grita ánimos pero no te entrega ningún papel.
