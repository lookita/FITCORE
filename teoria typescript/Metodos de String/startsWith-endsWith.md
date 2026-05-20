# StartsWith y EndsWith — Inicio y Fin

### ¿Qué es esto? (La analogía) 🚩🏁
- **`startsWith`**: ¿Este texto empieza con esta bandera?
- **`endsWith`**: ¿Este texto termina con esta meta?
Es como revisar si un ejercicio empieza con "Press" o si una nota termina con un emoji de "💪".

### ¿Cuándo lo uso en ObsidiantFit? 📏
- Quieres organizar ejercicios por tipo: ¿Empieza con "Cinta"? Entonces es Cardio.
- Quieres revisar si el usuario escribió una unidad de medida al final: ¿Termina con "kg" o con "lb"?
- Filtrar nombres de usuarios que empiecen con una letra específica.

### ¿Cómo se usa? (Paso a paso)
```typescript
const ejercicio = "Press de Banca Inclinado";

console.log(ejercicio.startsWith("Press")); // true
console.log(ejercicio.endsWith("Inclinado")); // true
console.log(ejercicio.endsWith("Plano")); // false
```

### El Truco del Experto 💡
Son geniales para validar cosas rápidas sin tener que leer todo el texto. Por ejemplo, ver si un link de imagen empieza con `https://`.
