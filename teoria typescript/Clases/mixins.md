# Mixins — El Buffet de Habilidades

### ¿Qué es esto? (La analogía) 🍱🦸‍♂️
Imagina que tienes un superhéroe. En vez de que nazca con todos los poderes (herencia), él va a un buffet y elige: "Quiero el poder de **Volar**" y "Quiero el poder de **Fuerza**". Los Mixins te permiten "mezclar" pequeñas piezas de código con habilidades específicas en una clase, sin usar la herencia tradicional que a veces es muy rígida.

### ¿Cuándo lo uso en ObsidiantFit? 🛠️
- Cuando tienes clases muy distintas (ej: `Usuario` y `Ejercicio`) que necesitan la misma habilidad (ej: `GuardarEnBaseDeDatos`).
- Para crear clases personalizadas combinando piezas de "Lego" de funcionalidad.

### ¿Cómo se usa? (Paso a paso)
```typescript
// Una función que recibe una clase y le "pega" una habilidad nueva
function ConSalud<TBase extends new (...args: any[]) => {}>(Base: TBase) {
  return class extends Base {
    vida = 100;
    recibirDanio(cantidad: number) { this.vida -= cantidad; }
  };
}

class Jugador {}
const JugadorConVida = ConSalud(Jugador);

const lucas = new JugadorConVida();
console.log(lucas.vida); // 100 (¡Heredó la vida sin ser un hijo directo!)
```

### El Truco del Experto 💡
Los Mixins son la alternativa moderna a la herencia profunda y complicada. En vez de tener un "Abuelo -> Padre -> Hijo", tienes una clase base y le vas sumando "superpoderes" según necesites.
