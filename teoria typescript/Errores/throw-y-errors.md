# Throw y Errors — Haciendo Sonar la Alarma 🚨

---

## 🧒 Parte 1 — ¿Qué es `throw`? (para niños de 5 años)

### La analogía del torniquete del gimnasio

Imaginate que entrás al gimnasio y querés pasar el torniquete, pero tu tarjeta de membresía **no existe**. El torniquete no te deja pasar en silencio, ¿no? Hace un **bip**, enciende una **luz roja** y un cartel dice "Acceso denegado".

Eso es exactamente `throw`.

En tu programa:
- **El torniquete** = tu función.
- **El bip y la luz roja** = el `throw`.
- **El cartel con el mensaje** = el objeto `new Error("mensaje")`.

Cuando una función detecta que algo está **mal**, en vez de continuar como si nada, **lanza una alarma** y se detiene.

```
función detecta problema
         ↓
     throw 💥
         ↓
el código SE DETIENE en esa línea
         ↓
el error "vuela hacia arriba" buscando un catch
         ↓
si no hay catch → el programa explota (crash)
si hay catch    → el error es atrapado y manejado
```

---

## 📦 Parte 2 — La sintaxis básica

Hay dos palabras que siempre van juntas:

```typescript
throw new Error("El mensaje que describe qué salió mal");
```

- **`throw`** → la acción de lanzar la alarma.
- **`new Error(...)`** → el objeto que contiene la información del error (el "cartel" del torniquete).

### Tu primer throw

```typescript
function entrarAlGimnasio(tieneMembresiaActiva: boolean): void {
  if (!tieneMembresiaActiva) {
    throw new Error("❌ Acceso denegado: tu membresía no está activa.");
    // 👆 El código SE PARA aquí. Lo de abajo NUNCA se ejecuta.
  }

  console.log("✅ ¡Bienvenido al gimnasio!");
}

entrarAlGimnasio(true);  // ✅ ¡Bienvenido al gimnasio!
entrarAlGimnasio(false); // 💥 Error: Acceso denegado: tu membresía no está activa.
```

---

## ⚠️ Parte 3 — ¿Qué pasa cuando se lanza un throw?

El error **viaja hacia arriba** por la cadena de funciones que lo llamaron, buscando un `catch`. Si no encuentra ninguno, el programa se cierra con un error en la consola.

```typescript
function calcularXP(series: number, repeticiones: number): number {
  if (series <= 0) {
    throw new Error("Las series deben ser mayor a 0.");
  }
  return series * repeticiones * 10;
}

function procesarEjercicio(): void {
  const xp = calcularXP(0, 12); // 💥 throw se lanza aquí
  console.log(`XP ganado: ${xp}`); // esta línea NUNCA se ejecuta
}

procesarEjercicio();
// 💥 Error: Las series deben ser mayor a 0.
// El programa se detiene.
```

### Diagrama del viaje del error

```
procesarEjercicio()
    └── calcularXP(0, 12)
            └── throw new Error(...)  💥
                    ↑ el error "sube"
            ← sale de calcularXP
        ← sale de procesarEjercicio
← sin catch → PROGRAMA SE CIERRA
```

---

## 🔴 Parte 4 — ¿Por qué NO usar `return null`?

Muchos principiantes hacen esto cuando algo falla:

```typescript
function buscarRutina(id: number) {
  const rutina = rutinas.find(r => r.id === id);

  if (!rutina) {
    return null; // ❌ el "silencio administrativo"
  }

  return rutina;
}
```

### El problema con `return null`

```typescript
const resultado = buscarRutina(999); // No existe

// Ahora el que llamó a la función tiene que recordar chequear null:
if (resultado !== null) {
  resultado.ejercicios.push(...); // ¿Y si te olvidás el if? 💥 crash
}
```

**¿Qué pasa si te olvidás de chequear null?**

```typescript
const resultado = buscarRutina(999); // null
resultado.ejercicios.push(nuevoEjercicio);
// 💥 TypeError: Cannot read properties of null (reading 'ejercicios')
// El error aparece en una línea DIFERENTE a donde empezó el problema.
// Es muy difícil saber por qué falló.
```

### La ventaja del `throw`

```typescript
function buscarRutina(id: number) {
  const rutina = rutinas.find(r => r.id === id);

  if (!rutina) {
    throw new Error(`🚨 Rutina con ID ${id} no encontrada.`);
    // El error aparece AQUÍ MISMO, donde está el problema real.
  }

  return rutina; // TypeScript sabe que si llegamos aquí, rutina existe.
}
```

**Filosofía: es mejor fallar rápido y ruidosamente que fallar tarde y en silencio.**

---

