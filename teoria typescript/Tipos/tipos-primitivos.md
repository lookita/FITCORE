# Tipos Primitivos — Los Ladrillos Básicos

### ¿Qué es esto? (La analogía) 🧱
Imagina que estás construyendo tu gimnasio con bloques de LEGO. Tienes bloques de distintas formas que no puedes mezclar:
- **`string` (Texto)**: Es como un bloque con letras. Sirve para nombres.
- **`number` (Número)**: Es un bloque pesado para pesas y medidas.
- **`boolean` (Sí/No)**: Es un bloque que solo tiene dos colores (Verde para Sí, Rojo para No).

### ¿Cuándo lo uso en ObsidiantFit? 🛠️
- **string**: Para el nombre del ejercicio `"Press de Banca"`.
- **number**: Para las repeticiones `12` o el peso `80.5`.
- **boolean**: Para saber si una serie fue completada `true` o no `false`.

### ¿Cómo se usa? (Paso a paso)
```typescript
let nombre: string = "Lucas";
let kilos: number = 100;
let estaEntrenando: boolean = true;

// TypeScript no te deja hacer esto:
// kilos = "Mucho"; // ❌ ¡Error! No puedes guardar letras en un bloque de números.
```

### El Truco del Experto 💡
Si no sabes qué tipo es algo, TypeScript es inteligente y lo adivina por ti (eso se llama *inferencia*). Pero escribir el tipo a mano hace que tu código sea mucho más fácil de leer para otros.
