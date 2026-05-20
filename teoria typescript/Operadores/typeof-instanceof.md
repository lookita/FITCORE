# Typeof e Instanceof — Los Detectives

### ¿Qué es esto? (La analogía) 🕵️‍♂️🔍
- **`typeof`**: Es un detective que mira el "ADN" básico de algo. ¿Es un texto? ¿Es un número? Solo sabe lo básico.
- **`instanceof`**: Es un detective más avanzado. Él no mira el ADN, mira el "Árbol Genealógico". ¿Este objeto nació de la clase `Atleta`? ¿O nació de la clase `Gimnasio`?

### ¿Cuándo lo uso en ObsidiantFit? 🧐
- **`typeof`**: Para revisar si lo que escribió el usuario es un **texto** antes de intentar ponerlo en mayúsculas.
- **`instanceof`**: Cuando recibes un objeto y quieres saber si es un **Usuario Premium** para dejarlo entrar a una zona especial de la app.

### ¿Cómo se usa? (Paso a paso)
```typescript
// Typeof (para lo básico)
console.log(typeof "Lucas"); // "string"
console.log(typeof 100);     // "number"

// Instanceof (para clases)
class Atleta {}
const lucas = new Atleta();

console.log(lucas instanceof Atleta); // true
console.log(lucas instanceof Array);  // false
```

### El Truco del Experto 💡
Usa `typeof` para tipos simples (como `string` o `number`) e `instanceof` para cosas más complejas que tú mismo creaste con `class`.
