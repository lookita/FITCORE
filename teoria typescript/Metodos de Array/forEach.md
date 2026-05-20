# .forEach() — El Entrenador que Revisa Todo

### ¿Qué es esto? (La analogía) 📋
Imagina que un entrenador pasa por cada máquina del gimnasio saludando a cada atleta. El entrenador no está cambiando a los atletas (como `.map`) ni echando a nadie (como `.filter`), simplemente está haciendo una acción con cada uno. 

### ¿Cuándo lo uso en ObsidiantFit? 🏃‍♂️
Cuando quieres hacer algo para cada elemento, pero no necesitas crear una lista nueva.
- Guardar cada ejercicio en una **base de datos**.
- Mandar un **mensaje de WhatsApp** a cada usuario de la lista.
- Imprimir en la consola el progreso de cada día.

### ¿Cómo se usa? (Paso a paso)
```typescript
const usuarios = ["Lucas", "Marcos", "Ana"];

usuarios.forEach((nombre) => {
  console.log(`¡Buen entrenamiento hoy, ${nombre}!`);
});

// Esto solo imprime mensajes, no crea una lista nueva de saludos.
```

### El Truco del Experto 💡
A diferencia de `.map()`, el `.forEach()` devuelve `undefined`. Si intentas guardar el resultado de un `.forEach()` en una variable, ¡estará vacía! Úsalo solo para "acciones externas" (side effects).
