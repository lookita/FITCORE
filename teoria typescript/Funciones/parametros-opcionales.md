# Parámetros Opcionales — El Equipo Extra

### ¿Qué es esto? (La analogía) 🎒
Imagina que vas al gimnasio. Siempre necesitas llevar **ropa de deporte** (obligatorio), pero si quieres, puedes llevar **guantes** (opcional). Si no llevas guantes, el gimnasio te deja entrar igual. El parámetro opcional es ese equipo que puedes traer o no, y se marca con un signo de pregunta `?`.

### ¿Cuándo lo uso en ObsidiantFit? 🧤
- Al registrar un entrenamiento: el **ejercicio** es obligatorio, pero la **nota o comentario** es opcional.
- Al crear un usuario: el **nombre** es obligatorio, pero la **foto de perfil** es opcional.

### ¿Cómo se usa? (Paso a paso)
```typescript
// El '?' significa que 'nota' puede no estar.
function registrarSerie(reps: number, nota?: string) {
  console.log(`Hiciste ${reps} repeticiones.`);
  
  if (nota) {
    console.log(`Nota guardada: ${nota}`);
  }
}

registrarSerie(10); // Funciona perfecto.
registrarSerie(12, "Me costó la última"); // También funciona perfecto.
```

### El Truco del Experto 💡
Dentro de la función, el parámetro opcional será `undefined` si no lo pasas. Siempre usa un `if` o el operador `??` para manejar el caso en que el usuario no lo envió.
