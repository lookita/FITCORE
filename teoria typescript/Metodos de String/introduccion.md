# Introducción a Strings — Las Etiquetas del Gym

### ¿Qué es esto? (La analogía) 🏷️💬
Imagina que un String es una **cinta de etiquetar**. La usas para ponerle nombre a las máquinas, escribir los nombres de los atletas o dejar notas en los entrenamientos. Básicamente, es cualquier texto que esté entre comillas.

### ¿Cuándo lo uso en ObsidiantFit? 🧱
- Para el nombre del atleta: `"Lucas"`.
- Para la descripción de un ejercicio: `"Mantener la espalda recta"`.
- Para mensajes dinámicos: `"¡Hola Lucas, hoy toca pierna!"`.

### Formas de crear un String:

#### 1. Comillas simples o dobles
Son las de siempre. No hay mucha diferencia, pero sé constante.
```typescript
let musculo = 'Bíceps';
let ejercicio = "Curl de martillo";
```

#### 2. Template Literals (Las Comillas Mágicas `` ` ``) 🪄
Son las mejores para ObsidiantFit. Te permiten meter variables adentro usando `${}`.
```typescript
let atleta = "Lucas";
let mensaje = `¡Bienvenido ${atleta}! Tu racha es de 5 días.`;
// Resultado: "¡Bienvenido Lucas, Tu racha es de 5 días."
```

### El Truco del Experto 💡
Usa siempre las comillas mágicas (Backticks) cuando quieras mezclar texto con números o variables. Es mucho más limpio que usar el símbolo `+` mil veces.
