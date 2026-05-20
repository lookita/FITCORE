# Relaciones y Modelado: El Árbol Genealógico de ObsidiantFit 🧬🌳

---

## 🏔️ La Jerarquía Maestra: User → Routine → Exercise

En el desarrollo de software, las relaciones definen cómo fluye la información. En nuestro programa, nada existe sin un dueño.

### 1. El Usuario (La Raíz)
Un usuario es la entidad de nivel superior. Sin un usuario, no hay rutinas ni progreso.
- **Relación:** 1 Usuario tiene **N** Rutinas.

### 2. La Rutina (El Contenedor)
La rutina agrupa el trabajo físico. No es solo una lista, es un contexto (ej: "Día de Fuerza", "Día de Cardio").
- **Relación:** 1 Rutina tiene **N** Ejercicios.

### 3. El Ejercicio (La Unidad Mínima)
Es el átomo de nuestra app. Contiene los datos crudos de esfuerzo.

---

## 🏗️ Relación 1:N (Uno a Muchos) en Detalle

### A. Perspectiva TypeScript (Orientada a Objetos)
En TypeScript, modelamos la relación 1:N usando **propiedades de tipo Array**. Esto permite que el "Padre" tenga acceso directo a sus "Hijos".

```typescript
interface IExercise {
  nombre: string;
  musculoTarget: string;
}

interface IRoutine {
  nombre: string;
  ejercicios: IExercise[]; // <--- La relación vive aquí como una colección
}

class User {
  username: string;
  rutinas: IRoutine[]; // <--- Otra relación 1:N
}
```

### B. Perspectiva SQL (Bases de Datos Relacionales)
En una base de datos (como la que usa NestJS), **no existen los arrays dentro de las tablas**. La relación se invierte: el Hijo guarda el ID del Padre (**Foreign Key**).

**Tabla `rutinas`:**
| id | nombre | usuario_id (FK) |
|---|---|---|
| 10 | Empuje | 1 (Lucas) |
| 11 | Tracción | 1 (Lucas) |

**Tabla `ejercicios`:**
| id | nombre | rutina_id (FK) |
|---|---|---|
| 101 | Press Banca | 10 |
| 102 | Press Militar | 10 |
| 103 | Dominadas | 11 |

---

## 🧙 Nivel Experto: El Flujo de Datos en el Programa

Imagina que quieres obtener todos los ejercicios de un usuario específico. El flujo de búsqueda sería:
1. Buscar el `User` por su ID.
2. Obtener su lista de `routines`.
3. Por cada rutina, extraer sus `exercises`.

Esto en SQL requiere un **JOIN** (Unión) y en TypeScript requiere un **.map()** o **.flatMap()**.

```typescript
// Ejemplo de búsqueda profunda:
const todosMisEjercicios = usuario.rutinas.flatMap(r => r.ejercicios);
```

---

## 🏋️ Ejemplo Extenso: El Modelo de Progreso

```typescript
/**
 * RELACIÓN 1:N: Un Ejercicio tiene muchos Registros de Peso
 */
interface IProgreso {
  fecha: Date;
  pesoLevantado: number;
  repsRealizadas: number;
}

class EjercicioEnRutina {
  constructor(
    public nombre: string,
    public historial: IProgreso[] // Relación 1:N con sus marcas personales
  ) {}

  obtenerRecordPersonal() {
    return this.historial.reduce((max, actual) => 
      actual.pesoLevantado > max.pesoLevantado ? actual : max
    );
  }
}
```

---

## 🔑 Resumen para el Arquitecto

1.  **Encapsulación:** En TypeScript, el padre encapsula a los hijos en un array.
2.  **Integridad:** En SQL, las Foreign Keys aseguran que no existan "ejercicios huérfanos" (ejercicios sin rutina).
3.  **Desempeño:** Manejar relaciones 1:N profundas puede ser lento si no usas técnicas como **Eager Loading** o **Lazy Loading** (temas que veremos en NestJS).
