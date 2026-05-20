# Redondeos: Round, Floor y Ceil

### ¿Qué es esto? (La analogía) 🪜
Imagina que estás en una escalera mecánica:
- **`Math.round` (El Justo)**: Si estás más cerca del escalón de arriba, subes. Si estás más cerca del de abajo, bajas. (Redondeo normal).
- **`Math.floor` (El Suelo)**: No importa dónde estés, siempre te tira al suelo del escalón de abajo.
- **`Math.ceil` (El Techo)**: No importa dónde estés, siempre te empuja al techo del escalón de arriba.

### ¿Cuándo lo uso en ObsidiantFit? 📏
- **Round**: Para el peso corporal. Si pesas 75.8, pon que pesas 76.
- **Floor**: Para las repeticiones. Si calculaste que puedes hacer 10.9 repeticiones, ¡en realidad solo completaste 10! Las repeticiones no se cuentan si no terminan.
- **Ceil**: Para los discos de pesas. Si necesitas 22.1 kg de peso, vas a tener que poner discos para llegar a 23 kg porque no existen discos de 0.1 kg.

### ¿Cómo se usa? (Paso a paso)
```typescript
const numero = 10.6;

console.log(Math.round(numero)); // 11 (está cerca del 11)
console.log(Math.floor(numero)); // 10 (tira para abajo)
console.log(Math.ceil(numero));  // 11 (tira para arriba)
```

### El Truco del Experto 💡
`Floor` es el más usado cuando trabajas con listas o índices, porque siempre quieres quedarte con la parte entera del número.
