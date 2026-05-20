# Exhaustive Checking: El Seguro de Vida de tus Estados 🛡️🕵️

---

## 🧒 Parte 1 — Entendiendo el problema (para niños de 5 años)

### La analogía de los cajones del gimnasio

Imaginate que en el vestuario del gimnasio hay **3 cajones**:

- 🟢 Cajón `LIBRE` → El locker está libre, cualquiera lo puede usar.
- 🔴 Cajón `OCUPADO` → Alguien ya lo está usando.
- 🟡 Cajón `RESERVADO` → Alguien lo reservó pero todavía no llegó.

El recepcionista (tu función en TypeScript) tiene que saber **qué hacer en cada caso**.

Pero... ¿qué pasa si el recepcionista solo sabe qué hacer cuando el cajón está `LIBRE` u `OCUPADO`, y nadie le enseñó qué hacer si está `RESERVADO`?

**Se queda parado, sin saber qué hacer. El programa falla en silencio.** 😬

Eso es exactamente lo que el **Exhaustive Checking** previene: obliga a que tu código trate **TODOS** los casos posibles. Si te olvidás uno, TypeScript te grita.

---

## 📦 Parte 2 — El punto de partida: Union Types

Antes de hablar de exhaustive checking, tenés que entender de dónde vienen los estados.

En TypeScript, definís los estados posibles con un **Union Type**:

```typescript
type EstadoLocker = "LIBRE" | "OCUPADO" | "RESERVADO";
```

Esto le dice a TypeScript: _"Una variable de tipo `EstadoLocker` SOLO puede ser uno de estos tres strings. Nada más."_

```typescript
let miLocker: EstadoLocker = "LIBRE"; // ✅ OK
let otroLocker: EstadoLocker = "ROTO"; // ❌ Error: Type '"ROTO"' is not assignable to type 'EstadoLocker'
```

---

## ⚠️ Parte 3 — El problema: el switch incompleto

Ahora escribís una función que reaccione a cada estado:

```typescript
type EstadoLocker = "LIBRE" | "OCUPADO" | "RESERVADO";

function obtenerMensaje(estado: EstadoLocker): string {
  switch (estado) {
    case "LIBRE":
      return "✅ Podés usar este locker.";
    case "OCUPADO":
      return "🔴 Este locker está en uso.";
    // ❌ ¡Olvidamos el caso 'RESERVADO'!
  }
  // TypeScript no grita. La función devuelve `undefined` en silencio.
}

console.log(obtenerMensaje("RESERVADO")); // undefined 😱
```

### ¿Qué pasó?

La función devolvió `undefined` sin que nadie se enterara. En una app real esto puede:

- Mostrarle texto vacío al usuario.
- Romper otra función que esperaba un string.
- Tardar meses en descubrirse.

---

## 🛡️ Parte 4 — La solución: el tipo `never`

### ¿Qué es `never`?

`never` es un tipo especial de TypeScript que significa: **"esto nunca debería ocurrir"**.

Imaginate que TypeScript tiene un detector de mentiras. Si le decís:

> "Esta variable va a ser `never`"...

...TypeScript verifica que sea imposible que esa variable tenga algún valor. Si SÍ tiene un valor (porque te olvidaste de manejar algún caso), TypeScript te **marca un error**.

```typescript
// Ejemplo directo de never
function funcionImposible(): never {
  throw new Error("Esto nunca debería ejecutarse");
}
```

### Usando `never` para el exhaustive check

```typescript
type EstadoLocker = "LIBRE" | "OCUPADO" | "RESERVADO";

function obtenerMensaje(estado: EstadoLocker): string {
  switch (estado) {
    case "LIBRE":
      return "✅ Podés usar este locker.";
    case "OCUPADO":
      return "🔴 Este locker está en uso.";
    case "RESERVADO":
      return "🟡 Este locker está reservado.";

    default:
      // 👇 Si llegamos aquí, algo está muy mal.
      // La variable 'estado' en este punto debería ser 'never' (imposible).
      // Si TypeScript no está de acuerdo (porque olvidamos un caso),
      // nos marca un error en esta línea.
      const _exhaustiveCheck: never = estado;
      throw new Error(`Estado no contemplado: ${_exhaustiveCheck}`);
  }
}
```

### ¿Cómo funciona el truco?

Cuando TypeScript analiza el `switch`:

1. Entra al `case 'LIBRE'` → elimina `'LIBRE'` de las posibilidades.
2. Entra al `case 'OCUPADO'` → elimina `'OCUPADO'`.
3. Entra al `case 'RESERVADO'` → elimina `'RESERVADO'`.
4. Al llegar al `default`, TypeScript sabe que **ya no quedan posibilidades**. La variable es de tipo `never`.
5. Asignar `never` a una variable de tipo `never` = perfecto ✅.

**Ahora, probemos olvidarnos de `'RESERVADO'`:**

