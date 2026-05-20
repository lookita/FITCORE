# Métodos de Array Pro: Transformando Datos en ObsidiantFit 🧪📊

---

## 🏔️ Los 5 Fantásticos de los Arrays

Manejar listas de ejercicios o usuarios requiere dominar estas herramientas. Aquí veremos cómo aplicarlas de forma profesional.

### 1. `.map()` (El Transformador)
Crea un nuevo array con la misma cantidad de elementos, pero transformados.

```typescript
// Ejemplo: Convertir una lista de ejercicios a solo sus nombres
const nombres = ejercicios.map(ex => ex.nombre.toUpperCase());
```

### 2. `.filter()` (El Portero)
Crea un nuevo array solo con los elementos que pasan una prueba.

```typescript
// Ejemplo: Buscar solo los ejercicios que son de "Pecho"
const dePecho = ejercicios.filter(ex => ex.musculo === 'Pecho');
```

### 3. `.find()` (El Rastreador)
Devuelve el **primer** elemento que coincida. Si no hay nada, devuelve `undefined`.

```typescript
// Ejemplo: Buscar una rutina por su ID único
const rutina = rutinas.find(r => r.id === 'R-123');
```

### 4. `.reduce()` (El Acumulador)
Convierte todo un array en **un solo valor** (un número, un objeto, otro array).

```typescript
// Ejemplo: Calcular el peso total levantado en una sesión
const pesoTotal = series.reduce((total, s) => total + s.peso, 0);
```

### 5. `.flatMap()` (El Aplanador Maestro) 🆕
Es la combinación de un `.map()` seguido de un `.flat()`. Es utilísimo cuando tienes arrays dentro de otros arrays (Relaciones 1:N).

```typescript
// Ejemplo: Tenemos una lista de Rutinas, y cada rutina tiene un array de Ejercicios.
// Queremos UNA SOLA LISTA con TODOS los ejercicios de todas las rutinas.

const todasLasRutinas = [
  { nombre: 'Lunes', ejercicios: ['Flexiones', 'Plancha'] },
  { nombre: 'Martes', ejercicios: ['Sentadillas', 'Zancadas'] }
];

const todosLosEjercicios = todasLasRutinas.flatMap(r => r.ejercicios);
// Resultado: ['Flexiones', 'Plancha', 'Sentadillas', 'Zancadas']
// ¡Sin flatMap, tendrías un array de arrays! [[...], [...]]
```

---

## 🧙 Nivel Experto: El "Piping" (Encadenamiento)

La verdadera potencia ocurre cuando combinas todos los métodos en una sola línea.

### Escenario: Queremos saber cuánta XP ganó el usuario hoy, pero solo contando los ejercicios de "Pierna".

```typescript
const xpTotalPierna = misRutinas
  .flatMap(rutina => rutina.ejercicios)      // 1. Juntamos todos los ejercicios
  .filter(ex => ex.musculo === 'Pierna')     // 2. Nos quedamos solo con los de pierna
  .map(ex => ex.series * ex.repeticiones)    // 3. Convertimos cada ejercicio en sus puntos
  .reduce((total, puntos) => total + puntos, 0); // 4. Sumamos todo
```

---

## 🏋️ Ejemplo Real: Generador de Resumen Semanal

```typescript
class StatsService {
  generarResumen(rutinasSemanales: IRoutine[]) {
    // 1. Obtener todos los ejercicios únicos realizados
    const ejerciciosUnicos = [...new Set(rutinasSemanales.flatMap(r => r.ejercicios.map(e => e.nombre)))];

    // 2. Contar cuántas veces se entrenó cada músculo
    const musculosEntrenados = rutinasSemanales
      .flatMap(r => r.ejercicios)
      .reduce((conteo, ex) => {
        conteo[ex.musculo] = (conteo[ex.musculo] || 0) + 1;
        return conteo;
      }, {} as Record<string, number>);

    return {
      totalSesiones: rutinasSemanales.length,
      ejerciciosVistos: ejerciciosUnicos,
      detallePorMusculo: musculosEntrenados
    };
  }
}
```

---

## 🔑 Resumen de Selección

- ¿Quieres **cambiar** los datos? Use `.map()`.
- ¿Quieres **quitar** datos? Use `.filter()`.
- ¿Quieres **uno** solo? Use `.find()`.
- ¿Quieres **sumar o agrupar** todo? Use `.reduce()`.
- ¿Tienes **listas dentro de listas**? Use `.flatMap()`.
