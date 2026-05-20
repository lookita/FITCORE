# Introducción a Arrays — Tu Almacén de Datos

### ¿Qué es esto? (La analogía) 🧺🔢
Imagina que un Array es una **estantería de pesas**. Puedes tener una estantería donde solo entran discos de hierro (`number[]`), otra donde guardas las etiquetas de los ejercicios (`string[]`), o incluso una estantería mixta donde guardas un poco de todo. 

En TypeScript, la estantería es inteligente: ella sabe qué tipo de cosas "pueden" entrar y te avisará si intentas meter algo que no corresponde.

### ¿Cuándo lo uso en ObsidiantFit? 🧱
- Para guardar una lista de kilos levantados: `[10, 20, 30]`.
- Para guardar los nombres de los músculos que entrenaste: `["Pecho", "Espalda"]`.
- Para tener una lista de "Series" que mezclan el peso y el comentario del usuario.

### ¿Cómo se declaran? (Paso a paso)

#### 1. Inferencia (TypeScript adivina por ti) 🧠
Si le das valores al principio, TypeScript ya sabe de qué es la lista.
```typescript
let pesos = [60, 80, 100];      // TypeScript dice: "Esto es number[]"
let nombres = ["Lucas", "Ana"]; // TypeScript dice: "Esto es string[]"

// Si mezclas, TypeScript crea una "Unión"
let mixto = [1, "a"];           // TypeScript dice: "Esto es (string | number)[]"
```

#### 2. Declaración Explícita (Tú pones las reglas) ✍️
Tú le dices a la estantería exactamente qué aceptar.
```typescript
let ejercicios: string[] = ["Sentadilla"];
ejercicios.push("Prensa"); // ✅ ¡Perfecto!
// ejercicios.push(10);    // ❌ ¡Error! No puedes meter un número en una lista de textos.
```

#### 3. El peligro del Array vacío (`any[]`) ⚠️
Si creas una lista vacía sin decir el tipo, TypeScript la deja como "Cualquier cosa" (`any[]`) al principio, pero luego se vuelve más estricta a medida que agregas cosas.
```typescript
let bolsaMagica = []; // Es any[]
bolsaMagica.push(1);  // Ahora es number[]
bolsaMagica.push("rojo"); // Ahora es (string | number)[]
```

### El Truco del Experto 💡
**¡Nunca dejes un array como `any[]`!** Siempre que declares una lista vacía, dile a TypeScript qué va a contener en el futuro: `let misSeries: number[] = [];`. Así evitarás que alguien meta un texto por error y rompa tus cálculos de volumen de entrenamiento.
