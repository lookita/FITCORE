# `type` vs `interface` — Los Planos de Construcción

---

## 🧠 ¿Qué es `type`?

`type` es una palabra clave de TypeScript que te permite **crear un nombre** para describir la forma de un dato.

Es como ponerle una **etiqueta** a algo para poder reutilizarla:

```typescript
// Sin type: tenés que escribir la forma completa cada vez
const usuario: { nombre: string; edad: number } = { nombre: "Lucas", edad: 25 };

// Con type: le ponés nombre a la forma y la reutilizás
type Usuario = {
  nombre: string;
  edad: number;
};

const usuario: Usuario = { nombre: "Lucas", edad: 25 };
```

---

## 📖 ¿Cómo se lee `type`?

```typescript
type    Usuario    =    {  nombre: string;  edad: number  };
↑         ↑       ↑         ↑
│         │       │         └── La "forma" del dato (sus propiedades y tipos)
│         │       └── El signo igual: "es igual a..."
│         └── El nombre que le damos (podés elegir cualquiera)
└── La palabra clave que le dice a TypeScript: "voy a definir un tipo"
```

**Se lee**: "El tipo `Usuario` es igual a un objeto que tiene `nombre` (string) y `edad` (number)."

---

## 🎯 ¿Para qué sirve `type`?

`type` tiene **3 usos principales**:

### 1. Describir la forma de un objeto

```typescript
type Ejercicio = {
  id: number;
  nombre: string;
  musculo: string;
  xp: number;
};

const dominada: Ejercicio = {
  id: 1,
  nombre: "Dominadas",
  musculo: "Espalda",
  xp: 75,
};
```

### 2. Crear Union Types (Esto O Aquello)

```typescript
// Un ID puede ser número o texto
type ID = number | string;

// El estado de una suscripción
type EstadoSuscripcion = "activa" | "vencida" | "cancelada";

// El nivel de un usuario
type Nivel = "principiante" | "intermedio" | "avanzado";
```

### 3. Crear alias para tipos complejos

```typescript
// En vez de escribir esto siempre...
const lista: Array<{ nombre: string; xp: number }> = [];

// ...le dás un nombre
type EjercicioSimple = { nombre: string; xp: number };
const lista: EjercicioSimple[] = [];
```

---

## 🏗️ ¿Qué es `interface`?

`interface` también describe la forma de un objeto, pero está pensada específicamente para **objetos y clases**.

```typescript
interface Ejercicio {
  id: number;
  nombre: string;
  musculo: string;
  xp: number;
}
```

¿Notás la diferencia? `interface` **no usa el `=`**. Simplemente abre las llaves directamente.

---

## ⚔️ `type` vs `interface` — Las Diferencias

### Diferencia 1: `type` puede describir más cosas

```typescript
// ✅ type puede hacer Union Types
type ID = number | string;

// ❌ interface NO puede hacer Union Types
interface ID = number | string; // Error: esto no existe
```

### Diferencia 2: `interface` se puede "extender" (agrandar) después

```typescript
// Definís una interface
interface Animal {
  nombre: string;
}

// Y después la podés agrandar con más propiedades
interface Animal {
  edad: number; // ✅ Se fusiona con la anterior automáticamente
}

// Ahora Animal tiene: nombre + edad
```

Con `type` esto **no funciona**:

```typescript
type Animal = { nombre: string };
type Animal = { edad: number }; // ❌ Error: el nombre ya está usado
```

### Diferencia 3: `interface` se puede heredar con `extends`

```typescript
interface Persona {
  nombre: string;
  edad: number;
}

interface Entrenador extends Persona {
  certificacion: string;
  // Hereda nombre y edad de Persona
}

// Con type hacés lo mismo pero con &
type Entrenador = Persona & { certificacion: string };
```

---

## 🏋️ Ejemplos Reales en ObsidiantFit

```typescript
// USANDO TYPE: para unions y aliases
type EstadoSuscripcion = "activa" | "vencida" | "cancelada";
type NivelUsuario = "principiante" | "intermedio" | "avanzado";
type ID = number | string;

// Para combinar tipos (Intersection)
type AdminUsuario = Usuario & { puedeEliminar: boolean };

// USANDO INTERFACE: para los objetos principales de la app
interface Usuario {
  id: number;
  nombre: string;
  email: string;
  nivel: NivelUsuario; // usa el type que definimos arriba
  suscripcion: EstadoSuscripcion; // usa el type que definimos arriba
}

interface Rutina {
  id: number;
  nombre: string;
  ejercicios: Ejercicio[]; // una lista de ejercicios
  creador: Usuario; // el usuario que la creó
}

interface Ejercicio {
  id: number;
  nombre: string;
  musculo: string;
  xp: number;
}
```

---

## 📊 Tabla: ¿Cuándo usar cada uno?

| Situación                      | Usar `type` | Usar `interface` |
| ------------------------------ | ----------- | ---------------- |
| Describir un objeto simple     | ✅          | ✅               |
| Union Types (`A \| B`)         | ✅          | ❌               |
| Intersection Types (`A & B`)   | ✅          | ❌               |
| Herencia / extends             | ❌          | ✅               |
| Clases que implementan el tipo | ❌          | ✅               |
| Alias para tipos primitivos    | ✅          | ❌               |

---

## 💡 La Regla Simple para ObsidiantFit

Seguí esta guía:

1. **¿Es un objeto principal** de la app (Usuario, Rutina, Ejercicio)? → Usá `interface`.
2. **¿Es una unión** de valores posibles (estados, niveles, roles)? → Usá `type`.
3. **¿Es una combinación** de dos tipos existentes? → Usá `type` con `&`.
4. **¿No sabés cuál usar?** → Usá `type`, nunca vas a estar muy equivocado.

---

## 🔑 Lo más importante

`type` e `interface` hacen cosas muy similares para los objetos. La diferencia real es:

- `interface` → diseñada para **objetos y herencia**
- `type` → más **flexible**, sirve para todo tipo de datos

En el 90% de los casos del día a día, son intercambiables. El equipo de TypeScript recomienda `interface` para objetos públicos de una librería y `type` para todo lo interno.