```typescript
function obtenerMensajeMal(estado: EstadoLocker): string {
  switch (estado) {
    case "LIBRE":
      return "✅ Podés usar este locker.";
    case "OCUPADO":
      return "🔴 Este locker está en uso.";
    // ❌ Sin case 'RESERVADO'

    default:
      const _exhaustiveCheck: never = estado;
      // 💥 ERROR: Type 'string' is not assignable to type 'never'
      // TypeScript sabe que 'estado' todavía puede ser 'RESERVADO'
      // Por lo tanto NO es never. ¡Y te lo dice!
      throw new Error(`Estado no contemplado: ${_exhaustiveCheck}`);
  }
}
```

**El error es tu amigo. TypeScript te está diciendo: "Ey, te falta manejar un caso".**

---

## 🏋️ Parte 5 — Ejercicio Nivel Básico: Suscripción de Gimnasio

```typescript
// Los estados posibles de una suscripción en ObsidiantFit
type EstadoSuscripcion = "ACTIVA" | "EXPIRADA" | "PAUSADA";

function obtenerAcceso(estado: EstadoSuscripcion): string {
  switch (estado) {
    case "ACTIVA":
      return "💪 ¡Bienvenido! Acceso completo al gimnasio.";
    case "EXPIRADA":
      return "❌ Tu membresía venció. Renová en recepción.";
    case "PAUSADA":
      return "⏸️ Tu membresía está pausada. Reactivala desde la app.";

    default:
      const _check: never = estado;
      throw new Error(`Estado desconocido: ${_check}`);
  }
}

// Prueba
console.log(obtenerAcceso("ACTIVA")); // 💪 ¡Bienvenido!...
console.log(obtenerAcceso("EXPIRADA")); // ❌ Tu membresía venció...
```

**¿Qué pasa si en 6 meses agregamos `'SUSPENDIDA'`?**

```typescript
type EstadoSuscripcion = "ACTIVA" | "EXPIRADA" | "PAUSADA" | "SUSPENDIDA"; // ← nuevo

// 💥 TypeScript marca error INMEDIATO en la línea del never:
// Type 'string' is not assignable to type 'never'
// Te obliga a ir a la función y agregar el case 'SUSPENDIDA'.
```

---

## 🔧 Parte 6 — Función Helper Reutilizable

En vez de escribir el bloque `const _check: never = ...` en cada switch, podés crear una función helper que lo encapsula:

```typescript
// Esta función la escribís UNA sola vez
function assertNever(value: never, mensaje?: string): never {
  throw new Error(mensaje ?? `Valor no contemplado: ${JSON.stringify(value)}`);
}

// Y la usás en cualquier switch
type TipoEjercicio = "FUERZA" | "CARDIO" | "FLEXIBILIDAD";

function calcularCalorias(tipo: TipoEjercicio, minutos: number): number {
  switch (tipo) {
    case "FUERZA":
      return minutos * 8;
    case "CARDIO":
      return minutos * 12;
    case "FLEXIBILIDAD":
      return minutos * 4;
    default:
      return assertNever(tipo, `Tipo de ejercicio desconocido: ${tipo}`);
    // Si olvidaste un case, TypeScript da error aquí.
  }
}

console.log(calcularCalorias("CARDIO", 30)); // 360 calorías
```

**Ventaja:** Si el código llega al `default` en runtime (algo que en teoría nunca debería pasar), lanzás un error con un mensaje claro en vez de fallar en silencio.

---

## 🧙 Parte 7 — Nivel Avanzado: Discriminated Unions

Los **Discriminated Unions** son la versión avanzada. En vez de un simple string de estado, cada estado es un **objeto con propiedades propias**.

### El problema que resuelven

¿Qué pasa si cada estado necesita guardar **datos distintos**?

- Estado `INICIADO` → necesita saber la `horaDeInicio`.
- Estado `PAUSADO` → necesita saber el `motivo` de la pausa.
- Estado `FINALIZADO` → necesita saber las `calorias` quemadas.

Con un union simple de strings, no podés adjuntar esos datos. Con **Discriminated Unions** sí:

```typescript
// Cada "estado" es su propia interfaz con una propiedad `tipo` fija
interface SesionIniciada {
  tipo: "INICIADA"; // ← la propiedad discriminante
  horaDeInicio: Date;
}

interface SesionPausada {
  tipo: "PAUSADA"; // ← diferente para cada interfaz
  motivo: string;
}

interface SesionFinalizada {
  tipo: "FINALIZADA"; // ← diferente para cada interfaz
  caloriasQuemadas: number;
  duracionMinutos: number;
}

// El union type que une los tres
type EstadoSesion = SesionIniciada | SesionPausada | SesionFinalizada;
```

### ¿Por qué se llaman "discriminated"?

Porque la propiedad `tipo` es el **discriminador**: TypeScript la usa para saber exactamente qué interfaz es dentro del switch. Gracias a eso, dentro de cada `case` tenés **autocompletado específico** de las propiedades de esa interfaz.

