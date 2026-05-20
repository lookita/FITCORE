# Math.max y Math.min — El Récord y el Mínimo

### ¿Qué es esto? (La analogía) 🏆🏅
Imagina una competencia de levantamiento de pesas. 
- **`Math.max`**: Es el juez que grita el nombre del que levantó más peso de todos. ¡Es tu **PR** (Personal Record)!
- **`Math.min`**: Es el juez que busca quién levantó menos, quizás para saber cuál es el peso más liviano que tienes en tu gimnasio.

### ¿Cuándo lo uso en ObsidiantFit? 📈
- **Max**: Para encontrar tu **Récord Histórico** en Sentadilla de una lista de todos tus entrenamientos.
- **Min**: Para ver cuál fue el entrenamiento más corto que hiciste en el mes.
- Para poner límites: "El peso no puede ser menor a 0" usando `Math.max(0, pesoElegido)`.

### ¿Cómo se usa? (Paso a paso)
```typescript
const misPesos = [60, 85, 100, 95];

// Buscamos el más grande
const record = Math.max(...misPesos); // Los '...' son para pasar toda la lista
console.log("¡Tu récord es:", record, "kg!"); // 100

// Buscamos el más pequeño
const minimo = Math.min(10, 5, 20); 
console.log("El mínimo es:", minimo); // 5
```

### El Truco del Experto 💡
Recuerda usar los tres puntitos `...` si tienes una lista (array). Si no los pones, `Math.max` se confunde y no sabe qué hacer con la lista.
