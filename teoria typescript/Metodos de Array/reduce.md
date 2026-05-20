# .reduce() — La Suma Total del Entrenamiento

### ¿Qué es esto? (La analogía) 🍎+🍎+🍎
Imagina que tienes una canasta vacía y una fila de cajas con pesas. El `.reduce()` es como un asistente que agarra la canasta, va caja por caja, saca la pesa y la mete en la canasta. Al final, no te da muchas cajas, sino una sola canasta con el peso total de todo lo que había.

### ¿Cuándo lo uso en ObsidiantFit? 🏋️‍♂️
Cuando quieres calcular un resultado único a partir de una lista. Por ejemplo:
- Calcular el **Volumen Total** de kilos levantados en un día.
- Saber cuántas **calorías totales** quemaste en la semana.
- Contar cuántos ejercicios de "Pecho" hiciste en total.

### ¿Cómo se usa? (Paso a paso)
```typescript
const pesosDeSeries = [80, 80, 90, 100]; // Kilos en cada serie

// 'canasta' es donde guardamos la suma (se llama acumulador).
// 'peso' es el valor de la serie actual que estamos mirando.
const volumenTotal = pesosDeSeries.reduce((canasta, peso) => {
  console.log(`Llevo ${canasta}kg y le sumo ${peso}kg`);
  return canasta + peso; 
}, 0); // Este '0' es muy importante: es la canasta empezando vacía.

console.log("Total levantado:", volumenTotal); // 350
```

### El Truco del Experto 💡
Si no pones el `0` al final, `.reduce()` usará el primer elemento del array como valor inicial. ¡Mejor pon siempre el `0` para evitar errores si el array está vacío!
