# `extends` vs `implements` — Ser o Parecer

---

## 🧠 La Gran Diferencia

En TypeScript, cuando una clase usa a otra (o a una interface), puede hacerlo de dos formas muy distintas.

---

## 🧬 `extends` (Herencia: "ES UN...")

Cuando usas `extends`, estás creando un **vínculo de sangre**. El hijo **hereda** todo lo que el padre ya sabe hacer.

- **Se usa con**: Clase → Clase.
- **Qué hace**: Copia el código del padre al hijo.
- **Poder**: El hijo puede usar las funciones del padre sin volver a escribirlas.

```typescript
class Animal {
  respirar() { console.log("Tomando aire..."); }
}

class Perro extends Animal {
  ladrar() { console.log("Guau!"); }
}

const miPerro = new Perro();
miPerro.respirar(); // ✅ Ya sabe hacerlo (lo heredó)
miPerro.ladrar();   // ✅ Es algo propio
```

---

## 📜 `implements` (Contrato: "ACTÚA COMO...")

Cuando usas `implements`, estás firmando un **contrato**. La clase se compromete a tener ciertas funciones, pero tiene que escribirlas ella misma desde cero.

- **Se usa con**: Clase → Interface (normalmente).
- **Qué hace**: Obliga a la clase a cumplir con una forma.
- **Poder**: Asegura que diferentes clases se comporten igual, aunque por dentro funcionen distinto.

```typescript
interface Reproductor {
  play(): void;
}

class Spotify implements Reproductor {
  play() { console.log("Streaming de música..."); } // ✅ Obligado a escribirlo
}

class Radio implements Reproductor {
  play() { console.log("Sintonizando frecuencia..."); } // ✅ Obligado a escribirlo
}
```

---

## ⚔️ Comparación Directa

| Característica | `extends` | `implements` |
|---|---|---|
| **Relación** | "Es un hijo de" | "Firma un contrato con" |
| **Código** | Se hereda (no hace falta reescribir) | No se hereda (hay que escribirlo todo) |
| **Cantidad** | Solo puedes tener **UN** padre | Puedes cumplir **MUCHOS** contratos |
| **Uso principal** | Reutilizar código | Estandarizar comportamientos |

---

## 🏋️ Ejemplo Real en ObsidiantFit

### Con `extends` (Reutilizar):
```typescript
class Ejercicio {
  constructor(public nombre: string) {}
}

// EjercicioDeFuerza ES UN Ejercicio (hereda el nombre)
class EjercicioDeFuerza extends Ejercicio {
  public peso: number = 0;
}
```

### Con `implements` (Cumplir reglas):
```typescript
interface Guardable {
  save(): void;
}

// Usuario firma el contrato Guardable (debe tener un método save)
class Usuario implements Guardable {
  save() {
    console.log("Guardando usuario en la BD...");
  }
}
```

---

## 🔑 Resumen para un niño de 10 años

- **`extends`**: Es como heredar el color de ojos de tu papá. No hiciste nada, simplemente ya los tienes porque eres su hijo.
- **`implements`**: Es como inscribirte en un torneo de Karate. Tienes que aprender todas las reglas y movimientos tú mismo para poder participar. El torneo no te da los movimientos, solo te dice cuáles **tienes** que saber.
