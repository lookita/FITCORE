# Genéricos — El Contenedor Universal

### ¿Qué es esto? (La analogía) 📦♻️
Imagina que tienes una caja transportadora. La caja es siempre la misma, pero hoy puede llevar **mancuernas** y mañana puede llevar **toallas**. No necesitas una caja distinta para cada cosa. Un Genérico es una función o tipo que acepta "cualquier cosa" pero mantiene el orden: lo que entra es lo que sale.

### ¿Cuándo lo uso en ObsidiantFit? 🌍
- Cuando creas una función para **traer datos de internet**. A veces traes `Ejercicios`, a veces traes `Usuarios`. El código de la función es el mismo, solo cambia lo que hay dentro.
- Para crear una "Lista de Favoritos" que sirva tanto para ejercicios como para rutinas.

### ¿Cómo se usa? (Paso a paso)
```typescript
// La <T> es como una etiqueta vacía que se llenará después.
function envolverEnCaja<T>(objeto: T): T {
  console.log("Envolviendo...");
  return objeto;
}

const miEjercicio = envolverEnCaja({ nombre: "Sentadilla" }); // T ahora es 'Ejercicio'
const miPeso = envolverEnCaja(80); // T ahora es 'number'
```

### El Truco del Experto 💡
Casi siempre verás la letra `<T>`, pero puedes usar cualquier letra o palabra. Se usa `T` por "Type". Es como el `x` en las matemáticas.
