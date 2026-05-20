# .replace() — El Cambia-Palabras

### ¿Qué es esto? (La analogía) 🔄
Imagina que escribiste "Pecho" en una nota, pero te diste cuenta de que en realidad era "Espalda". El `.replace()` busca la palabra equivocada y la cambia por la correcta, como si usaras un corrector líquido sobre el papel.

### ¿Cuándo lo uso en ObsidiantFit? ✏️
- Tienes una fecha en formato raro `2024/05/10` y quieres cambiar las barras `/` por guiones `-`.
- El usuario escribió "Kgs" y quieres que siempre diga "Kilos".
- Quieres censurar palabras feas en los comentarios de la comunidad.

### ¿Cómo se usa? (Paso a paso)
```typescript
const nota = "Hoy entrené Pecho muy pesado.";

// Buscamos "Pecho" y lo cambiamos por "Pierna"
const notaCorregida = nota.replace("Pecho", "Pierna");

console.log(notaCorregida); // "Hoy entrené Pierna muy pesado."
```

### El Truco del Experto 💡
El `.replace()` normal solo cambia la **primera vez** que encuentra la palabra. Si quieres cambiar todas las veces que aparezca, usa `.replaceAll()`.
