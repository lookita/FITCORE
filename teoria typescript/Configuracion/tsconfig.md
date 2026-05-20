# tsconfig.json — El Manual de Reglas de TypeScript

### ¿Qué es esto? (La analogía) 📖⚙️
Imagina que compras un juego de mesa complejo. El `tsconfig.json` es el libro de reglas. Ahí decides: "¿Vamos a jugar en modo difícil?", "¿Se permiten trampas?", "¿En qué idioma vamos a hablar?". Es el archivo donde le dices a TypeScript cómo quieres que se comporte en **tu proyecto**.

### ¿Cuándo lo uso en ObsidiantFit? 🛠️
- Para decidir si quieres que TypeScript sea **muy estricto** (`strict: true`) y te avise de cada pequeño error, o si quieres que sea más relajado.
- Para decirle dónde están guardados tus archivos (`rootDir`) y dónde quieres que guarde el resultado final (`outDir`).
- Para activar funciones modernas como los decoradores (`experimentalDecorators`).

### Las reglas más importantes:
- **`target`**: ¿Qué tan "vieja" es la computadora donde va a correr el código? (Ej: `ES2020`).
- **`module`**: Cómo se conectan los archivos entre sí.
- **`strict`**: El "Modo Difícil". Si está en `true`, TypeScript te obliga a ser un programador perfecto.

### El Truco del Experto 💡
No necesitas tocar este archivo todos los días. Una vez que lo configuras bien al principio de tu proyecto **ObsidiantFit**, ¡te olvidas de que existe! Pero si algo no compila, ¡seguro la respuesta está en este manual de reglas!
