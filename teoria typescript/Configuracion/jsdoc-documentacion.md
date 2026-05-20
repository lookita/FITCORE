# JSDoc: Documentando como un Senior en ObsidiantFit 📝👨‍💻

---

## 🧠 ¿Qué es JSDoc? (ELI5)

Imagina que compras un **Suplemento Vitamínico**. El bote tiene una etiqueta que te dice para qué sirve, cuántas pastillas tomar y qué efectos tiene.

**JSDoc** es esa etiqueta para tu código. Son comentarios especiales que hacen que cuando otro programador (o tú mismo en el futuro) pase el ratón sobre una función, aparezca un cartel explicativo con toda la información necesaria.

---

## 🛡️ Nivel 1: El Comentario Básico

Empieza siempre con `/**` (dos asteriscos).

```typescript
/**
 * Calcula el índice de masa corporal (IMC) del atleta.
 */
function calcularIMC(peso: number, altura: number) {
  return peso / (altura * altura);
}
```

---

## 🏗️ Nivel 2: Parámetros, Retorno y Ejemplos

Podemos usar etiquetas especiales como `@param`, `@returns` y `@example`. Esto hace que Visual Studio Code muestre una ayuda increíble.

```typescript
/**
 * Registra una nueva sesión de entrenamiento en el historial.
 * 
 * @param usuarioId - El identificador único del atleta.
 * @param duracion - Tiempo en minutos (debe ser > 0).
 * @returns El objeto de la sesión creada con su ID único.
 * 
 * @example
 * const sesion = registrarEntrenamiento("user_123", 45);
 */
function registrarEntrenamiento(usuarioId: string, duracion: number) {
  // ... lógica
}
```

---

## 🧙 Nivel Experto: Tipos y Deprecación

### 1. Documentar Interfaces Complejas
Puedes explicar qué es cada campo de una interfaz para que el equipo no tenga dudas.

```typescript
interface IRoutine {
  /** Nombre descriptivo de la rutina (ej: "Lunes de Pierna") */
  name: string;
  /** Nivel de esfuerzo requerido de 1 a 10 */
  intensity: number;
}
```

### 2. Avisar sobre código viejo (`@deprecated`)
Si vas a cambiar una función por otra mejor, usa `@deprecated`. Visual Studio tachará el nombre de la función cuando alguien intente usarla.

```typescript
/**
 * @deprecated Usa `calcularCaloriasPro()` en su lugar. Esta función es imprecisa.
 */
function viejaCalculadora() { ... }
```

---

## 🏋️ ¿Por qué es vital en ObsidiantFit?

Cuando el proyecto crezca y tengas cientos de funciones como `validarSuscripcion()`, `obtenerXP()`, `generarDieta()`, no podrás recordar qué hacía cada una. JSDoc es tu **memoria externa**.

Además, herramientas como **Compodoc** pueden leer estos comentarios y crear una página web automática con toda la documentación de tu app.

---

## 🔑 Etiquetas más usadas

- **`@param`**: Describe una entrada de la función.
- **`@returns`**: Describe qué devuelve.
- **`@throws`**: Avisa si la función puede lanzar un error (muy importante para nuestro `throw`).
- **`@see`**: Enlace a otra función o documentación relacionada.
- **`@todo`**: Cosas que faltan por programar.

---
*💡 Truco pro: Escribe `/**` encima de cualquier función y presiona Enter. ¡Visual Studio Code generará el esquema de JSDoc por ti automáticamente!*
