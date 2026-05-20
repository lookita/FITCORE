# .includes() — ¿Está en la Lista?

### ¿Qué es esto? (La analogía) 🎫
Es como revisar si tu nombre está en la lista de invitados de una fiesta. No buscas detalles, solo quieres un "Sí" o un "No". "¿Está el nombre 'Lucas' en este grupo? Sí, ahí está".

### ¿Cuándo lo uso en ObsidiantFit? 🧐
Cuando tienes una lista simple de palabras o números y quieres saber si algo está ahí.
- Ver si el ejercicio actual incluye la etiqueta **"Cardio"**.
- Revisar si el ID de un usuario está en la lista de **Administradores**.
- Saber si el día de hoy es uno de los **días de descanso** permitidos.

### ¿Cómo se usa? (Paso a paso)
```typescript
const musculosEntrenados = ["Pecho", "Tríceps", "Hombro"];

const entrenePierna = musculosEntrenados.includes("Pierna");
const entrenePecho = musculosEntrenados.includes("Pecho");

console.log(entrenePierna); // false
console.log(entrenePecho);  // true
```

### El Truco del Experto 💡
`.includes()` es genial para listas de texto o números simples. Si tienes una lista de objetos (como `{nombre: "Lucas"}`), es mejor usar `.some()` o `.find()`.
