# Nullish Coalescing — El "Si no hay nada, usa esto"

### ¿Qué es esto? (La analogía) 🕯️💡
Imagina que entras a una habitación y tratas de prender la **luz eléctrica** (`??`). Si no hay luz (es `null` o `undefined`), entonces prendes una **vela** (el valor por defecto). Es tu plan de respaldo para cuando algo está realmente vacío.

### ¿Cuándo lo uso en ObsidiantFit? 🏮
- Cuando quieres mostrar el nombre del usuario, pero si no tiene nombre, muestras **"Invitado"**.
- Si un ejercicio no tiene descripción, muestras **"Sin descripción disponible"**.
- Para poner **0** en el volumen total si todavía no se cargó ninguna serie.

### ¿Cómo se usa? (Paso a paso)
```typescript
const nombreUsuario = null;

// Si lo de la izquierda es nulo, usa lo de la derecha
const nombreAMostrar = nombreUsuario ?? "Atleta Anónimo";

console.log(nombreAMostrar); // "Atleta Anónimo"
```

### El Truco del Experto 💡
¿Cuál es la diferencia con el símbolo `||`? El `||` se activa con el número `0` o con textos vacíos `""`. El `??` es más inteligente: solo se activa si el valor es **nulo o indefinido**. ¡Usa `??` para no borrar el `0` por error!