## 🏋️ Parte 5 — Ejercicio Nivel Básico: Validar un Ejercicio

```typescript
interface IExercise {
  id: number;
  nombre: string;
  series: number;
  repeticiones: number;
}

function validarEjercicio(ejercicio: IExercise): void {
  if (ejercicio.series <= 0) {
    throw new Error(`El ejercicio "${ejercicio.nombre}" debe tener al menos 1 serie.`);
  }

  if (ejercicio.repeticiones <= 0) {
    throw new Error(`El ejercicio "${ejercicio.nombre}" debe tener al menos 1 repetición.`);
  }

  if (ejercicio.nombre.trim() === '') {
    throw new Error('El nombre del ejercicio no puede estar vacío.');
  }

  console.log(`✅ Ejercicio "${ejercicio.nombre}" validado correctamente.`);
}

// Prueba con datos válidos
validarEjercicio({ id: 1, nombre: 'Flexiones', series: 3, repeticiones: 12 });
// ✅ Ejercicio "Flexiones" validado correctamente.

// Prueba con datos inválidos
validarEjercicio({ id: 2, nombre: 'Sentadillas', series: 0, repeticiones: 12 });
// 💥 Error: El ejercicio "Sentadillas" debe tener al menos 1 serie.
```

---

## 🛡️ Parte 6 — throw + try/catch trabajando juntos

El `throw` solo lanza la alarma. El `try/catch` la **atrapa** para que el programa no explote.

```typescript
// La función que puede explotar
function obtenerRutina(id: number, rutinas: any[]): any {
  const rutina = rutinas.find(r => r.id === id);
  if (!rutina) {
    throw new Error(`Rutina con ID ${id} no encontrada.`);
  }
  return rutina;
}

// El código que la llama, protegido con try/catch
const rutinas = [
  { id: 1, nombre: 'Full Body' },
  { id: 2, nombre: 'Piernas' }
];

try {
  const rutina = obtenerRutina(99, rutinas); // ID que no existe
  console.log(`Rutina encontrada: ${rutina.nombre}`);
} catch (error) {
  if (error instanceof Error) {
    console.error(`❌ Algo salió mal: ${error.message}`);
    // ❌ Algo salió mal: Rutina con ID 99 no encontrada.
  }
}

console.log('El programa SIGUE corriendo después del catch ✅');
```

### El diagrama ahora completo:

```
try {
    obtenerRutina(99)
        └── throw new Error(...)  💥
                ↑ el error sube
        ← sale de obtenerRutina
← lo atrapa el catch
}
catch (error) {
    → mostramos mensaje amigable
    → el programa NO explota
}
→ el código continúa normalmente
```

---

## 🧙 Parte 7 — Custom Errors (Errores Personalizados)

En apps grandes, necesitás saber **qué tipo de error** ocurrió para mostrar mensajes distintos.

- ¿Fue un error de "datos no encontrados"?
- ¿Fue un error de "permiso denegado"?
- ¿Fue un error de "datos inválidos"?

Con `new Error()` genérico no podés distinguirlos fácilmente en el `catch`. Para eso creás tus propias clases de error:

```typescript
// Error personalizado: extiende la clase Error nativa
class RutinaNoEncontradaError extends Error {
  constructor(id: number) {
    super(`Rutina con ID ${id} no existe en ObsidiantFit.`);
    this.name = 'RutinaNoEncontradaError'; // nombre identificador
  }
}

class AccesoDenegadoError extends Error {
  constructor(usuario: string) {
    super(`El usuario "${usuario}" no tiene permisos para esta acción.`);
    this.name = 'AccesoDenegadoError';
  }
}

class DatosInvalidosError extends Error {
  constructor(campo: string, valor: unknown) {
    super(`El campo "${campo}" tiene un valor inválido: ${valor}`);
    this.name = 'DatosInvalidosError';
  }
}
```

### Usando los Custom Errors

```typescript
function buscarRutina(id: number, rutinas: any[]): any {
  const rutina = rutinas.find(r => r.id === id);
  if (!rutina) {
    throw new RutinaNoEncontradaError(id); // ← error específico
  }
  return rutina;
}

function actualizarRutina(usuario: string, esAdmin: boolean, id: number): void {
  if (!esAdmin) {
    throw new AccesoDenegadoError(usuario); // ← otro tipo de error
  }
  console.log(`Rutina ${id} actualizada.`);
}
```

### Capturando distintos tipos de error

