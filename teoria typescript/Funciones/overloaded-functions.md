# Overloaded Functions — La Función Multi-Uso

### ¿Qué es esto? (La analogía) 🍴🛠️
Imagina que tienes una herramienta mágica que puede ser un **martillo** si le pasas un clavo, o un **destornillador** si le pasas un tornillo. La función es la misma, pero se comporta de forma distinta según lo que reciba. La sobrecarga (overload) permite definir varias formas de usar una misma función.

### ¿Cuándo lo uso en ObsidiantFit? 🧬
- Una función `buscar()` que puede recibir un **ID (número)** para buscar un ejercicio, o un **Nombre (texto)**.
- Una función `entrenar()` que puede recibir una **Rutina Completa** o solo un **Ejercicio Suelto**.

### ¿Cómo se usa? (Paso a paso)
Se hace en dos partes: primero las "firmas" (las opciones) y luego el código real.

```typescript
// 1. LAS FIRMAS (Opciones)
function buscar(id: number): string;
function buscar(nombre: string): string[];

// 2. LA IMPLEMENTACIÓN (El código real que maneja todo)
function buscar(valor: number | string): any {
  if (typeof valor === "number") {
    return "Ejercicio encontrado por ID";
  } else {
    return ["Ejercicio 1", "Ejercicio 2"];
  }
}
```

### El Truco del Experto 💡
La implementación real (la última parte) tiene que ser capaz de manejar **todos** los casos de las firmas de arriba. Es un poco difícil de escribir al principio, ¡pero hace que usar la función sea un placer!