```typescript
function assertNever(value: never): never {
  throw new Error(`Estado no contemplado: ${JSON.stringify(value)}`);
}

function procesarSesion(estado: EstadoSesion): void {
  switch (estado.tipo) {
    case "INICIADA":
      // 👇 TypeScript sabe que aquí estado es SesionIniciada
      // Podés acceder a estado.horaDeInicio con autocompletado
      console.log(
        `🟢 Sesión iniciada a las ${estado.horaDeInicio.toLocaleTimeString()}`,
      );
      break;

    case "PAUSADA":
      // 👇 Aquí TypeScript sabe que estado es SesionPausada
      // Solo podés acceder a estado.motivo
      console.log(`⏸️  Sesión pausada. Motivo: ${estado.motivo}`);
      break;

    case "FINALIZADA":
      // 👇 Aquí TypeScript sabe que estado es SesionFinalizada
      console.log(
        `🏁 Sesión terminada: ${estado.caloriasQuemadas} kcal en ${estado.duracionMinutos} min`,
      );
      break;

    default:
      // Si añadís una nueva interfaz al union y olvidás el case, error aquí
      return assertNever(estado);
  }
}

// Ejemplo de uso
const sesionActual: EstadoSesion = {
  tipo: "FINALIZADA",
  caloriasQuemadas: 420,
  duracionMinutos: 45,
};

procesarSesion(sesionActual);
// 🏁 Sesión terminada: 420 kcal en 45 min
```

---

## 🏆 Parte 8 — Ejercicio Final: Sistema de Pagos ObsidiantFit

Aplicá todo lo aprendido. Tenés que modelar el flujo de un pago en la app.

### Los estados del pago:

| Estado       | Datos adicionales                                               |
| ------------ | --------------------------------------------------------------- |
| `PENDIENTE`  | `montoTotal: number`                                            |
| `PROCESANDO` | `montoTotal: number`, `transaccionId: string`                   |
| `APROBADO`   | `montoTotal: number`, `recibo: string`, `fechaAprobacion: Date` |
| `RECHAZADO`  | `montoTotal: number`, `motivo: string`                          |

```typescript
// Definí las interfaces (una por estado)
interface PagoPendiente {
  tipo: "PENDIENTE";
  montoTotal: number;
}

interface PagoProcesando {
  tipo: "PROCESANDO";
  montoTotal: number;
  transaccionId: string;
}

interface PagoAprobado {
  tipo: "APROBADO";
  montoTotal: number;
  recibo: string;
  fechaAprobacion: Date;
}

interface PagoRechazado {
  tipo: "RECHAZADO";
  montoTotal: number;
  motivo: string;
}

// El union type
type EstadoPago = PagoPendiente | PagoProcesando | PagoAprobado | PagoRechazado;

// El helper
function assertNever(value: never): never {
  throw new Error(`Estado de pago no contemplado: ${JSON.stringify(value)}`);
}

// La función principal con exhaustive checking completo
function procesarPago(pago: EstadoPago): string {
  switch (pago.tipo) {
    case "PENDIENTE":
      return `⏳ Pago de $${pago.montoTotal} pendiente de confirmación.`;

    case "PROCESANDO":
      return `🔄 Procesando $${pago.montoTotal}... (ID: ${pago.transaccionId})`;

    case "APROBADO":
      return `✅ Pago de $${pago.montoTotal} aprobado. Recibo: ${pago.recibo}`;

    case "RECHAZADO":
      return `❌ Pago de $${pago.montoTotal} rechazado. Motivo: ${pago.motivo}`;

    default:
      return assertNever(pago);
  }
}

// Probamos
const pago1: EstadoPago = {
  tipo: "APROBADO",
  montoTotal: 5000,
  recibo: "REC-001",
  fechaAprobacion: new Date(),
};
const pago2: EstadoPago = {
  tipo: "RECHAZADO",
  montoTotal: 5000,
  motivo: "Fondos insuficientes",
};

console.log(procesarPago(pago1)); // ✅ Pago de $5000 aprobado...
console.log(procesarPago(pago2)); // ❌ Pago de $5000 rechazado...
```

---

## 🔑 Resumen Final

| Concepto                | ¿Para qué sirve?                                                                         |
| ----------------------- | ---------------------------------------------------------------------------------------- |
| `Union Type`            | Define TODOS los estados posibles.                                                       |
| `switch / case`         | Maneja cada estado por separado.                                                         |
| `never` en el `default` | El "seguro": si olvidás un case, TypeScript grita.                                       |
| `assertNever()`         | Función helper reutilizable para el bloque `default`.                                    |
| Discriminated Union     | Cuando cada estado necesita datos distintos. Usás una propiedad `tipo` para discriminar. |

### La regla de oro 🥇

> Si tu estado puede crecer en el futuro, usá exhaustive checking desde el primer día.
> Así, cuando agregues un estado nuevo, TypeScript te dice exactamente dónde tenés que actualizar el código.
