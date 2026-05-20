# Literal Types — Valores Exactos

### ¿Qué es esto? (La analogía) 🎯
Imagina que tienes una remera que solo viene en tres talles: **Small**, **Medium** o **Large**. Si alguien pide un talle "Extra-Gigante", la fábrica no sabe qué hacer. Un Literal Type es un tipo que no acepta "cualquier texto", sino solamente **palabras específicas**.

### ¿Cuándo lo uso en ObsidiantFit? 👕
- Para el **Género** del usuario: `"Hombre" | "Mujer" | "Otro"`.
- Para el **Tipo de Peso**: `"kg" | "lb"`.
- Para los **Días de Entrenamiento**: `"Lunes" | "Miércoles" | "Viernes"`.

### ¿Cómo se usa? (Paso a paso)
```typescript
// Solo aceptamos estas dos palabras exactas
type UnidadMedida = "kilos" | "libras";

let miPeso: UnidadMedida = "kilos"; // OK
// miPeso = "gramos"; // ❌ Error! "gramos" no está en la lista de permitidos.
```

### El Truco del Experto 💡
Esto es mucho mejor que usar un `string` común, porque TypeScript te va a avisar si escribes mal la palabra (por ejemplo, si pones "kilo" sin la S). ¡Te ahorra muchísimos bugs!
