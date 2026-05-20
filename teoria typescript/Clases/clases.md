# Clases — La Fábrica de Atletas

### ¿Qué es esto? (La analogía) 🏭🏢
Imagina que tienes un plano para construir **Gimnasios**. El plano dice que todos los gimnasios tienen una "puerta", un "dueño" y una "lista de máquinas". La **Clase** es ese plano. Los gimnasios reales que construyes (como "Gym de Lucas" o "Gym de Ana") son los **Objetos**. Con un solo plano, ¡puedes construir mil gimnasios!

### ¿Cuándo lo uso en ObsidiantFit? 🏗️
- Para crear una "plantilla" de lo que es un **Usuario**. Todos tienen nombre, email y racha.
- Para definir qué es un **Ejercicio**. Todos tienen nombre, grupo muscular y series.
- Cuando quieres que tus datos no sean solo "texto", sino que también puedan "hacer cosas" (funciones dentro de la clase).

### ¿Cómo se usa? (Paso a paso)
```typescript
class Atleta {
  nombre: string;
  racha: number;

  // El constructor es como la ceremonia de inauguración
  constructor(nombre: string) {
    this.nombre = nombre;
    this.racha = 0; // Todos empiezan con racha 0
  }

  entrenar() {
    this.racha++;
    console.log(`¡${this.nombre} ha entrenado! Racha: ${this.racha}`);
  }
}

// Creamos un atleta real usando la clase
const lucas = new Atleta("Lucas");
lucas.entrenar(); // "¡Lucas ha entrenado! Racha: 1"
```

### El Truco del Experto 💡
Las clases son geniales porque mantienen el código ordenado. En vez de tener datos por un lado y funciones por otro, ¡lo tienes todo junto en un solo lugar!
