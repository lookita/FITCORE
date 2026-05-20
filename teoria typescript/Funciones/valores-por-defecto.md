# Valores por Defecto — El Plan B

### ¿Qué es esto? (La analogía) 🍔
Imagina que vas a un restaurante y pides el "Menú del Día". Si no dices qué quieres de tomar, el mozo te trae **agua** por defecto. El valor por defecto es ese "Plan B" que se activa automáticamente si tú no eliges nada.

### ¿Cuándo lo uso en ObsidiantFit? 💧
- Si un usuario no elige un idioma, ponle **"Español"** por defecto.
- Si no pone cuántas series quiere hacer, ponle **3 series** por defecto.
- Si no elige una dificultad, ponle **"Intermedio"**.

### ¿Cómo se usa? (Paso a paso)
```typescript
// Si no pasas 'series', valdrá '3' automáticamente.
function crearRutina(nombre: string, series: number = 3) {
  return `Rutina ${nombre} con ${series} series.`;
}

console.log(crearRutina("Pecho")); // "Rutina Pecho con 3 series."
console.log(crearRutina("Pierna", 5)); // "Rutina Pierna con 5 series."
```

### El Truco del Experto 💡
Los valores por defecto hacen que tu código sea más "inteligente" y fácil de usar, porque el usuario (o tú mismo) tiene que escribir menos cosas para que funcione.
