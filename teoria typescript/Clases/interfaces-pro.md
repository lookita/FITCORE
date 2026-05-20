# Interfaces Pro — El Contrato Inquebrantable

### ¿Qué es esto? (La analogía) 📜🤝
Imagina que contratas a un arquitecto. El contrato dice: "El edificio debe tener al menos 4 paredes, 1 techo y 1 puerta". No importa si las paredes son de ladrillo o de madera, el contrato se debe cumplir. Una **Interface** es ese contrato: obliga a una Clase u Objeto a tener ciertas propiedades y funciones obligatorias.

### ¿Cuándo lo uso en ObsidiantFit? 🏗️
- Para asegurar que todos los ejercicios (cardio, pesas, yoga) tengan una función llamada `iniciar()`.
- Para crear "moldes" que se pueden combinar (composición). Una clase puede cumplir muchos contratos a la vez.

### ¿Cómo se usa? (Paso a paso)
```typescript
interface Movible {
  velocidad: number;
  moverse(): void;
}

interface Identificable {
  id: string;
}

// Una clase puede cumplir varios contratos
class AtletaCorredor implements Movible, Identificable {
  id: string = "A1";
  velocidad: number = 10;
  
  moverse() {
    console.log("Corriendo por la pista...");
  }
}
```

### El Truco del Experto 💡
A diferencia de las Clases, las Interfaces **desaparecen por completo** cuando el código se convierte a JavaScript. No ocupan espacio, solo sirven para que TypeScript te ayude mientras programas. ¡Úsalas siempre para definir la "forma" de tus datos!