```typescript
const rutinas = [{ id: 1, nombre: 'Full Body' }];

try {
  buscarRutina(99, rutinas);
} catch (error) {
  if (error instanceof RutinaNoEncontradaError) {
    console.error('🔍 No encontramos esa rutina. Verificá el ID.');
  } else if (error instanceof AccesoDenegadoError) {
    console.error('🔒 No tenés permisos. Contactá al administrador.');
  } else if (error instanceof DatosInvalidosError) {
    console.error('📝 Los datos que enviaste no son válidos.');
  } else if (error instanceof Error) {
    console.error(`❌ Error inesperado: ${error.message}`);
  }
}
// 🔍 No encontramos esa rutina. Verificá el ID.
```

**¿Ves la diferencia?** Con `instanceof` podés dar mensajes específicos para cada tipo de error, en lugar de un mensaje genérico para todos.

---

## 🏆 Parte 8 — Ejemplo Final Completo: ServicioRutinas

```typescript
// --- Custom Errors ---
class RutinaNoEncontradaError extends Error {
  constructor(id: number) {
    super(`Rutina con ID ${id} no encontrada.`);
    this.name = 'RutinaNoEncontradaError';
  }
}

class DatosInvalidosError extends Error {
  constructor(campo: string) {
    super(`El campo "${campo}" tiene un valor inválido.`);
    this.name = 'DatosInvalidosError';
  }
}

// --- Interfaces ---
interface IEjercicio {
  id: number;
  nombre: string;
  series: number;
  repeticiones: number;
}

interface IRutina {
  id: number;
  nombre: string;
  ejercicios: IEjercicio[];
}

// --- Servicio con throw ---
class ServicioRutinas {
  private rutinas: IRutina[] = [];

  crearRutina(nombre: string): IRutina {
    if (nombre.trim() === '') {
      throw new DatosInvalidosError('nombre'); // ← throw si el nombre está vacío
    }

    const nueva: IRutina = {
      id: Date.now(),
      nombre,
      ejercicios: []
    };
    this.rutinas.push(nueva);
    return nueva;
  }

  agregarEjercicio(rutinaId: number, ejercicio: IEjercicio): void {
    const rutina = this.rutinas.find(r => r.id === rutinaId);

    if (!rutina) {
      throw new RutinaNoEncontradaError(rutinaId); // ← throw si no existe
    }

    if (ejercicio.series <= 0 || ejercicio.repeticiones <= 0) {
      throw new DatosInvalidosError('series/repeticiones'); // ← throw si datos inválidos
    }

    rutina.ejercicios.push(ejercicio);
    console.log(`✅ Ejercicio "${ejercicio.nombre}" añadido.`);
  }
}

// --- Uso con try/catch ---
const servicio = new ServicioRutinas();

try {
  const rutina = servicio.crearRutina('Full Body Lunes');

  servicio.agregarEjercicio(rutina.id, {
    id: 1,
    nombre: 'Sentadillas',
    series: 3,
    repeticiones: 12
  });

  // Esto va a fallar: ID que no existe
  servicio.agregarEjercicio(9999, {
    id: 2,
    nombre: 'Flexiones',
    series: 3,
    repeticiones: 10
  });

} catch (error) {
  if (error instanceof RutinaNoEncontradaError) {
    console.error(`🔍 ${error.message}`);
  } else if (error instanceof DatosInvalidosError) {
    console.error(`📝 ${error.message}`);
  } else if (error instanceof Error) {
    console.error(`❌ Error inesperado: ${error.message}`);
  }
}
```

---

## 🔑 Resumen Final

| Concepto | ¿Para qué sirve? |
|---|---|
| `throw` | Detiene la función y lanza la alarma. |
| `new Error("msg")` | Crea el objeto de error con un mensaje descriptivo. |
| Custom Error (`extends Error`) | Crea tipos de error propios para distinguirlos en el catch. |
| `try / catch` | Atrapa el error para que el programa no explote. |
| `instanceof` | Identifica qué tipo de error fue para mostrar mensajes distintos. |

### Las dos reglas de oro 🥇

> 1. **Fallá rápido y ruidoso**: usá `throw` en cuanto detectés el problema, no silencies errores con `return null`.
> 2. **Siempre hay un catch arriba**: algún nivel de tu app debe atrapar el error y mostrar un mensaje amigable al usuario.

---

## 🗓️ Parte 9 — ¿En qué momento del programa puedo usar `throw`?

La respuesta corta: **en cualquier momento dentro de una función**. Pero la pregunta real es: **¿cuándo TIENE SENTIDO hacerlo?**

Hay **5 situaciones típicas** donde usás `throw`:

---

### 1️⃣ Cuando los datos de entrada son inválidos (validación)

Antes de hacer cualquier lógica, chequeás que lo que te pasaron tenga sentido.

