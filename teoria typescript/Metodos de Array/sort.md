# .sort() — El Organizador del Gimnasio

### ¿Qué es esto? (La analogía) 📊
Imagina que tienes las pesas todas desordenadas por el suelo. El `.sort()` es como un ayudante que las agarra y las pone en el rack en orden: de la más liviana a la más pesada (o al revés).

### ¿Cuándo lo uso en ObsidiantFit? ↕️
Cuando quieres ordenar una lista.
- Mostrar los ejercicios **por orden alfabético**.
- Ver tus entrenamientos del **más reciente al más antiguo**.
- Listar los usuarios del que tiene **más puntos** al que tiene menos.

### ¿Cómo se usa? (Paso a paso)
```typescript
const pesos = [100, 20, 50, 80];

// Para números, usamos esta "fórmula" mágica (a - b):
const ordenados = pesos.sort((a, b) => a - b); 

console.log(ordenados); // [20, 50, 80, 100] (de menor a mayor)

// Si quieres de mayor a menor, usas (b - a):
const deMayorAMenor = pesos.sort((a, b) => b - a);
```

### El Truco del Experto 💡
⚠️ ¡CUIDADO! `.sort()` **cambia tu lista original**. Si no quieres que se desordene la lista vieja, haz una copia antes usando `[...miLista].sort()`.
