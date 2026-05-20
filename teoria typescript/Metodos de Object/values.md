# Object.values() — El Contenido de la Caja

### ¿Qué es esto? (La analogía) 📦
A diferencia de las etiquetas (`keys`), el `Object.values()` abre la caja y saca **lo que hay dentro**. Si la etiqueta dice "Nombre", él saca "Lucas". Si dice "Peso", él saca "80kg". Te da una lista con todos los tesoros que encontró dentro.

### ¿Cuándo lo uso en ObsidiantFit? 💎
- Cuando no te importa el nombre del dato, solo quieres los valores. Por ejemplo, tienes un objeto con los pesos de cada serie `{ serie1: 80, serie2: 85 }` y quieres una lista `[80, 85]` para sumarlos con `.reduce()`.
- Para buscar si un valor específico existe dentro de cualquier parte de un objeto.

### ¿Cómo se usa? (Paso a paso)
```typescript
const entrenamiento = {
  lunes: "Pecho",
  martes: "Pierna",
  miercoles: "Descanso"
};

// Sacamos los valores reales
const dias = Object.values(entrenamiento);

console.log(dias); // ["Pecho", "Pierna", "Descanso"]
```

### El Truco del Experto 💡
Úsalo junto con `.includes()` para saber si un usuario tiene un valor prohibido o especial en cualquier parte de su perfil.
