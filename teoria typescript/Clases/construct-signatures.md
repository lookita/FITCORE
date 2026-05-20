# Construct Signatures — Tipando Fábricas

### ¿Qué es esto? (La analogía) 🏭🤖
Imagina que tienes una fábrica que fabrica robots. Tú no quieres el robot, quieres la **instrucción de cómo fabricar un robot**. El Construct Signature es el tipo de dato que describe a la "clase" en sí misma, o sea, a la función que usa `new` para crear objetos.

### ¿Cuándo lo uso en ObsidiantFit? 🛠️
- Cuando creas una función que recibe una **Clase** como argumento (por ejemplo, un sistema que crea componentes automáticamente).
- Para tipar "Fábricas" de objetos complejos.

### ¿Cómo se usa? (Paso a paso)
```typescript
class Ejercicio {
  constructor(public nombre: string) {}
}

// Esto dice: "Cualquier cosa que se pueda crear con 'new' y devuelva un Ejercicio"
type ConstructorDeEjercicio = new (n: string) => Ejercicio;

function fabricar(Clase: ConstructorDeEjercicio, nombre: string) {
  return new Clase(nombre);
}

const miPrensa = fabricar(Ejercicio, "Prensa");
```

### El Truco del Experto 💡
Es un concepto avanzado de "Meta-programación" (programar programas). Se usa mucho en frameworks como NestJS o Angular. ¡Es como ser el arquitecto que diseña las máquinas que construyen los edificios!
