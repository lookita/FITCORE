# Void, Null y Undefined — Los Vacíos

### ¿Qué es esto? (La analogía) 📭
Imagina que tienes varios tipos de "nada" en tu gimnasio:
- **`void` (La Acción sin Premio)**: Es como hacer una serie de calentamiento. La haces, te sirve, pero no te llevas ninguna medalla ni peso registrado. No devuelve nada.
- **`undefined` (El Olvido)**: Es cuando buscas una pesa que jurabas que estaba ahí, pero alguien se la llevó y no hay nada. La variable existe, pero no tiene valor.
- **`null` (El Espacio Vacío)**: Es cuando quitas intencionalmente un disco de la barra. El espacio está ahí, pero tú decidiste que esté vacío.

### ¿Cuándo lo uso en ObsidiantFit? 🛠️
- **`void`**: Casi siempre en funciones que solo imprimen algo o guardan datos sin devolver un resultado (ej: `console.log` o `guardarRutina()`).
- **`undefined`**: Cuando una propiedad es opcional, como una "nota" en un ejercicio que el usuario puede escribir o no.
- **`null`**: Cuando quieres resetear algo. Por ejemplo: `ejercicioSeleccionado = null` para indicar que el usuario ya no está eligiendo nada.

### ¿Cómo se usa? (Ejemplo Real en ObsidiantFit)

```typescript
// 1. VOID: Solo realiza una acción (enviar a la base de datos)
function registrarEntrenamiento(ejercicio: string, reps: number): void {
  console.log(`Guardando: ${ejercicio} - ${reps} reps...`);
  // Aquí no hay 'return', por eso es void.
}

// 2. UNDEFINED: El usuario puede o no escribir un comentario
function finalizarSesion(usuario: string, comentario?: string) {
  if (comentario === undefined) {
    console.log(`Sesión de ${usuario} guardada sin comentarios.`);
  } else {
    console.log(`Sesión de ${usuario} guardada con nota: ${comentario}`);
  }
}

// 3. NULL: Resetear el estado del entrenamiento
let ejercicioActivo: string | null = "Sentadilla"; 

// El usuario termina el ejercicio y limpiamos el espacio:
ejercicioActivo = null; 

if (ejercicioActivo === null) {
  console.log("No hay ningún ejercicio seleccionado actualmente.");
}
```

### El Truco del Experto 💡
En TypeScript, `void` se usa **solo para funciones**. No tiene sentido crear una variable de tipo `void`. 

**Recuerda la diferencia:**
*   `undefined` suele ser "automático" (algo que no existe aún).
*   `null` suele ser "manual" (tú decides que algo esté vacío ahora).
