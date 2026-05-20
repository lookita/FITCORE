# Object.freeze() — El Objeto Congelado

### ¿Qué es esto? (La analogía) 🧊
Imagina que escribes las reglas de tu gimnasio en un bloque de hielo. Una vez que el hielo se congela, **nadie** puede borrar ni cambiar lo que dice. El `Object.freeze()` hace que un objeto sea imposible de modificar: no se pueden agregar cosas, ni borrar, ni cambiar los valores que ya tiene.

### ¿Cuándo lo uso en ObsidiantFit? 🚫
- Para guardar **Configuraciones** que nunca deberían cambiar, como los nombres de los días de la semana o los niveles de suscripción.
- Para proteger datos importantes y evitar que otro programador (o tú mismo por error) los cambie durante la ejecución del programa.

### ¿Cómo se usa? (Paso a paso)
```typescript
const CONFIG = {
  MAX_SERIES: 10,
  VERSION: "1.0.0"
};

Object.freeze(CONFIG);

// Intentamos cambiarlo (por error)
CONFIG.MAX_SERIES = 99; 

console.log(CONFIG.MAX_SERIES); // ¡Sigue siendo 10! No cambió nada.
```

### El Truco del Experto 💡
Congelar objetos ayuda a que tu código sea más seguro y predecible. Si algo es una "Constante", ¡congélalo!
