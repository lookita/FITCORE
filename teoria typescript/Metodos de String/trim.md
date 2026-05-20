# .trim() — Limpiando Espacios

### ¿Qué es esto? (La analogía) 🧹
Imagina que el usuario escribe su nombre pero, por error, deja muchos espacios vacíos antes y después: `"   Lucas   "`. El `.trim()` es como una escoba que barre esos espacios inútiles de las puntas y deja solo el nombre limpio: `"Lucas"`.

### ¿Cuándo lo uso en ObsidiantFit? 🧼
- Cuando un usuario crea su cuenta y escribe su email o nombre. Los espacios al principio o al final pueden causar errores al intentar iniciar sesión.
- Cuando el usuario anota una nota en su ejercicio y le da a la barra espaciadora sin querer.

### ¿Cómo se usa? (Paso a paso)
```typescript
const nombreSucio = "   Marcos Entrenador   ";
const nombreLimpio = nombreSucio.trim();

console.log(nombreLimpio); // "Marcos Entrenador" (¡Sin espacios en las puntas!)
```

### El Truco del Experto 💡
También existen `.trimStart()` (solo limpia el principio) y `.trimEnd()` (solo limpia el final), pero el `.trim()` normal es el que más vas a usar el 99% de las veces.
