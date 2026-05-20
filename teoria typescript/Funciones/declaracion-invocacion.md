# Formas de Declarar Funciones — Los Estilos de Cocina

### ¿Qué es esto? (La analogía) 🍳
Imagina que quieres cocinar una tortilla. Puedes seguir una receta escrita (Named Function), improvisar sobre la marcha (Function Expression) o usar una máquina rápida de hacer tortillas (Arrow Function). Al final el resultado es el mismo, pero la forma en que lo preparas cambia según lo que necesites.

### ¿Cuándo lo uso en ObsidiantFit? 🛠️
- **Named Function**: Para funciones importantes y globales, como `calcularIMC()`.
- **Arrow Function**: Para cosas rápidas que pasan dentro de otras, como cuando usas `.map()` para listar ejercicios.
- **Function Expression**: Cuando quieres guardar una función dentro de una variable para usarla después.

### Los 4 Estilos de Declaración:

#### 1. Named Function (La Clásica)
Es la que tiene nombre propio. Es fácil de encontrar y leer.
```typescript
function saludarAtleta(nombre: string) {
  return `¡Hola ${nombre}!`;
}
```

#### 2. Function Expression (La Anónima)
Guardas la función en una variable. No tiene nombre propio, el nombre es el de la variable.
```typescript
const despedirAtleta = function(nombre: string) {
  return `¡Adiós ${nombre}!`;
};
```

#### 3. Arrow Function (La Flecha Veloz 🏹)
Es la forma más moderna y corta. Muy usada hoy en día.
```typescript
const entrenar = (ejercicio: string) => `Entrenando ${ejercicio}...`;

// Shorthand (si es una sola línea, no necesita 'return' ni llaves)
const sumarPeso = (a: number, b: number) => a + b;
```

#### 4. Function Constructor (La Máquina Rara ⚠️)
Crea una función a partir de un texto. **Casi nunca se usa** porque es lenta y peligrosa, pero existe.
```typescript
const sumaRara = new Function('a', 'b', 'return a + b');
```

### Validación de Llamadas 👮‍♂️
TypeScript siempre revisará que:
1. Pases la cantidad de argumentos correcta.
2. Los tipos sean los que pediste (no puedes pasar un texto donde pides un peso).
Si te equivocas, ¡el programa no te dejará ni empezar!

### El Truco del Experto 💡
Usa **Arrow Functions** casi siempre, especialmente dentro de métodos de Array como `.map()` o `.filter()`. Son más cortas y manejan mejor el misterioso `this`.
