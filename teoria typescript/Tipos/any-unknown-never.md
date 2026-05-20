# Any, Unknown y Never — Los Comodines

### ¿Qué es esto? (La analogía) 📦❓🕳️
- **`any` (El Rebelde)**: Es una caja donde puedes meter **lo que quieras** y TypeScript no te dirá nada. Es como un gimnasio sin reglas: puedes entrar con zapatillas, en ojotas o descalzo. ¡Es peligroso porque puedes romper algo!
- **`unknown` (La Caja Misteriosa)**: Es una caja que tiene algo adentro, pero **no sabemos qué es**. TypeScript no te deja usarlo hasta que "abras la caja" y compruebes qué hay. Es mucho más seguro que `any`.
- **`never` (El Agujero Negro)**: Representa algo que **nunca sucede**. Por ejemplo, una función que siempre lanza un error y nunca termina de ejecutarse.

### ¿Cuándo lo uso en ObsidiantFit? 🛠️
- **`unknown`**: Cuando recibes datos de una API externa y no estás 100% seguro de qué te enviaron. Primero compruebas si es un `string` y recién ahí lo usas.
- **`any`**: **¡Casi nunca!** Solo cuando estás migrando código viejo o cuando de verdad no hay otra opción.
- **`never`**: Cuando quieres asegurar que un proceso se detenga por completo si algo sale muy mal.

### ¿Cómo se usa? (Paso a paso)
```typescript
// --- ANY (Peligroso ❌) ---
let datoCualquiera: any = "Hola";
// datoCualquiera.hacerEjercicio(); // TS no te avisa, pero esto va a explotar en ejecución.

// --- UNKNOWN (Seguro ✅) ---
let datoMisterioso: unknown = "Hola";
// datoMisterioso.toUpperCase(); // ❌ TS te detiene: "¡No sabes si es un texto!"

if (typeof datoMisterioso === "string") {
  console.log(datoMisterioso.toUpperCase()); // ✅ Ahora sí, ya abrimos la caja y comprobamos.
}

// --- NEVER (El Final Absoluto 🕳️) ---
function lanzarError(msj: string): never {
  throw new Error(msj); // La función nunca termina, "muere" aquí.
}
```

### El Truco del Experto 💡
Si tienes dudas entre usar `any` o `unknown`, **elige siempre `unknown`**. Te obliga a usar "Type Guards" (como `typeof`) para estar seguro de lo que haces, lo que evita muchísimos bugs.
