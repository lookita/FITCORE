# Type-Driven Development — Diseñar con Tipos

### ¿Qué es esto? (La analogía) 📐🏗️
Imagina que antes de construir un gimnasio, dibujas todos los planos, decides dónde van los enchufes y de qué color son las paredes. El "Desarrollo Guiado por Tipos" es escribir primero las reglas (los tipos e interfaces) antes de escribir una sola línea de código real. Es como poner los rieles antes de que pase el tren.

### ¿Cuándo lo uso en ObsidiantFit? 🛤️
- Para definir exactamente qué datos necesita un **Nuevo Entrenamiento** antes de crear el formulario.
- Para diseñar cómo se van a comunicar el **Frontend** y el **Backend** sin errores.

### El Flujo de Trabajo:
1.  **Definir Tipos**: ¿Qué es un `Ejercicio`? ¿Qué devuelve la función `crearRutina`?
2.  **Escribir la Firma**: `function crearRutina(e: Ejercicio[]): Rutina`.
3.  **Implementar**: Escribir el código real. TypeScript te irá avisando si te desvías del plano que dibujaste en el paso 1.

### El Truco del Experto 💡
Diseñar primero los tipos te ayuda a pensar mejor en la lógica de tu app. ¡Si el tipo está bien diseñado, el código se escribe casi solo!
