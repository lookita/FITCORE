# 🧭 Guía de Decisiones: ¿Cómo pensar como un Programador?

Cuando estés frente a la pantalla y no sepas por dónde empezar, sigue esta guía. No necesitas que la IA te resuelva todo, ¡tienes todas las herramientas en este manual!

## 1. Identifica tu Problema (El Diagnóstico) 🔍

Hazte estas preguntas:

### A. "¿Necesito traer o enviar información de afuera?" 🌐
*   **Si la respuesta es SÍ**: Necesitas **Peticiones HTTP (fetch)**. 
*   **Para qué**: Traer la lista de ejercicios del servidor o guardar el peso que el usuario anotó.
*   **Busca en**: `teoria/Peticiones HTTP/fetch.md`

### B. "¿Tengo una lista de muchas cosas?" 🧺
*   **Si la respuesta es SÍ**: Necesitas **Arrays**.
*   **Si quieres transformar la lista** (ej: pasar de kilos a libras) → Usa `.map()`.
*   **Si quieres sacar solo algunos** (ej: solo ejercicios de pecho) → Usa `.filter()`.
*   **Si quieres sumar todo** (ej: calcular el volumen total) → Usa `.reduce()`.
*   **Busca en**: `teoria/Metodos de Array/`

### C. "¿Tengo un dato complejo con muchas partes?" 📂
*   **Si la respuesta es SÍ**: Necesitas **Objetos** o **Clases**.
*   **Si solo quieres guardar datos** (ej: nombre, edad, peso) → Usa un **Objeto**.
*   **Si además quieres que "haga cosas"** (ej: que el usuario pueda "entrenar" o "subir de nivel") → Usa una **Clase**.
*   **Busca en**: `teoria/Metodos de Object/` o `teoria/Clases/`

### D. "¿Tengo que hacer un cálculo o una lógica que se repite?" ⚙️
*   **Si la respuesta es SÍ**: Necesitas una **Función**.
*   **Busca en**: `teoria/Funciones/`

### E. "¿Qué pasa si algo sale mal?" 🚑
*   **Si la respuesta es SÍ**: Necesitas **Errores (try-catch)**.
*   **Cuándo**: Siempre que uses `fetch` o hagas algo "peligroso" con datos que no controlas.
*   **Busca en**: `teoria/Errores/try-catch.md`

---

## 2. El Mapa para Construir Solo (Paso a Paso) 🗺️

Si tienes que programar una funcionalidad nueva (ej: "Un cronómetro que avisa cuando descansar"), sigue este orden:

1.  **Define los Tipos (El Plano)** 📐: ¿Qué datos necesito? ¿Es un número? ¿Un texto?
    *   *Mira en `teoria/Tipos/`*.
2.  **Crea la Máquina (La Lógica)** 🏗️: Crea una función o clase que haga el trabajo.
    *   *Mira en `teoria/Funciones/`*.
3.  **Añade Seguridad (La Red)** 🛡️: Envuelve todo en un `try-catch` si es necesario.
    *   *Mira en `teoria/Errores/`*.
4.  **Conecta con el Mundo (El Cable)** 🔌: Si los datos deben guardarse para siempre, envíalos con `fetch`.
    *   *Mira en `teoria/Peticiones HTTP/`*.

---

### El Truco Final: "Divide y Vencerás" ⚔️
Si el problema parece gigante (ej: "Hacer toda la pantalla de progreso"), divídelo en mini-problemas:
1.  Traer los datos (`fetch`).
2.  Filtrar los del mes actual (`filter`).
3.  Sumar los kilos (`reduce`).
4.  Mostrar el mensaje final (`template literals`).

¡Si sigues este camino, serás capaz de construir cualquier cosa en **ObsidiantFit**! 🚀
