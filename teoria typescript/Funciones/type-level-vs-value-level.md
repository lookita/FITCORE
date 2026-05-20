# Type-Level vs Value-Level — Los Dos Mundos

### ¿Qué es esto? (La analogía) 🌌👻
En TypeScript vives en dos mundos paralelos que se tocan pero son distintos:
1.  **Value-Level (El Mundo Real 🏃‍♂️)**: Es el código que realmente corre en tu computadora. Son los datos de los usuarios, los números de las pesas y los mensajes de consola.
2.  **Type-Level (El Mundo Fantasma 👻)**: Es el código que desaparece cuando el programa arranca. Son las interfaces, los tipos y las reglas que TypeScript usa para que no cometas errores.

### ¿Cuándo lo uso en ObsidiantFit? 🧱
- Es fundamental para entender que un `interface` o un `type` no ocupan espacio en tu app final (son fantasmas).
- Para saber qué palabras puedes usar en cada mundo (ej: `typeof` puede usarse en ambos, ¡pero significa cosas distintas!).

### ¿Cómo se usa? (Paso a paso)
```typescript
// MUNDO FANTASMA (Type-Level)
type ID = string | number;
interface Usuario { nombre: string; }

// MUNDO REAL (Value-Level)
const miID: ID = 123; // 'miID' y '123' existen en el programa real.
const nombre = "Lucas";
```

### El Truco del Experto 💡
Si intentas imprimir un `type` con `console.log`, ¡la computadora se confundirá porque los tipos no existen en el mundo real! Los tipos son solo "anteojos" que usas mientras programas para ver mejor tus errores.
