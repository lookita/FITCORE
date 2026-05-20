# Introducción a Clases — La Fábrica de Equipamiento

### ¿Qué es esto? (La analogía) 🏭📋
Imagina que eres el dueño de una fábrica que hace **Mancuernas**. No haces cada mancuerna desde cero sin pensar; tienes un **molde** o un **plano**. Ese plano dice que todas las mancuernas tienen un peso, un material y un color. 
- La **Clase** es el plano/molde.
- El **Objeto** es la mancuerna real que sale de la fábrica.
Con un solo plano, ¡puedes fabricar miles de mancuernas iguales!

### ¿Cuándo lo uso en ObsidiantFit? 🏗️
- Para crear una "plantilla" de lo que es un **Entrenamiento**.
- Para definir el comportamiento de un **Cronómetro** que todos los usuarios puedan usar.

### Concepto Clave: Datos + Acciones
A diferencia de los objetos simples, las clases pueden guardar datos (propiedades) y también saber "hacer cosas" (métodos).

```typescript
class Mancuerna {
  peso: number = 10;
  
  levantar() {
    console.log(`Levantando ${this.peso}kg... ¡Ufff!`);
  }
}
```

### El Truco del Experto 💡
Usa clases cuando necesites crear muchas cosas que compartan la misma lógica. Si solo necesitas guardar datos quietos, una `Interface` o un `Type` es mejor. Pero si necesitas que "hagan algo", ¡la Clase es tu mejor amiga!
