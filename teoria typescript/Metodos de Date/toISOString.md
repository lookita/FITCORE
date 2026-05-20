# .toISOString() — El Formato Universal

### ¿Qué es esto? (La analogía) 🌍
Imagina que hablas con un amigo en China sobre un entrenamiento. Si le dices "el 5 de abril", él podría confundirse. El `.toISOString()` es como el "esperanto" de las fechas: un formato que todas las computadoras del mundo entienden perfectamente, sin importar en qué país estén. Se ve así: `2024-05-10T18:30:00.000Z`.

### ¿Cuándo lo uso en ObsidiantFit? 🚀
- Para guardar la fecha en la **base de datos**. Es el estándar de oro.
- Para enviar datos del teléfono al servidor.
- Para comparar dos fechas de forma fácil, ya que al ser texto ordenado, se pueden comparar alfabéticamente.

### ¿Cómo se usa? (Paso a paso)
```typescript
const hoy = new Date();

const fechaParaGuardar = hoy.toISOString();

console.log(fechaParaGuardar); // "2024-05-10T18:30:00.000Z"
```

### El Truco del Experto 💡
Este formato siempre usa la hora de Londres (UTC/Z). Si quieres mostrarle la hora real al usuario en su país, tendrás que convertirlo de nuevo a un objeto `new Date(stringISO)`.
