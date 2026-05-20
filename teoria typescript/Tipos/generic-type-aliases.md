# Generic Type Aliases — Alias Mágicos

### ¿Qué es esto? (La analogía) 🏷️♻️
Imagina que tienes una etiqueta que dice "Caja de [ALGO]". En vez de crear una etiqueta para "Caja de Fruta" y otra para "Caja de Pesas", creas una etiqueta inteligente donde puedes escribir lo que quieras en el espacio vacío. Un Alias Genérico es un nombre que le das a una estructura de datos que puede cambiar de tipo según necesites.

### ¿Cuándo lo uso en ObsidiantFit? 🖇️
- Para definir cómo se ve una **Respuesta del Servidor**. Siempre tiene un `status` y un `mensaje`, pero los `datos` pueden ser una lista de ejercicios, un usuario o un error.
- Para crear tipos reutilizables como `ListaPaginada<T>`.

### ¿Cómo se usa? (Paso a paso)
```typescript
// Creamos el alias inteligente
type Respuesta<T> = {
  exito: boolean;
  datos: T; // Aquí irá el tipo que elijamos
  error?: string;
};

// Lo usamos para distintos casos
const respuestaEjercicios: Respuesta<Ejercicio[]> = {
  exito: true,
  datos: [{ nombre: "Prensa" }]
};

const respuestaUsuario: Respuesta<Usuario> = {
  exito: true,
  datos: { nombre: "Lucas" }
};
```

### El Truco del Experto 💡
Los alias genéricos son la mejor forma de evitar repetir código. Si ves que estás creando interfaces muy parecidas, ¡probablemente necesites un alias genérico!
