# Unión e Intersección — Mezclando Tipos

### ¿Qué es esto? (La analogía) 🧪
- **Unión (`|`) - El "O"**: Es como una máquina que acepta tanto **monedas** como **billetes**. Puede ser una cosa O la otra.
- **Intersección (`&`) - El "Súper-Combo"**: Es como un atleta que es **Corredor** Y también **Levantador de pesas**. Tiene las habilidades de ambos al mismo tiempo.

### ¿Cuándo lo uso en ObsidiantFit? 🧬
- **Unión**: El ID de un ejercicio puede ser un número `1` o un texto `"ej-01"`. Se escribe `number | string`.
- **Intersección**: Tienes un tipo `Persona` y un tipo `Atleta`. Si los unes con `&`, tienes un `Usuario` que tiene nombre (de persona) y también récords (de atleta).

### ¿Cómo se usa? (Paso a paso)
```typescript
// Unión: puede ser uno de los dos
let peso: number | string;
peso = 80;      // OK
peso = "80kg";  // OK

// Intersección: tiene que tener TODO de ambos
type Persona = { nombre: string };
type Atleta = { record: number };

type UsuarioFit = Persona & Atleta;

const lucas: UsuarioFit = {
  nombre: "Lucas",
  record: 150
};
```

### El Truco del Experto 💡
La unión `|` es súper común para manejar estados, como: `type Estado = "Cargando" | "Éxito" | "Error"`. ¡Así te aseguras de que no existan estados raros!
