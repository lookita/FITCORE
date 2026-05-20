# Inmutabilidad Pro: Protegiendo los Datos de ObsidiantFit 🛡️💎

---

## 🧠 ¿Qué es la Inmutabilidad? (ELI5)

Imagina que un usuario hace un pago de $50 por su suscripción. Una vez que el recibo se imprime, **nadie debería poder cambiar ese número**. Si alguien pudiera cambiarlo a $5 después de pagado, el gimnasio perdería dinero.

La **Inmutabilidad** es el arte de crear datos que, una vez nacen, no pueden ser modificados. En lugar de "cambiar" algo, creamos una copia nueva con el cambio.

---

## 🛡️ Nivel 1: El modificador `readonly` en Propiedades

Lo usamos dentro de interfaces o clases para proteger campos específicos.

```typescript
interface IPago {
  readonly idTransaccion: string; // Prohibido cambiar el ID
  readonly monto: number;         // Prohibido cambiar cuánto pagó
  readonly fecha: Date;
  usuarioId: string;              // Esto sí podría cambiar si se reasigna
}

const miPago: IPago = {
  idTransaccion: "STRIPE_123",
  monto: 50,
  fecha: new Date(),
  usuarioId: "user_01"
};

// miPago.monto = 5; // ❌ Error de TS: "Cannot assign to 'monto' because it is a read-only property"
```

---

## 🏗️ Nivel 2: `Readonly<T>` (Protección Total)

¿Qué pasa si tienes un objeto de "Configuración del Gimnasio" y quieres que ABSOLUTAMENTE TODO sea intocable? En lugar de poner `readonly` línea por línea, usamos el Utility Type `Readonly`.

```typescript
interface IGymConfig {
  nombre: string;
  maxUsuarios: number;
  precioBase: number;
}

// Convertimos toda la interfaz en inmutable
const config: Readonly<IGymConfig> = {
  nombre: "ObsidiantFit Center",
  maxUsuarios: 100,
  precioBase: 25
};

// config.nombre = "Otro Gym"; // ❌ Error: Todo el objeto está blindado.
```

---

## 🧙 Nivel Experto: ¿Por qué es vital en Pagos y Redux?

En arquitecturas profesionales, la inmutabilidad evita **efectos secundarios inesperados**. 

Si tienes una función que calcula descuentos y, por error, modifica el objeto original del pago, estarías corrompiendo la base de datos en memoria. Al usar `Readonly`, TypeScript te obliga a crear un objeto nuevo:

```typescript
function aplicarDescuento(pago: Readonly<IPago>): IPago {
  // En lugar de: pago.monto = pago.monto * 0.9 (Error)
  // Hacemos esto:
  return {
    ...pago, // Copiamos todo lo anterior
    monto: pago.monto * 0.9 // Sobrescribimos solo el monto en la COPIA
  };
}
```

---

## 🏋️ Ejemplo Real: El Historial de Récords

Los récords personales de un atleta son "fotos en el tiempo". No deben ser modificables.

```typescript
interface IRecordPersonal {
  readonly fecha: Date;
  readonly ejercicio: string;
  readonly peso: number;
}

class Atleta {
  // Usamos ReadonlyArray para que nadie pueda borrar o cambiar el historial sin permiso
  private _historial: ReadonlyArray<IRecordPersonal> = [];

  get historial() {
    return this._historial;
  }

  registrarRecord(nuevo: IRecordPersonal) {
    // Para añadir, creamos un array nuevo con el elemento
    this._historial = [...this._historial, nuevo];
  }
}
```

---

## 🔑 Resumen Maestro

1.  **`readonly`**: Protege una sola propiedad.
2.  **`Readonly<T>`**: Protege todas las propiedades de un objeto de golpe.
3.  **`ReadonlyArray<T>`**: Protege una lista para que no se puedan hacer `push`, `pop` o cambios de índice.
4.  **Propósito:** Evitar bugs accidentales y asegurar que los datos sensibles (como pagos) sean íntegros.
