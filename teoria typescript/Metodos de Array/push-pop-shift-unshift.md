# Push, Pop, Shift y Unshift — El Rack de Mancuernas

### ¿Qué es esto? (La analogía) 🏋️‍♂️
Imagina un rack donde guardas las mancuernas en fila.
- **`.push()`**: Pones una mancuerna nueva al **final** de la fila.
- **`.pop()`**: Sacas la **última** mancuerna de la fila.
- **`.unshift()`**: Empujas a todos y pones una mancuerna al **principio** de la fila.
- **`.shift()`**: Sacas la **primera** mancuerna de la fila.

### ¿Cuándo lo uso en ObsidiantFit? 📝
Cuando quieres agregar o quitar elementos de una lista que ya tienes.
- El usuario hizo una serie nueva → **`.push(serie)`**.
- El usuario se equivocó y quiere borrar la última serie que anotó → **`.pop()`**.
- Quieres poner un mensaje de "Bienvenida" al principio de las notificaciones → **`.unshift(mensaje)`**.

### ¿Cómo se usa? (Paso a paso)
```typescript
const miRutina = ["Sentadilla", "Prensa"];

// Agregar al final
miRutina.push("Estocadas"); // ["Sentadilla", "Prensa", "Estocadas"]

// Quitar el último
miRutina.pop(); // ["Sentadilla", "Prensa"]

// Agregar al principio
miRutina.unshift("Calentamiento"); // ["Calentamiento", "Sentadilla", "Prensa"]

// Quitar el primero
miRutina.shift(); // ["Sentadilla", "Prensa"]
```

### El Truco del Experto 💡
`.push()` y `.unshift()` te dicen el nuevo tamaño de la lista. `.pop()` y `.shift()` te devuelven el elemento que acabas de sacar. ¡Útil si quieres mostrar qué borraste!
