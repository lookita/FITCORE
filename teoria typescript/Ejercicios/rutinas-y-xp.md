# Ejercicio Práctico: Modelado de Rutinas y Sistema de XP 🏋️‍♂️📈

---

## 🎯 Objetivo

Implementar un sistema básico de gestión de rutinas donde podamos calcular la experiencia (XP) que gana un usuario al completar ejercicios.

---

## 🛠️ Requerimientos Técnicos

### 1. Interfaces Estrictas

Crea las interfaces necesarias para representar el modelo de datos de **ObsidiantFit**:

- **`IExercise`**: Debe tener `id`, `nombre`, `series`, `repeticiones` y `puntosPorRepeticion`.
- **`IRoutine`**: Debe tener `id`, `nombre`, una lista de `ejercicios` y un `nivelDificultad` (usando un tipo de unión).

### 2. Clase `RoutineService`

Implementa una clase que gestione la lógica de las rutinas:

- **`crearRutina(nombre: string)`**: Inicializa una nueva rutina vacía.
- **`agregarEjercicio(rutinaId: string, ejercicio: IExercise)`**: Añade un ejercicio a la rutina correspondiente.
- **`calcularXP(rutinaId: string)`**: Suma todos los puntos de todos los ejercicios de la rutina.

---

## 💡 Pista de Implementación (Estructura)

// Tipo de unión para dificultad
type Dificultad = "facil" | "medio" | "dificil";

// Interfaz de Ejercicio
interface IExercise {
id: number;
nombre: string;
series: number;
repeticiones: number;
puntosPorRepeticion: number;
}

// Interfaz de Rutina 
interface IRoutine {
id: number;
nombre: string;
nivelDificultad: Dificultad;
ejercicios: IExercise[];
}

// Clase de servicio para rutinas
class RoutineService {
private rutinas: IRoutine[] = [];

// Crear una nueva rutina vacía
crearRutina(nombre: string, nivel: Dificultad): IRoutine {
const nuevaRutina: IRoutine = {
id: Date.now(),
nombre,
nivelDificultad: nivel,
ejercicios: []
};
this.rutinas.push(nuevaRutina);
return nuevaRutina;
}

// Agregar ejercicio a una rutina
agregarEjercicio(rutinaId: number, ejercicio: IExercise): void {
const rutina = this.rutinas.find(r => r.id === rutinaId);
if (!rutina) {
throw new Error("Rutina no encontrada");
}
rutina.ejercicios.push(ejercicio);
}

// Calcular XP total de una rutina
calcularXP(rutinaId: number): number {
const rutina = this.rutinas.find(r => r.id === rutinaId);
if (!rutina) {
throw new Error("Rutina no encontrada");
}
return rutina.ejercicios.reduce((total, ej) => {
return total + (ej.series _ ej.repeticiones _ ej.puntosPorRepeticion);
}, 0);
}
}

// -------------------
// Ejemplo de uso
// -------------------
const servicio = new RoutineService();

// Crear rutina
const rutina = servicio.crearRutina("Full Body", "medio");

// Agregar ejercicios
servicio.agregarEjercicio(rutina.id, {
id: 1,
nombre: "Sentadillas",
series: 3,
repeticiones: 12,
puntosPorRepeticion: 2
});

servicio.agregarEjercicio(rutina.id, {
id: 2,
nombre: "Flexiones",
series: 4,
repeticiones: 10,
puntosPorRepeticion: 3
});

// Calcular XP
const xpTotal = servicio.calcularXP(rutina.id);
console.log(`XP total de la rutina "${rutina.nombre}": ${xpTotal}`);

```

```
