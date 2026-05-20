# Union Types (A | B) — Esto O Aquello

---

## 🧠 ¿Qué es un Union Type?

Imaginate que sos árbitro en una competencia de calistenia y le preguntás al atleta:
> "¿Cuántas dominadas hiciste? Podés decirme el número, o si no lo recordás, decirme un texto como 'muchas'."

Eso es un **Union Type**: una variable que puede contener **uno u otro tipo**, pero no los dos al mismo tiempo.

El símbolo es la **barra vertical**: `|` (se lee "o")

---

## 📖 La Sintaxis Básica

```typescript
// Esta variable puede ser un número O un texto
type ID = number | string;

let idUsuario: ID;

idUsuario = 123;          // ✅ Es un número, válido
idUsuario = "abc-456";    // ✅ Es un string, también válido
idUsuario = true;         // ❌ Error: no es ni número ni string
idUsuario = [1, 2, 3];   // ❌ Error: tampoco es un array
```

---

## 🃏 ¿Por qué no usar `any` y ya?

Podrías pensar: "¿Para qué tanto lío? Uso `any` y acepta todo."

La diferencia es fundamental:

```typescript
// ❌ Con 'any': TypeScript se desactiva, no te protege de nada
let valor: any = "hola";
valor = 42;
valor.toUpperCase(); // ¡No da error pero podría fallar en runtime!

// ✅ Con Union Type: TypeScript sigue protegiéndote
let valor2: string | number = "hola";
valor2.toUpperCase();  // ✅ string tiene toUpperCase
valor2 = 42;
valor2.toUpperCase();  // ❌ TypeScript avisa: número no tiene toUpperCase
```

---

## 🕵️ Narrowing — TypeScript se vuelve Detective

Cuando tenés un Union Type, TypeScript no sabe cuál de los dos tipos es en ese momento. Por eso te **obliga** a verificarlo antes de usar propiedades específicas.

Esto se llama **Narrowing** (achicar o reducir las posibilidades):

```typescript
type Resultado = string | number;

function procesar(valor: Resultado) {
  // Aquí TypeScript no sabe si es string o number

  if (typeof valor === "string") {
    // Dentro de este bloque, TypeScript SABE que es string
    console.log(valor.toUpperCase());  // ✅ OK
    console.log(valor.toFixed(2));     // ❌ Error: no es number
  } else {
    // Aquí TypeScript SABE que es number (porque descartó string)
    console.log(valor.toFixed(2));     // ✅ OK
    console.log(valor.toUpperCase());  // ❌ Error: no es string
  }
}
```

---

## 🏋️ Ejemplo Real en ObsidiantFit

### El estado de una suscripción:

```typescript
// Sin Union Type: podrías escribir cualquier cosa
let estado: string = "activaaaaa"; // ¡Typo y nadie te avisa!

// Con Union Type: TypeScript solo acepta estos valores exactos
type EstadoSuscripcion = "activa" | "vencida" | "cancelada" | "pendiente";

let estado2: EstadoSuscripcion;

estado2 = "activa";     // ✅ Válido
estado2 = "vencida";    // ✅ Válido
estado2 = "activaaa";   // ❌ Error: ese valor no existe en el tipo!
estado2 = "gratis";     // ❌ Error: tampoco existe!

// Función que usa el Union Type
function mostrarEstadoSuscripcion(estado: EstadoSuscripcion) {
  if (estado === "activa") {
    console.log("✅ ¡Suscripción activa! A entrenar.");
  } else if (estado === "vencida") {
    console.log("⚠️ Tu suscripción venció. Renovar ahora.");
  } else if (estado === "cancelada") {
    console.log("❌ Suscripción cancelada.");
  } else {
    console.log("⏳ Pago pendiente de confirmación.");
  }
}
```

### El nivel de un ejercicio:

```typescript
type NivelEjercicio = "principiante" | "intermedio" | "avanzado";

type Ejercicio = {
  nombre: string;
  nivel: NivelEjercicio; // Solo puede ser uno de estos tres valores
  xp: number;
};

const dominada: Ejercicio = {
  nombre: "Dominadas",
  nivel: "intermedio",   // ✅
  xp: 75
};
```

---

## 🎭 Union Types con Objetos Completos

También podés hacer Union de objetos enteros. Esto es muy poderoso:

```typescript
type Atleta = {
  tipo: "atleta";        // Propiedad "discriminador"
  nombre: string;
  nivel: number;
};

type Entrenador = {
  tipo: "entrenador";    // Propiedad "discriminador"
  nombre: string;
  certificacion: string;
};

// Un usuario puede ser Atleta O Entrenador
type Usuario = Atleta | Entrenador;

function mostrarPerfil(usuario: Usuario) {
  console.log(`Nombre: ${usuario.nombre}`); // ✅ nombre está en ambos

  if (usuario.tipo === "entrenador") {
    // Aquí TypeScript SABE que es un Entrenador
    console.log(`Certificación: ${usuario.certificacion}`); // ✅
  } else {
    // Aquí TypeScript SABE que es un Atleta
    console.log(`Nivel: ${usuario.nivel}`); // ✅
  }
}
```

La propiedad `tipo` que actúa como identificador se llama **"discriminated union"** o **unión discriminada**. Es el patrón más limpio para trabajar con objetos union.

---

## 🔢 Union con Valores Literales (Literal Types)

Una de las formas más útiles: limitar una variable a valores específicos:

```typescript
// Solo estos 3 valores son válidos
type DireccionOrden = "ASC" | "DESC";
type TipoSuscripcion = "mensual" | "anual" | "vitalicio";
type EstadoPago = 200 | 400 | 401 | 404 | 500; // Códigos HTTP

function ordenarRutinas(direccion: DireccionOrden) {
  // direccion solo puede ser "ASC" o "DESC"
  // TypeScript te avisa si escribís cualquier otra cosa
}
```

---

## 📊 Tabla Resumen de Narrowing

Dependiendo del tipo, usás distintas formas de "verificar" cuál es:

| Situación | Cómo verificar |
|---|---|
| `string \| number` | `typeof valor === "string"` |
| Objeto Atleta o Entrenador | `usuario.tipo === "atleta"` |
| `string \| null` | `if (valor !== null)` |
| Con clases | `valor instanceof MiClase` |

---

## 💡 El Truco del Experto

Usar **cadenas literales** como Union Type (ej: `"activa" | "vencida"`) es mucho mejor que usar `string` genérico. TypeScript te protege de:
- Errores de tipeo (typos): `"activaa"` da error automáticamente.
- Valores inválidos: `"borrada"` da error si no está en el tipo.
- Autocompletado: el editor te sugiere los valores válidos.