```typescript
function crearRutina(nombre: string, series: number): void {
  // ❌ Antes de hacer nada, validamos los datos
  if (nombre.trim() === '') {
    throw new Error('El nombre no puede estar vacío.');
  }
  if (series <= 0) {
    throw new Error('Las series deben ser un número positivo.');
  }

  // ✅ Si llegamos aquí, los datos son válidos
  console.log(`Rutina "${nombre}" creada con ${series} series.`);
}
```

**¿Cuándo?** → Al **principio** de la función, antes de hacer nada con los datos.

---

### 2️⃣ Cuando buscás algo y no lo encontrás

Usás `.find()`, `.findIndex()` o una búsqueda en base de datos, y el resultado no existe.

```typescript
function buscarEjercicio(id: number, lista: IExercise[]): IExercise {
  const ejercicio = lista.find(e => e.id === id);

  // ❌ No lo encontramos → throw
  if (!ejercicio) {
    throw new Error(`No existe ningún ejercicio con ID ${id}.`);
  }

  // ✅ Lo encontramos → lo devolvemos
  return ejercicio;
}
```

**¿Cuándo?** → Justo **después de la búsqueda**, antes de intentar usar el resultado.

---

### 3️⃣ Cuando una condición de negocio se rompe

"Regla de negocio" = una regla propia de tu app, no de TypeScript.

```typescript
function agregarEjercicio(rutina: IRutina, ejercicio: IExercise): void {
  // Regla de negocio: una rutina no puede tener más de 10 ejercicios
  if (rutina.ejercicios.length >= 10) {
    throw new Error(`La rutina "${rutina.nombre}" ya tiene el máximo de ejercicios (10).`);
  }

  rutina.ejercicios.push(ejercicio);
}
```

**¿Cuándo?** → Cuando una **regla de tu app** (no de TypeScript) no se cumple.

---

### 4️⃣ En el `default` de un exhaustive check

Como viste en el archivo de `exhaustive-checking.md`, usás `throw` en el `default` del switch para detectar estados no contemplados.

```typescript
type EstadoPago = 'PENDIENTE' | 'APROBADO' | 'RECHAZADO';

function procesarPago(estado: EstadoPago): string {
  switch (estado) {
    case 'PENDIENTE':  return '⏳ Esperando confirmación...';
    case 'APROBADO':   return '✅ Pago exitoso.';
    case 'RECHAZADO':  return '❌ Pago rechazado.';
    default:
      // Si llegamos aquí, algo muy raro pasó
      const _check: never = estado;
      throw new Error(`Estado de pago no contemplado: ${_check}`);
  }
}
```

**¿Cuándo?** → En el `default` de un `switch` con exhaustive checking.

---

### 5️⃣ Dentro de un `catch`, para re-lanzar el error

A veces atrapás un error, le agregás información, y lo volvés a lanzar hacia arriba.

```typescript
function guardarRutinaEnDB(rutina: IRutina): void {
  try {
    // Simulamos una operación de base de datos
    db.save(rutina);
  } catch (error) {
    // Atrapamos el error de la DB y lo re-lanzamos con más contexto
    throw new Error(`Fallo al guardar la rutina "${rutina.nombre}": ${(error as Error).message}`);
  }
}
```

**¿Cuándo?** → Dentro de un `catch`, cuando querés agregar contexto antes de propagar el error.

---

### 🗺️ Mapa visual: el ciclo de vida del `throw`

```
┌─────────────────────────────────────────────┐
│               Tu función                     │
│                                             │
│  1. Recibís datos de entrada               │
│         ↓                                  │
│  2. Validás los datos      → throw si mal  │
│         ↓                                  │
│  3. Buscás en la lista/DB  → throw si null │
│         ↓                                  │
│  4. Aplicás reglas negocio → throw si rota │
│         ↓                                  │
│  5. Ejecutás la lógica     → throw si falla│
│         ↓                                  │
│  6. Devolvés el resultado ✅               │
└─────────────────────────────────────────────┘
         ↓ (si hubo throw en cualquier paso)
┌─────────────────────────────────────────────┐
│            El error "sube"                  │
│  hasta encontrar un try/catch               │
│  o el programa explota                      │
└─────────────────────────────────────────────┘
```

---

### ⛔ ¿Cuándo NO usar `throw`?

| Situación | ¿Qué usar en su lugar? |
|---|---|
| Un resultado vacío es válido (ej: buscar rutinas de hoy y no hay ninguna) | Devolvé `[]` o `null`, no es un error |
| Querés mostrar un mensaje al usuario en la UI | Manejalo con estado del componente, no con throw |
| Dentro de un `forEach` (los errores dentro no se pueden catchear fácilmente) | Usá un `for...of` en su lugar |
