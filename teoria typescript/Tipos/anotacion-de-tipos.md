# Los Dos Puntos `:` — La Etiqueta de Tipo

---

## 🧠 ¿Qué son los dos puntos en TypeScript?

En la vida real, cuando guardás cosas en cajas, le ponés una etiqueta:
- 📦 "Caja de zapatos" → solo guardás zapatos
- 📦 "Caja de libros" → solo guardás libros

En TypeScript, los **dos puntos `:` son esa etiqueta**. Le dicen a la variable qué tipo de dato puede guardar.

```typescript
let nombre: string = "Lucas";
//         ^^^^^^^^
//     Etiqueta: "solo puedo guardar texto"
```

---

## 📍 ¿Dónde aparecen los dos puntos?

Los `:` aparecen en **4 lugares diferentes**. Cada uno significa algo ligeramente distinto.

---

### 1. En Variables (declarar el tipo)

```typescript
let edad: number = 25;
let nombre: string = "Lucas";
let activo: boolean = true;
let pesos: number[] = [75, 80, 78];

// Lectura: "la variable 'edad' ES DE TIPO number"
```

**¿Por qué?** Para que TypeScript te avise si intentás guardar algo equivocado:

```typescript
let edad: number = 25;
edad = "veinticinco"; // ❌ Error: "veinticinco" no es un number
edad = 26;            // ✅ Correcto
```

---

### 2. En Parámetros de Funciones (qué tipo entra)

```typescript
function saludar(nombre: string) {
  //              ^^^^^^^^^^^^^
  //   "el parámetro 'nombre' tiene que ser un string"
  console.log(`Hola ${nombre}`);
}

saludar("Lucas");  // ✅
saludar(42);       // ❌ Error: 42 no es un string
```

---

### 3. Después de los paréntesis de una función (qué tipo devuelve)

```typescript
function sumar(a: number, b: number): number {
  //                                 ^^^^^^^^
  //               "esta función DEVUELVE un number"
  return a + b;
}

function obtenerNombre(): string {
  return "Lucas"; // ✅ devuelve un string, todo bien
}

function mostrarMensaje(): void {
  //                     ^^^^^^
  // "void" = esta función NO devuelve nada
  console.log("Hola!");
}
```

---

### 4. En Objetos y Types (definir la forma)

```typescript
type Usuario = {
  id: number;        // la propiedad 'id' es de tipo number
  nombre: string;    // la propiedad 'nombre' es de tipo string
  activo: boolean;   // la propiedad 'activo' es de tipo boolean
};

const usuario: Usuario = {
//            ^^^^^^^^
//  "esta variable tiene que tener la forma de Usuario"
  id: 1,
  nombre: "Lucas",
  activo: true
};
```

---

## 🏋️ Ejemplos Reales en ObsidiantFit

```typescript
// Variable: una rutina es de tipo string
let rutinaActual: string = "Push-Pull-Legs";

// Parámetros: la función recibe un nombre (texto) y un nivel (número)
function crearPerfil(nombre: string, nivel: number): void {
  console.log(`Perfil de ${nombre}, nivel ${nivel}`);
}

// Objeto: cómo se ve un ejercicio en la app
type Ejercicio = {
  id: number;
  nombre: string;
  musculo: string;
  xp: number;
  esAvanzado: boolean;
};

// Usando el tipo Ejercicio
const dominada: Ejercicio = {
  id: 1,
  nombre: "Dominadas",
  musculo: "Espalda",
  xp: 75,
  esAvanzado: false
};
```

---

## 🤔 ¿Siempre tengo que poner los dos puntos?

**No siempre.** TypeScript es inteligente y puede **inferir** el tipo solo:

```typescript
// Con dos puntos (explícito)
let edad: number = 25;

// Sin dos puntos (TypeScript lo deduce)
let edad = 25; // TypeScript ve el 25 y sabe que es un number

// Ambas formas son correctas. TypeScript prefiere inferir cuando puede.
```

**¿Cuándo SÍ ponemos los dos puntos explícitamente?**
- Cuando la variable empieza vacía (sin valor inicial).
- En los parámetros de funciones (TypeScript no puede inferirlos).
- Cuando queremos ser muy claros y explícitos.

```typescript
// Empieza vacío: TypeScript no sabe el tipo, hay que decírselo
let rutinasDelUsuario: string[] = [];

// En funciones: SIEMPRE hay que tipar los parámetros
function calcularXP(series: number, repeticiones: number): number {
  return series * repeticiones * 10;
}
```

---

## 📊 Resumen Visual

```
variable    : tipo     = valor
────────────────────────────────
let nombre  : string  = "Lucas"
let edad    : number  = 25
let activo  : boolean = true


parámetro   : tipo         tipo de retorno
──────────────────────────────────────────────
function f  (x: number)  : string  { ... }


propiedad   : tipo
──────────────────────
type T = {
  nombre    : string
  edad      : number
}
```

