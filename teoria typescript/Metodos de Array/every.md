# .every() — ¿Son todos de Élite?

### ¿Qué es esto? (La analogía) 👮‍♂️
A diferencia de `.some()`, el `.every()` es mucho más exigente. Imagina que un sargento dice: "¿Todos trajeron su uniforme?". Si **tan solo uno** no lo trajo, el sargento dice "NO" (false). Solo dirá "SÍ" (true) si el 100% de la fila cumple la regla.

### ¿Cuándo lo uso en ObsidiantFit? 🏆
Cuando necesitas que se cumpla una regla en absolutamente todos los elementos.
- Verificar si **todas las series** de un ejercicio fueron completadas.
- Saber si **todos los días** de la semana el usuario registró su peso.
- Asegurarse de que **todos los ejercicios** de una rutina tienen asignado un grupo muscular.

### ¿Cómo se usa? (Paso a paso)
```typescript
const miSemana = [true, true, true, true, false, true, true]; // ¿Entrené cada día?

const semanaPerfecta = miSemana.every((dia) => {
  return dia === true; // ¿Es verdad que entrené este día?
});

console.log(semanaPerfecta); // false (porque el quinto día no entrenaste)
```

### El Truco del Experto 💡
Si usas `.every()` en un array vacío `[]`, ¡siempre te dirá `true`! Ten cuidado con ese pequeño detalle.
