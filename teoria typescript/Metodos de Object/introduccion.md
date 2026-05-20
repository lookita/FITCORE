# Introducción a Objects — La Carpeta del Atleta

### ¿Qué es esto? (La analogía) 📂👤
Imagina una **carpeta física** de un atleta en el gimnasio. Adentro de la carpeta hay varias fichas: una con su nombre, otra con su edad, otra con su peso actual. Un Objeto es exactamente eso: una estructura que agrupa muchos datos diferentes que pertenecen a una sola "entidad".

### ¿Cuándo lo uso en ObsidiantFit? 🏗️
- Para representar a un **Usuario**: tiene nombre, email, racha y suscripción.
- Para representar un **Ejercicio**: tiene nombre, grupo muscular y récord personal.

### ¿Cómo se construye?
Se usan llaves `{}` y cada dato tiene una "clave" y un "valor".

```typescript
const usuario = {
  nombre: "Lucas",
  edad: 25,
  estaActivo: true
};

// Para leer un dato, usamos el punto '.'
console.log(usuario.nombre); // "Lucas"
```

### El Truco del Experto 💡
En TypeScript, los objetos son muy estrictos. Si defines que un usuario tiene `nombre` y `edad`, ¡no puedes intentar leer `usuario.direccion` si no lo definiste antes! TypeScript te avisará que esa ficha no existe en la carpeta.
