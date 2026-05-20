# Modelado con Clases — El Mapa del Gimnasio

### ¿Qué es esto? (La analogía) 🗺️♟️
Imagina que vas a diseñar un juego de ajedrez. No empiezas moviendo piezas; primero dibujas qué es un "Rey", qué es un "Peón" y qué reglas tiene cada uno. El **Modelado con Clases** es diseñar el corazón de tu app antes de que se vea bonita. Es decidir quién es el jefe, quién es el empleado y cómo se hablan entre ellos.

### ¿Cuándo lo uso en ObsidiantFit? 🏗️
- Para diseñar la estructura de una **Rutina**. ¿Una rutina tiene muchos ejercicios? ¿O un ejercicio pertenece a muchas rutinas?
- Para crear una jerarquía de usuarios: un `Admin` que puede borrar todo, un `Coach` que crea planes y un `Alumno` que los sigue.

### Ejemplo de Diseño (El Alumno y el Coach):
```typescript
class Persona {
  constructor(public nombre: string) {}
}

class Alumno extends Persona {
  constructor(nombre: string, public nivel: string) {
    super(nombre);
  }
}

class Coach extends Persona {
  constructor(nombre: string, public especialidad: string) {
    super(nombre);
  }
  
  asignarPlan(alumno: Alumno) {
    console.log(`Asignando plan de ${this.especialidad} a ${alumno.nombre}`);
  }
}
```

### El Truco del Experto 💡
Antes de programar, agarra un papel y dibuja cajas con los nombres de tus clases y flechas que digan cómo se conectan. ¡Este diseño de "dominio" te ahorrará semanas de trabajo arreglando errores de lógica!
