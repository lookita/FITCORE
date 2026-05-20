# Herencia — El Árbol Genealógico

### ¿Qué es esto? (La analogía) 👪🧬
Imagina que un padre es un **Atleta** y su hijo es un **Atleta Olímpico**. El hijo hereda todo lo que tiene el padre (nombre, fuerza, ganas de entrenar), pero además le agrega cosas suyas (medallas de oro, patrocinio de marcas). La herencia permite crear clases nuevas basadas en otras que ya existen para no tener que escribir todo de nuevo.

### ¿Cuándo lo uso en ObsidiantFit? 🌳
- Tienes una clase base llamada `Usuario`. De ella heredan `Entrenador` (que tiene alumnos) y `Alumno` (que tiene un entrenador asignado). Ambos comparten el nombre y el mail de la clase base `Usuario`.
- Tienes un `Ejercicio` base, y heredas `EjercicioCardio` (que suma km) y `EjercicioFuerza` (que suma kilos).

### ¿Cómo se usa? (Paso a paso)
```typescript
class Usuario {
  nombre: string;
  constructor(nombre: string) { this.nombre = nombre; }
}

// 'extends' significa que Entrenador hereda de Usuario
class Entrenador extends Usuario {
  alumnos: string[] = [];

  asignarAlumno(alumno: string) {
    this.alumnos.push(alumno);
    console.log(`${this.nombre} ahora entrena a ${alumno}`);
  }
}

const marcos = new Entrenador("Marcos");
console.log(marcos.nombre); // "Marcos" (lo heredó de Usuario)
marcos.asignarAlumno("Lucas");
```

### El Truco del Experto 💡
Cuando heredas, puedes usar la palabra `super()` en el constructor. Es como llamar al padre para decirle: "Oye, ayúdame a construir mi parte primero".