---

## 💡 El Truco del Experto

Los dos puntos `:` son solo para TypeScript. Cuando el código se **compila a JavaScript**, desaparecen completamente.

```typescript
// TypeScript
let nombre: string = "Lucas";

// JavaScript compilado (sin los dos puntos)
let nombre = "Lucas";
```

Son una herramienta de **desarrollo** para que TypeScript te ayude a no cometer errores. En producción, el código que corre no los tiene.

---

## 🔬 Nivel Avanzado: Los `:` en Funciones con Generics

Cuando combinás Generics con anotaciones de tipo, el código puede verse así de complejo:

```typescript
function combinar<T, U>(primero: T, segundo: U): { primero: T; segundo: U } {
  return { primero, segundo };
}
```

En esta función hay **dos puntos en 3 lugares distintos**, y cada uno hace algo diferente. Los vamos a numerar:

```typescript
function combinar<T, U>(primero: T, segundo: U): { primero: T; segundo: U }
//                               ↑          ↑  ↑            ↑          ↑
//                              [1]        [2] [3]          [4]        [5]
```

---

### [1] `primero: T` — "El primer ingrediente es de tipo T"

Esto es una **etiqueta en el parámetro**. Le decimos a TypeScript:
> "La cosa que entre por acá, llamala `primero`, y va a ser del tipo `T`."

`T` todavía no sabemos qué es — es como decir "lo que me den". Si te dan un texto, `T` = string. Si te dan un número, `T` = number.

---

### [2] `segundo: U` — "El segundo ingrediente es de tipo U"

Exactamente igual que el primero, pero para el **segundo parámetro**.

> "La cosa que entre por acá, llamala `segundo`, y va a ser del tipo `U`."

`U` puede ser un tipo diferente al de `T`. Por eso usamos letras distintas.

---

### [3] `): {` — "Esta función devuelve un objeto que tiene esta forma..."

El `:` después del paréntesis de cierre `)` siempre significa **"lo que esta función devuelve"**.

Lo que viene después de ese `:` es el **tipo de retorno**. En este caso, lo que devuelve es un objeto `{ }`.

---

### [4] y [5] `primero: T` y `segundo: U` — "Las propiedades del objeto que devuelvo"

Dentro del objeto de retorno `{ primero: T; segundo: U }`, los `:` funcionan igual que en cualquier objeto:

> "El objeto que devuelvo tiene una propiedad llamada `primero` que es de tipo `T`, y una propiedad llamada `segundo` que es de tipo `U`."

El `;` (punto y coma) dentro del objeto separa las propiedades, igual que las comas en un objeto normal.

---

### La historia completa en español:

> "Soy la función `combinar`. Acepto dos cosas de tipos desconocidos (`T` y `U`). Las recibo con los nombres `primero` y `segundo`. Cuando termino, **te devuelvo un objeto** que tiene esas dos cosas guardadas adentro, con sus mismos tipos."


### Traducido al español simple:

> "Hola TypeScript, te presento la función `combinar`.
> Acepta DOS tipos de datos que no conozco todavía (`T` y `U`).
> Va a recibir dos parámetros: `primero` del tipo `T`, y `segundo` del tipo `U`.
> Y me va a devolver un objeto que tiene dos propiedades: `primero` (del tipo `T`) y `segundo` (del tipo `U`)."

### Cómo TypeScript lo resuelve cuando la usás:

```typescript
// Cuando llamás la función así:
const resultado = combinar("Lucas", 42);
//                          ↑        ↑
//                    T = string   U = number

// TypeScript deduce automáticamente:
// T = string → primero: string
// U = number → segundo: number
// Retorno: { primero: string; segundo: number }
```

### El tipo de retorno como objeto inline:

```typescript
// El tipo de retorno `: { primero: T; segundo: U }` 
// es un objeto definido DIRECTAMENTE ahí, sin crear un type separado.

// Forma corta (inline):
function f(): { nombre: string; edad: number } { ... }

// Forma larga (con type separado, equivalente):
type Resultado = { nombre: string; edad: number };
function f(): Resultado { ... }

// Ambas formas son idénticas. La inline se usa para tipos simples.
```

---

## 🎯 Guía de Lectura Rápida

Cuando veas una firma de función compleja, leéla de izquierda a derecha así:

| Parte | Ejemplo | Pregunta que responde |
|---|---|---|
| Nombre | `combinar` | ¿Cómo se llama? |
| Generics | `<T, U>` | ¿Con qué tipos trabaja? |
| Parámetros | `(primero: T, segundo: U)` | ¿Qué recibe? |
| `:` de retorno | `:` | ¿Qué devuelve? |
| Tipo de retorno | `{ primero: T; segundo: U }` | ¿Cómo es lo que devuelve? |
