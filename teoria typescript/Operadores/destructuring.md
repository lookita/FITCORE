# Destructuring — El Extractor de Tesoros

### ¿Qué es esto? (La analogía) ⛏️💎
Imagina que te regalan una caja de herramientas gigante, pero tú solo necesitas el **martillo** y el **destornillador**. En vez de cargar con toda la caja pesada, "metes la mano" y sacas solo lo que vas a usar. Eso es la desestructuración: sacar pedacitos de un objeto o lista y darles su propio nombre.

### ¿Cuándo lo uso en ObsidiantFit? 📦
- Tienes un objeto `Usuario` con 50 datos, pero solo quieres usar el `nombre` y la `foto` en la cabecera.
- Tienes una lista con tu **Récord Máximo** y tu **Récord Mínimo** `[150, 40]` y quieres guardarlos en variables llamadas `max` y `min`.

### ¿Cómo se usa? (Paso a paso)
```typescript
const entrenamiento = {
  ejercicio: "Press Banca",
  kilos: 100,
  fecha: "2024-05-10"
};

// Extraemos solo lo que necesitamos
const { ejercicio, kilos } = entrenamiento;

console.log(`Hoy hiciste ${ejercicio} con ${kilos}kg`);
```

### El Truco del Experto 💡
También funciona con listas: `const [primero, segundo] = ["Bíceps", "Tríceps"];`. ¡Es la forma más rápida de crear variables a partir de datos complejos!
